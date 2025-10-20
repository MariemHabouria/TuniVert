<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Services\DonationAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // Liste des événements avec recherche et filtres
    public function index(Request $request)
    {
        $q = $request->input('q');
        $category = $request->input('category');

        $categories = [
            'Nettoyage de plage',
            'Plantation d’arbres',
            'Sensibilisation au recyclage',
            'Conférence environnementale',
            'Campagne de réduction des déchets',
            'Énergie renouvelable',
            'Journée écologique scolaire',
            'Écotourisme',
            'Collecte de fonds écologique'
        ];

        $events = Event::query();

        if ($q) {
            $events->where(function($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                      ->orWhere('location', 'like', "%$q%")
                      ->orWhere('category', 'like', "%$q%");
            });
        }

        if ($category) {
            $events->where('category', $category);
        }

        $events = $events->latest()->paginate(10);

        // Recommandations améliorées
        $recommendedEvents = collect();
        $jsonPath = base_path('recommendations.json');

        if (auth()->check()) {
            $userId = auth()->id();
            
            // Essayer de charger depuis le fichier JSON
            if (file_exists($jsonPath)) {
                $data = json_decode(file_get_contents($jsonPath), true);
                $eventIds = $data[$userId] ?? [];
                if (!empty($eventIds)) {
                    $recommendedEvents = Event::whereIn('id', $eventIds)->take(5)->get();
                }
            }
            
            // Fallback: recommandations basées sur les catégories préférées de l'utilisateur
            if ($recommendedEvents->isEmpty()) {
                // Récupérer les événements auxquels l'utilisateur a participé
                $userParticipations = \DB::table('participants')
                    ->where('user_id', $userId)
                    ->pluck('event_id');
                
                if ($userParticipations->isNotEmpty()) {
                    // Trouver les catégories favorites
                    $favoriteCategories = Event::whereIn('id', $userParticipations)
                        ->groupBy('category')
                        ->selectRaw('category, COUNT(*) as count')
                        ->orderBy('count', 'desc')
                        ->pluck('category')
                        ->take(3);
                    
                    if ($favoriteCategories->isNotEmpty()) {
                        $recommendedEvents = Event::whereIn('category', $favoriteCategories)
                            ->whereNotIn('id', $userParticipations) // Exclure les événements déjà participés
                            ->where('date', '>=', now())
                            ->take(5)
                            ->get();
                    }
                }
                
                // Fallback final: événements populaires récents
                if ($recommendedEvents->isEmpty()) {
                    $recommendedEvents = Event::withCount('participants')
                        ->where('date', '>=', now())
                        ->orderBy('participants_count', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                }
            }
        } else {
            // Pour les utilisateurs non connectés: événements populaires
            $recommendedEvents = Event::withCount('participants')
                ->where('date', '>=', now())
                ->orderBy('participants_count', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        if ($request->ajax()) {
            return view('events.partials.list', compact('events'))->render();
        }

        return view('events.index', compact('events', 'q', 'categories', 'category', 'recommendedEvents'));
    }

    // Formulaire création événement
    public function create()
    {
        return view('events.create');
    }

    // Enregistrer événement
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'details' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'location', 'date', 'category', 'details']);
        $data['organizer_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $data['image'] = $path;
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Événement créé ✅');
    }

    // Afficher un événement avec calcul global des sentiments et suggestions IA
    public function show(Event $event)
    {
        $comments = $event->comments;

        // Compter les sentiments
        $sentimentCounts = [
            'positif' => 0,
            'neutre' => 0,
            'negatif' => 0,
            'en_attente' => 0,
        ];

        foreach ($comments as $comment) {
            $sentiment = $comment->sentiment;
            
            // Si pas de sentiment ou null, considérer comme "en attente"
            if (!$sentiment || empty($sentiment)) {
                $sentimentCounts['en_attente']++;
            } elseif (isset($sentimentCounts[$sentiment])) {
                $sentimentCounts[$sentiment]++;
            } else {
                // Sentiment non reconnu, mettre en attente
                $sentimentCounts['en_attente']++;
            }
        }

        $totalAnalyzed = array_sum($sentimentCounts);

        $sentimentPercent = [];
        if ($totalAnalyzed > 0) {
            foreach ($sentimentCounts as $key => $count) {
                $sentimentPercent[$key] = round($count / $totalAnalyzed * 100);
            }
        } else {
            $sentimentPercent = ['positif'=>0,'neutre'=>0,'negatif'=>0,'en_attente'=>0];
        }

        // Emoji dominant
        $dominantSentiment = array_keys($sentimentCounts, max($sentimentCounts))[0] ?? null;
        $dominantEmoji = match($dominantSentiment) {
            'positif' => '😊',
            'neutre'  => '😐',
            'negatif' => '😞',
            'en_attente' => '⏳',
            default => '❓',
        };

        // === DONATION AI SUGGESTIONS ===
        $donationSuggestion = null;
        if (auth()->check()) {
            $user = auth()->user();
            
            // Build context for AI suggestion
            $ctx = [
                'user_total_tnd' => $user->donations()->sum('montant') ?? 0,
                'user_count' => $user->donations()->count() ?? 0,
                'user_avg_tnd' => $user->donations()->avg('montant') ?? 0,
                'user_last_days' => $user->donations()->latest()->first()?->created_at?->diffInDays() ?? null,
                'user_badge_count' => $user->badges ?? 0,
                'default_method' => session('last_method', 'paymee'),
                'event_popularity' => $event->participants()->count() ?? 0,
                'event_days_left' => now()->diffInDays($event->date, false),
                'event_category' => $event->category ?? 'general',
                'event_goal_progress' => 0.0, // TODO: if you have event goals
                'hour' => now()->hour,
                'weekday' => now()->dayOfWeek,
                'is_mobile' => request()->header('User-Agent') && str_contains(request()->header('User-Agent'), 'Mobile') ? 1 : 0,
            ];

            try {
                $ai = app(DonationAI::class);
                $donationSuggestion = $ai->suggest($event->id, $user->id, $ctx);
                
                // Log the suggestion exposure for analytics
                \Log::info('donation_ai_suggestion_exposed', [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'suggestion' => $donationSuggestion,
                ]);
            } catch (\Exception $e) {
                \Log::error('Donation AI error: ' . $e->getMessage());
                $donationSuggestion = null;
            }
        }

        return view('events.show', compact('event', 'sentimentPercent', 'dominantEmoji', 'donationSuggestion'));
    }

    // Formulaire édition
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    // Mise à jour événement
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'details' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'location', 'date', 'category', 'details']);

        if ($request->hasFile('image')) {
            if ($event->image && Storage::exists('public/'.$event->image)) {
                Storage::delete('public/'.$event->image);
            }
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->storeAs('public/events', $imageName);
            $data['image'] = 'events/'.$imageName;
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Événement mis à jour ✅');
    }

    // Suppression
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->organizer_id !== auth()->id()) {
            return redirect()->route('events.index')->with('error', 'Vous ne pouvez pas supprimer cet événement ❌');
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Événement supprimé ✅');
    }

    // Participer à un événement
    public function participate($id)
    {
        $event = Event::findOrFail($id);

        if ($event->participants()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('info', 'Vous participez déjà à cet événement.');
        }

        Participant::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Votre participation a été enregistrée.');
    }

    // Recommandations
    public function showRecommendations($userId)
    {
        $jsonPath = base_path('python/recommendations.json');

        if (!file_exists($jsonPath)) {
            return redirect()->back()->with('error', 'Recommandations non générées.');
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        $eventIds = $data[$userId] ?? [];
        $events = Event::whereIn('id', $eventIds)->get();

        return view('events.recommendations', compact('events'));
    }
}