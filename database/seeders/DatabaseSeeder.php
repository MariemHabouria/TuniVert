<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crée ou garantit la présence d'un utilisateur de test
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Complète le nombre d'utilisateurs démo jusqu'à 20
        $target = 20;
        $current = User::count();
        if ($current < $target) {
            User::factory($target - $current)->create();
        }

        // Seeders de contenu applicatif
        $this->call([
            ChallengeSeeder::class,
            DonationSeeder::class,
            GamificationSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
