<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventsWithImagesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Find a user to be the organizer (or create one)
        $organizer = User::where('role', 'association')->first();
        
        if (!$organizer) {
            $organizer = User::create([
                'name' => 'TuniVert Admin',
                'email' => 'admin@tunivert.com',
                'password' => bcrypt('password'),
                'role' => 'association',
                'email_verified_at' => now(),
            ]);
        }

        $events = [
            [
                'title' => 'Nettoyage de la Plage de Sidi Bou Said',
                'location' => 'Plage de Sidi Bou Said, Tunis',
                'date' => Carbon::now()->addDays(7),
                'category' => 'Nettoyage de plage',
                'details' => 'Rejoignez-nous pour une journée de nettoyage de plage à Sidi Bou Said. Ensemble, protégeons notre magnifique côte tunisienne des déchets plastiques et préservons la biodiversité marine.',
                'image' => 'img/events/beach-cleanup.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => 'Plantation d\'Oliviers dans la Région de Kairouan',
                'location' => 'Kairouan, Tunisie',
                'date' => Carbon::now()->addDays(14),
                'category' => 'Plantation',
                'details' => 'Participez à notre initiative de reboisement en plantant des oliviers traditionnels tunisiens. Une action concrète pour lutter contre la désertification et préserver notre patrimoine agricole.',
                'image' => 'img/events/tree-planting.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => 'Campagne de Sensibilisation Écologique',
                'location' => 'Avenue Habib Bourguiba, Tunis',
                'date' => Carbon::now()->addDays(21),
                'category' => 'Sensibilisation',
                'details' => 'Stand d\'information et de sensibilisation sur les gestes éco-responsables au quotidien. Apprenez comment réduire votre empreinte carbone et adopter un mode de vie durable.',
                'image' => 'img/events/awareness-campaign.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => 'Conférence: L\'Avenir Écologique de la Tunisie',
                'location' => 'Université de Tunis, Amphithéâtre A',
                'date' => Carbon::now()->addDays(30),
                'category' => 'Conférence',
                'details' => 'Conférence internationale sur les défis environnementaux en Tunisie. Experts, chercheurs et acteurs de la société civile partageront leurs solutions pour un avenir durable.',
                'image' => 'img/events/conference.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => 'Nettoyage du Parc Belvedère',
                'location' => 'Parc du Belvedère, Tunis',
                'date' => Carbon::now()->addDays(10),
                'category' => 'Nettoyage',
                'details' => 'Action de nettoyage et d\'embellissement du parc du Belvedère. Redonnons à ce poumon vert de Tunis toute sa beauté naturelle.',
                'image' => 'img/events/beach-cleanup.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => 'Formation sur le Compostage Domestique',
                'location' => 'Centre Culturel de la Marsa',
                'date' => Carbon::now()->addDays(18),
                'category' => 'Formation',
                'details' => 'Atelier pratique pour apprendre à transformer vos déchets organiques en compost. Réduisez vos déchets tout en enrichissant votre jardin naturellement.',
                'image' => 'img/events/tree-planting.jpg',
                'organizer_id' => $organizer->id,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('Events with images have been seeded successfully!');
    }
}