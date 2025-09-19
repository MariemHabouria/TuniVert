<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.submit');


   // Mot de passe oublié
    Route::get('/password/forgot',  [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Réinitialisation via lien reçu par email
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset',       [AuthController::class, 'resetPassword'])->name('password.update');
});

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

// 404 standard
Route::fallback(fn () => abort(404));
