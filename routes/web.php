<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ScoreChallengeController;

// Pages publiques
Route::view('/', 'pages.index')->name('home');

$pages = ['about','blog','causes','contact','donation','events','gallery','service'];
foreach ($pages as $page) {
    Route::view("/{$page}", "pages.{$page}")->name($page);
    Route::view("/{$page}.html", "pages.{$page}");
}

// Auth routes pour invités
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

// Routes protégées pour utilisateurs connectés
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Routes Challenges
Route::prefix('challenges')->group(function () {

    // Liste des challenges
    Route::get('/', [ChallengeController::class, 'index'])->name('challenges.index');

    // Routes protégées par authentification
    Route::middleware('auth')->group(function () {

        // Route statique profil → doit être avant la route dynamique /{id}
        Route::get('/profil', [ChallengeController::class, 'profil'])->name('challenges.profil');

        // Participant
        Route::post('/{id}/participate', [ChallengeController::class, 'participer'])->name('challenges.participate');
        Route::post('/{id}/submit-proof', [ChallengeController::class, 'soumettrePreuve'])->name('challenges.submit');

        // CRUD association
        Route::prefix('association')->group(function () {
            Route::get('/create', [ChallengeController::class, 'create'])->name('challenges.create');
            Route::post('/', [ChallengeController::class, 'store'])->name('challenges.store');
            Route::get('/crud', [ChallengeController::class, 'crud'])->name('challenges.crud');
            Route::get('/{id}/edit', [ChallengeController::class, 'edit'])->name('challenges.edit');
            Route::put('/{id}', [ChallengeController::class, 'update'])->name('challenges.update');
            Route::delete('/{id}', [ChallengeController::class, 'destroy'])->name('challenges.destroy');

            // Actions sur les participants
            Route::post('/participants/{participant}/action', [ChallengeController::class, 'actionParticipant'])
                ->name('challenges.participants.action');

            // Liste des participants
            Route::get('/participants/{id}', [ChallengeController::class, 'participants'])
                ->name('challenges.participants');
        });
    });

    // Route dynamique show → toujours à la fin
    Route::get('/{id}', [ChallengeController::class, 'show'])->name('challenges.show');
});

// Routes Scores
Route::prefix('scores')->name('scores.')->middleware('auth')->group(function () {
    Route::post('/{participant}', [ScoreChallengeController::class, 'storeOrUpdate'])->name('update');
    Route::delete('/{score}', [ScoreChallengeController::class, 'destroy'])->name('destroy');
    Route::get('/classement/{challenge}', [ScoreChallengeController::class, 'classement'])->name('classement');
});
