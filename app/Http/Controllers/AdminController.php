<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Challenge;
use App\Models\Forum;
use App\Models\AlerteForum;
use App\Models\User;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use App\Models\ScoreChallenge; // <- IMPORT DU MODELE

class AdminController extends Controller
{
    /**
     * Vérifie si l'utilisateur est admin
     */
    private function checkAdmin()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->send();
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        return true;
    }

    /**
     * Dashboard principal
     */
   public function dashboard()
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    // 🔥 Statistiques
    $forumsCount   = Forum::count();
    $topicsCount   = Forum::count();        // si pas de table "topics", garder Forum
    $messagesCount = AlerteForum::count();
    $membersCount  = User::count();

    // 🔥 Liste des derniers forums (par ex. 10 derniers)
    $forums = Forum::latest()->take(10)->get();

    return view('admin.dashboard.index', compact(
        'forumsCount',
        'topicsCount',
        'messagesCount',
        'membersCount',
        'forums'             // 👉 ajout ici
    ));
}

    /* ==============================
     *     UTILISATEURS
     * ==============================*/
public function utilisateursIndex(Request $request)
{
    $query = User::query();

    // Filtre par nom
    if ($request->filled('name')) {
        $query->where('name', 'like', '%'.$request->name.'%');
    }

    // Filtre par email
    if ($request->filled('email')) {
        $query->where('email', 'like', '%'.$request->email.'%');
    }

    // Filtre par rôle
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->orderBy('id', 'desc')->paginate(10);

    return view('admin.utilisateurs.index', compact('users'));
}

// Bloquer / Débloquer un utilisateur
public function toggleUser(User $user)
{
    // Interdire à l'admin de se bloquer lui-même
    if (auth()->id() === $user->id) {
        return redirect()->back()->with('error', "Vous ne pouvez pas vous bloquer vous-même.");
    }

    // Inverser l'état du blocage
    $user->is_blocked = !$user->is_blocked;
    $user->save();

    return redirect()->back()->with(
        'success',
        'Utilisateur ' . ($user->is_blocked ? 'bloqué' : 'débloqué') . ' avec succès.'
    );
}
public function associationsVerify(Request $request)
{
    $this->checkAdmin();

    $query = User::where('role', 'association');

    // Filtre par nom
    if ($request->filled('name')) {
        $query->where('name', 'like', '%'.$request->name.'%');
    }

    // Filtre par matricule
    if ($request->filled('matricule')) {
        $query->where('matricule', 'like', '%'.$request->matricule.'%');
    }

    // Filtre par statut de vérification
    if ($request->filled('verified')) {
        if ($request->verified == '1') {
            $query->whereNotNull('email_verified_at')
                  ->whereNotNull('matricule');
        } else {
            $query->where(function($q) {
                $q->whereNull('email_verified_at')
                  ->orWhereNull('matricule');
            });
        }
    }

    // Filtre par statut (actif/bloqué)
    if ($request->filled('status')) {
        if ($request->status == 'active') {
            $query->where('is_blocked', false);
        } elseif ($request->status == 'blocked') {
            $query->where('is_blocked', true);
        }
    }

    $users = $query->orderBy('created_at', 'desc')->get();

    return view('admin.utilisateurs.verify', compact('users'));
}

/**
 * Vérifier/Déverifier une association
 */
public function toggleVerifyAssociation(User $user)
{
    $this->checkAdmin();

    // Vérifier que c'est bien une association
    if ($user->role !== 'association') {
        return redirect()->back()->with('error', 'Cet utilisateur n\'est pas une association.');
    }

    // Si on veut vérifier, il faut un matricule
    if (!$user->email_verified_at && empty($user->matricule)) {
        return redirect()->back()->with('error', 'Impossible de vérifier cette association : aucun matricule fourni.');
    }

    // Toggle la vérification
    if ($user->email_verified_at) {
        // Révoquer la vérification
        $user->email_verified_at = null;
        $message = 'Association dévérifiée avec succès.';
    } else {
        // Vérifier l'association
        $user->email_verified_at = now();
        $message = 'Association vérifiée avec succès.';
        
        // Optionnel : Envoyer un email de notification
        try {
            Mail::to($user->email)->send(new \App\Mail\AssociationVerified($user));
        } catch (\Exception $e) {
            // Continuer même si l'email échoue
        }
    }

    $user->save();

    return redirect()->back()->with('success', $message);
}

/**
 * Mettre à jour le matricule d'une association
 */
public function updateAssociationMatricule(Request $request, User $user)
{
    $this->checkAdmin();

    // Vérifier que c'est bien une association
    if ($user->role !== 'association') {
        return redirect()->back()->with('error', 'Cet utilisateur n\'est pas une association.');
    }

    $request->validate([
        'matricule' => 'required|string|max:255|unique:users,matricule,' . $user->id,
    ], [
        'matricule.required' => 'Le matricule est obligatoire.',
        'matricule.unique' => 'Ce matricule est déjà utilisé par une autre association.',
    ]);

    $user->matricule = $request->matricule;
    $user->save();

    return redirect()->back()->with('success', 'Matricule mis à jour avec succès.');
}
    public function utilisateursCreate()
    {
        $this->checkAdmin();
        return view('admin.utilisateurs.create');
    }
   public function utilisateursStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role, // "admin" ou autre
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }
    public function utilisateursShow($id)
{
    $this->checkAdmin();
    $user = User::findOrFail($id);
    return view('admin.utilisateurs.show', compact('user'));
}

// Ajoutez cette méthode de suppression
public function utilisateursDestroy($id)
{
    $this->checkAdmin();
    
    if ($id === Auth::id()) {
        return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
    }
    
    $user = User::findOrFail($id);
    $user->delete();
    
    return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé avec succès.');
}
    public function utilisateursEdit($id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        return view('admin.utilisateurs.edit', compact('user'));
    }

    public function utilisateursUpdate(Request $request, $id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:user,admin,association',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function utilisateursRoles()
    {
        $this->checkAdmin();
        return view('admin.utilisateurs.roles');
    }


    /* ==============================
     *     ÉVÉNEMENTS
     * ==============================*/
    public function evenementsIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        try {
            // Statistics
            $totalEvents = Event::count();
            $eventsThisMonth = Event::whereMonth('date', now()->month)
                                   ->whereYear('date', now()->year)
                                   ->count();
            $totalParticipants = Participant::count();
            $totalComments = Comment::count();
            
            // Events with participants and comments count
            $events = Event::withCount(['participants', 'comments'])
                           ->with(['organizer', 'participants.user', 'comments.user'])
                           ->orderBy('date', 'desc')
                           ->paginate(10);

            // Recent activity
            $recentComments = Comment::with(['user', 'event'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();

            // Events by category
            $eventsByCategory = Event::selectRaw('category, COUNT(*) as count')
                                    ->groupBy('category')
                                    ->get();

            // Monthly participants trend
            $monthlyParticipants = Participant::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                             ->whereYear('created_at', now()->year)
                                             ->groupBy('month')
                                             ->orderBy('month')
                                             ->get();

            // Top events by participants
            $topEvents = Event::withCount('participants')
                             ->orderBy('participants_count', 'desc')
                             ->limit(5)
                             ->get();

            return view('admin.evenements.index', compact(
                'events',
                'totalEvents',
                'eventsThisMonth', 
                'totalParticipants',
                'totalComments',
                'recentComments',
                'eventsByCategory',
                'monthlyParticipants',
                'topEvents'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in evenementsIndex: ' . $e->getMessage());
            
            // Fallback with basic data
            $events = Event::orderBy('date', 'desc')->paginate(10);
            return view('admin.evenements.index', compact('events'));
        }
    }

    public function evenementsCreate()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.evenements.create');
    }

    public function evenementsCategories()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.evenements.categories');
    }

    /**
     * Challenges
     */
    public function challengesIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $challenges = Challenge::withCount('participants')->get();

        return view('admin.challenges.index', compact('challenges'));
    }

    public function challengesCreate()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.challenges.create');
    }

    public function challengesParticipations($id)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $challenge = Challenge::with('participants')->findOrFail($id);

        return view('admin.challenges.participations', compact('challenge'));
    }

    public function challengesScores($challengeId)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $challenge = Challenge::with(['participants.utilisateur', 'participants.score'])->findOrFail($challengeId);

        return view('admin.challenges.scores', compact('challenge'));
    }

public function allScores(Request $request)
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    $query = Challenge::with(['participants.utilisateur', 'participants.score']);

    // --- Filtres dynamiques ---
    if ($request->filled('challenge_id')) {
        $query->where('id', $request->challenge_id);
    }

    $challenges = $query->get();

    // Filtre badge au niveau des scores
    $scoresQuery = ScoreChallenge::query();
    if ($request->filled('badge')) {
        $scoresQuery->whereRaw('LOWER(badge) = ?', [strtolower($request->badge)]);
    }

    // Filtre par période
    if ($request->filled('periode')) {
        $scoresQuery->where('created_at', '>=', now()->subDays((int)$request->periode));
    }

    // Badges stats filtrés
    $badgesStats = $scoresQuery
        ->selectRaw('LOWER(badge) as badge, COUNT(*) as count')
        ->whereNotNull('badge')
        ->groupBy('badge')
        ->pluck('count', 'badge')
        ->toArray();

    // Statistiques générales
    $totalParticipants = $challenges->sum(fn($c) => $c->participants->count());
    $totalPoints = $challenges->sum(fn($c) => $c->participants->sum(fn($p) => $p->score->points ?? 0));
    $pointsMoyens = $totalParticipants > 0 ? round($totalPoints / $totalParticipants, 2) : 0;

    // Graphiques
    $performanceData = [];
    $performanceLabels = [];
    $participantsData = [];
    $participantsLabels = [];

    foreach($challenges->take(8) as $challenge) {
        $total = $challenge->participants->sum(fn($p) => $p->score->points ?? 0);
        $count = $challenge->participants->count();
        $avg = $count > 0 ? $total / $count : 0;
        $performanceData[] = round($avg, 1);
        $performanceLabels[] = \Illuminate\Support\Str::limit($challenge->titre, 20);
    }

    $popular = $challenges->sortByDesc(fn($c) => $c->participants->count())->take(6);
    foreach($popular as $challenge) {
        $participantsData[] = $challenge->participants->count();
        $participantsLabels[] = \Illuminate\Support\Str::limit($challenge->titre, 25);
    }

    $stats = [
        'total_challenges' => $challenges->count(),
        'total_participants' => $totalParticipants,
        'total_points' => $totalPoints,
        'points_moyens' => $pointsMoyens,
        'badges_count' => [
            'Or' => $badgesStats['or'] ?? 0,
            'Argent' => $badgesStats['argent'] ?? 0,
            'Bronze' => $badgesStats['bronze'] ?? 0,
        ]
    ];

    return view('admin.challenges.all_scores', compact(
        'challenges', 'stats',
        'performanceData', 'performanceLabels',
        'participantsData', 'participantsLabels'
    ));
}

public function toggleChallenge($id)
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    $challenge = Challenge::findOrFail($id);
    $challenge->actif = !$challenge->actif;
    $challenge->save();

    return redirect()->back()->with('success', 'Le statut du challenge a été mis à jour.');
}

public function challengesEdit($id)
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    $challenge = Challenge::findOrFail($id);
    return view('admin.challenges.edit', compact('challenge'));
}

public function challengesUpdate(Request $request, $id)
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'required|string',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after:date_debut',
        'categorie' => 'nullable|string',
        'difficulte' => 'nullable|in:facile,moyen,difficile',
        'objectif' => 'nullable|integer|min:1',
        'actif' => 'boolean'
    ]);

    $challenge = Challenge::findOrFail($id);
    $challenge->update([
        'titre' => $request->titre,
        'description' => $request->description,
        'date_debut' => $request->date_debut,
        'date_fin' => $request->date_fin,
        'categorie' => $request->categorie,
        'difficulte' => $request->difficulte,
        'objectif' => $request->objectif,
        'actif' => $request->has('actif')
    ]);

    return redirect()->route('admin.challenges.index')
                   ->with('success', 'Challenge mis à jour avec succès.');
}

public function participantAction(Request $request, $participantId)
{
    $this->checkAdmin();

    $request->validate([
        'action' => 'required|in:valider,rejeter',
    ]);

    $participant = \App\Models\Participant::findOrFail($participantId);

    if ($request->action === 'valider') {
        $participant->statut = 'valide';
    } elseif ($request->action === 'rejeter') {
        $participant->statut = 'rejete';
    }

    $participant->save();

    return redirect()->back()->with('success', 'Statut du participant mis à jour.');
}



    /* ==============================
     *     FORUMS
     * ==============================*/
    public function forumsIndex()
    {
        $this->checkAdmin();

        // Statistics
        $totalForums = Forum::count();
        $forumsThisMonth = Forum::whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->count();
        $activeForums = Forum::where('created_at', '>=', now()->subDays(7))->count();
        $totalReplies = class_exists('App\Models\ReponseForum') ? 
                       \App\Models\ReponseForum::count() : 0;

        // Forums with pagination and relationships
        $forums = Forum::with(['utilisateur'])
                       ->withCount(['reponses' => function($query) {
                           if (class_exists('App\Models\ReponseForum')) {
                               return $query;
                           }
                           return $query->whereRaw('1=0'); // Empty result if model doesn't exist
                       }])
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        // Recent activity
        $recentForums = Forum::with('utilisateur')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

        // Most popular forums by views
        $popularForums = Forum::orderBy('nb_vues', 'desc')
                             ->limit(5)
                             ->get();

        // Forums by category/topic - Using tags or create simple categories
        try {
            // Try to use tags column if it exists, otherwise create simple categories
            $forumsByTopic = Forum::selectRaw('
                CASE 
                    WHEN tags LIKE "%transport%" THEN "Transport"
                    WHEN tags LIKE "%energie%" OR tags LIKE "%énergie%" THEN "Énergie"
                    WHEN tags LIKE "%dechet%" OR tags LIKE "%déchet%" THEN "Déchets"
                    WHEN tags LIKE "%eau%" THEN "Eau"
                    WHEN tags LIKE "%environnement%" THEN "Environnement"
                    WHEN tags LIKE "%pollution%" THEN "Pollution"
                    ELSE "Général"
                END as topic, 
                COUNT(*) as count
            ')
            ->groupBy(DB::raw('
                CASE 
                    WHEN tags LIKE "%transport%" THEN "Transport"
                    WHEN tags LIKE "%energie%" OR tags LIKE "%énergie%" THEN "Énergie"
                    WHEN tags LIKE "%dechet%" OR tags LIKE "%déchet%" THEN "Déchets"
                    WHEN tags LIKE "%eau%" THEN "Eau"
                    WHEN tags LIKE "%environnement%" THEN "Environnement"
                    WHEN tags LIKE "%pollution%" THEN "Pollution"
                    ELSE "Général"
                END
            '))
            ->get();
        } catch (\Exception $e) {
            // Fallback if tags column doesn't exist or query fails
            $forumsByTopic = collect([
                (object)['topic' => 'Général', 'count' => $totalForums],
            ]);
        }

        return view('admin.forums.index', compact(
            'forums', 
            'totalForums', 
            'forumsThisMonth', 
            'activeForums', 
            'totalReplies',
            'recentForums',
            'popularForums',
            'forumsByTopic'
        ));
    }

    public function forumsCategories()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.forums.categories');
    }

    public function forumsModerations()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.forums.moderations');
    }
public function alertesIndex()
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    // Statistics
    $totalAlertes = AlerteForum::count();
    $alertesThisMonth = AlerteForum::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();
    $alertesHaute = AlerteForum::where('gravite', 'haute')->count();
    $alertesMoyenne = AlerteForum::where('gravite', 'moyenne')->count();
    $alertesBasse = AlerteForum::where('gravite', 'basse')->count();
    
    // Recent alerts
    $recentAlertes = AlerteForum::with('user')
                               ->orderBy('created_at', 'desc')
                               ->limit(5)
                               ->get();

    // Alerts by severity
    $alertesBySeverity = AlerteForum::selectRaw('gravite, COUNT(*) as count')
                                   ->groupBy('gravite')
                                   ->get();

    // Alerts by location (zone_geographique field)
    $alertesByLocation = AlerteForum::selectRaw('zone_geographique, COUNT(*) as count')
                                   ->whereNotNull('zone_geographique')
                                   ->groupBy('zone_geographique')
                                   ->limit(10)
                                   ->get();

    // Monthly trend
    $monthlyTrend = AlerteForum::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                              ->whereYear('created_at', now()->year)
                              ->groupBy('month')
                              ->orderBy('month')
                              ->get();

    $alertes = AlerteForum::with('user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

    return view('admin.alertes.index', compact(
        'alertes', 
        'totalAlertes', 
        'alertesThisMonth', 
        'alertesHaute', 
        'alertesMoyenne', 
        'alertesBasse',
        'recentAlertes',
        'alertesBySeverity',
        'alertesByLocation',
        'monthlyTrend'
    ));
}
    /* ==============================
     *     FORMATIONS
     * ==============================*/
    public function formationsIndex(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $query = \App\Models\Formation::with(['organisateur', 'inscrits']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('categorie', 'LIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Status filter
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $formations = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get statistics
        $stats = [
            'total' => \App\Models\Formation::count(),
            'active' => \App\Models\Formation::where('statut', 'actif')->count(),
            'draft' => \App\Models\Formation::where('statut', 'brouillon')->count(),
            'total_inscriptions' => \Illuminate\Support\Facades\DB::table('formation_user')->count(),
        ];

        // Get categories for filter
        $categories = \App\Models\Formation::distinct('categorie')->pluck('categorie')->filter();

        return view('admin.formations.index', compact('formations', 'stats', 'categories'));
    }

    public function formationsCreate()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Get unique categories for the dropdown
        $categories = \App\Models\Formation::distinct('categorie')->pluck('categorie')->filter();
        
        // Get potential organizers (associations and admins)
        $organisateurs = \App\Models\User::where(function($query) {
            $query->where('role', 'association')
                  ->orWhere('role', 'admin');
        })->orderBy('name')->get();

        return view('admin.formations.create', compact('categories', 'organisateurs'));
    }

    public function formationsStore(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie' => 'required|string|max:100',
            'type' => 'required|in:presentiel,ligne,hybride',
            'capacite' => 'nullable|integer|min:1',
            'organisateur_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lien_visio' => 'nullable|url',
            'statut' => 'required|in:actif,inactif,brouillon'
        ]);

        $data = $request->only([
            'titre', 'description', 'categorie', 'type', 
            'capacite', 'organisateur_id', 'lien_visio', 'statut'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/formations'), $imageName);
            $data['image'] = 'img/formations/' . $imageName;
        }

        \App\Models\Formation::create($data);

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation créée avec succès !');
    }

    public function formationsInscriptions(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $query = \Illuminate\Support\Facades\DB::table('formation_user')
            ->join('formations', 'formation_user.formation_id', '=', 'formations.id')
            ->join('users', 'formation_user.user_id', '=', 'users.id')
            ->select(
                'formation_user.*',
                'formations.titre as formation_title',
                'formations.categorie',
                'users.name as user_name',
                'users.email as user_email'
            );

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('formations.titre', 'LIKE', "%{$search}%")
                  ->orWhere('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('users.email', 'LIKE', "%{$search}%");
            });
        }

        // Formation filter
        if ($request->filled('formation_id')) {
            $query->where('formations.id', $request->formation_id);
        }

        $inscriptions = $query->orderBy('formation_user.created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_inscriptions' => \Illuminate\Support\Facades\DB::table('formation_user')->count(),
            'inscriptions_mois' => \Illuminate\Support\Facades\DB::table('formation_user')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'formations_actives' => \App\Models\Formation::where('statut', 'actif')->count(),
            'utilisateurs_uniques' => \Illuminate\Support\Facades\DB::table('formation_user')->distinct('user_id')->count('user_id'),
        ];

        // Get formations for filter
        $formations = \App\Models\Formation::orderBy('titre')->pluck('titre', 'id');

        return view('admin.formations.inscriptions', compact('inscriptions', 'stats', 'formations'));
    }

    /* ==============================
     *     DONATIONS
     * ==============================*/
    public function donationsIndex(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Importer le modèle Donation
        $query = \App\Models\Donation::with(['user']);

        // Filtres
        if ($request->filled('moyen_paiement')) {
            $query->where('moyen_paiement', $request->moyen_paiement);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_don', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_don', '<=', $request->date_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($subQ) use ($search) {
                    $subQ->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhere('transaction_id', 'LIKE', "%{$search}%")
                  ->orWhere('montant', 'LIKE', "%{$search}%");
            });
        }

        // Statistiques
        $stats = [
            'total' => \App\Models\Donation::sum('montant') ?? 0,
            'count' => \App\Models\Donation::count(),
            'total_mois' => \App\Models\Donation::whereMonth('date_don', now()->month)
                                               ->whereYear('date_don', now()->year)
                                               ->sum('montant') ?? 0,
            'count_mois' => \App\Models\Donation::whereMonth('date_don', now()->month)
                                                ->whereYear('date_don', now()->year)
                                                ->count(),
            'donateurs_uniques' => \App\Models\Donation::distinct()->count('utilisateur_id')
        ];

        // Moyens de paiement disponibles
        $moyens_paiement = \App\Models\Donation::distinct()
                                              ->pluck('moyen_paiement')
                                              ->filter()
                                              ->values();

        $dons = $query->orderBy('date_don', 'desc')->paginate(20);

        return view('admin.donations.index', compact('dons', 'stats', 'moyens_paiement'));
    }

    public function donationsCampagnes()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.donations.campagnes');
    }

    public function donationsRapports()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Statistiques pour les rapports
        $stats = [
            'total_annee' => \App\Models\Donation::whereYear('date_don', now()->year)->sum('montant'),
            'total_mois' => \App\Models\Donation::whereMonth('date_don', now()->month)->sum('montant'),
            'total_semaine' => \App\Models\Donation::where('date_don', '>=', now()->startOfWeek())->sum('montant'),
            'moyens_stats' => \App\Models\Donation::selectRaw('moyen_paiement, COUNT(*) as count, SUM(montant) as total')
                ->groupBy('moyen_paiement')
                ->get(),
            'donations_par_mois' => \App\Models\Donation::selectRaw('YEAR(date_don) as year, MONTH(date_don) as month, COUNT(*) as count, SUM(montant) as total')
                ->whereYear('date_don', now()->year)
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
        ];

        return view('admin.donations.rapports', compact('stats'));
    }

    public function donationsShow(int $id)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $donation = \App\Models\Donation::with(['user','event'])->find($id);
        if (!$donation) {
            return response()->json(['ok'=>false,'error'=>'Donation introuvable'], 404);
        }
        return response()->json(['ok'=>true,'donation'=>$donation]);
    }

    public function donationsSendReceipt(int $id)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $donation = \App\Models\Donation::with('user')->find($id);
        if (!$donation) {
            return response()->json(['ok'=>false,'error'=>'Donation introuvable'], 404);
        }
        try {
            // If anonymous donation without email, block
            if (!$donation->user || empty($donation->user->email)) {
                return response()->json(['ok'=>false,'error'=>'Aucun email disponible pour ce don.'], 422);
            }
            Mail::to($donation->user->email)->queue(new \App\Mail\DonationReceipt($donation));
            return response()->json(['ok'=>true,'message'=>'Reçu envoyé']);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false,'error'=>$e->getMessage()], 500);
        }
    }

    public function donationsDestroy(int $id)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $donation = \App\Models\Donation::find($id);
        if (!$donation) {
            return response()->json(['ok'=>false,'error'=>'Donation introuvable'], 404);
        }
        $donation->delete();
        return response()->json(['ok'=>true]);
    }

    public function donationsMethodes()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Méthodes configurées par l'admin
        $configMethods = \App\Models\PaymentMethod::orderBy('active','desc')->orderBy('sort_order')->get();

        // Statistiques d'utilisation réelles (à partir des dons)
        $usage = \App\Models\Donation::selectRaw('moyen_paiement, COUNT(*) as usage_count, SUM(montant) as total_amount')
            ->groupBy('moyen_paiement')
            ->get()
            ->keyBy('moyen_paiement');

        // Fusionner pour l'affichage
        $methodes = $configMethods->map(function($m) use ($usage){
            $stat = $usage->get($m->key);
            $m->usage_count = $stat->usage_count ?? 0;
            $m->total_amount = (float) ($stat->total_amount ?? 0);
            return $m;
        });

        return view('admin.donations.methodes', compact('methodes'));
    }

    public function donationsMethodesStore(\Illuminate\Http\Request $request)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Normalize key: lowercase, spaces to underscore, strip invalid chars
        try {
            $raw = (string) $request->input('method_key', '');
            $normalized = \Illuminate\Support\Str::of($raw)
                ->lower()
                ->replace(' ', '_')
                ->replaceMatches('/[^a-z0-9_\-]/', '')
                ->limit(100, '')
                ->value();
            if ($normalized !== '') { $request->merge(['method_key' => $normalized]); }
        } catch (\Throwable $e) {}

        $data = $request->validate([
            'method_key' => ['required','string','max:100','regex:/^[a-z0-9_\-]+$/'],
            'method_name' => ['required','string','max:150'],
            'method_type' => ['nullable','string','in:card,paypal,bank_transfer,paymee,custom,test'],
            'method_icon' => ['nullable','string','max:150'],
            // Use file+mimes so SVG passes (image rule may reject some svg mime types)
            'method_icon_file' => ['nullable','file','mimes:jpg,jpeg,png,webp,svg','max:4096'],
            'method_description' => ['nullable','string','max:2000'],
            // Accept checkbox 'on' value without strict boolean validation
            'method_active' => ['nullable'],
            'color_primary' => ['nullable','string','max:20'],
            'color_secondary' => ['nullable','string','max:20'],
            'button_text' => ['nullable','string','max:100'],
            'custom_form_fields' => ['nullable','json'],
            'custom_css' => ['nullable','string','max:10000'],
            'instructions_html' => ['nullable','string','max:10000'],
        ]);

        $payload = [
            'name' => $data['method_name'],
            'type' => $data['method_type'] ?? null,
            'icon' => $data['method_icon'] ?? null,
            'description' => $data['method_description'] ?? null,
            'active' => $request->boolean('method_active'),
            'color_primary' => $data['color_primary'] ?? '#007bff',
            'color_secondary' => $data['color_secondary'] ?? '#ffffff',
            'button_text' => $data['button_text'] ?? 'Pay now',
            'custom_form_fields' => $data['custom_form_fields'] ?? null,
            'custom_css' => $data['custom_css'] ?? null,
            'instructions_html' => $data['instructions_html'] ?? null,
        ];

        // Handle optional icon file upload
        if ($request->hasFile('method_icon_file')) {
            // Store on the public disk for predictable path
            $relative = $request->file('method_icon_file')->store('payment_methods', 'public');
            $payload['icon_path'] = 'storage/' . ltrim($relative, '/');
        }

        $pm = \App\Models\PaymentMethod::updateOrCreate(
            ['key' => strtolower($data['method_key'])],
            $payload
        );

        return response()->json(['ok' => true, 'method' => $pm]);
    }

    public function donationsMethodesGet($key)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $method = \App\Models\PaymentMethod::whereRaw('LOWER(`key`) = ?', [strtolower($key)])->first();
        if (!$method && is_numeric($key)) {
            $method = \App\Models\PaymentMethod::find((int)$key);
        }
        if (!$method) {
            return response()->json(['ok' => false, 'error' => 'Méthode introuvable'], 404);
        }
        return response()->json(['ok' => true, 'method' => $method]);
    }

    public function donationsMethodesUpdate(\Illuminate\Http\Request $request, $key)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        try {
            $method = \App\Models\PaymentMethod::whereRaw('LOWER(`key`) = ?', [strtolower($key)])->first();
            if (!$method && is_numeric($key)) {
                $method = \App\Models\PaymentMethod::find((int)$key);
            }
            if (!$method) {
                return response()->json(['ok'=>false,'error'=>'Méthode introuvable'], 404);
            }

            // Normalize key if changed
            $newKey = $key; // Default to current key
            if ($request->has('method_key') && $request->input('method_key') !== $key) {
                try {
                    $raw = (string) $request->input('method_key', '');
                    $normalized = \Illuminate\Support\Str::of($raw)
                        ->lower()
                        ->replace(' ', '_')
                        ->replaceMatches('/[^a-z0-9_\-]/', '')
                        ->limit(100, '')
                        ->value();
                    if ($normalized !== '') { 
                        $newKey = $normalized;
                        $request->merge(['method_key' => $normalized]); 
                    }
                } catch (\Throwable $e) {}
            }

            $data = $request->validate([
                'method_key' => ['required','string','max:100','regex:/^[a-z0-9_\-]+$/','unique:payment_methods,key,'.$method->id],
                'method_name' => ['required','string','max:150'],
                'method_type' => ['nullable','string','in:card,paypal,bank_transfer,paymee,custom,test'],
                'method_icon' => ['nullable','string','max:150'],
                'method_icon_file' => ['nullable','file','mimes:jpg,jpeg,png,webp,svg','max:4096'],
                'method_description' => ['nullable','string','max:2000'],
                'method_active' => ['nullable'],
                'color_primary' => ['nullable','string','max:20'],
                'color_secondary' => ['nullable','string','max:20'],
                'button_text' => ['nullable','string','max:100'],
                'custom_form_fields' => ['nullable','json'],
                'custom_css' => ['nullable','string','max:10000'],
                'instructions_html' => ['nullable','string','max:10000'],
            ]);

            // If changing key and it belongs to a different record, block explicitly
            if ($newKey !== $key) {
                $exists = \App\Models\PaymentMethod::where('key', $newKey)->where('id', '!=', $method->id)->exists();
                if ($exists) {
                    return response()->json(['ok'=>false,'error'=>'Cette clé est déjà utilisée par une autre méthode.'], 422);
                }
            }

            $payload = [
                'key' => $newKey,
                'name' => $data['method_name'],
                'type' => $data['method_type'] ?? null,
                'icon' => $data['method_icon'] ?? null,
                'description' => $data['method_description'] ?? null,
                'active' => $request->boolean('method_active'),
                'color_primary' => $data['color_primary'] ?? '#007bff',
                'color_secondary' => $data['color_secondary'] ?? '#ffffff',
                'button_text' => $data['button_text'] ?? 'Pay now',
                'custom_form_fields' => $data['custom_form_fields'] ?? null,
                'custom_css' => $data['custom_css'] ?? null,
                'instructions_html' => $data['instructions_html'] ?? null,
            ];

            // Handle optional icon file upload
            if ($request->hasFile('method_icon_file')) {
                $relative = $request->file('method_icon_file')->store('payment_methods', 'public');
                $payload['icon_path'] = 'storage/' . ltrim($relative, '/');
            }

            $method->update($payload);

            return response()->json(['ok' => true, 'method' => $method->fresh()]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json(['ok'=>false,'error'=>'Validation error','errors'=>$ve->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false,'error'=>$e->getMessage()], 500);
        }
    }

    /**
     * UI Features
     */
    public function uiButtons()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.ui-features.buttons');
    }

    public function uiTypography()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.ui-features.typography');
    }

    /* ==============================
     *     FORMS
     * ==============================*/
    public function formsBasic()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.forms.basic');
    }

    /* ==============================
     *     CHARTS
     * ==============================*/
    public function chartsChartjs()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.charts.chartjs');
    }

    /* ==============================
     *     TABLES
     * ==============================*/
    public function tablesBasic()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.tables.basic');
    }
}