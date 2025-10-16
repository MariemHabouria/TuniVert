<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\Forum;
use App\Models\AlerteForum;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\ScoreChallenge; // <- IMPORT DU MODELE

class AdminController extends Controller
{
    /**
     * Vérifier si l'utilisateur est admin
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