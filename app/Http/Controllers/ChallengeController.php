<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ParticipantChallenge;
use App\Models\ScoreChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $participant = ParticipantChallenge::create([
            'challenge_id'   => $challenge->id,
            'utilisateur_id' => Auth::id(),
            'statut'         => 'en_cours',
        ]);

        // CORRECTION : Ne pas créer le score immédiatement, seulement après validation
        // $this->createScore($participant, $challenge);

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

            // CORRECTION : utiliser 'en_cours' puisque 'en_attente' n'existe pas
            $participant->update([
                'preuve' => $filePath,
                'statut' => 'en_cours', // Changé de 'en_attente' à 'en_cours'
            ]);

            return redirect()->back()->with('success', 'Preuve soumise avec succès ! En attente de validation.');
        }

        return redirect()->back()->with('error', 'Erreur lors du téléchargement de la preuve.');
    }

    // Crée ou met à jour le score et le badge
    private function createScore(ParticipantChallenge $participant, Challenge $challenge)
    {
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

        // CORRECTION : Vérification que le participant est validé
        if ($participant->statut !== 'valide') {
            return;
        }

        // CORRECTION : Utilisation de updateOrCreate avec tous les champs
        ScoreChallenge::updateOrCreate(
            ['participant_challenge_id' => $participant->id],
            [
                'points' => $points,
                'badge' => $badge,
                'rang' => 0, // Le rang sera calculé plus tard
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

        // CORRECTION : Récupérer les badges depuis ScoreChallenge
        $badges = ScoreChallenge::whereHas('participant', function($query) use ($userId) {
                $query->where('utilisateur_id', $userId);
            })
            ->whereNotNull('badge')
            ->pluck('badge')
            ->unique()
            ->values();

        return view('challenges.participant.profil', compact('participations', 'badges', 'classement'));
    }

    // Calcul du classement général avec rang
    private function calculerClassementGeneral()
    {
        // CORRECTION : Utiliser ScoreChallenge pour calculer les scores totaux
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

    // Classement d'un challenge avec mise à jour des rangs
    public function classement($id)
    {
        $challenge = Challenge::findOrFail($id);

        // CORRECTION : Récupérer les participants avec leurs scores
        $participants = ParticipantChallenge::with(['utilisateur', 'score'])
            ->where('challenge_id', $id)
            ->where('statut', 'valide') // CORRECTION : seulement les validés
            ->get()
            ->sortByDesc(function($participant) {
                return $participant->score ? $participant->score->points : 0;
            })
            ->values();

        // Mise à jour des rangs dans la base de données
        foreach ($participants as $index => $participant) {
            $rang = $index + 1;
            if ($participant->score) {
                $participant->score->update([
                    'rang' => $rang,
                    'date_maj' => now()
                ]);
            }
            // Pour l'affichage immédiat
            $participant->current_rang = $rang;
        }

        return view('challenges.participant.classement', compact('challenge', 'participants'));
    }

    // Actions sur participants
    public function actionParticipant(Request $request, $participantId)
    {
        $participant = ParticipantChallenge::with(['challenge', 'score'])->findOrFail($participantId);

        if ($participant->challenge->organisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        if ($request->action === 'valider') {
            $participant->statut = 'valide';
            $participant->save();

            // CORRECTION : Crée le score seulement après validation
            $this->createScore($participant, $participant->challenge);

            // Recalculer le classement après validation
            $this->recalculerClassementChallenge($participant->challenge_id);

        } elseif ($request->action === 'rejeter') {
            $participant->statut = 'rejete';
            $participant->save();
            
            if ($participant->score) {
                $participant->score->delete();
                // Recalculer le classement après suppression
                $this->recalculerClassementChallenge($participant->challenge_id);
            }
        }

        return redirect()->back()->with('success', 'Action effectuée avec succès !');
    }

    // Recalculer le classement d'un challenge
    private function recalculerClassementChallenge($challengeId)
    {
        $participants = ParticipantChallenge::with('score')
            ->where('challenge_id', $challengeId)
            ->where('statut', 'valide')
            ->get()
            ->sortByDesc(function($participant) {
                return $participant->score ? $participant->score->points : 0;
            })
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

    // ... (les autres méthodes CRUD restent inchangées)
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
        $user = auth()->user();
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
        $user = auth()->user();
        $challenges = Challenge::where('organisateur_id', $user->id)
                    ->withCount('participants')
                    ->get();
        return view('challenges.association.index', compact('challenges'));
    }
}
