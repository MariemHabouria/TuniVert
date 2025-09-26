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
        // si tu as ajouté ces colonnes : is_association (bool), matricule_association (nullable)
        $isAssociation = $this->faker->boolean(35); // ~35% d'associations

        // matricule RNE TN: 7 chiffres + 1 lettre
        $matricule = $isAssociation
            ? str_pad((string)$this->faker->numberBetween(1, 9999999), 7, '0', STR_PAD_LEFT).$this->faker->randomLetter()
            : null;

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // mot de passe par défaut
            'remember_token' => Str::random(10),

            // retire ces deux lignes si ton schéma ne les a pas
            'is_association' => $isAssociation,
            'matricule_association' => $matricule,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
