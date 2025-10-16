<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Challenge;
use App\Models\ParticipantChallenge;
use App\Models\ScoreChallenge;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Créer 5 associations et 20 utilisateurs simples
        $associations = User::factory(5)->create(['role' => 'association']);
        $users = User::factory(20)->create(['role' => 'user']);

        // 2️⃣ Pour chaque association, créer 3 challenges
        $associations->each(function ($association) use ($users) {
            $challenges = Challenge::factory(3)->create([
                'organisateur_id' => $association->id,
            ]);

            // 3️⃣ Pour chaque challenge, créer 5 participants uniques avec score automatique
            $challenges->each(function ($challenge) use ($users) {
                $participants = $users->random(5);

                foreach ($participants as $user) {
                    $participant = ParticipantChallenge::factory()->create([
                        'challenge_id'   => $challenge->id,
                        'utilisateur_id' => $user->id,
                        'preuve'         => rand(0,1) 
                                            ? 'https://via.placeholder.com/640x480.png?text=image' 
                                            : 'https://sample-videos.com/video123/mp4/240/big_buck_bunny_240p_1mb.mp4',
                        'statut'         => 'valide', // pour que le score soit pris en compte immédiatement
                    ]);

                    // Générer automatiquement un score pour ce participant
                    $points = rand(10, 200);
                    $badge = match(true) {
                        $points >= 200 => 'Or',
                        $points >= 100 => 'Argent',
                        $points >= 50  => 'Bronze',
                        default => null,
                    };

                    $score = ScoreChallenge::create([
                        'participant_challenge_id' => $participant->id,
                        'points' => $points,
                        'rang' => 0, // sera mis à jour juste après
                        'badge' => $badge,
                        'date_maj' => now(),
                    ]);
                }

                // 🔹 Calculer le rang pour chaque participant du challenge
                $participantsScores = ParticipantChallenge::with('score')
                    ->where('challenge_id', $challenge->id)
                    ->whereHas('score')
                    ->get()
                    ->sortByDesc(fn($p) => $p->score->points)
                    ->values();

                foreach ($participantsScores as $index => $p) {
                    $p->score->update([
                        'rang' => $index + 1,
                        'date_maj' => now(),
                    ]);
                }
            });
        });
    }
}
