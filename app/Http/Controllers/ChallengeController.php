<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ParticipantChallenge;
use App\Models\ScoreChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
class ChallengeController extends Controller
{
    // Liste des challenges pour les participants
    public function index()
    {
        $challenges = Challenge::with('organisateur')->get();
        return view('challenges.participant.index', compact('challenges'));
    }

    // Détails d'un challenge
    public function show($id)
    {
        $challenge = Challenge::with(['participants.utilisateur', 'participants.score'])
                              ->findOrFail($id);

        $participantChallenge = null;
        if (Auth::check()) {
            $participantChallenge = ParticipantChallenge::where('challenge_id', $id)
                                    ->where('utilisateur_id', Auth::id())
                                    ->first();
        }

        return view('challenges.participant.show', compact('challenge', 'participantChallenge'));
    }

    // Participer à un challenge
    public function participer($id)
    {
        $exists = ParticipantChallenge::where('challenge_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->exists();

        if ($exists) {
            return redirect()->route('challenges.show', $id)
                             ->with('error', 'Vous participez déjà à ce challenge.');
        }

        $challenge = Challenge::findOrFail($id);

        ParticipantChallenge::create([
            'challenge_id'   => $challenge->id,
            'utilisateur_id' => Auth::id(),
            'statut'         => 'en_cours',
        ]);

        return redirect()->route('challenges.show', $id)
                         ->with('success', 'Vous participez maintenant à ce challenge !');
    }

    // Soumettre une preuve
public function soumettrePreuve(Request $request, $challengeId)
{
    $request->validate([
        'preuve' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
    ]);

    $participant = ParticipantChallenge::where('challenge_id', $challengeId)
                    ->where('utilisateur_id', Auth::id())
                    ->firstOrFail();

    if ($request->hasFile('preuve')) {
        $file = $request->file('preuve');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('preuves', $fileName, 'public');

        // Mettre à jour la preuve
        $participant->update([
            'preuve' => $filePath,
            'statut' => 'en_cours', // temporaire avant évaluation IA
        ]);

        // Évaluer la preuve avec HuggingFace
        $scorePourcentage = $this->evaluateProof(storage_path('app/public/' . $filePath));

        // Mettre à jour le statut selon IA
        $participant->update([
            'statut' => $scorePourcentage >= 50 ? 'valide' : 'rejete',
        ]);

        // Créer le score si valide
        if ($scorePourcentage >= 50) {
    $participant->update(['statut' => 'valide']);
    $challenge = $participant->challenge;
    $this->createScore($participant, $challenge);
    $this->recalculerClassementChallenge($challengeId);
} else {
    $participant->update(['statut' => 'rejete']);
}

// Mettre le score IA et le label dans la session pour affichage Blade
session()->flash('ai_score', $scorePourcentage / 100); // pourcentage en fraction (0-1)
session()->flash('ai_label', $scorePourcentage >= 50 ? 'valide' : 'rejete');

return redirect()->back()->with('success', 
    'Preuve soumise avec succès ! Statut : ' . ucfirst($participant->statut)
);
    }

    return redirect()->back()->with('error', 'Erreur lors du téléchargement de la preuve.');
}

/**
 * Fonction privée pour évaluer la preuve avec HuggingFace
 */
private function evaluateProof($filePath)
{
    $hfToken = env('HUGGINGFACE_API_KEY');

    // Lire le fichier en base64
    $fileContent = base64_encode(file_get_contents($filePath));

    // Appel API HuggingFace (exemple avec modèle image: vit-base-patch16-224)
    $response = Http::withHeaders([
        'Authorization' => "Bearer $hfToken",
    ])->post('https://api-inference.huggingface.co/models/google/vit-base-patch16-224', [
        'inputs' => $fileContent
    ]);

    $result = $response->json();

    // Vérifie que la réponse contient bien un score
    if (isset($result[0]['score'])) {
        $score = floatval($result[0]['score']); // score entre 0 et 1
        $pourcentage = round($score * 100, 2); // convertir en pourcentage
        return $pourcentage; // retourne le pourcentage exact
    }

    return 0; // si erreur
}


    // Créer ou mettre à jour le score et badge
    private function createScore(ParticipantChallenge $participant, Challenge $challenge)
    {
        if ($participant->statut !== 'valide') return;

        $points = match($challenge->difficulte) {
            'facile' => 50,
            'moyen' => 100,
            'difficile' => 200,
            default => 50,
        };

        $badge = match(true) {
            $points >= 200 => 'Or',
            $points >= 100 => 'Argent',
            $points >= 50  => 'Bronze',
            default => null,
        };

        ScoreChallenge::updateOrCreate(
            ['participant_challenge_id' => $participant->id],
            [
                'points' => $points,
                'badge' => $badge,
                'rang' => 0,
                'date_maj' => now(),
            ]
        );
    }

    // Profil utilisateur avec badges et classement général
    public function profil()
    {
        $userId = Auth::id();

        $participations = ParticipantChallenge::with(['challenge', 'score'])
                            ->where('utilisateur_id', $userId)
                            ->get();

        $classement = $this->calculerClassementGeneral();

        $badges = ScoreChallenge::whereHas('participant', function($query) use ($userId) {
                $query->where('utilisateur_id', $userId);
            })
            ->whereNotNull('badge')
            ->pluck('badge')
            ->unique()
            ->values();

        return view('challenges.participant.profil', compact('participations', 'badges', 'classement'));
    }

    private function calculerClassementGeneral()
    {
        $scoresUtilisateurs = ScoreChallenge::with('participant.utilisateur')
            ->get()
            ->groupBy('participant.utilisateur_id')
            ->map(function($scores, $utilisateurId) {
                $firstScore = $scores->first();
                return [
                    'utilisateur' => $firstScore->participant->utilisateur,
                    'score_total' => $scores->sum('points'),
                    'utilisateur_id' => $utilisateurId
                ];
            })
            ->sortByDesc('score_total')
            ->values()
            ->map(function($item, $index) {
                return (object) array_merge($item, ['rang' => $index + 1]);
            });

        return $scoresUtilisateurs;
    }

    public function classement($id)
    {
        $challenge = Challenge::findOrFail($id);

        $participants = ParticipantChallenge::with(['utilisateur', 'score'])
            ->where('challenge_id', $id)
            ->where('statut', 'valide')
            ->get()
            ->sortByDesc(fn($p) => $p->score ? $p->score->points : 0)
            ->values();

        foreach ($participants as $index => $participant) {
            $rang = $index + 1;
            if ($participant->score) {
                $participant->score->update([
                    'rang' => $rang,
                    'date_maj' => now()
                ]);
            }
            $participant->current_rang = $rang;
        }

        return view('challenges.participant.classement', compact('challenge', 'participants'));
    }

    public function actionParticipant(Request $request, $participantId)
    {
        $participant = ParticipantChallenge::with(['challenge', 'score'])->findOrFail($participantId);

        if ($participant->challenge->organisateur_id != Auth::id()) abort(403);

        if ($request->action === 'valider') {
            $participant->update(['statut' => 'valide']);
            $this->createScore($participant, $participant->challenge);
            $this->recalculerClassementChallenge($participant->challenge_id);
        } elseif ($request->action === 'rejeter') {
            $participant->update(['statut' => 'rejete']);
            if ($participant->score) $participant->score->delete();
            $this->recalculerClassementChallenge($participant->challenge_id);
        }

        return redirect()->back()->with('success', 'Action effectuée avec succès !');
    }

    private function recalculerClassementChallenge($challengeId)
    {
        $participants = ParticipantChallenge::with('score')
            ->where('challenge_id', $challengeId)
            ->where('statut', 'valide')
            ->get()
            ->sortByDesc(fn($p) => $p->score ? $p->score->points : 0)
            ->values();

        foreach ($participants as $index => $participant) {
            if ($participant->score) {
                $participant->score->update([
                    'rang' => $index + 1,
                    'date_maj' => now()
                ]);
            }
        }
    }

    // Les méthodes CRUD et listes association
    public function create() { return view('challenges.association.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'categorie' => 'nullable|string|max:255',
            'difficulte' => 'nullable|in:facile,moyen,difficile',
            'objectif' => 'nullable|integer',
        ]);

        $validated['organisateur_id'] = Auth::id();
        Challenge::create($validated);

        return redirect()->route('challenges.crud')->with('success', 'Challenge créé avec succès !');
    }

    public function crud()
    {
        $user = Auth::user();
        $challenges = Challenge::where('organisateur_id', $user->id)
                        ->withCount('participants')
                        ->get();
        return view('challenges.association.index', compact('challenges'));
    }

    public function edit($id)
    {
        $challenge = Challenge::findOrFail($id);
        return view('challenges.association.edit', compact('challenge'));
    }

    public function update(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'categorie' => 'nullable|string|max:255',
            'difficulte' => 'nullable|in:facile,moyen,difficile',
            'objectif' => 'nullable|integer',
        ]);
        $challenge->update($validated);
        return redirect()->route('challenges.crud')->with('success', 'Challenge mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);
        $challenge->delete();
        return redirect()->route('challenges.crud')->with('success', 'Challenge supprimé avec succès !');
    }

    public function participants($id)
    {
        $challenge = Challenge::findOrFail($id);
        $participants = ParticipantChallenge::with(['utilisateur', 'score'])
                            ->where('challenge_id', $id)
                            ->get();
        return view('challenges.association.participants', compact('challenge', 'participants'));
    }

    public function associationIndex()
    {
        $user = Auth::user();
        $challenges = Challenge::where('organisateur_id', $user->id)
                    ->withCount('participants')
                    ->get();
        return view('challenges.association.index', compact('challenges'));
    }
    
}
