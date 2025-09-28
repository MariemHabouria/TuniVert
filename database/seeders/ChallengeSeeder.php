<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Challenge;
use App\Models\ParticipantChallenge;

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

            // 3️⃣ Pour chaque challenge, créer 5 participants avec score automatique
            $challenges->each(function ($challenge) use ($users) {
                ParticipantChallenge::factory(5)->create()->each(function ($participant) use ($challenge, $users) {
                    $participant->challenge_id = $challenge->id;
                    $participant->utilisateur_id = $users->random()->id;
                    $participant->preuve = rand(0,1) 
                        ? 'https://via.placeholder.com/640x480.png?text=image' 
                        : 'https://sample-videos.com/video123/mp4/240/big_buck_bunny_240p_1mb.mp4';
                    $participant->save();

                    // $participant->score est déjà créé via afterCreating
                });
            });
        });
    }
}
