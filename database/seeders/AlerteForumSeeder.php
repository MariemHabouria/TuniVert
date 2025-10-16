<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlerteForum; // ✅ IMPORT du modèle
use App\Models\User;

class AlerteForumSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier s’il y a au moins un utilisateur
        $user = User::first() ?? User::factory()->create();

        // Créer quelques alertes
        AlerteForum::create([
            'titre'          => 'Fuite d’eau rue centrale',
            'description'    => 'Une fuite d’eau importante est signalée près du parc central.',
            'gravite'        => 'moyenne',
            'utilisateur_id' => $user->id,
        ]);

        AlerteForum::create([
            'titre'          => 'Incendie dans le parc nord',
            'description'    => 'Début d’incendie signalé, pompiers en route.',
            'gravite'        => 'feu',
            'utilisateur_id' => $user->id,
        ]);

        AlerteForum::create([
            'titre'          => 'Panne d’éclairage public',
            'description'    => 'Plusieurs lampadaires éteints dans le quartier sud.',
            'gravite'        => 'basse',
            'utilisateur_id' => $user->id,
        ]);
    }
}

