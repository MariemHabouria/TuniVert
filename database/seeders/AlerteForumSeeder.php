<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlerteForum;
use App\Models\User;

class AlerteForumSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier s'il y a au moins un utilisateur
        $user = User::first() ?? User::factory()->create();

        // Créer quelques alertes avec le statut correct pour l'ENUM
        AlerteForum::create([
            'titre'          => 'Fuite d\'eau rue centrale',
            'description'    => 'Une fuite d\'eau importante est signalée près du parc central. Débordement sur la chaussée.',
            'gravite'        => 'moyenne',
            'statut'         => 'en_cours',
            'localisation'   => 'Rue Centrale, près du Parc',
            'utilisateur_id' => $user->id,
        ]);

        AlerteForum::create([
            'titre'          => 'Incendie dans le parc nord',
            'description'    => 'Début d\'incendie signalé, pompiers en route. Évitez la zone.',
            'gravite'        => 'feu',
            'statut'         => 'en_cours',
            'localisation'   => 'Parc Nord, Quartier Résidentiel',
            'utilisateur_id' => $user->id,
        ]);

        AlerteForum::create([
            'titre'          => 'Panne d\'éclairage public',
            'description'    => 'Plusieurs lampadaires éteints dans le quartier sud. Prudence la nuit.',
            'gravite'        => 'basse',
            'statut'         => 'en_cours',
            'localisation'   => 'Quartier Sud, Avenue des Lilas',
            'utilisateur_id' => $user->id,
        ]);

        AlerteForum::create([
            'titre'          => 'Arbre tombé sur la route',
            'description'    => 'Un grand arbre est tombé suite aux intempéries. Route bloquée.',
            'gravite'        => 'haute',
            'statut'         => 'en_cours',
            'localisation'   => 'Boulevard Principal, Section Est',
            'utilisateur_id' => $user->id,
        ]);

        AlerteForum::create([
            'titre'          => 'Pollution de la rivière',
            'description'    => 'Déversement suspect observé dans la rivière. Équipes d\'analyse sur place.',
            'gravite'        => 'moyenne',
            'statut'         => 'resolue',
            'localisation'   => 'Rivière Ville, Pont Ouest',
            'utilisateur_id' => $user->id,
        ]);

        $this->command->info('🚨 ' . AlerteForum::count() . ' alertes de forum créées avec succès !');
    }
}
