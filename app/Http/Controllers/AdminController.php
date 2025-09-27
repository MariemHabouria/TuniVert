<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;

class AdminController extends Controller
{
    /**
     * Vérifier si l'utilisateur est admin
     */
    private function checkAdmin()
    {
        if (!Auth::check()) {
            // Redirection immédiate si pas connecté
            return redirect()->route('admin.login')->send();
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

        // ✅ Récupérer tous les challenges avec le nombre de participants
        $challenges = Challenge::withCount('participants')->get();

        // ✅ Envoyer à la vue
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

        // ✅ Charger un challenge avec ses participants
        $challenge = Challenge::with('participants')->findOrFail($id);

        return view('admin.challenges.participations', compact('challenge'));
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
