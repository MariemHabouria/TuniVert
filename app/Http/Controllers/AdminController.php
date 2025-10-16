<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\ScoreChallenge; // <- IMPORT DU MODELE

class AdminController extends Controller
{
    /**
     * Vérifier si l'utilisateur est admin
     */
    private function checkAdmin()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }

        return true;
    }

    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.dashboard.index');
    }

    /**
     * Gestion des utilisateurs
     */
    public function utilisateursIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.utilisateurs.index');
    }

    public function utilisateursCreate()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.utilisateurs.create');
    }

    public function utilisateursRoles()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.utilisateurs.roles');
    }

    /**
     * Événements
     */
    public function evenementsIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.evenements.index');
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



    /**
     * Forums
     */
    public function forumsIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.forums.index');
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

    /**
     * Formations
     */
    public function formationsIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.formations.index');
    }

    public function formationsCreate()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.formations.create');
    }

    public function formationsInscriptions()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.formations.inscriptions');
    }

    /**
     * Donations
     */
    public function donationsIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.donations.index');
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

        return view('admin.donations.rapports');
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

    /**
     * Forms
     */
    public function formsBasic()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.forms.basic');
    }

    /**
     * Charts
     */
    public function chartsChartjs()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.charts.chartjs');
    }

    /**
     * Tables
     */
    public function tablesBasic()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        return view('admin.tables.basic');
    }
}