<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $categories = ['Formation', 'Challenge', 'Conférence', 'Atelier', 'Webinaire'];

        return [
            'title' => $this->faker->sentence(4),
            'location' => $this->faker->city(),
            'date' => $this->faker->dateTimeBetween('+1 week', '+6 months'),
            'category' => $this->faker->randomElement($categories),
            'details' => $this->faker->paragraph(4),
            'image' => $this->faker->imageUrl(800, 600, 'events', true, 'event'),
            'organizer_id' => User::factory(), // crée un user si non fourni
        ];
    }
}
