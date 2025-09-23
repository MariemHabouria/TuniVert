<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure some users exist
        if (User::count() < 5) {
            User::factory(5)->create();
        }

        // Seed donations
        Donation::factory()->count(30)->create();
    }
}
