<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Challenge;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    protected $model = Challenge::class;

    public function definition(): array
    {
        $debut = $this->faker->dateTimeBetween('now', '+1 week');
        $fin   = $this->faker->dateTimeBetween($debut, '+1 month');

        return [
            'titre'           => $this->faker->sentence(3),
            'description'     => $this->faker->paragraph(),
            'slug'            => $this->faker->unique()->slug(),
            'categorie'       => $this->faker->randomElement([
                'Recyclage', 
                'Énergie renouvelable', 
                'Nettoyage de quartier', 
                'Sensibilisation environnementale', 
                'Plantation d\'arbres'
            ]),
            'difficulte'      => $this->faker->randomElement(['facile', 'moyen', 'difficile']),
            'objectif'        => $this->faker->numberBetween(10, 100),
            'organisateur_id' => User::factory()->state(['role' => 'association'])->create()->id,
            'date_debut'      => $debut,
            'date_fin'        => $fin,
            'actif'           => $this->faker->boolean(80),
        ];
    }
}