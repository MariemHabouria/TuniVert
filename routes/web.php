<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChallengeController;

// Pages principales
Route::view('/', 'pages.index')->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.submit');
});

// Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Challenges pour participants
Route::prefix('challenges')->group(function () {
    Route::get('/', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::get('/{id}', [ChallengeController::class, 'show'])->name('challenges.show');
});

Route::middleware('auth')->prefix('challenges')->group(function () {

    // Participant
    Route::post('/{id}/participate', [ChallengeController::class, 'participer'])->name('challenges.participate');
    Route::post('/participant/{id}/submit', [ChallengeController::class, 'soumettrePreuve'])->name('challenges.submit');

    // Eviter l’erreur GET accidentelle
    Route::get('/participant/{id}/submit', function() {
        return redirect()->back()->with('error', 'Veuillez utiliser le formulaire pour soumettre la preuve.');
    });

    Route::get('/{id}/classement', [ChallengeController::class, 'classement'])->name('challenges.classement');
    Route::get('/profil', [ChallengeController::class, 'profil'])->name('challenges.profil');

    // Association
    Route::prefix('association')->group(function () {
        Route::get('/create', [ChallengeController::class, 'create'])->name('challenges.create');
        Route::post('/', [ChallengeController::class, 'store'])->name('challenges.store');
        Route::get('/participants/{id}', [ChallengeController::class, 'participants'])->name('challenges.participants');
        Route::get('/crud', [ChallengeController::class, 'crud'])->name('challenges.crud');
    });
});
