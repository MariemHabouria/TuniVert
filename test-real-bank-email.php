<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use App\Models\Donation;
use App\Mail\DonationReceipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Services\GamificationService;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Bank Transfer Email with Real SMTP\n";
echo "==========================================\n";

// Check current environment
echo "Environment: " . app()->environment() . "\n";

// Check mail configuration
echo "Mail Driver: " . config('mail.default') . "\n";
echo "SMTP Host: " . config('mail.mailers.smtp.host') . "\n";
echo "From Email: " . config('mail.from.address') . "\n";

// Find a test user
$user = User::first();
if (!$user) {
    echo "No users found in database. Please create a user first.\n";
    exit(1);
}

echo "Test User: {$user->name} ({$user->email})\n";

// Simulate being logged in as this user
Auth::login($user);

echo "Creating bank transfer donation...\n";

// Simulate the donation controller logic
$validated = [
    'montant' => '75.50',
    'moyen_paiement' => 'virement_bancaire',
    'evenement_id' => null,
    'is_anonymous' => false,
];

try {
    // Create donation (like the controller does)
    $don = Donation::create([
        'utilisateur_id' => Auth::id(),
        'is_anonymous' => (bool)($validated['is_anonymous'] ?? false),
        'evenement_id' => $validated['evenement_id'] ?? null,
        'montant' => (float)$validated['montant'],
        'moyen_paiement' => $validated['moyen_paiement'],
        'transaction_id' => 'REAL_TEST_' . time(),
        'date_don' => now(),
    ]);
    
    echo "✅ Donation created: ID {$don->id}, Amount: {$don->montant} TND\n";
    
    // Apply gamification
    try {
        $res = app(GamificationService::class)->onDonation($don);
        echo "✅ Gamification applied\n";
        if (!empty($res['new_badges'])) {
            echo "🏆 New badges earned: " . implode(', ', array_column($res['new_badges'], 'name')) . "\n";
        }
    } catch (\Throwable $e) {
        echo "⚠️  Gamification error (non-critical): " . $e->getMessage() . "\n";
    }
    
    // Email logic (copied from controller)
    echo "Sending email...\n";
    
    $owner = $don->user ?? Auth::user();
    if ($owner) {
        $recipient = $owner->email ?? null;
        
        if (!app()->environment('local')) {
            $owner->notify(new \App\Notifications\DonationReceived($don));
        }
        
        if ($recipient) {
            // Use the exact same logic as the updated controller
            $mailable = ($validated['moyen_paiement'] === 'virement_bancaire') 
                ? new \App\Mail\DonationReceipt($don)
                : new \App\Mail\DonationReceiptSimple($don);
                
            // For bank transfer, always send real emails to test email functionality
            if ($validated['moyen_paiement'] === 'virement_bancaire') {
                echo "📧 Sending bank transfer email via SMTP to: {$recipient}\n";
                Mail::to($recipient)->sendNow($mailable);
                echo "✅ Bank transfer email sent successfully!\n";
                echo "📬 Check your inbox: {$recipient}\n";
            } else {
                // Other payment methods logic (not used in this test)
                if (app()->environment('local')) {
                    Mail::mailer('log')->to($recipient)->queue($mailable);
                } else {
                    Mail::to($recipient)->queue($mailable);
                }
            }
        } else {
            echo "❌ No recipient email found\n";
        }
    } else {
        echo "❌ No donation owner found\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🎉 Test completed! Check your email inbox for the donation receipt.\n";