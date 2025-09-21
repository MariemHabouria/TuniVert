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

    // Détails d’un challenge
    public function show($id)
    {
        $challenge = Challenge::with(['participants.utilisateur', 'participants.score'])->findOrFail($id);

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
        $challenge = Challenge::findOrFail($id);

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
            'statut'         => 'en_cours', // doit correspondre à la valeur autorisée dans la BDD
            'score'          => 0,
        ]);

        return redirect()->route('challenges.show', $id)
                         ->with('success', 'Vous participez maintenant à ce challenge !');
    }

    // Soumettre une preuve
    public function soumettrePreuve(Request $request, $id)
    {
        $request->validate([
            'preuve' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $participant = ParticipantChallenge::findOrFail($id);

        // Vérifie que l'utilisateur est bien le participant
        if ($participant->utilisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        $participant->preuve = $request->file('preuve')->store('preuves', 'public');
        $participant->statut = 'termine'; // doit correspondre aux valeurs BDD
        $participant->save();

        return redirect()->back()->with('success', 'Preuve soumise avec succès !');
    }

    // Classement général d’un challenge
    public function classement($id)
    {
        $challenge = Challenge::with(['participants.utilisateur', 'participants.score'])
                              ->findOrFail($id);

        $participants = $challenge->participants->sortByDesc(function($p) {
            return $p->score ?? 0;
        });

        // On réutilise la vue profil si tu n’as pas de vue classement séparée
        return view('challenges.participant.profil', [
            'participations' => $participants,
            'badges' => [], // tu peux adapter si nécessaire
            'classement' => $participants,
        ]);
    }

    // CRUD pour association
    public function create()
    {
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

    public function participants($id)
    {
        $challenge = Challenge::findOrFail($id);
        $participants = ParticipantChallenge::with(['utilisateur', 'score'])
                            ->where('challenge_id', $id)
                            ->get();

        return view('challenges.association.participants', compact('challenge', 'participants'));
    }

    // Profil utilisateur avec badges et classement
    public function profil()
    {
        $userId = Auth::id();

        $participations = ParticipantChallenge::with('challenge')
                            ->where('utilisateur_id', $userId)
                            ->get();

        $badges = ScoreChallenge::whereHas('participant', function($q) use ($userId) {
                        $q->where('utilisateur_id', $userId);
                    })->get();

        $classement = ParticipantChallenge::with(['utilisateur', 'score'])->get()
                        ->sortByDesc(function($p){ return $p->score ? $p->score->points : 0; });

        return view('challenges.participant.profil', compact('participations', 'badges', 'classement'));
    }
}
