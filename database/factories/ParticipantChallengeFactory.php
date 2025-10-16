<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ParticipantChallenge;
use App\Models\User;
use App\Models\Challenge;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParticipantChallenge>
 */
class ParticipantChallengeFactory extends Factory
{
    protected $model = ParticipantChallenge::class;

    public function definition(): array
    {
        // Choix aléatoire entre 'image' ou 'video'
        $typePreuve = $this->faker->randomElement(['image', 'video']);

        // Génération du lien selon le type
        $preuve = $typePreuve === 'image'
            ? $this->faker->imageUrl(640, 480, 'nature')      // image
            : $this->faker->url() . '/video.mp4';             // vidéo fictive

        return [
            'challenge_id'   => Challenge::factory(),
            'utilisateur_id' => User::factory()->state(['role' => 'user']),
            'statut'         => $this->faker->randomElement(['en_cours','complet','annule']),
            'preuve'         => $preuve,
        ];
    }
}
