<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Crée un admin fixe
        User::factory()->create([
            'name' => 'Admin Principal',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'matricule' => null,
        ]);

        // ✅ 5 associations avec matricule
        User::factory(5)->create([
            'role' => 'association',
        ]);

        // ✅ 10 utilisateurs simples
        User::factory(10)->create([
            'role' => 'user',
        ]);
    }
}
