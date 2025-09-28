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

    /* ==============================
     *     DONATIONS
     * ==============================*/
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
