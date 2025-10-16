<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'location' => $this->faker->city,
            'date' => $this->faker->dateTimeBetween('+1 days', '+1 year'),
            'category' => $this->faker->randomElement(['Conférence', 'Atelier', 'Campagne', 'Formation']),
            'details' => $this->faker->paragraph(),
            'image' => null, // tu peux mettre un lien d'image factice si tu veux
            'organizer_id' => User::factory(), // crée un utilisateur organisateur
        ];
    }
}
