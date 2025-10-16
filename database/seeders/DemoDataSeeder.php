<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Formation;
use App\Models\RessourceFormation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1) Utilisateur "association"
        $assoc = User::updateOrCreate(
            ['email' => 'assoc@demo.tn'],
            [
                'name' => 'Association Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // change le mdp si tu veux
                'remember_token' => Str::random(10),
                'role' => 'association',
                'matricule' => '1234567A', // 7 chiffres + 1 lettre
            ]
        );

        // --- 2) Utilisateur simple
        $user = User::updateOrCreate(
            ['email' => 'user@demo.tn'],
            [
                'name' => 'User Demo',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role' => 'user',
                'matricule' => null,
            ]
        );

        // --- 3) Quelques formations de démonstration
        $f1 = Formation::updateOrCreate(
            ['titre' => 'Atelier Compostage'],
            [
                'description' => 'Apprendre à composter à la maison.',
                'categorie' => 'Environnement',
                'type' => 'atelier',
                'capacite' => 30,
                'organisateur_id' => $assoc->id,
                'image' => null,
                'lien_visio' => null,
                'statut' => 'ouverte',
            ]
        );

        $f2 = Formation::updateOrCreate(
            ['titre' => 'Webinaire Économie d’eau'],
            [
                'description' => 'Bonnes pratiques pour réduire sa consommation d’eau.',
                'categorie' => 'Sensibilisation',
                'type' => 'webinaire',
                'capacite' => 200,
                'organisateur_id' => $assoc->id,
                'image' => null,
                'lien_visio' => 'https://meet.example.com/eco-eau',
                'statut' => 'ouverte',
            ]
        );

        // --- 4) Ressources demo
        RessourceFormation::updateOrCreate(
            ['formation_id' => $f1->id, 'titre' => 'Guide Compost PDF'],
            ['type' => 'pdf', 'url' => 'https://example.com/guide-compost.pdf']
        );

        RessourceFormation::updateOrCreate(
            ['formation_id' => $f2->id, 'titre' => 'Vidéo tuto'],
            ['type' => 'video', 'url' => 'https://youtu.be/dQw4w9WgXcQ']
        );

        // --- 5) Inscription de l’utilisateur simple à une formation (si la table pivot existe)
        if (\Schema::hasTable('formation_user')) {
            $user->formationsInscrites()->syncWithoutDetaching([
                $f1->id => ['inscrit_at' => now()],
            ]);
        }
    }
}
