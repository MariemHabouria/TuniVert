<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Challenge;
use App\Models\ScoreChallenge;   // <- depuis HEAD
use App\Models\Formation;        // <- formation
use App\Models\Donation;         // <- formation (adapte le nom au besoin)

class AdminController extends Controller
{
    /**
     * Vérifier si l'utilisateur est admin
     */
    private function checkAdmin()
    {
        if (!Auth::check()) {
            // on renvoie une redirection standard (pas de ->send() pour rester cohérent avec le reste du code)
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
    public function utilisateursIndex(Request $request)
    {
        $check = $this->checkAdmin(); 
        if ($check !== true) return $check;

        $q = trim($request->get('q', ''));

        $users = User::query()
            ->when($q, function($query) use ($q) {
                $query->where(function($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('role', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.utilisateurs.index', compact('users', 'q'));
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

    public function utilisateursShow(User $user)
    {
        $check = $this->checkAdmin(); 
        if ($check !== true) return $check;

        return view('admin.utilisateurs.show', compact('user'));
    }

    public function utilisateursEdit(User $user)
    {
        $check = $this->checkAdmin(); 
        if ($check !== true) return $check;

        return view('admin.utilisateurs.edit', compact('user'));
    }

    public function utilisateursDestroy(User $user)
    {
        $check = $this->checkAdmin(); 
        if ($check !== true) return $check;

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tu ne peux pas supprimer ton propre compte.');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé.');
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

        // Liste + nombre de participants
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

        // Challenge + participants
        $challenge = Challenge::with('participants')->findOrFail($id);

        return view('admin.challenges.participations', compact('challenge'));
    }

    public function challengesScores($challengeId)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        $challenge = Challenge::with(['participants.utilisateur', 'participants.score'])
            ->findOrFail($challengeId);

        return view('admin.challenges.scores', compact('challenge'));
    }

    public function allScores()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;

        // Charger les participants et leurs scores
        $challenges = Challenge::with(['participants.utilisateur', 'participants.score'])->get();

        // Compter les badges (normalisé en minuscules)
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
            'total_challenges'   => $challenges->count(),
            'total_participants' => $totalParticipants,
            'total_points'       => $totalPoints,
            'points_moyens'      => $pointsMoyens,
            'badges_count'       => [
                'Or'     => $badgesStats['or'] ?? 0,
                'Argent' => $badgesStats['argent'] ?? 0,
                'Bronze' => $badgesStats['bronze'] ?? 0,
            ],
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
    public function formationsIndex(Request $request)
    {
        $check = $this->checkAdmin(); 
        if ($check !== true) return $check;

        $q = trim($request->get('q', ''));

        // Détecter la clé étrangère vers users si elle existe
        $possibleFks = ['organizer_id','user_id','created_by','owner_id','association_id'];
        $orgFk = null;
        foreach ($possibleFks as $fk) {
            if (Schema::hasColumn('formations', $fk)) { $orgFk = $fk; break; }
        }

        $query = Formation::query();

        // Join users si une FK est trouvée
        if ($orgFk) {
            $query->leftJoin('users as org', "org.id", "=", "formations.$orgFk");
        }

        // Recherche
        $query->when($q, function ($query) use ($q, $orgFk) {
            $query->where(function ($qq) use ($q, $orgFk) {
                $qq->where('formations.title', 'like', "%{$q}%")
                   ->orWhere('formations.nom',   'like', "%{$q}%")
                   ->orWhere('formations.slug',  'like', "%{$q}%");

                if ($orgFk) {
                    $qq->orWhere('org.name', 'like', "%{$q}%");
                }
            });
        });

        // Sélection des colonnes
        $select = ['formations.*'];
        if ($orgFk) {
            $select[] = 'org.name as organizer_name';
            $select[] = 'org.email as organizer_email';
        }

        $formations = $query->orderByDesc('formations.id')
            ->select($select)
            ->paginate(12)
            ->withQueryString();

        return view('admin.formations.index', compact('formations','q'));
    }

    public function formationsShow($id)
    {
        $check = $this->checkAdmin(); 
        if ($check !== true) return $check;

        // Détection robuste de la FK vers users (comme pour l'index)
        $possibleFks = ['organizer_id','user_id','created_by','owner_id','association_id'];
        $orgFk = null;
        foreach ($possibleFks as $fk) {
            if (Schema::hasColumn('formations', $fk)) { $orgFk = $fk; break; }
        }

        $q = Formation::query();
        if ($orgFk) {
            $q->leftJoin('users as org', "org.id", "=", "formations.$orgFk");
        }

        $select = ['formations.*'];
        if ($orgFk) {
            $select[] = 'org.name as organizer_name';
            $select[] = 'org.email as organizer_email';
        }

        $formation = $q->where('formations.id', $id)
            ->select($select)
            ->firstOrFail();

        return view('admin.formations.show', compact('formation'));
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

    public function donationsHistory(Request $request)
    {
        $check = $this->checkAdmin(); 
        if ($check !== true) return $check;

        $q      = trim($request->get('q',''));
        $status = trim($request->get('status',''));

        // FK vers users (si présente)
        $userFk = null;
        foreach (['user_id','donor_id','payer_id'] as $fk) {
            if (Schema::hasColumn('donations', $fk)) { $userFk = $fk; break; }
        }

        // FK campagne/événement (si présente)
        $campFk = null;
        foreach (['event_id','campaign_id','cause_id'] as $fk) {
            if (Schema::hasColumn('donations', $fk)) { $campFk = $fk; break; }
        }

        $qb = Donation::query();

        if ($userFk)  $qb->leftJoin('users as u', "u.id", "=", "donations.$userFk");
        if ($campFk && Schema::hasTable('events'))
            $qb->leftJoin('events as e', "e.id", "=", "donations.$campFk");
        elseif ($campFk && Schema::hasTable('campaigns'))
            $qb->leftJoin('campaigns as e', "e.id", "=", "donations.$campFk");

        $qb->when($q, function($qB) use ($q, $userFk){
            $qB->where(function($w) use ($q, $userFk){
                $w->where('donations.reference','like',"%{$q}%")
                  ->orWhere('donations.cause','like',"%{$q}%")
                  ->orWhere('donations.label','like',"%{$q}%");
                if ($userFk) {
                  $w->orWhere('u.name','like',"%{$q}%")
                    ->orWhere('u.email','like',"%{$q}%");
                }
            });
        });

        if ($status !== '') {
            $qb->where(function($w) use ($status){
                $w->where('donations.status', $status)
                  ->orWhere('donations.payment_status', $status);
            });
        }

        $select = ['donations.*'];
        if ($userFk) { 
            $select[] = 'u.name as donor_name'; 
            $select[] = 'u.email as donor_email'; 
        }
        if ($campFk) { 
            $select[] = 'e.title as campaign_name'; 
        }

        $donations = $qb->orderByDesc('donations.id')
            ->select($select)
            ->paginate(12)
            ->withQueryString();

        return view('admin.donations.history', compact('donations','q','status'));
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
