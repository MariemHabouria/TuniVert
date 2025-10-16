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

        ParticipantChallenge::create([
            'challenge_id'   => $id,
            'utilisateur_id' => Auth::id(),
            'statut'         => 'en_cours',
        ]);

        return redirect()->route('challenges.show', $id)
                         ->with('success', 'Vous participez maintenant à ce challenge !');
    }

    // CORRECTION : Soumettre une preuve
    public function soumettrePreuve(Request $request, $challengeId)
    {
        $request->validate([
            'preuve' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB max
        ]);

        // Trouver la participation de l'utilisateur
        $participant = ParticipantChallenge::where('challenge_id', $challengeId)
                        ->where('utilisateur_id', Auth::id())
                        ->first();

        if (!$participant) {
            return redirect()->back()->with('error', 'Vous ne participez pas à ce challenge.');
        }

        if ($participant->utilisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        // Stocker le fichier
        if ($request->hasFile('preuve')) {
            $file = $request->file('preuve');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('preuves', $fileName, 'public');
            
            $participant->preuve = $filePath;
            $participant->statut = 'en_cours';
            $participant->save();

            // Créer ou mettre à jour le score (mais pas encore attribué de points)
            $score = ScoreChallenge::updateOrCreate(
                ['participant_challenge_id' => $participant->id],
                ['points' => 0] // Points à 0 en attendant la validation
            );

            return redirect()->back()->with('success', 'Preuve soumise avec succès ! En attente de validation.');
        }

        return redirect()->back()->with('error', 'Erreur lors du téléchargement de la preuve.');
    }

    // Calcul des points selon la difficulté
    private function calculerPointsParDifficulte($difficulte)
    {
        return match($difficulte) {
            'facile' => 50,
            'moyen' => 100,
            'difficile' => 200,
            default => 50,
        };
    }

    // Déterminer le badge selon les points
    private function determinerBadge($points)
    {
        if ($points >= 200) return 'Or';
        if ($points >= 100) return 'Argent';
        if ($points >= 50) return 'Bronze';
        return null;
    }

    // Profil utilisateur avec badges et classement général
    public function profil()
    {
        $userId = Auth::id();

        // Récupérer toutes les participations de l'utilisateur
        $participations = ParticipantChallenge::with(['challenge', 'score'])
                            ->where('utilisateur_id', $userId)
                            ->get();

        // Calculer le classement général
        $classement = $this->calculerClassementGeneral();

        // Récupérer tous les badges de l'utilisateur
        $badges = ScoreChallenge::whereHas('participant', function($q) use ($userId) {
                            $q->where('utilisateur_id', $userId);
                        })
                        ->whereNotNull('badge')
                        ->get();

        return view('challenges.participant.profil', compact('participations', 'badges', 'classement'));
    }

    // Calculer le classement général
    private function calculerClassementGeneral()
    {
        // Récupérer tous les utilisateurs avec leur score total
        $utilisateursAvecScores = ParticipantChallenge::with('utilisateur')
            ->get()
            ->groupBy('utilisateur_id')
            ->map(function ($participations) {
                $scoreTotal = $participations->sum(function ($participation) {
                    return $participation->score->points ?? 0;
                });
                
                return [
                    'utilisateur' => $participations->first()->utilisateur,
                    'score_total' => $scoreTotal,
                    'utilisateur_id' => $participations->first()->utilisateur_id
                ];
            })
            ->sortByDesc('score_total')
            ->values();

        // Ajouter le rang
        $classement = $utilisateursAvecScores->map(function ($item, $index) {
            $item['rang'] = $index + 1;
            return (object) $item;
        });

        return $classement;
    }

    // Classement d'un challenge spécifique
    public function classement($id)
    {
        $challenge = Challenge::findOrFail($id);

        // Récupère les participants avec leur utilisateur et leur score
        $participants = ParticipantChallenge::with(['utilisateur', 'score'])
            ->where('challenge_id', $id)
            ->where('statut', '!=', 'rejete')
            ->get();

        // Tri par score décroissant et ajout du rang
        $participants = $participants->sortByDesc(function ($participant) {
                return $participant->score->points ?? 0;
            })
            ->values()
            ->map(function ($participant, $index) {
                $participant->rang = $index + 1;
                return $participant;
            });

        return view('challenges.participant.classement', compact('challenge', 'participants'));
    }

    // 🔹 CRUD Association

    public function create() { 
        return view('challenges.association.create'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'categorie'   => 'nullable|string|max:255',
            'difficulte'  => 'nullable|in:facile,moyen,difficile',
            'objectif'    => 'nullable|integer',
        ]);

        $validated['organisateur_id'] = Auth::id();
        Challenge::create($validated);

        return redirect()->route('challenges.crud')->with('success', 'Challenge créé avec succès !');
    }

    public function crud()
    {
        $challenges = Challenge::where('organisateur_id', Auth::id())->get();
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
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'categorie'   => 'nullable|string|max:255',
            'difficulte'  => 'nullable|in:facile,moyen,difficile',
            'objectif'    => 'nullable|integer',
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

    public function actionParticipant(Request $request, $participantId)
    {
        $participant = ParticipantChallenge::findOrFail($participantId);

        // Vérifie que l'utilisateur connecté est l'organisateur
        if ($participant->challenge->organisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        if ($request->action == 'valider') {
            $participant->statut = 'valide';
            
            // Attribuer les points si validation
            $points = $this->calculerPointsParDifficulte($participant->challenge->difficulte);
            $score = ScoreChallenge::updateOrCreate(
                ['participant_challenge_id' => $participant->id],
                ['points' => $points]
            );
            $score->badge = $this->determinerBadge($score->points);
            $score->save();
            
        } elseif ($request->action == 'rejeter') {
            $participant->statut = 'rejete';
            
            // Supprimer le score si rejet
            if ($participant->score) {
                $participant->score->delete();
            }
        }

        $participant->save();

        return redirect()->back()->with('success', 'Action effectuée avec succès !');
    }
}