<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crée quelques utilisateurs basiques
        \App\Models\User::factory(10)->create();

        // ⚡ Crée un admin fixe
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tunivert.tn',
            'password' => bcrypt('admin123'), // mot de passe pour se connecter
            'role' => 'admin',
            'is_association' => false,
            'matricule_association' => null,
        ]);

        // Appelle le seeder de challenges
        $this->call(ChallengeSeeder::class);

        // ⚡ Utilisateur test
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Appelle le seeder de données de démonstration
        $this->call(DemoDataSeeder::class);
    }
}
