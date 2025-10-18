<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ScoreChallengeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\FormationInscriptionController;
use App\Http\Controllers\AvisFormationController;
use App\Http\Controllers\RessourceFormationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationAdminController;
use App\Http\Controllers\DonationOrganizerController;
use App\Http\Controllers\TestPaymentController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AlerteForumController;
use App\Http\Controllers\NotificationController;

// QR verification
require __DIR__ . '/qr-verify.php';

/*
|--------------------------------------------------------------------------
| Pages publiques
|--------------------------------------------------------------------------
*/
Route::view('/', 'pages.index')->name('home');

$pages = ['about','blog','causes','contact','donation','events','gallery','service'];
foreach ($pages as $page) {
    Route::view("/{$page}", "pages.{$page}")->name($page);
    Route::view("/{$page}.html", "pages.{$page}");
}

// Events
Route::get('/events', [EventsController::class, 'index'])->name('events.browse');
Route::get('/events/{id}', [EventsController::class, 'show'])->name('events.show');

// Alias
Route::view('/index', 'pages.index');
Route::view('/index.html', 'pages.index');
Route::view('/services', 'pages.service');
Route::view('/services.html', 'pages.service');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.submit');

    // Mot de passe oublié
    Route::get('/password/forgot',  [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // Formations
    Route::get('/organisateur/formations/create', [FormationController::class, 'create'])->name('formations.create');
    Route::post('/organisateur/formations', [FormationController::class, 'store'])->name('formations.store');
    Route::post('/organisateur/formations/{formation}/ressources', [FormationController::class, 'storeResource'])->name('formations.resources.store');
    Route::post('/formations/{formation}/inscrire', [FormationInscriptionController::class, 'store'])->name('formations.inscrire');
    Route::delete('/formations/{formation}/desinscrire', [FormationInscriptionController::class, 'destroy'])->name('formations.desinscrire');
    Route::post('/formations/{formation}/avis', [AvisFormationController::class, 'store'])->name('formations.avis.store');
    Route::post('/formations/{formation}/ressources', [RessourceFormationController::class, 'store'])->name('formations.ressources.store');
    Route::get('organisateur/formations/{formation}/edit', [FormationController::class, 'edit'])->name('formations.edit');
    Route::put('organisateur/formations/{formation}', [FormationController::class, 'update'])->name('formations.update');
    Route::delete('organisateur/formations/{formation}', [FormationController::class, 'destroy'])->name('formations.destroy');
    Route::get('/mes-formations/stats', [FormationController::class, 'dashboard'])->name('formations.dashboard');

    // Donations
    Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/history', [DonationController::class, 'history'])->name('donations.history');

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Catalogue formations public
|--------------------------------------------------------------------------
*/
Route::get('/formations', [FormationController::class, 'index'])->name('formations.index');
Route::get('/formations/{formation}', [FormationController::class, 'show'])->name('formations.show');

/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/
Route::post('/payments/stripe/intent', [DonationController::class, 'createStripeIntent'])->name('payments.stripe.intent');
Route::post('/payments/stripe/confirm', [DonationController::class, 'confirmStripePayment'])->name('payments.stripe.confirm');
Route::post('/payments/paypal/create-order', [DonationController::class, 'createPayPalOrder'])->name('payments.paypal.create');
Route::post('/payments/paypal/capture', [DonationController::class, 'capturePayPalOrder'])->name('payments.paypal.capture');
Route::post('/payments/paymee/create', [DonationController::class, 'createPaymeePayment'])->name('payments.paymee.create');
Route::get('/payments/paymee/return', [DonationController::class, 'paymeeReturn'])->name('payments.paymee.return');
Route::get('/payments/paymee/cancel', [DonationController::class, 'paymeeCancel'])->name('payments.paymee.cancel');
Route::post('/webhooks/paymee', [DonationController::class, 'paymeeWebhook'])->middleware('api')->name('webhooks.paymee');

// TestPay public
Route::get('/payments/test/checkout', [TestPaymentController::class, 'checkout'])->name('payments.test.checkout');
Route::get('/payments/test/cancel', [TestPaymentController::class, 'cancel'])->name('payments.test.cancel');

/*
|--------------------------------------------------------------------------
| Challenges
|--------------------------------------------------------------------------
*/
Route::prefix('challenges')->group(function () {
    Route::get('/', [ChallengeController::class, 'index'])->name('challenges.index');

    Route::middleware('auth')->group(function () {
        Route::get('/profil', [ChallengeController::class, 'profil'])->name('challenges.profil');
        Route::post('/{id}/participate', [ChallengeController::class, 'participer'])->name('challenges.participate');
        Route::post('/{id}/submit-proof', [ChallengeController::class, 'soumettrePreuve'])->name('challenges.submit');

        Route::prefix('association')->group(function () {
            Route::get('/create', [ChallengeController::class, 'create'])->name('challenges.create');
            Route::post('/', [ChallengeController::class, 'store'])->name('challenges.store');
            Route::get('/crud', [ChallengeController::class, 'crud'])->name('challenges.crud');
            Route::get('/{id}/edit', [ChallengeController::class, 'edit'])->name('challenges.edit');
            Route::put('/{id}', [ChallengeController::class, 'update'])->name('challenges.update');
            Route::delete('/{id}', [ChallengeController::class, 'destroy'])->name('challenges.destroy');
            Route::post('/participants/{participant}/action', [ChallengeController::class, 'actionParticipant'])->name('challenges.participants.action');
            Route::get('/participants/{id}', [ChallengeController::class, 'participants'])->name('challenges.participants');
        });
    });

    Route::get('/{id}', [ChallengeController::class, 'show'])->name('challenges.show');
});

// Scores
Route::prefix('scores')->name('scores.')->middleware('auth')->group(function () {
    Route::post('/{participant}', [ScoreChallengeController::class, 'storeOrUpdate'])->name('update');
    Route::delete('/{score}', [ScoreChallengeController::class, 'destroy'])->name('destroy');
    Route::get('/classement/{challenge}', [ScoreChallengeController::class, 'classement'])->name('classement');
});

/*
|--------------------------------------------------------------------------
| Forums & Alertes - NOUVELLES FONCTIONNALITÉS
|--------------------------------------------------------------------------
*/Route::middleware('auth')->group(function () {
    // Alertes - création, édition, suppression
    Route::get('/alertes/create', [AlerteForumController::class, 'create'])->name('alertes.create');
    Route::post('/alertes', [AlerteForumController::class, 'store'])->name('alertes.store');
    Route::get('/alertes/{id}/edit', [AlerteForumController::class, 'edit'])->name('alertes.edit');
    Route::put('/alertes/{id}', [AlerteForumController::class, 'update'])->name('alertes.update');
    Route::delete('/alertes/{id}', [AlerteForumController::class, 'destroy'])->name('alertes.destroy');});
    // Nouvelles routes pour les fonctionnalités avancées
    Route::post('/alertes/{id}/resoudre', [AlerteForumController::class, 'marquerResolue'])->name('alertes.marquer-resolue');
    Route::post('/alertes/{id}/commenter', [AlerteForumController::class, 'ajouterCommentaire'])->name('alertes.ajouter-commentaire');
    Route::post('/alertes/{id}/partager', [AlerteForumController::class, 'partager'])->name('alertes.partager');
    Route::get('/alertes/carte', [AlerteForumController::class, 'carte'])->name('alertes.carte');
    Route::get('/alertes/statistiques', [AlerteForumController::class, 'statistiques'])->name('alertes.statistiques');

Route::resource('forums', ForumController::class);
Route::post('/forums/{id}/reply', [ForumController::class, 'reply'])->middleware('auth')->name('forums.reply');

Route::resource('alertes', AlerteForumController::class)->middleware('auth')->except(['index','show']);
Route::resource('alertes', AlerteForumController::class)->only(['index','show']);
Route::middleware('auth')->group(function () {
    // ... autres routes ...

    // Route pour supprimer un commentaire
    Route::delete('/commentaires/{id}', [AlerteForumController::class, 'destroyCommentaire'])->name('commentaires.destroy');
});

// Routes publiques pour forums (lecture seule)
Route::get('/forums', [ForumController::class, 'index'])->name('forums.index');
Route::get('/forums/{id}', [ForumController::class, 'show'])->name('forums.show');
Route::get('/recherche', [ForumController::class, 'rechercheAvancee'])->name('recherche.avancee');

// Routes publiques pour alertes (lecture seule)
Route::get('/alertes', [AlerteForumController::class, 'index'])->name('alertes.index');
Route::get('/alertes/{id}', [AlerteForumController::class, 'show'])->name('alertes.show');

// Routes protégées pour forums (création, édition, suppression)
Route::middleware('auth')->group(function () {
    // Forums - création, édition, suppression
    Route::get('/forums/create', [ForumController::class, 'create'])->name('forums.create');
    Route::post('/forums', [ForumController::class, 'store'])->name('forums.store');
    Route::get('/forums/{id}/edit', [ForumController::class, 'edit'])->name('forums.edit');
    Route::put('/forums/{id}', [ForumController::class, 'update'])->name('forums.update');
    Route::delete('/forums/{id}', [ForumController::class, 'destroy'])->name('forums.destroy');
    
    // Système de réponses aux forums
    Route::post('/forums/{forum}/reponses', [ForumController::class, 'storeReponse'])->name('forums.reponses.store');
    Route::post('/forums/{forum}/reponses/{reponse}/solution', [ForumController::class, 'marquerSolution'])->name('forums.reponses.solution');
    
    // Alertes - création, édition, suppression
    Route::get('/alertes/create', [AlerteForumController::class, 'create'])->name('alertes.create');
    Route::post('/alertes', [AlerteForumController::class, 'store'])->name('alertes.store');
    Route::get('/alertes/{id}/edit', [AlerteForumController::class, 'edit'])->name('alertes.edit');
    Route::put('/alertes/{id}', [AlerteForumController::class, 'update'])->name('alertes.update');
    Route::delete('/alertes/{id}', [AlerteForumController::class, 'destroy'])->name('alertes.destroy');
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/lue', [NotificationController::class, 'marquerLue'])->name('lue');
        Route::post('/toutes-lues', [NotificationController::class, 'marquerToutesLues'])->name('toutes-lues');
    });
});
Route::post('/forums/{forum}/reponse-suggestion', [ForumController::class, 'suggestionIA'])
     ->name('forums.reponse.suggestion');

// API pour notifications
Route::get('/api/notifications/non-lues', [NotificationController::class, 'getNonLues'])->name('api.notifications.non-lues');
// Routes pour les notifications
Route::prefix('notifications')->name('notifications.')->middleware('auth')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/toutes-lues', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/non-lues/count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
});
// Route de test pour les notifications
Route::get('/test-notifications-fix', function () {
    try {
        // Tester la création d'une notification
        $user = \App\Models\User::first();
        
        if (!$user) {
            return "Aucun utilisateur trouvé pour le test";
        }
        
        $alerte = \App\Models\AlerteForum::first();
        
        if (!$alerte) {
            // Créer une alerte de test
            $alerte = \App\Models\AlerteForum::create([
                'utilisateur_id' => $user->id,
                'titre' => 'Alerte de test',
                'description' => 'Ceci est une alerte de test pour les notifications',
                'gravite' => 'haute',
                'zone_geographique' => 'Zone test',
                'statut' => 'en_cours'
            ]);
        }
        
        // Tester l'envoi de notification
        $user->notify(new \App\Notifications\NouvelleAlerteNotification($alerte));
        
        // Vérifier si la notification a été créée
        $notificationCount = $user->notifications()->count();
        $unreadCount = $user->unreadNotifications()->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Test des notifications réussi!',
            'notifications_total' => $notificationCount,
            'notifications_non_lues' => $unreadCount,
            'structure_table' => \Illuminate\Support\Facades\DB::select('DESCRIBE notifications')
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
// routes/web.php
Route::get('/alertes/moderation/admin', [AlerteForumController::class, 'moderationAdmin'])
    ->name('alertes.moderation.admin')
    ->middleware('auth', 'admin'); // Assurez-vous d'avoir un middleware admin
Route::post('/forums/{forum}/reponses', [ForumController::class, 'storeReponse'])
    ->name('forums.reponses.store');

    /*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Statistiques forums avancées
    Route::get('/statistiques-forums', [ForumController::class, 'statistiquesAvancees'])->name('statistiques.forums');
    Route::post('/update-stats', function () {
        Artisan::call('forum:update-stats');
        return redirect()->back()->with('success', 'Statistiques mises à jour manuellement');
    })->name('update.stats');

    Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
        Route::get('/', [AdminController::class, 'utilisateursIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'utilisateursCreate'])->name('create');
        Route::get('/roles', [AdminController::class, 'utilisateursRoles'])->name('roles');
    });

    Route::prefix('evenements')->name('evenements.')->group(function () {
        Route::get('/', [AdminController::class, 'evenementsIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'evenementsCreate'])->name('create');
        Route::get('/categories', [AdminController::class, 'evenementsCategories'])->name('categories');
    });

    Route::prefix('challenges')->name('challenges.')->group(function () {
        Route::get('/', [AdminController::class, 'challengesIndex'])->name('index');
        Route::get('{id}/participants', [AdminController::class, 'challengesParticipations'])->name('participations');
        Route::get('/scores/tous', [AdminController::class, 'allScores'])->name('all_scores');
        Route::post('{id}/toggle', [AdminController::class, 'toggleChallenge'])->name('toggle');
    });

    Route::prefix('forums')->name('forums.')->group(function () {
        Route::get('/', [AdminController::class, 'forumsIndex'])->name('index');
        Route::get('/categories', [AdminController::class, 'forumsCategories'])->name('categories');
        Route::get('/moderations', [AdminController::class, 'forumsModerations'])->name('moderations');
        Route::get('/statistiques', [ForumController::class, 'statistiquesAvancees'])->name('statistiques');
    });

    Route::prefix('formations')->name('formations.')->group(function () {
        Route::get('/', [AdminController::class, 'formationsIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'formationsCreate'])->name('create');
        Route::get('/inscriptions', [AdminController::class, 'formationsInscriptions'])->name('inscriptions');
    });

    Route::prefix('donations')->name('donations.')->group(function () {
        Route::get('/', [AdminController::class, 'donationsIndex'])->name('index');
        Route::get('/campagnes', [AdminController::class, 'donationsCampagnes'])->name('campagnes');
        Route::get('/rapports', [AdminController::class, 'donationsRapports'])->name('rapports');
    });

    Route::prefix('ui-features')->name('ui-features.')->group(function () {
        Route::get('/buttons', [AdminController::class, 'uiButtons'])->name('buttons');
        Route::get('/typography', [AdminController::class, 'uiTypography'])->name('typography');
    });

    Route::prefix('forms')->name('forms.')->group(function () {
        Route::get('/basic', [AdminController::class, 'formsBasic'])->name('basic');
    });

    Route::prefix('charts')->name('charts.')->group(function () {
        Route::get('/chartjs', [AdminController::class, 'chartsChartjs'])->name('chartjs');
    });

    Route::prefix('tables')->name('tables.')->group(function () {
        Route::get('/basic', [AdminController::class, 'tablesBasic'])->name('basic');
    });

    // 🔥 Gestion des alertes
    Route::get('/alertes', [AdminController::class, 'alertesIndex'])->name('alertes.index');
Route::get('/admin/alertes/stats', [AlerteController::class, 'statsAlertes'])
     ->name('admin.alertes.stats');
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(fn () => abort(404));