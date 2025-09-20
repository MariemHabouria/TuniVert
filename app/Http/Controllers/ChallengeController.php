<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ParticipantChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    // Liste des challenges pour affichage
    public function index()
    {
        $challenges = Challenge::with('organisateur')->get();
        return view('challenges.index', compact('challenges'));
    }

    // Affichage d'un challenge spécifique
    public function show($id)
    {
        $challenge = Challenge::with('participants.utilisateur')->findOrFail($id);
        return view('challenges.show', compact('challenge'));
    }

    // Création d’un challenge (formulaire)
    public function create()
    {
        return view('challenges.create');
    }

    // Enregistrement d’un nouveau challenge
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

        // Récupérer l'utilisateur authentifié comme organisateur
        $validated['organisateur_id'] = Auth::id();

        $challenge = Challenge::create($validated);

        return redirect()->route('challenges.show', $challenge->id)
                         ->with('success', 'Challenge créé avec succès !');
    }

    // Inscription d'un utilisateur à un challenge
    public function participer($challenge_id)
    {
        $user_id = Auth::id();

        // Vérifier si déjà inscrit
        $exist = ParticipantChallenge::where('challenge_id', $challenge_id)
                    ->where('utilisateur_id', $user_id)
                    ->first();

        if ($exist) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à ce challenge.');
        }

        ParticipantChallenge::create([
            'challenge_id' => $challenge_id,
            'utilisateur_id' => $user_id,
            'statut' => 'inscrit',
        ]);

        return redirect()->back()->with('success', 'Inscription réussie !');
    }

    // Soumettre une preuve (photo/vidéo)
    public function soumettrePreuve(Request $request, $participant_id)
    {
        $validated = $request->validate([
            'preuve' => 'required|file|mimes:jpg,png,mp4,mov|max:20480', // 20MB
        ]);

        $participant = ParticipantChallenge::findOrFail($participant_id);

        $path = $request->file('preuve')->store('preuves', 'public');
        $participant->update(['preuve' => $path]);

        return redirect()->back()->with('success', 'Preuve soumise avec succès !');
    }

    // Classement d’un challenge
    public function classement($challenge_id)
    {
        $challenge = Challenge::findOrFail($challenge_id);
        $participants = ParticipantChallenge::where('challenge_id', $challenge_id)
                        ->orderByDesc('score')
                        ->with('utilisateur')
                        ->get();

        return view('challenges.classement', compact('challenge', 'participants'));
    }

    // Valider ou rejeter une preuve (organisateur)
    public function validerPreuve(Request $request, $participant_id)
    {
        $participant = ParticipantChallenge::findOrFail($participant_id);

        $decision = $request->input('decision'); // "accepter" ou "rejeter"

        if ($decision === 'accepter') {
            $participant->statut = 'terminé';
            $participant->score += 10; // points par défaut
        } else {
            $participant->preuve = null;
        }

        $participant->save();

        return redirect()->back()->with('success', 'Preuve traitée avec succès !');
    }
}
