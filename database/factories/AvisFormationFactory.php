<?php

namespace Database\Factories;

use App\Models\AvisFormation;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvisFormationFactory extends Factory
{
    protected $model = AvisFormation::class;

    public function definition(): array
    {
        return [
            'user_id'      => null, // défini dans le seeder
            'formation_id' => null, // défini dans le seeder
            'note'         => $this->faker->numberBetween(1, 5),
            'commentaire'  => $this->faker->boolean(70) ? $this->faker->sentences(2, true) : null,
        ];
    }
}
