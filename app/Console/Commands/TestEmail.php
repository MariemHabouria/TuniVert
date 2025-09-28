<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Donation;
use App\Mail\DonationReceipt;

class TestEmail extends Command
{
    protected $signature = 'test:email {--user=test@example.com}';
    protected $description = 'Test email configuration by sending a sample donation receipt';

    public function handle()
    {
        $email = $this->option('user');
        
        // Find or create a test user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => $email,
            ]);
            $this->info("Created test user: {$email}");
        }

        // Create a test donation
        $donation = new Donation([
            'utilisateur_id' => $user->id,
            'evenement_id' => 2, // Ecosystem
            'montant' => 50.00,
            'moyen_paiement' => 'test',
            'transaction_id' => 'test_' . time(),
            'date_don' => now(),
        ]);
        $donation->save();

        $this->info("Created test donation ID: {$donation->id}");

        try {
            // Send the receipt email
            Mail::to($email)->send(new DonationReceipt($donation));
            $this->info("✅ Email sent successfully to: {$email}");
            $this->info("Check your Mailtrap inbox or logs for the receipt email with QR code.");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            $this->info("Check your .env mail configuration and try again.");
        }

        // Clean up test donation
        $donation->delete();
        $this->info("Cleaned up test donation.");

        return 0;
    }
}