<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\FormationInscriptionController;  // Ajout de l'import pour ce controller
use App\Http\Controllers\AvisFormationController;
use App\Http\Controllers\RessourceFormationController; // ← ajout

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

// Auth (formulaires accessibles seulement si non connecté)
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

// Profil et opérations nécessitant authentification
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


        // Avis sur une formation (auth requis)
Route::post('/formations/{formation}/avis', [AvisFormationController::class, 'store'])
    ->middleware('auth')
    ->name('formations.avis.store');

    // Ajout d'une ressource à une formation (réservé aux connectés ; tu peux ajouter un middleware "organisateur" si tu en as un)
Route::post('/formations/{formation}/ressources', [RessourceFormationController::class, 'store'])
    ->middleware('auth')
    ->name('formations.ressources.store');

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Catalogue accessible à tous
Route::get('/formations',        [FormationController::class, 'index'])->name('formations.index');
Route::get('/formations/{formation}', [FormationController::class, 'show'])->name('formations.show');

// 404 standard
Route::fallback(fn () => abort(404));
