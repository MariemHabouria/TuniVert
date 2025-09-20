<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChallengeController;

/*
|--------------------------------------------------------------------------
| Pages principales
|--------------------------------------------------------------------------
*/

// Accueil
Route::view('/', 'pages.index')->name('home');

// Pages principales (avec et sans .html)
$pages = ['about','blog','causes','contact','donation','events','gallery','service'];
foreach ($pages as $page) {
    Route::view("/{$page}", "pages.{$page}")->name($page);
    Route::view("/{$page}.html", "pages.{$page}");
}

// Alias pour la home (index.html) et pour "services"
Route::view('/index', 'pages.index');
Route::view('/index.html', 'pages.index');
Route::view('/services', 'pages.service');
Route::view('/services.html', 'pages.service');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

// Formulaires accessibles seulement si non connecté
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.submit');

    // Mot de passe oublié
    Route::get('/password/forgot',  [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Réinitialisation via lien reçu par email
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset',       [AuthController::class, 'resetPassword'])->name('password.update');
});

// Profil (uniquement si connecté)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
});

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Partie Challenge
|--------------------------------------------------------------------------
*/

// Routes publiques (voir les challenges)
Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
// Route::get('/challenges/{id}', [ChallengeController::class, 'show'])->name('challenges.show');

// Routes protégées (utilisateurs connectés)
Route::middleware('auth')->group(function () {
    // Créer un challenge
    Route::get('/challenges/create', function () {
        return view('challenges.create');
    })->name('challenges.create');

    Route::post('/challenges', [ChallengeController::class, 'store'])->name('challenges.store');

    // Participer à un challenge
    Route::post('/challenges/{id}/participate', [ChallengeController::class, 'participer'])->name('challenges.participate');

    // Soumettre une preuve (photo/vidéo)
    Route::post('/challenges/participant/{id}/submit', [ChallengeController::class, 'soumettrePreuve'])->name('challenges.submit');

    // Classement d’un challenge
    Route::get('/challenges/{id}/classement', [ChallengeController::class, 'classement'])->name('challenges.classement');

    // Valider/Rejeter une preuve
    Route::post('/challenges/participant/{id}/validate', [ChallengeController::class, 'validerPreuve'])->name('challenges.validate');
});

/*
|--------------------------------------------------------------------------
| Fallback (404)
|--------------------------------------------------------------------------
*/

Route::fallback(fn () => abort(404));
