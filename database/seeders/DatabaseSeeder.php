<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ⚡ Crée ou garantit la présence d'un utilisateur admin fixe
        if (!User::where('email', 'admin@tunivert.tn')->exists()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@tunivert.tn',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'is_association' => false,
                'matricule_association' => null,
            ]);
        }

        // ⚡ Crée ou garantit la présence d'un utilisateur test
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

        // ✅ Appelle tous les seeders nécessaires
        $this->call([
            ChallengeSeeder::class,
            DonationSeeder::class,
            GamificationSeeder::class,
            DemoDataSeeder::class,

            // 👉 Ajoute tes seeders ici
            ForumSeeder::class,
            AlerteForumSeeder::class,
        ]);
    }
}