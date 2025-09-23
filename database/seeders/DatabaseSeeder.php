<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Base users
        if (!User::where('email','test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Additional random users
        if (User::count() < 5) {
            User::factory(4)->create();
        }

        // Donations
        $this->call([
            DonationSeeder::class,
            GamificationSeeder::class,
        ]);
    }
}
