<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer des associations comme organisateurs
        $associations = User::where('role', 'association')->get();

        // Si aucune association, créer quelques organisateurs
        if ($associations->isEmpty()) {
            $associations = User::factory(3)->create([
                'role' => 'association',
            ]);
        }

        // Créer 3 à 5 événements par association
        foreach ($associations as $association) {
            Event::factory(rand(3, 5))->create([
                'organizer_id' => $association->id,
            ]);
        }
    }
}
