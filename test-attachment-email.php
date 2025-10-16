<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Mail\DonationReceiptWithAttachment;
use App\Models\Donation;
use Illuminate\Support\Facades\Mail;

// Find last donation or create test data
$don = Donation::latest('id')->first();
if (!$don) {
    $don = new Donation([
        'id' => 999,
        'utilisateur_id' => 1,
        'montant' => 25.50,
        'evenement_id' => 1,
        'moyen_paiement' => 'test',
        'transaction_id' => 'test_attach_'.time(),
        'date_don' => now(),
    ]);
}

try {
    Mail::to('hamoudachkir@yahoo.fr')->send(new DonationReceiptWithAttachment($don));
    echo "✅ Email avec QR en pièce jointe envoyé vers hamoudachkir@yahoo.fr\n";
    echo "📎 Le QR code est maintenant en fichier attaché PNG\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}