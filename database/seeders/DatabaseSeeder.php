<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        // Crée quelques utilisateurs basiques
        \App\Models\User::factory(10)->create();

        // Appelle le seeder de challenges
        $this->call(ChallengeSeeder::class);

        // ⚡ Si tu veux toujours ton utilisateur test
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
=======
        $this->call([
            DemoDataSeeder::class,
>>>>>>> d5aff4a5164f4361cb1432e9ba7aaa839255f3ba
        ]);
    }
}
