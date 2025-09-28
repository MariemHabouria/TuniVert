<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ScoreChallenge;
use App\Models\ParticipantChallenge;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScoreChallenge>
 */
class ScoreChallengeFactory extends Factory
{
    protected $model = ScoreChallenge::class;

    public function definition(): array
    {
        return [
            'participant_challenge_id' => ParticipantChallenge::factory(),
            'points' => $this->faker->numberBetween(0, 100),
            'rang'   => $this->faker->numberBetween(1, 10),
            'badge'  => $this->faker->randomElement(['bronze', 'argent', 'or']),
            'date_maj' => $this->faker->dateTime(),
        ];
    }
}