<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ScoreChallenge;

class ScoreChallengeFactory extends Factory
{
    protected $model = ScoreChallenge::class;

    public function definition(): array
    {
        return [
            'participant_challenge_id' => null, // sera fourni par afterCreating
            'points' => $this->faker->numberBetween(10, 100),
            'rang' => $this->faker->numberBetween(1, 10),
            'badge' => $this->faker->randomElement(['Bronze','Argent','Or']),
            'date_maj' => now(),
        ];
    }
}
