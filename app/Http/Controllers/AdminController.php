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

 public function allScores()
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    // Charger les participants et leurs scores
    $challenges = Challenge::with(['participants.utilisateur', 'participants.score'])->get();

    // Compter les badges en normalisant en minuscules
    $badgesStats = ScoreChallenge::selectRaw('LOWER(badge) as badge, COUNT(*) as count')
        ->whereNotNull('badge')
        ->where('badge', '!=', '')
        ->groupBy('badge')
        ->pluck('count', 'badge')
        ->toArray();

    // Nombre total de participants
    $totalParticipants = $challenges->sum(fn($challenge) => $challenge->participants->count());

    // Total des points
    $totalPoints = $challenges->sum(fn($challenge) => 
        $challenge->participants->sum(fn($p) => $p->points)
    );

    // Points moyens par participant
    $pointsMoyens = $totalParticipants > 0 ? round($totalPoints / $totalParticipants, 2) : 0;

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

    return view('admin.challenges.all_scores', compact('challenges', 'stats'));
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

    public function donationsMethodes()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Méthodes de paiement disponibles avec statistiques
        $methodes = \App\Models\Donation::selectRaw('moyen_paiement, COUNT(*) as usage_count, SUM(montant) as total_amount')
            ->groupBy('moyen_paiement')
            ->orderBy('usage_count', 'desc')
            ->get();

        return view('admin.donations.methodes', compact('methodes'));
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