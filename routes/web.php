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
    
    // Association dashboard
    Route::get('/donations/dashboard', [DonationController::class, 'associationDashboard'])
        ->name('donations.dashboard');

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
| Forums & Alertes
|--------------------------------------------------------------------------
*/
Route::resource('forums', ForumController::class);
Route::post('/forums/{id}/reply', [ForumController::class, 'reply'])->middleware('auth')->name('forums.reply');

Route::resource('alertes', AlerteForumController::class)->middleware('auth')->except(['index','show']);
Route::resource('alertes', AlerteForumController::class)->only(['index','show']);

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
        Route::get('/methodes', [AdminController::class, 'donationsMethodes'])->name('methodes');
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
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(fn () => abort(404));
