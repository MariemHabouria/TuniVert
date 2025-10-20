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
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AlerteForumController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChatbotEventController;
use App\Http\Controllers\FormationChatController;
use App\Http\Controllers\QuizController;
// QR verification
// Include test upload routes for debugging
// require __DIR__.'/test-upload.php'; // Commented out - file doesn't exist in production

// Metrics for Prometheus
Route::get('/metrics', [MetricsController::class, 'index'])->name('metrics');

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


// Routes événements
// chatBot
Route::post('/chatbot/ask', [ChatbotEventController::class, 'ask'])->name('chatbot.ask');

// Créer un événement (statique, avant la route dynamique)
Route::get('events/create', [EventController::class, 'create'])->name('events.create')->middleware('auth');
// Recommandation d'événements
Route::get('/recommendations/{user}', [EventController::class, 'showRecommendations'])
     ->name('events.recommendations');
//participation aux événements
Route::post('/events/{event}/participate', [EventController::class, 'participate'])->name('events.participate')->middleware('auth');
// commentaire 
Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update')->middleware('auth');
Route::post('/comments/{comment}/reanalyse', [CommentController::class, 'reanalyse'])
    ->name('comments.reanalyse')
    ->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('auth');
// Liste des événements
Route::get('events', [EventController::class, 'index'])->name('events.index');
// Alias legacy: some views reference route('events.browse')
Route::get('events/browse', function() {
    return redirect()->route('events.index');
})->name('events.browse');

// Afficher un événement (dynamique)
Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
// Routes protégées
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/deactivate', [ProfileController::class, 'deactivate'])->name('profile.deactivate');
    Route::delete('/profile', [ProfileController::class, 'delete'])->name('profile.delete');
    
// event

Route::post('events', [EventController::class, 'store'])->name('events.store');
// chatBot
Route::post('/chatbot/ask', [ChatbotEventController::class, 'ask'])->name('chatbot.ask');
    // Modifier / Supprimer un événement
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

   // Formations
    Route::get('/organisateur/formations/create', [FormationController::class, 'create'])->name('formations.create');
    Route::post('/organisateur/formations', [FormationController::class, 'store'])->name('formations.store');
    Route::post('/organisateur/formations/{formation}/ressources', [FormationController::class, 'storeResource'])->name('formations.resources.store');
    Route::post('/formations/{formation}/inscrire', [FormationInscriptionController::class, 'store'])->name('formations.inscrire');
    Route::delete('/formations/{formation}/desinscrire', [FormationInscriptionController::class, 'destroy'])->name('formations.desinscrire');
    Route::post('/formations/{formation}/avis', [AvisFormationController::class, 'store'])->name('formations.avis.store');
    Route::post('/formations/{formation}/ressources', [RessourceFormationController::class, 'store'])->name('formations.ressources.store');

  Route::post('/formations/{formation}/chat', [FormationChatController::class, 'chat'])
    ->name('formations.chat');
     Route::get('/formations/{formation}/chat/history', [FormationChatController::class, 'history'])->name('formations.chat.history');
    Route::delete('/formations/{formation}/chat/history', [FormationChatController::class, 'clear'])->name('formations.chat.clear');
    Route::post('/formations/chat/{message}/feedback', [FormationChatController::class, 'feedback'])->name('formations.chat.feedback');

    Route::prefix('formations/{formation}/quiz')->name('quiz.')->group(function () {
    Route::get('/',            [QuizController::class,'show'])->name('show');              // Voir/générer/jouer
    Route::post('/generate',   [QuizController::class,'generate'])->middleware('auth')->name('generate'); // orga
    Route::post('/submit',     [QuizController::class,'submit'])->middleware('auth')->name('submit');     // envoyer réponses
    Route::get('/history',     [QuizController::class,'history'])->middleware('auth')->name('history');   // tentatives
});    


    Route::get('organisateur/formations/{formation}/edit', [FormationController::class, 'edit'])->name('formations.edit');
    Route::put('organisateur/formations/{formation}', [FormationController::class, 'update'])->name('formations.update');
    Route::delete('organisateur/formations/{formation}', [FormationController::class, 'destroy'])->name('formations.destroy');
    Route::get('/mes-formations/stats', [FormationController::class, 'dashboard'])->name('formations.dashboard');


    // Donations
    // Association dashboard
    Route::get('/donations/dashboard', [DonationController::class, 'associationDashboard'])->name('donations.dashboard');
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

// Lightweight analytics: log suggestion button clicks (no CSRF via api middleware)
Route::post('/donations/suggestion-click', [DonationController::class, 'logSuggestionClick'])
    ->middleware('api')
    ->name('donations.suggestionClick');

// TestPay (mock) — POST endpoints utilisés par donation.blade.php
Route::post('/payments/test/create',   [TestPaymentController::class, 'create'])->name('payments.test.create');
Route::post('/payments/test/complete', [TestPaymentController::class, 'complete'])->name('payments.test.complete');


// TestPay public
Route::get('/payments/test/checkout', [TestPaymentController::class, 'checkout'])->name('payments.test.checkout');
Route::get('/payments/test/cancel', [TestPaymentController::class, 'cancel'])->name('payments.test.cancel');

/*
|--------------------------------------------------------------------------
| Challenges
|--------------------------------------------------------------------------
*/
// Routes Challenges
Route::prefix('challenges')->group(function () {
    Route::get('/', [ChallengeController::class, 'index'])->name('challenges.index');
    
    Route::middleware('auth')->group(function () {
        Route::get('/profil', [ChallengeController::class, 'profil'])->name('challenges.profil');
        Route::post('/{id}/participate', [ChallengeController::class, 'participer'])->name('challenges.participate');
        Route::post('/challenges/{id}/submit-proof', [ChallengeController::class, 'soumettrePreuve'])->name('challenges.submit');

    Route::get('/create', [AdminController::class, 'challengesCreate'])->name('create');

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

// Routes Scores
Route::prefix('scores')->name('scores.')->middleware('auth')->group(function () {
    Route::post('/{participant}', [ScoreChallengeController::class, 'storeOrUpdate'])->name('update');
    Route::delete('/{score}', [ScoreChallengeController::class, 'destroy'])->name('destroy');
    Route::get('/classement/{challenge}', [ScoreChallengeController::class, 'classement'])->name('classement');
});

/*
|--------------------------------------------------------------------------
| Forums & Alertes - NOUVELLES FONCTIONNALITÉS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Alertes - création, édition, suppression
    Route::get('/alertes/create', [AlerteForumController::class, 'create'])->name('alertes.create');
    Route::post('/alertes', [AlerteForumController::class, 'store'])->name('alertes.store');
    Route::get('/alertes/{id}/edit', [AlerteForumController::class, 'edit'])->name('alertes.edit');
    Route::put('/alertes/{id}', [AlerteForumController::class, 'update'])->name('alertes.update');
    Route::delete('/alertes/{id}', [AlerteForumController::class, 'destroy'])->name('alertes.destroy');
    
    // Nouvelles routes pour les fonctionnalités avancées
    Route::post('/alertes/{id}/resoudre', [AlerteForumController::class, 'marquerResolue'])->name('alertes.marquer-resolue');
    Route::post('/alertes/{id}/commenter', [AlerteForumController::class, 'ajouterCommentaire'])->name('alertes.ajouter-commentaire');
    Route::post('/alertes/{id}/partager', [AlerteForumController::class, 'partager'])->name('alertes.partager');
    Route::get('/alertes/carte', [AlerteForumController::class, 'carte'])->name('alertes.carte');
    Route::get('/alertes/statistiques', [AlerteForumController::class, 'statistiques'])->name('alertes.statistiques');
});

Route::resource('forums', ForumController::class);
Route::post('/forums/{id}/reply', [ForumController::class, 'reply'])->middleware('auth')->name('forums.reply');

// Public routes for alertes (index, show)
Route::resource('alertes', AlerteForumController::class)->only(['index','show']);

Route::middleware('auth')->group(function () {
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
// ===== ROUTES ADMIN (application wide) =====
Route::prefix('admin')->name('admin.')->group(function () {

    // ===== AUTH ADMIN (sans middleware admin) =====
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');

    // ===== TOUTES LES ROUTES ADMIN (middleware auth+admin) =====
Route::middleware('auth')->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistiques-forums', [ForumController::class, 'statistiquesAvancees'])->name('statistiques.forums');
    Route::post('/update-stats', function () {
        Artisan::call('forum:update-stats');
        return redirect()->back()->with('success', 'Statistiques mises à jour manuellement');
    })->name('update.stats');

        // UTILISATEURS
Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
    Route::get('/', [AdminController::class, 'utilisateursIndex'])->name('index');
    Route::get('/create', [AdminController::class, 'utilisateursCreate'])->name('create');
    Route::post('/', [AdminController::class, 'utilisateursStore'])->name('store');
    Route::get('/{id}', [AdminController::class, 'utilisateursShow'])->name('show'); // Ajouté
    Route::get('/{id}/edit', [AdminController::class, 'utilisateursEdit'])->name('edit');
    Route::put('/{id}', [AdminController::class, 'utilisateursUpdate'])->name('update');
    Route::delete('/{id}', [AdminController::class, 'utilisateursDestroy'])->name('destroy'); // Changé de POST à DELETE
    Route::post('/{user}/toggle', [AdminController::class, 'toggleUser'])->name('toggle');
});
Route::prefix('associations')->name('associations.')->group(function () {
    Route::get('/verify', [AdminController::class, 'associationsVerify'])->name('verify');
    Route::post('/{user}/toggle-verify', [AdminController::class, 'toggleVerifyAssociation'])->name('toggle-verify');
    Route::put('/{user}/matricule', [AdminController::class, 'updateAssociationMatricule'])->name('update-matricule');
});

        // EVENEMENTS
        Route::prefix('evenements')->name('evenements.')->group(function () {
            Route::get('/', [AdminController::class, 'evenementsIndex'])->name('index');
            Route::get('/create', [AdminController::class, 'evenementsCreate'])->name('create');
            Route::get('/categories', [AdminController::class, 'evenementsCategories'])->name('categories');
        });

        // CHALLENGES
        Route::prefix('challenges')->name('challenges.')->group(function () {
            Route::get('/', [AdminController::class, 'challengesIndex'])->name('index');
            Route::get('{id}/participants', [AdminController::class, 'challengesParticipations'])->name('participations');
            Route::get('all-scores', [AdminController::class, 'allScores'])->name('allScores');
            Route::get('scores/tous', [AdminController::class, 'allScores'])->name('all_scores');
            Route::get('{id}/edit', [AdminController::class, 'challengesEdit'])->name('edit');
            Route::put('{id}', [AdminController::class, 'challengesUpdate'])->name('update');
            Route::post('{id}/toggle', [AdminController::class, 'toggleChallenge'])->name('toggle');
            Route::post('participants/{id}/action', [AdminController::class, 'participantAction'])->name('participants.action');
        });

        // FORUMS
    Route::prefix('forums')->name('forums.')->group(function () {
        Route::get('/', [AdminController::class, 'forumsIndex'])->name('index');
        Route::get('/categories', [AdminController::class, 'forumsCategories'])->name('categories');
        Route::get('/moderations', [AdminController::class, 'forumsModerations'])->name('moderations');
        Route::get('/statistiques', [ForumController::class, 'statistiquesAvancees'])->name('statistiques');
    });

        // FORMATIONS
        Route::prefix('formations')->name('formations.')->group(function () {
            Route::get('/', [AdminController::class, 'formationsIndex'])->name('index');
            Route::get('/create', [AdminController::class, 'formationsCreate'])->name('create');
            Route::post('/store', [AdminController::class, 'formationsStore'])->name('store');
            Route::get('/inscriptions', [AdminController::class, 'formationsInscriptions'])->name('inscriptions');
        });

        // DONATIONS
        Route::prefix('donations')->name('donations.')->group(function () {
            Route::get('/', [AdminController::class, 'donationsIndex'])->name('index');
            Route::get('/campagnes', [AdminController::class, 'donationsCampagnes'])->name('campagnes');
            Route::get('/rapports', [AdminController::class, 'donationsRapports'])->name('rapports');

            // MÉTHODES DE PAIEMENT
            Route::get('/methodes', [AdminController::class, 'donationsMethodes'])->name('methodes');
            Route::post('/methodes', [AdminController::class, 'donationsMethodesStore'])->name('methodes.store');
            Route::get('/methodes/{key}', [AdminController::class, 'donationsMethodesGet'])->name('methodes.get');
            Route::put('/methodes/{key}', [AdminController::class, 'donationsMethodesUpdate'])->name('methodes.update');

            // ENTRIES / DONATIONS
            Route::get('/entries/{id}', [AdminController::class, 'donationsShow'])->name('entries.show');
            Route::post('/entries/{id}/send-receipt', [AdminController::class, 'donationsSendReceipt'])->name('entries.sendReceipt');
            Route::delete('/entries/{id}', [AdminController::class, 'donationsDestroy'])->name('entries.destroy');
        });
    });

    // ÉVÉNEMENTS ADMIN
    Route::prefix('evenements')->name('evenements.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EvenementsController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\EvenementsController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\EvenementsController::class, 'store'])->name('store');
        Route::get('/{evenement}', [\App\Http\Controllers\Admin\EvenementsController::class, 'show'])->name('show');
        Route::get('/{evenement}/edit', [\App\Http\Controllers\Admin\EvenementsController::class, 'edit'])->name('edit');
        Route::put('/{evenement}', [\App\Http\Controllers\Admin\EvenementsController::class, 'update'])->name('update');
        Route::delete('/{evenement}', [\App\Http\Controllers\Admin\EvenementsController::class, 'destroy'])->name('destroy');
        
        // AJAX endpoints for participants and comments
        Route::get('/{evenement}/participants', [\App\Http\Controllers\Admin\EvenementsController::class, 'getParticipants'])->name('participants');
        Route::get('/{evenement}/comments', [\App\Http\Controllers\Admin\EvenementsController::class, 'getComments'])->name('comments');
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

// Paymee return/cancel (public)
Route::get('/payments/paymee/return', [DonationController::class, 'paymeeReturn'])->name('payments.paymee.return');
Route::get('/payments/paymee/cancel', [DonationController::class, 'paymeeCancel'])->name('payments.paymee.cancel');

// TestPay checkout and cancel (public)
if (config('services.testpay.enabled')) {
    Route::get('/payments/test/checkout', [TestPaymentController::class, 'checkout'])->name('payments.test.checkout');
    Route::get('/payments/test/cancel', [TestPaymentController::class, 'cancel'])->name('payments.test.cancel');
}

// Paymee webhook (public API, CSRF exempt via api middleware)
Route::post('/webhooks/paymee', [DonationController::class, 'paymeeWebhook'])
        ->name('webhooks.paymee')
            ->middleware('api');

// Test route for force badge creation (development only)
Route::get('/force-badge', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    $user = auth()->user();
    
    // Create a test donation that will trigger badges
    $don = \App\Models\Donation::create([
        'utilisateur_id' => $user->id,
        'is_anonymous' => false,
        'evenement_id' => null,
        'montant' => 100.00, // Should trigger bronze badge
        'moyen_paiement' => 'test',
        'transaction_id' => 'force_badge_' . time(),
        'date_don' => now(),
    ]);
    
    // Trigger gamification
    $gamificationService = app(\App\Services\GamificationService::class);
    $result = $gamificationService->onDonation($don);
    
    // Set badges in session
    if (!empty($result['new_badges'])) {
        session()->flash('new_badges', $result['new_badges']);
        session()->flash('points_earned', $result['points']);
    }
    
    return redirect('/donations/history')->with('status', 'Force badge test completed! Badges: ' . count($result['new_badges']));
})->name('force.badge');

// Test route for badge notification (development only)
Route::get('/test-badge', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    // Simulate earning new badges
    session()->flash('new_badges', [
        [
            'slug' => 'donor_bronze', 
            'name' => 'Donateur Bronze', 
            'icon' => '🥉', 
            'description' => 'Vous avez atteint 50 TND de dons! Bravo pour votre générosité.'
        ],
        [
            'slug' => 'protector_oceans', 
            'name' => 'Protecteur des Océans', 
            'icon' => '🌊', 
            'description' => 'Vous avez soutenu la cause Écosystème avec plus de 100 TND!'
        ]
    ]);
    
    return redirect('/donations/history')->with('status', 'Badges de test ajoutés!');
})->name('test.badge');

// Test route for badge notification on events page
Route::get('/test-badge-events', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    // Simulate earning new badges
    session()->flash('new_badges', [
        [
            'slug' => 'donor_silver', 
            'name' => 'Donateur Argent', 
            'icon' => '🥈', 
            'description' => 'Vous avez atteint 200 TND de dons! Votre engagement fait la différence.'
        ]
    ]);
    
    return redirect('/events')->with('status', 'Badge de test ajouté!');
})->name('test.badge.events');

// Force badge award and create real donation
Route::get('/force-badge', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    try {
        // Create a test donation to trigger real badge logic
        $donation = \App\Models\Donation::create([
            'utilisateur_id' => auth()->id(),
            'is_anonymous' => false,
            'evenement_id' => 1,
            'montant' => 100.00, // This should trigger bronze badge
            'moyen_paiement' => 'test_force',
            'transaction_id' => 'force_' . time(),
            'date_don' => now(),
        ]);
        
        // Force badge evaluation
        $service = app(\App\Services\GamificationService::class);
        $result = $service->onDonation($donation);
        
        if (!empty($result['new_badges'])) {
            session()->flash('new_badges', $result['new_badges']);
            return redirect('/donations/history')->with('status', 'Force badge test completed! ' . count($result['new_badges']) . ' badges awarded.');
        } else {
            return redirect('/donations/history')->with('status', 'Force badge test completed but no badges awarded. Check logs.');
        }
        
    } catch (\Exception $e) {
        return redirect('/donations/history')->with('error', 'Error: ' . $e->getMessage());
    }
})->name('force.badge');

// Test badge popup page
Route::get('/test-badge-page', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    return view('test-badge');
})->name('test.badge.page');

// Simple badge test that always works
Route::get('/test-badge-simple', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    // Always set badge session data for testing
    session()->flash('new_badges', [
        [
            'slug' => 'test_badge', 
            'name' => 'Badge de Test', 
            'icon' => '🎯', 
            'description' => 'Ce badge confirme que le système de notification fonctionne parfaitement!'
        ]
    ]);
    
    return redirect('/test-badge-page')->with('status', 'Badge de test défini! Le popup devrait apparaître.');
})->name('test.badge.simple');

// Test specifically for Protecteur des Océans badge
Route::get('/test-protecteur-oceans', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    // Set Protecteur des Océans badge session data for testing
    session()->flash('new_badges', [
        [
            'slug' => 'protector_oceans', 
            'name' => 'Protecteur des Océans', 
            'icon' => '🌊', 
            'description' => 'A soutenu la cause Écosystème (≥100 TND)'
        ]
    ]);
    
    return redirect('/test-badge-page')->with('status', 'Badge Protecteur des Océans défini! Le popup devrait apparaître.');
})->name('test.protector.oceans');

// Test actual donation to event 2 to earn Protecteur des Océans
Route::get('/test-earn-protecteur-oceans', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    $user = Auth::user();
    
    // Create a donation to event 2 (Écosystème) to trigger Protecteur des Océans badge
    $don = new App\Models\Donation([
        'utilisateur_id' => $user->id,  // Correct field name
        'evenement_id' => 2, // Event 2 is Écosystème 
        'montant' => 100, // 100 TND to meet threshold
        'moyen_paiement' => 'test',
        'transaction_id' => 'test_protector_' . time(),
        'date_don' => now(),
        'statut' => 'confirmé'
    ]);
    $don->save();
    
    try {
        $gamificationService = app(\App\Services\GamificationService::class);
        $result = $gamificationService->onDonation($don);
        
        \Log::info('Test Protecteur des Océans - Gamification result', [
            'result' => $result,
            'new_badges' => $result['new_badges'] ?? [],
            'user_id' => $user->id,
            'donation_id' => $don->id,
            'event_id' => $don->evenement_id
        ]);
        
        if (!empty($result['new_badges'])) {
            session()->flash('new_badges', $result['new_badges']);
            \Log::info('Session flash set for new_badges', [
                'session_badges' => session('new_badges'),
                'result_badges' => $result['new_badges']
            ]);
            return redirect('/test-badge-page')->with('status', 'Donation créée! Badge earned: ' . count($result['new_badges']) . ' badge(s)');
        } else {
            return redirect('/test-badge-page')->with('error', 'Aucun nouveau badge earned. Vous avez peut-être déjà ce badge.');
        }
    } catch (\Throwable $e) {
        \Log::error('Test Protecteur des Océans error', ['error' => $e->getMessage()]);
        return redirect('/test-badge-page')->with('error', 'Erreur: ' . $e->getMessage());
    }
})->name('test.earn.protector.oceans');

// Reset protector_oceans badge for testing
Route::get('/reset-protector-badge', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    $user = Auth::user();
    
    // Remove protector_oceans badge if it exists
    $badge = DB::table('badges')->where('slug', 'protector_oceans')->first();
    if ($badge) {
        DB::table('user_badges')
            ->where('user_id', $user->id)
            ->where('badge_id', $badge->id)
            ->delete();
    }
    
    // Also remove test donations to event 2 to reset the threshold
    DB::table('donations')
        ->where('utilisateur_id', $user->id)
        ->where('evenement_id', 2)
        ->where('moyen_paiement', 'test')
        ->delete();
    
    return redirect('/test-badge-page')->with('status', 'Badge Protecteur des Océans supprimé et donations de test event 2 supprimées!');
})->name('test.reset.protector');

// Check current user's badge status and event 2 donations
Route::get('/check-protector-status', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    $user = Auth::user();
    
    // Check if user has protector_oceans badge
    $badge = DB::table('badges')->where('slug', 'protector_oceans')->first();
    $hasBadge = false;
    if ($badge) {
        $hasBadge = DB::table('user_badges')
            ->where('user_id', $user->id)
            ->where('badge_id', $badge->id)
            ->exists();
    }
    
    // Check total donations to event 2
    $event2Total = DB::table('donations')
        ->where('utilisateur_id', $user->id)
        ->where('evenement_id', 2)
        ->sum('montant');
    
    // Check all donations to event 2
    $event2Donations = DB::table('donations')
        ->where('utilisateur_id', $user->id)
        ->where('evenement_id', 2)
        ->orderBy('date_don', 'desc')
        ->get();
    
    $statusHtml = "
    <div style='padding: 20px; font-family: Arial; line-height: 1.6;'>
        <h2>Statut Badge Protecteur des Océans - User ID: {$user->id}</h2>
        <p><strong>Badge possédé:</strong> " . ($hasBadge ? '✅ OUI' : '❌ NON') . "</p>
        <p><strong>Total donations Event 2:</strong> {$event2Total} TND</p>
        <p><strong>Seuil requis:</strong> 100 TND</p>
        <p><strong>Éligible:</strong> " . ($event2Total >= 100 ? '✅ OUI' : '❌ NON') . "</p>
        
        <h3>Donations Event 2 (dernières 10):</h3>
        <table border='1' style='border-collapse: collapse; width: 100%;'>
            <tr><th>Date</th><th>Montant</th><th>Méthode</th><th>Transaction ID</th><th>Statut</th></tr>";
    
    foreach ($event2Donations->take(10) as $don) {
        $statusHtml .= "<tr>
            <td>{$don->date_don}</td>
            <td>{$don->montant} TND</td>
            <td>{$don->moyen_paiement}</td>
            <td>{$don->transaction_id}</td>
            <td>{$don->statut}</td>
        </tr>";
    }
    
    $statusHtml .= "</table>
        <br><a href='/test-badge-page' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Retour aux tests</a>
    </div>";
    
    return response($statusHtml);
})->name('check.protector.status');

// 404 fallback
/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(fn () => abort(404));