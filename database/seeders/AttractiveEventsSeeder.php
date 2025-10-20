<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class AttractiveEventsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Find or create organizer
        $organizer = User::where('role', 'association')->first();
        
        if (!$organizer) {
            $organizer = User::create([
                'name' => 'TuniVert Organizer',
                'email' => 'organizer@tunivert.com',
                'password' => bcrypt('password'),
                'role' => 'association',
                'email_verified_at' => now(),
            ]);
        }

        $attractiveEvents = [
            [
                'title' => '🌊 Grand Nettoyage de la Plage de Sidi Bou Said',
                'location' => 'Plage de Sidi Bou Said, Tunis',
                'date' => Carbon::now()->addDays(7),
                'category' => 'Nettoyage de plage',
                'details' => '🏖️ Rejoignez-nous pour une action écologique majeure ! Ensemble, libérons nos côtes des déchets plastiques et préservons la beauté naturelle de Sidi Bou Said. Matériel fourni, collation offerte !',
                'image' => 'img/events/beach-cleanup.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => '🌳 Plantation Massive d\'Oliviers Centenaires',
                'location' => 'Kairouan, Tunisie',
                'date' => Carbon::now()->addDays(14),
                'category' => 'Plantation',
                'details' => '🫒 Participez à la renaissance de nos terres ! Plantez des oliviers traditionnels tunisiens et luttez contre la désertification. Une journée conviviale avec déjeuner traditionnel.',
                'image' => 'img/events/tree-planting.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => '♻️ Festival Écologique & Sensibilisation Verte',
                'location' => 'Avenue Habib Bourguiba, Tunis',
                'date' => Carbon::now()->addDays(21),
                'category' => 'Sensibilisation',
                'details' => '🎪 Un festival éco-responsable unique ! Stands interactifs, ateliers DIY, spectacles verts, et découverte des éco-gestes du quotidien. Entrée gratuite, fun garanti !',
                'image' => 'img/events/awareness-campaign.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => '🎓 Conférence Internationale: Tunisie Verte 2030',
                'location' => 'Université de Tunis, Grand Amphithéâtre',
                'date' => Carbon::now()->addDays(30),
                'category' => 'Conférence',
                'details' => '🌍 Rencontrez les leaders mondiaux de l\'écologie ! Experts internationaux, innovations vertes, solutions durables pour la Tunisie. Networking et certificat de participation.',
                'image' => 'img/events/conference.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => '🦋 Sauvegarde de la Biodiversité au Parc Belvedère',
                'location' => 'Parc du Belvedère, Tunis',
                'date' => Carbon::now()->addDays(10),
                'category' => 'Écosystème',
                'details' => '🌺 Action spéciale biodiversité ! Nettoyage écologique, création d\'habitats pour la faune, plantation de fleurs locales. Découvrez la richesse naturelle urbaine !',
                'image' => 'img/events/environmental-protection.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => '🏭 Atelier Zéro Déchet & Compostage Magique',
                'location' => 'Centre Culturel de la Marsa',
                'date' => Carbon::now()->addDays(18),
                'category' => 'Formation',
                'details' => '✨ Transformez vos déchets en or vert ! Apprenez le compostage domestique, créez vos produits naturels, et découvrez l\'art du zéro déchet. Kit de démarrage offert !',
                'image' => 'img/events/recycling.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => '🐠 Protection des Écosystèmes Marins',
                'location' => 'Port de Bizerte, Tunisie',
                'date' => Carbon::now()->addDays(25),
                'category' => 'Écosystème',
                'details' => '🌊 Plongée éco-citoyenne ! Nettoyage sous-marin, découverte de la faune marine, sensibilisation à la pollution aquatique. Équipement fourni, tous niveaux bienvenus !',
                'image' => 'img/events/wildlife-protection.jpg',
                'organizer_id' => $organizer->id,
            ],
            [
                'title' => '🤝 Action Communautaire: Quartier Vert',
                'location' => 'Cité Olympique, Tunis',
                'date' => Carbon::now()->addDays(12),
                'category' => 'Sensibilisation',
                'details' => '🏘️ Transformons ensemble notre quartier ! Création d\'espaces verts communautaires, ateliers éco-citoyens, et barbecue végétarien. Voisins de tous âges bienvenus !',
                'image' => 'img/events/community-action.jpg',
                'organizer_id' => $organizer->id,
            ],
        ];

        foreach ($attractiveEvents as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('Attractive events with beautiful images have been created successfully!');
    }
}