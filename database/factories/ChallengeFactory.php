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
        return [
            'titre'           => $this->faker->sentence(3),
            'description'     => $this->faker->paragraph(),
            'organisateur_id' => User::factory()->state(['role' => 'association']),
            'date_debut'      => $this->faker->dateTimeBetween('now', '+1 week'),
            'date_fin'        => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'categorie'       => $this->faker->randomElement([
                'Recyclage', 
                'Énergie renouvelable', 
                'Nettoyage de quartier', 
                'Sensibilisation environnementale', 
                'Plantation d’arbres'
            ]),
            'difficulte'      => $this->faker->randomElement(['facile','moyen','difficile']),
            'objectif'        => $this->faker->numberBetween(10,100),
        ];
    }
}
