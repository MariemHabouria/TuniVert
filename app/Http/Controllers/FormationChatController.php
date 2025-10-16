<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

// Optionnel : modèle d'historique si tu l’as créé
// php artisan make:model FormationChatMessage -m
// Colonne attendue: content (texte du message)
use App\Models\FormationChatMessage;

class FormationChatController extends Controller
{
    /**
     * POST /formations/{formation}/chat
     * name: formations.chat
     */
    public function chat(Formation $formation, Request $request)
    {
        $data = $request->validate([
            'message' => ['required','string','max:4000'],
            'mode'    => ['nullable','in:formation,general'], // 'formation' (par défaut) | 'general'
        ]);

        $mode    = $data['mode'] ?: 'formation';
        $userMsg = trim($data['message']);

        // -------- Contexte formation
        $ctx = [
            'titre'         => data_get($formation, 'titre') ?? data_get($formation, 'title') ?? "Formation",
            'description'   => data_get($formation, 'description'),
            'type'          => data_get($formation, 'type'),
            'statut'        => data_get($formation, 'statut') ?? data_get($formation, 'status'),
            'date_debut'    => $this->fmtDate(data_get($formation, 'date_debut') ?? data_get($formation, 'debut') ?? data_get($formation, 'start_at') ?? data_get($formation, 'start')),
            'date_fin'      => $this->fmtDate(data_get($formation, 'date_fin')   ?? data_get($formation, 'fin')   ?? data_get($formation, 'end_at')   ?? data_get($formation, 'end')),
            'lieu'          => data_get($formation, 'lieu') ?? data_get($formation, 'adresse') ?? data_get($formation, 'location'),
            'lien_visio'    => data_get($formation, 'lien_visio') ?? data_get($formation, 'visio_link') ?? data_get($formation, 'meeting_url'),
            'capacite'      => data_get($formation, 'capacite') ?? data_get($formation, 'capacity'),
            'prix'          => data_get($formation, 'prix') ?? data_get($formation, 'price'),
            'organisateur'  => optional(data_get($formation, 'organisateur') ?? data_get($formation, 'organizer'))->name
                            ?? optional($formation->organisateur ?? $formation->organizer)->name
                            ?? optional($formation->user)->name,
            'organisateur_email' => optional(data_get($formation, 'organisateur') ?? data_get($formation, 'organizer'))->email
                            ?? optional($formation->organisateur ?? $formation->organizer)->email
                            ?? optional($formation->user)->email,
            'restantes'     => method_exists($formation, 'placesRestantes') ? $formation->placesRestantes() : null,
        ];

        // -------- Prompt (selon mode)
        if ($mode === 'general') {
            $system = "Tu es un assistant fiable et utile en français. Réponds clairement et brièvement. "
                    . "Si la question touche à des sujets sensibles (médical, légal, financier), donne un avis général et recommande de consulter un professionnel. "
                    . "Évite toute information dangereuse ou illégale. "
                    . "Si la question concerne la formation ci-dessous, base-toi sur le contexte. Sinon, réponds de façon générale.\n\n"
                    . "Contexte formation (si pertinent):\n"
                    . "- Titre: {$ctx['titre']}\n"
                    . "- Type: " . ($ctx['type'] ?? 'non renseigné') . "\n"
                    . "- Statut: " . ($ctx['statut'] ?? 'non renseigné') . "\n"
                    . "- Début: " . ($ctx['date_debut'] ?? 'non renseigné') . "\n"
                    . "- Fin: "   . ($ctx['date_fin']   ?? 'non renseigné') . "\n"
                    . "- Lieu: "  . ($ctx['lieu']       ?? 'non renseigné') . "\n"
                    . "- Visioconférence: " . ($ctx['lien_visio'] ? 'oui' : 'non / non renseigné') . "\n"
                    . "- Capacité: " . ($ctx['capacite'] ?? 'non renseigné') . "\n"
                    . "- Places restantes: " . ($ctx['restantes'] ?? 'non renseigné') . "\n"
                    . "- Prix: " . ($ctx['prix'] ?? 'non renseigné') . "\n"
                    . "- Organisateur: " . ($ctx['organisateur'] ?? 'non renseigné') . " (" . ($ctx['organisateur_email'] ?? 'email non renseigné') . ")\n"
                    . "- Description: " . ($ctx['description'] ?? '—') . "\n";
        } else {
            $system = "Tu es l’assistant d’une formation. Réponds brièvement, factuellement, "
                    . "en te basant UNIQUEMENT sur le contexte fourni. Si une information manque, dis-le poliment "
                    . "et propose de contacter l’organisateur.\n\n"
                    . "Contexte formation:\n"
                    . "- Titre: {$ctx['titre']}\n"
                    . "- Type: " . ($ctx['type'] ?? 'non renseigné') . "\n"
                    . "- Statut: " . ($ctx['statut'] ?? 'non renseigné') . "\n"
                    . "- Début: " . ($ctx['date_debut'] ?? 'non renseigné') . "\n"
                    . "- Fin: "   . ($ctx['date_fin']   ?? 'non renseigné') . "\n"
                    . "- Lieu: "  . ($ctx['lieu']       ?? 'non renseigné') . "\n"
                    . "- Visioconférence: " . ($ctx['lien_visio'] ? 'oui' : 'non / non renseigné') . "\n"
                    . "- Capacité: " . ($ctx['capacite'] ?? 'non renseigné') . "\n"
                    . "- Places restantes: " . ($ctx['restantes'] ?? 'non renseigné') . "\n"
                    . "- Prix: " . ($ctx['prix'] ?? 'non renseigné') . "\n"
                    . "- Organisateur: " . ($ctx['organisateur'] ?? 'non renseigné') . " (" . ($ctx['organisateur_email'] ?? 'email non renseigné') . ")\n"
                    . "- Description: " . ($ctx['description'] ?? '—') . "\n"
                    . "Si l’utilisateur demande des choses hors sujet (par ex. météo), recentre-le.";
        }

        $reply  = null;
        $usedLLM = false;

        // -------- Appel LLM (OpenAI)
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        $model  = config('services.openai.model', env('OPENAI_MODEL', 'gpt-4o-mini'));

        try {
            if (!empty($apiKey)) {
                // Client OpenAI PHP (openai-php/client)
                $client = \OpenAI::client($apiKey);
                $result = $client->chat()->create([
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user',   'content' => $userMsg],
                    ],
                    'temperature' => $mode === 'general' ? 0.4 : 0.2,
                ]);
                $reply   = trim($result->choices[0]->message->content ?? '');
                $usedLLM = true;
            }
        } catch (\Throwable $e) {
            Log::error('Chat LLM error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }

        // -------- Fallback si LLM KO / clé absente
        if (!$reply) {
            $reply = $mode === 'general'
                ? "Je peux répondre de manière générale, mais je n’ai pas pu joindre le modèle IA. Réessaie plus tard."
                : $this->fallbackAnswer($userMsg, $ctx);
        }

        // -------- Journalisation (si table + modèle existent)
        try {
            if (Schema::hasTable('formation_chat_messages') && class_exists(FormationChatMessage::class)) {
                // message utilisateur
                FormationChatMessage::create([
                    'formation_id' => $formation->id,
                    'user_id'      => Auth::id(),
                    'role'         => 'user',
                    'content'      => $userMsg,
                ]);
                // réponse assistant
                FormationChatMessage::create([
                    'formation_id' => $formation->id,
                    'user_id'      => Auth::id(),
                    'role'         => $usedLLM ? "assistant-llm:$mode" : 'assistant-fallback',
                    'content'      => $reply,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Chat log skipped: '.$e->getMessage());
        }

        return response()->json([
            'reply' => $reply,
            'mode'  => $mode,
        ]);
    }

    /**
     * --- Helpers offline : réponse simple sans LLM ---
     */
    private function fallbackAnswer(string $q, array $c): string
    {
        $qL = mb_strtolower($q);

        // Dates
        if (str_contains($qL, 'date') || str_contains($qL, 'début') || str_contains($qL, 'fin')) {
            $debut = $c['date_debut'] ?? 'non renseignée';
            $fin   = $c['date_fin']   ?? 'non renseignée';
            return "La formation « {$c['titre']} » commence le {$debut} et se termine le {$fin}.";
        }

        // Lieu / visio
        if (str_contains($qL, 'lieu') || str_contains($qL, 'où') || str_contains($qL, 'adresse')) {
            if (!empty($c['lieu'])) {
                return "Elle se tient à « {$c['lieu']} ».";
            }
            if (!empty($c['lien_visio'])) {
                return "Cette formation est en visioconférence. Lien: {$c['lien_visio']}.";
            }
            return "Le lieu n’est pas encore renseigné.";
        }

        if (str_contains($qL, 'visio') || str_contains($qL, 'en ligne') || str_contains($qL, 'zoom') || str_contains($qL, 'teams')) {
            return $c['lien_visio']
                ? "Oui, elle est en ligne. Lien de visioconférence: {$c['lien_visio']}."
                : "Aucun lien de visioconférence n’est renseigné pour le moment.";
        }

        // Prix
        if (str_contains($qL, 'prix') || str_contains($qL, 'payant') || str_contains($qL, 'gratuit')) {
            return isset($c['prix']) ? "Le prix indiqué est: {$c['prix']}." : "Le prix n’est pas renseigné.";
        }

        // Capacité / places
        if (str_contains($qL, 'place') || str_contains($qL, 'capacité') || str_contains($qL, 'inscription')) {
            $cap = $c['capacite'] ?? 'non renseignée';
            $res = $c['restantes'];
            $resTxt = $res === null ? 'non renseigné' : $res;
            return "Capacité: {$cap}. Places restantes: {$resTxt}.";
        }

        // Organisateur
        if (str_contains($qL, 'organisateur') || str_contains($qL, 'contact') || str_contains($qL, 'email')) {
            $org  = $c['organisateur'] ?? 'non renseigné';
            $mail = $c['organisateur_email'] ?? 'non renseigné';
            return "Organisateur: {$org} — Email: {$mail}.";
        }

        // Description / contenu
        if (str_contains($qL, 'contenu') || str_contains($qL, 'programme') || str_contains($qL, 'description') || str_contains($qL, 'objectif')) {
            return $c['description'] ? "Description: {$c['description']}" : "La description détaillée n’est pas disponible.";
        }

        // Par défaut
        $base = "Je n’ai pas cette information précisément. ";
        if ($c['organisateur_email']) {
            $base .= "Tu peux contacter l’organisateur à {$c['organisateur_email']}.";
        } elseif ($c['organisateur']) {
            $base .= "Tu peux contacter l’organisateur: {$c['organisateur']}.";
        } else {
            $base .= "Essaie de reformuler ta question (dates, lieu, prix, inscriptions…).";
        }
        return $base;
    }

    private function fmtDate($value): ?string
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value)->isoFormat('DD/MM/YYYY HH:mm');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }
}
