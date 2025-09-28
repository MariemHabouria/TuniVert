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
// Donations / Events additions
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationAdminController;
use App\Http\Controllers\DonationOrganizerController;
use App\Http\Controllers\TestPaymentController;
use App\Http\Controllers\EventsController;

// Include QR verification route (donations branch)
require __DIR__.'/qr-verify.php';

// Pages publiques
Route::view('/', 'pages.index')->name('home');

// Union des pages (HEAD + donations)
$pages = ['about','blog','causes','contact','donation','events','gallery','service'];
foreach ($pages as $page) {
    Route::view("/{$page}", "pages.{$page}")->name($page);
    Route::view("/{$page}.html", "pages.{$page}");
}

// Events routes (dynamic listing)
Route::get('/events', [EventsController::class, 'index'])->name('events.browse');
Route::get('/events/{id}', [EventsController::class, 'show'])->name('events.show');

// Alias pour legacy chemins
Route::view('/index', 'pages.index');
Route::view('/index.html', 'pages.index');
Route::view('/services', 'pages.service');
Route::view('/services.html', 'pages.service');

// Auth routes (invités uniquement)
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

// Routes protégées pour utilisateurs connectés (profil + formations + donations)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // Création (associations uniquement)
    Route::get('/organisateur/formations/create', [FormationController::class, 'create'])->name('formations.create');
    Route::post('/organisateur/formations',        [FormationController::class, 'store'])->name('formations.store');

    // Ressources
    Route::post('/organisateur/formations/{formation}/ressources', [FormationController::class, 'storeResource'])
        ->name('formations.resources.store');

    // Inscription aux formations
    Route::post('/formations/{formation}/inscrire', [FormationInscriptionController::class, 'store'])
        ->name('formations.inscrire');
    Route::delete('/formations/{formation}/desinscrire', [FormationInscriptionController::class, 'destroy'])
        ->name('formations.desinscrire');

    // Avis sur une formation
    Route::post('/formations/{formation}/avis', [AvisFormationController::class, 'store'])
        ->middleware('auth')
        ->name('formations.avis.store');

    // Ajout d'une ressource à une formation
    Route::post('/formations/{formation}/ressources', [RessourceFormationController::class, 'store'])
        ->middleware('auth')
        ->name('formations.ressources.store');

    // Edit / Update / Destroy formations
    Route::get('organisateur/formations/{formation}/edit', [FormationController::class, 'edit'])->name('formations.edit');
    Route::put('organisateur/formations/{formation}', [FormationController::class, 'update'])->name('formations.update');
    Route::delete('organisateur/formations/{formation}', [FormationController::class, 'destroy'])->name('formations.destroy');

    Route::middleware(['auth'])->get('/mes-formations/stats', [FormationController::class, 'dashboard'])
        ->name('formations.dashboard');

    // Déconnexion
    // Donations (donor actions)
    Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/history', [DonationController::class, 'history'])->name('donations.history');

    // Payments - Stripe
    Route::post('/payments/stripe/intent', [DonationController::class, 'createStripeIntent'])->name('payments.stripe.intent');
    Route::post('/payments/stripe/confirm', [DonationController::class, 'confirmStripePayment'])->name('payments.stripe.confirm');

    // Payments - PayPal
    Route::post('/payments/paypal/create-order', [DonationController::class, 'createPayPalOrder'])->name('payments.paypal.create');
    Route::post('/payments/paypal/capture', [DonationController::class, 'capturePayPalOrder'])->name('payments.paypal.capture');

    // Payments - Paymee (e‑DINAR)
    Route::post('/payments/paymee/create', [DonationController::class, 'createPaymeePayment'])->name('payments.paymee.create');

    // Payments - Test (mock) - only if enabled
    if (config('services.testpay.enabled')) {
        Route::post('/payments/test/create', [TestPaymentController::class, 'create'])->name('payments.test.create');
        Route::post('/payments/test/complete', [TestPaymentController::class, 'complete'])->name('payments.test.complete');
    }

    // Admin donations listing (simple placeholder separate from admin area)
    Route::get('/admin/donations', [DonationAdminController::class, 'index'])->name('admin.donations.index');

    // Test bank transfer email functionality
    Route::get('/test-bank-donation', function() {
        return view('test-bank-donation');
    })->name('test.bank.donation');
    Route::post('/test-bank-donation', [DonationController::class, 'testBankTransferEmail'])->name('test.bank.donation.post');

    // Test badge notifications (disabled - no more popups)
    Route::get('/test-badges', function() {
        return redirect('/donations/history')->with('success', 'Badge notifications are now disabled. Badges only show in navbar and history page.');
    })->name('test.badges');

    // Organizer donations by event
    Route::get('/organizer/events/{eventId}/donations', [DonationOrganizerController::class, 'byEvent'])->name('organizer.donations.byEvent');

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Catalogue accessible à tous
Route::get('/formations',        [FormationController::class, 'index'])->name('formations.index');
Route::get('/formations/{formation}', [FormationController::class, 'show'])->name('formations.show');

// Routes Challenges
Route::prefix('challenges')->group(function () {
    Route::get('/', [ChallengeController::class, 'index'])->name('challenges.index');
    
    Route::middleware('auth')->group(function () {
        Route::get('/profil', [ChallengeController::class, 'profil'])->name('challenges.profil');
        Route::post('/{id}/participate', [ChallengeController::class, 'participer'])->name('challenges.participate');
        Route::post('/{id}/submit-proof', [ChallengeController::class, 'soumettrePreuve'])->name('challenges.submit');
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

// ===== ROUTES ADMIN (application wide) =====
Route::prefix('admin')->name('admin.')->group(function () {
    // Login Admin accessible sans auth
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.submit'); 
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');

    // Routes admin protégées
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
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

        // Participations d’un challenge
        Route::get('{id}/participants', [AdminController::class, 'challengesParticipations'])
            ->name('participations');

        Route::get('/scores/tous', [AdminController::class, 'allScores'])->name('all_scores');

        // Toggle challenge (bloquer/débloquer)
        Route::post('{id}/toggle', [AdminController::class, 'toggleChallenge'])->name('toggle');
    });

    Route::prefix('forums')->name('forums.')->group(function () {
        Route::get('/', [AdminController::class, 'forumsIndex'])->name('index');
        Route::get('/categories', [AdminController::class, 'forumsCategories'])->name('categories');
        Route::get('/moderations', [AdminController::class, 'forumsModerations'])->name('moderations');
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

// 404 fallback
Route::fallback(fn () => abort(404));
