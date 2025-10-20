<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EvenementsController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        $events = Event::withCount(['participants', 'comments'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        // Calculate event statistics
        $stats = [
            'total_events' => Event::count(),
            'events_this_month' => Event::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
            'upcoming_events' => Event::where('date', '>=', now())->count(),
            'past_events' => Event::where('date', '<', now())->count(),
            'events_by_category' => Event::selectRaw('category, COUNT(*) as count')
                                        ->groupBy('category')
                                        ->orderBy('count', 'desc')
                                        ->get(),
            'recent_events' => Event::whereDate('created_at', '>=', now()->subDays(30))
                                   ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                   ->groupBy(DB::raw('DATE(created_at)'))
                                   ->orderBy('date')
                                   ->get(),
            'donations_by_event' => Event::leftJoin('donations', 'events.id', '=', 'donations.evenement_id')
                                        ->selectRaw('events.title, COALESCE(SUM(donations.montant), 0) as total_donations, COUNT(donations.id) as donation_count')
                                        ->groupBy('events.id', 'events.title')
                                        ->orderBy('total_donations', 'desc')
                                        ->limit(5)
                                        ->get()
        ];
        
        return view('admin.evenements.index', compact('events', 'stats'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return view('admin.evenements.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'details' => 'nullable|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['title', 'date', 'location', 'details', 'category']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/events'), $imageName);
            $data['image'] = 'img/events/' . $imageName;
        }

        Event::create($data);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement créé avec succès.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $evenement)
    {
        return view('admin.evenements.show', compact('evenement'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $evenement)
    {
        return view('admin.evenements.edit', compact('evenement'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $evenement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'details' => 'nullable|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['title', 'date', 'location', 'details', 'category']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($evenement->image && file_exists(public_path($evenement->image))) {
                unlink(public_path($evenement->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/events'), $imageName);
            $data['image'] = 'img/events/' . $imageName;
        }

        $evenement->update($data);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement modifié avec succès.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $evenement)
    {
        // Delete image if exists
        if ($evenement->image && file_exists(public_path($evenement->image))) {
            unlink(public_path($evenement->image));
        }

        $evenement->delete();

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement supprimé avec succès.');
    }

    /**
     * Get participants for an event (AJAX)
     */
    public function getParticipants(Event $evenement)
    {
        $participants = $evenement->participants()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'participants' => $participants->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'user' => [
                        'id' => $participant->user->id,
                        'name' => $participant->user->name,
                        'email' => $participant->user->email,
                        'avatar' => $participant->user->avatar ?? '/img/default-avatar.png'
                    ],
                    'created_at' => $participant->created_at->format('d/m/Y H:i'),
                    'created_at_human' => $participant->created_at->diffForHumans()
                ];
            })
        ]);
    }

    /**
     * Get comments for an event (AJAX)
     */
    public function getComments(Event $evenement)
    {
        $comments = $evenement->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'email' => $comment->user->email,
                        'avatar' => $comment->user->avatar ?? '/img/default-avatar.png'
                    ],
                    'created_at' => $comment->created_at->format('d/m/Y H:i'),
                    'created_at_human' => $comment->created_at->diffForHumans()
                ];
            })
        ]);
    }
}