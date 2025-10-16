<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
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

    
    // 🔹 Ajouter ici la partie "Recommended"
    $recommendedEvents = collect(); // collection vide par défaut
    $jsonPath = base_path('recommendations.json');

    if (auth()->check() && file_exists($jsonPath)) {
        $data = json_decode(file_get_contents($jsonPath), true);
        $userId = auth()->id();
        $eventIds = $data[$userId] ?? [];

        if (!empty($eventIds)) {
            $recommendedEvents = Event::whereIn('id', $eventIds)->take(5)->get();
        }
    }


    // Si AJAX → renvoyer seulement les résultats (HTML partiel)
    if ($request->ajax()) {
        return view('events.partials.list', compact('events'))->render();
    }

    return view('events.index', compact('events', 'q', 'categories', 'category', 'recommendedEvents'));
}

    // Formulaire de création
    public function create()
    {
        return view('events.create');
    }

    // Enregistrer un événement
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'details' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Préparer uniquement les champs fillables
        $data = $request->only(['title', 'location', 'date', 'category', 'details']);
        $data['organizer_id'] = auth()->id();

      if ($request->hasFile('image')) {
    $path = $request->file('image')->store('events', 'public'); // stocke dans storage/app/public/events
    $data['image'] = $path; // enregistre le chemin relatif
}

        // Création de l'événement
        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Événement créé ✅');
    }

    // Afficher un événement
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    // Formulaire d'édition
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    // Mise à jour
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

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
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

    // Vérifier si l'utilisateur connecté est l'organisateur
    if ($event->organizer_id !== auth()->id()) {
        return redirect()->route('events.index')->with('error', 'Vous ne pouvez pas supprimer cet événement ❌');
    }

    $event->delete();
    return redirect()->route('events.index')->with('success', 'Événement supprimé ✅');
}

    // Participation à un événement
    public function participate($id)
{
    $event = Event::findOrFail($id);

    // Vérifier si l'utilisateur est déjà participant
    if ($event->participants()->where('user_id', auth()->id())->exists()) {
        return redirect()->back()->with('info', 'Vous participez déjà à cet événement.');
    }

    // Ajouter la participation
    Participant::create([
        'event_id' => $event->id,
        'user_id' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Votre participation a été enregistrée.');
}
    // Recommandation d'événements
    public function showRecommendations($userId)
{
    $jsonPath = base_path('python/recommendations.json');

    if (!file_exists($jsonPath)) {
        return redirect()->back()->with('error', 'Recommandations non générées.');
    }

    $data = json_decode(file_get_contents($jsonPath), true);

    $eventIds = $data[$userId] ?? [];
    $events = \App\Models\Event::whereIn('id', $eventIds)->get();

    return view('events.recommendations', compact('events'));
}

}
