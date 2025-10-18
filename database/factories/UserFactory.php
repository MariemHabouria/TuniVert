<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        // Rôle de l'utilisateur
        $role = $this->faker->randomElement(['user','association','admin']);

        // Générer matricule si association
        $matricule = $role === 'association'
            ? str_pad((string)$this->faker->numberBetween(1, 9999999), 7, '0', STR_PAD_LEFT).$this->faker->randomLetter()
            : null;

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'role' => $role,
            // Champs existants dans User.php
            'matricule' => $matricule,
            'is_blocked' => false,
            // Champs supplémentaires si tu veux les garder
            'is_association' => $role === 'association',        // ajouté
            'matricule_association' => $matricule,             // ajouté
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
