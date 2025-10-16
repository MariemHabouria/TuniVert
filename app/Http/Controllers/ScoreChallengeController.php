<?php

namespace App\Http\Controllers;

use App\Models\ParticipantChallenge;
use App\Models\ScoreChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreChallengeController extends Controller
{
    /**
     * Ajouter ou mettre à jour un score pour un participant
     */
    public function storeOrUpdate(Request $request, $participantId)
    {
        $request->validate([
            'points' => 'required|integer|min:0',
        ]);

        $participant = ParticipantChallenge::with('challenge')->findOrFail($participantId);

        // Vérification des permissions
        if (!Auth::check() || $participant->challenge->organisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        // Vérification que le participant est validé
        if ($participant->statut !== 'valide') {
            return redirect()->back()->with('error', 'Le participant doit être validé avant de pouvoir avoir un score.');
        }

        // Créer ou mettre à jour le score
        $score = ScoreChallenge::updateOrCreate(
            ['participant_challenge_id' => $participantId],
            [
                'points' => $request->points,
                'badge' => $this->determinerBadge($request->points),
                'rang' => 0, // sera recalculé
                'date_maj' => now()
            ]
        );

        // Recalculer le classement après modification
        $this->recalculerClassementChallenge($participant->challenge_id);

        return redirect()->back()->with('success', 'Score mis à jour avec succès !');
    }

    /**
     * Supprimer un score
     */
    public function destroy($id)
    {
        $score = ScoreChallenge::findOrFail($id);
        $participant = $score->participant;

        // Vérification des permissions
        if (!Auth::check() || $participant->challenge->organisateur_id != Auth::id()) {
            abort(403, "Accès refusé");
        }

        $challengeId = $participant->challenge_id;
        $score->delete();

        // Recalculer le classement après suppression
        $this->recalculerClassementChallenge($challengeId);

        return redirect()->back()->with('success', 'Score supprimé avec succès !');
    }

    /**
     * Déterminer le badge selon les points
     */
    private function determinerBadge($points)
    {
        if ($points >= 200) return 'Or';
        if ($points >= 100) return 'Argent';
        if ($points >= 50) return 'Bronze';
        return null;
    }

    /**
     * Recalculer le classement d'un challenge
     */
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

    /**
     * Classement d'un challenge spécifique ou du plus récent
     */
    public function classement($challengeId = null)
    {
        if ($challengeId) {
            $challenge = $challengeId === 'current'
                ? \App\Models\Challenge::latest()->firstOrFail()
                : \App\Models\Challenge::findOrFail($challengeId);

            $participants = ParticipantChallenge::with(['utilisateur', 'score'])
                ->where('challenge_id', $challenge->id)
                ->where('statut', 'valide')
                ->get()
                ->sortByDesc(fn($p) => $p->score ? $p->score->points : 0)
                ->values()
                ->map(function($participant, $index) {
                    $participant->current_rang = $index + 1;
                    return $participant;
                });

            return view('challenges.participant.classement', compact('participants', 'challenge'));
        }

        return $this->classementGlobal();
    }

    /**
     * Classement global (tous challenges confondus)
     */
    public function classementGlobal()
    {
        $scoresGrouped = ScoreChallenge::with('participant.utilisateur')
            ->get()
            ->groupBy('participant.utilisateur_id')
            ->map(function ($scores, $utilisateurId) {
                $firstScore = $scores->first();
                return [
                    'utilisateur' => $firstScore->participant->utilisateur,
                    'score_total' => $scores->sum('points'),
                    'badges' => $scores->pluck('badge')->filter()->unique()->values(),
                    'utilisateur_id' => $utilisateurId
                ];
            })
            ->sortByDesc('score_total')
            ->values()
            ->map(function($item, $index) {
                return (object) array_merge((array)$item, ['rang' => $index + 1]);
            });

        return view('challenges.participant.classement_global', [
            'participants' => $scoresGrouped
        ]);
    }
}
