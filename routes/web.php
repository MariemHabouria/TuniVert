<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationAdminController;
use App\Http\Controllers\DonationOrganizerController;
use App\Http\Controllers\TestPaymentController;
use App\Http\Controllers\EventsController;

// Include QR verification route
require __DIR__.'/qr-verify.php';

// Accueil
Route::view('/', 'pages.index')->name('home');

// Pages principales (avec et sans .html)
$pages = ['about','blog','causes','contact','donation','gallery','service'];
foreach ($pages as $page) {
    Route::view("/{$page}", "pages.{$page}")->name($page);
    Route::view("/{$page}.html", "pages.{$page}");
}

// Events routes (separate from static pages)
Route::get('/events', [EventsController::class, 'index'])->name('events.browse');
Route::get('/events/{id}', [EventsController::class, 'show'])->name('events.show');

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

// Donations (auth required)
Route::middleware('auth')->group(function(){
    // Donateur
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

    // Admin (you can replace with middleware like 'can:admin')
    Route::get('/admin/donations', [DonationAdminController::class, 'index'])->name('admin.donations.index');

    // Organizer
    Route::get('/organizer/events/{eventId}/donations', [DonationOrganizerController::class, 'byEvent'])->name('organizer.donations.byEvent');
});
// Paymee return/cancel should be public (no auth required)
Route::get('/payments/paymee/return', [DonationController::class, 'paymeeReturn'])->name('payments.paymee.return');
Route::get('/payments/paymee/cancel', [DonationController::class, 'paymeeCancel'])->name('payments.paymee.cancel');

// TestPay checkout and cancel are public endpoints for redirect
if (config('services.testpay.enabled')) {
    Route::get('/payments/test/checkout', [TestPaymentController::class, 'checkout'])->name('payments.test.checkout');
    Route::get('/payments/test/cancel', [TestPaymentController::class, 'cancel'])->name('payments.test.cancel');
}

// 404 standard
Route::fallback(fn () => abort(404));

// Paymee webhook (must be reachable publicly) - use API middleware to avoid CSRF
Route::post('/webhooks/paymee', [DonationController::class, 'paymeeWebhook'])
    ->name('webhooks.paymee')
    ->middleware('api');
