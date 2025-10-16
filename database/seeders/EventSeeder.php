<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 10 utilisateurs
        $users = User::factory(10)->create();

        // Créer 5 événements par utilisateur
        $users->each(function ($user) {
            Event::factory(5)->create([
                'organizer_id' => $user->id,
            ]);
        });

        // Créer 30 participants
        Participant::factory(30)->create();

        // Créer 50 commentaires
        Comment::factory(50)->create();
    }
}
