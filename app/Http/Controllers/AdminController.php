<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\Forum;
use App\Models\AlerteForum;
use App\Models\User;

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

    /* ==============================
     *     ÉVÉNEMENTS
     * ==============================*/
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

    /* ==============================
     *     CHALLENGES
     * ==============================*/
    public function challengesIndex()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Charger les challenges avec le nombre de participants
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

    /* ==============================
     *     FORUMS
     * ==============================*/
    public function forumsIndex()
{
    $this->checkAdmin();

    // Utiliser paginate() au lieu de all()
    $forums = Forum::orderBy('created_at', 'desc')->paginate(10);

    return view('admin.forums.index', compact('forums'));
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

    $alertes = AlerteForum::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

    // ✅ Vue à l’intérieur du sous-dossier "alertes"
    return view('admin.alertes.index', compact('alertes'));
}
    /* ==============================
     *     FORMATIONS
     * ==============================*/
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

    /* ==============================
     *     UI FEATURES
     * ==============================*/
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