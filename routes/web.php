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
use App\Http\Controllers\TestPaymentController;
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
$pages = ['about', 'blog', 'causes', 'contact', 'donation', 'events', 'gallery', 'service'];
foreach ($pages as $page) {
    Route::view("/{$page}", "pages.{$page}")->name($page);
    Route::view("/{$page}.html", "pages.{$page}");
}

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
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/password/forgot', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Routes événements
|--------------------------------------------------------------------------
*/
Route::get('events', [EventController::class, 'index'])->name('events.index');
Route::get('events/create', [EventController::class, 'create'])->name('events.create')->middleware('auth');
Route::post('events', [EventController::class, 'store'])->name('events.store')->middleware('auth');
Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit')->middleware('auth');
Route::put('events/{event}', [EventController::class, 'update'])->name('events.update')->middleware('auth');
Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Forums & Alertes
|--------------------------------------------------------------------------
*/
Route::resource('forums', ForumController::class);
Route::post('/forums/{id}/reply', [ForumController::class, 'reply'])->middleware('auth')->name('forums.reply');
Route::post('/forums/{forum}/reponse-suggestion', [ForumController::class, 'suggestionIA'])->name('forums.reponse.suggestion');

Route::resource('alertes', AlerteForumController::class)->only(['index', 'show']);
Route::middleware('auth')->group(function () {
    Route::resource('alertes', AlerteForumController::class)->except(['index', 'show']);
    Route::delete('/commentaires/{id}', [AlerteForumController::class, 'destroyCommentaire'])->name('commentaires.destroy');
});

Route::get('/recherche', [ForumController::class, 'rechercheAvancee'])->name('recherche.avancee');

// Alertes publiques
Route::get('/alertes', [AlerteForumController::class, 'index'])->name('alertes.index');
Route::get('/alertes/{id}', [AlerteForumController::class, 'show'])->name('alertes.show');

// Alertes admin/modération
Route::get('/alertes/moderation/admin', [AlerteForumController::class, 'moderationAdmin'])
    ->name('alertes.moderation.admin')
    ->middleware(['auth', 'admin']);

/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
*/
Route::prefix('notifications')->name('notifications.')->middleware('auth')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/toutes-lues', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/non-lues/count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
});
Route::get('/api/notifications/non-lues', [NotificationController::class, 'getNonLues'])->name('api.notifications.non-lues');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');

    Route::middleware('auth')->group(function () {
        // Tableau de bord
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Utilisateurs
        Route::prefix('utilisateurs')->name('utilisateurs.')->group(function () {
            Route::get('/', [AdminController::class, 'utilisateursIndex'])->name('index');
            Route::get('/create', [AdminController::class, 'utilisateursCreate'])->name('create');
            Route::post('/', [AdminController::class, 'utilisateursStore'])->name('store');
            Route::get('/{id}/edit', [AdminController::class, 'utilisateursEdit'])->name('edit');
            Route::put('/{id}', [AdminController::class, 'utilisateursUpdate'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'utilisateursDestroy'])->name('destroy');
        });

        // Challenges
        Route::prefix('challenges')->name('challenges.')->group(function () {
            Route::get('/', [AdminController::class, 'challengesIndex'])->name('index');
            Route::get('/{id}/participants', [AdminController::class, 'challengesParticipations'])->name('participations');
            Route::get('/scores/tous', [AdminController::class, 'allScores'])->name('all_scores');
            Route::post('/{id}/toggle', [AdminController::class, 'toggleChallenge'])->name('toggle');
        });

        // Forums
        Route::prefix('forums')->name('forums.')->group(function () {
            Route::get('/', [AdminController::class, 'forumsIndex'])->name('index');
            Route::get('/categories', [AdminController::class, 'forumsCategories'])->name('categories');
            Route::get('/moderations', [AdminController::class, 'forumsModerations'])->name('moderations');
            Route::get('/statistiques', [ForumController::class, 'statistiquesAvancees'])->name('statistiques');
        });

        // Formations
        Route::prefix('formations')->name('formations.')->group(function () {
            Route::get('/', [AdminController::class, 'formationsIndex'])->name('index');
            Route::get('/create', [AdminController::class, 'formationsCreate'])->name('create');
            Route::get('/inscriptions', [AdminController::class, 'formationsInscriptions'])->name('inscriptions');
        });

        // Donations
        Route::prefix('donations')->name('donations.')->group(function () {
            Route::get('/', [AdminController::class, 'donationsIndex'])->name('index');
            Route::get('/campagnes', [AdminController::class, 'donationsCampagnes'])->name('campagnes');
            Route::get('/rapports', [AdminController::class, 'donationsRapports'])->name('rapports');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Fallback (404)
|--------------------------------------------------------------------------
*/
Route::fallback(fn () => abort(404));
