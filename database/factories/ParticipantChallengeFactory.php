<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ParticipantChallenge;
use App\Models\User;
use App\Models\Challenge;
use App\Models\ScoreChallenge;

class ParticipantChallengeFactory extends Factory
{
    protected $model = ParticipantChallenge::class;

    public function definition(): array
    {
        $typePreuve = $this->faker->randomElement(['image', 'video']);
        $preuve = $typePreuve === 'image'
            ? $this->faker->imageUrl(640, 480, 'nature')
            : $this->faker->url() . '/video.mp4';

        return [
            'challenge_id'   => Challenge::factory(),
            'utilisateur_id' => User::factory()->state(['role' => 'user']),
            'statut'         => $this->faker->randomElement(['en_cours','valide','rejete']),
            'preuve'         => $preuve,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ParticipantChallenge $participant) {
            ScoreChallenge::factory()->create([
                'participant_challenge_id' => $participant->id,
                'points' => rand(10, 100),
                'rang' => 1,
                'badge' => ['Bronze','Argent','Or'][array_rand(['Bronze','Argent','Or'])],
                'date_maj' => now(),
            ]);

            $participant->refresh(); // recharge le score lié pour points/badge
        });
    }
}
