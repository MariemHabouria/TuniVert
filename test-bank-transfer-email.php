<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use App\Models\Donation;
use App\Mail\DonationReceipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Bank Transfer Email Logic\n";
echo "=================================\n";

// Check current environment
echo "Environment: " . app()->environment() . "\n";

// Check mail configuration
echo "Mail Driver: " . config('mail.default') . "\n";

// Create a test user
$user = User::first();
if (!$user) {
    echo "No users found in database. Please create a user first.\n";
    exit;
}

echo "Test User: {$user->name} ({$user->email})\n";

// Create and save a test bank transfer donation
$donation = Donation::create([
    'utilisateur_id' => $user->id,
    'montant' => 50.00,
    'moyen_paiement' => 'virement_bancaire',
    'transaction_id' => 'TEST_BANK_' . time(),
    'date_don' => now(),
    'is_anonymous' => false
]);

echo "Test Donation created: {$donation->montant} TND via {$donation->moyen_paiement} (ID: {$donation->id})\n";

try {
    // Test the DonationReceipt mailable
    $mailable = new DonationReceipt($donation);
    echo "DonationReceipt mailable created successfully\n";
    
    // Test sending in local environment (should use log mailer with sendNow)
    if (app()->environment('local')) {
        echo "Sending email via log mailer with sendNow...\n";
        Mail::mailer('log')->to($user->email)->sendNow($mailable);
        echo "Email sent to log successfully!\n";
    } else {
        echo "Sending email via default mailer with sendNow...\n";
        Mail::to($user->email)->sendNow($mailable);
        echo "Email sent successfully!\n";
    }
    
    // Check for recent log entries
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
        if (strpos($logContent, 'DonationReceipt') !== false) {
            echo "✅ Found DonationReceipt in log file\n";
        } else {
            echo "❌ No DonationReceipt found in log file\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";