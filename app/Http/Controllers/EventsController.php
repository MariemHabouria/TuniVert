<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        // Données de démonstration pour les événements
        $events = collect([
            [
                'id' => 1,
                'title' => 'Campagne Organique',
                'category' => 'Agriculture',
                'location' => 'Tunis',
                'description' => 'Promotion de l\'agriculture biologique et soutien aux fermiers locaux.',
                'image' => '/img/causes-1.jpg',
                'goal_amount' => 5000.00,
                'collected_amount' => 3250.00,
                'progress' => 65,
                'status' => 'active',
                'end_date' => '2025-12-31'
            ],
            [
                'id' => 2,
                'title' => 'Écosystème Durable',
                'category' => 'Environnement',
                'location' => 'Sousse',
                'description' => 'Protection des écosystèmes marins et terrestres de la région.',
                'image' => '/img/causes-2.jpg',
                'goal_amount' => 8000.00,
                'collected_amount' => 4800.00,
                'progress' => 60,
                'status' => 'active',
                'end_date' => '2025-11-30'
            ],
            [
                'id' => 3,
                'title' => 'Recyclage Communautaire',
                'category' => 'Nettoyage',
                'location' => 'Sfax',
                'description' => 'Initiative de recyclage et sensibilisation dans les quartiers.',
                'image' => '/img/causes-3.jpg',
                'goal_amount' => 3000.00,
                'collected_amount' => 2100.00,
                'progress' => 70,
                'status' => 'active',
                'end_date' => '2025-10-31'
            ],
            [
                'id' => 4,
                'title' => 'Journée de Sensibilisation',
                'category' => 'Éducation',
                'location' => 'Monastir',
                'description' => 'Sensibilisation aux enjeux environnementaux dans les écoles.',
                'image' => '/img/causes-4.jpg',
                'goal_amount' => 2000.00,
                'collected_amount' => 1800.00,
                'progress' => 90,
                'status' => 'active',
                'end_date' => '2025-09-30'
            ],
            [
                'id' => 5,
                'title' => 'Forêts Urbaines',
                'category' => 'Environnement',
                'location' => 'Tunis',
                'description' => 'Plantation d\'arbres et création d\'espaces verts en ville.',
                'image' => '/img/service-1.jpg',
                'goal_amount' => 6000.00,
                'collected_amount' => 2400.00,
                'progress' => 40,
                'status' => 'active',
                'end_date' => '2026-01-31'
            ],
            [
                'id' => 6,
                'title' => 'Nettoyage des Plages',
                'category' => 'Nettoyage',
                'location' => 'Sousse',
                'description' => 'Opérations de nettoyage et protection du littoral.',
                'image' => '/img/service-2.jpg',
                'goal_amount' => 4000.00,
                'collected_amount' => 3600.00,
                'progress' => 90,
                'status' => 'active',
                'end_date' => '2025-10-15'
            ]
        ]);

        // Filtres
        $location = $request->get('location');
        $category = $request->get('category');
        $search = $request->get('search');

        if ($location) {
            $events = $events->filter(function($event) use ($location) {
                return stripos($event['location'], $location) !== false;
            });
        }

        if ($category) {
            $events = $events->filter(function($event) use ($category) {
                return stripos($event['category'], $category) !== false;
            });
        }

        if ($search) {
            $events = $events->filter(function($event) use ($search) {
                return stripos($event['title'], $search) !== false || 
                       stripos($event['description'], $search) !== false;
            });
        }

        // Listes pour les filtres
        $locations = ['Tunis', 'Sousse', 'Sfax', 'Monastir', 'Gabès', 'Bizerte'];
        $categories = ['Agriculture', 'Environnement', 'Nettoyage', 'Éducation', 'Sensibilisation'];

        return view('pages.events-browse', compact('events', 'locations', 'categories'));
    }

    public function show($id)
    {
        // Données de démonstration pour un événement spécifique
        $events = [
            1 => [
                'id' => 1,
                'title' => 'Campagne Organique',
                'category' => 'Agriculture',
                'location' => 'Tunis',
                'description' => 'Promotion de l\'agriculture biologique et soutien aux fermiers locaux. Cette initiative vise à encourager les pratiques agricoles durables et à soutenir les petits producteurs de la région.',
                'image' => '/img/causes-1.jpg',
                'goal_amount' => 5000.00,
                'collected_amount' => 3250.00,
                'progress' => 65,
                'status' => 'active',
                'end_date' => '2025-12-31',
                'organizer' => 'Association TuniVert',
                'start_date' => '2025-01-01'
            ],
            2 => [
                'id' => 2,
                'title' => 'Écosystème Durable',
                'category' => 'Environnement',
                'location' => 'Sousse',
                'description' => 'Protection des écosystèmes marins et terrestres de la région de Sousse. Ce projet comprend la restauration des habitats naturels et la sensibilisation des communautés locales.',
                'image' => '/img/causes-2.jpg',
                'goal_amount' => 8000.00,
                'collected_amount' => 4800.00,
                'progress' => 60,
                'status' => 'active',
                'end_date' => '2025-11-30',
                'organizer' => 'Eco-Sousse',
                'start_date' => '2025-03-01'
            ]
        ];

        $event = $events[$id] ?? abort(404);
        
        return view('pages.event-detail', compact('event'));
    }
}