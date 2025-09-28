<?php

namespace App\Http\Controllers;
use App\Models\Challenge;

use App\Models\ParticipantChallenge;
use App\Models\ScoreChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ScoreChallengeController extends Controller
{
    // Ajouter ou mettre à jour un score pour un participant
    public function storeOrUpdate(Request $request, $participantId)
    {
        $request->validate([
            'points' => 'required|integer|min:0',
        ]);

        $participant = ParticipantChallenge::findOrFail($participantId);

        // Vérification des permissions (à adapter selon votre système d'authentification)
        if (!Auth::user() || $participant->challenge->organisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        $score = ScoreChallenge::updateOrCreate(
            ['participant_challenge_id' => $participantId],
            ['points' => $request->points]
        );

        // Définir badge basé sur les points
        $score->badge = $this->determinerBadge($score->points);
        $score->save();

        return redirect()->back()->with('success', 'Score mis à jour avec succès !');
    }

    // Déterminer le badge selon les points
    private function determinerBadge($points)
    {
        if ($points >= 200) return 'Or';
        if ($points >= 100) return 'Argent';
        if ($points >= 50) return 'Bronze';
        return null;
    }

    // Supprimer un score
    public function destroy($id)
    {
        $score = ScoreChallenge::findOrFail($id);
        $participant = $score->participant;

        // Vérification des permissions
        if (!Auth::user() || $participant->challenge->organisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        $score->delete();
        return redirect()->back()->with('success', 'Score supprimé avec succès !');
    }


public function classement($challengeId = null)
{
    if ($challengeId) {
        // Classement par challenge spécifique
        if ($challengeId === 'current') {
            $challenge = Challenge::latest()->firstOrFail();
        } else {
            $challenge = Challenge::findOrFail($challengeId);
        }

        $participants = ParticipantChallenge::with(['utilisateur', 'score'])
            ->where('challenge_id', $challenge->id)
            ->where('statut', '!=', 'rejete')
            ->get()
            ->sortByDesc(fn($p) => $p->score->points ?? 0)
            ->values()
            ->map(fn($p, $i) => tap($p, fn($p) => $p->rang = $i + 1));

        return view('challenges.participant.classement', compact('participants', 'challenge'));
    }

    // Classement global par participant (tous challenges confondus)
    $participantsGrouped = ParticipantChallenge::with(['utilisateur', 'score'])
        ->get()
        ->groupBy('utilisateur_id')
        ->map(function ($group) {
            $scoreTotal = $group->sum(fn($p) => $p->score->points ?? 0);
            return [
                'utilisateur' => $group->first()->utilisateur,
                'score_total' => $scoreTotal
            ];
        })
        ->sortByDesc('score_total')
        ->values()
        ->map(fn($item, $i) => (object) array_merge((array)$item, ['rang' => $i + 1]));

    return view('challenges.participant.classement_global', ['participants' => $participantsGrouped]);
}
public function classementGlobal()
{
    // Récupérer tous les participants avec leurs scores et utilisateurs
    $participantsGrouped = ParticipantChallenge::with(['utilisateur', 'score'])
        ->get()
        ->groupBy('utilisateur_id') // Regrouper par utilisateur
        ->map(function ($group) {
            $scoreTotal = $group->sum(fn($p) => $p->score->points ?? 0);
            return [
                'utilisateur' => $group->first()->utilisateur,
                'score_total' => $scoreTotal,
                'badges' => $group->pluck('score.badge')->filter() // badges existants
            ];
        })
        ->sortByDesc('score_total') // Tri décroissant
        ->values()
        ->map(fn($item, $i) => (object) array_merge((array)$item, ['rang' => $i + 1]));

    return view('challenges.participant.classement_global', [
        'participants' => $participantsGrouped
    ]);
}
}