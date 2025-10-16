<?php

use Illuminate\Support\Facades\Route;
use App\Services\QrCodeService;
use App\Models\Donation;

Route::get('/donation/verify/{code}', function($code) {
    $donation = Donation::whereRaw("CONCAT('TV-', UPPER(SUBSTR(UNHEX(SHA2(CONCAT(id, '|', IFNULL(transaction_id, ''), '|', date_don), 512)), 1, 10))) = ?", [$code])->first();
    
    if (!$donation) {
        return response()->json(['valid' => false, 'message' => 'Code QR invalide ou expiré']);
    }
    
    $eventLabels = [1 => 'Organic Campaign', 2 => 'Ecosystem', 3 => 'Recycling', 4 => 'Awareness Day'];
    $eventTitle = $donation->evenement_id ? ($eventLabels[$donation->evenement_id] ?? ('Event #'.$donation->evenement_id)) : 'General Donation';
    
    return response()->json([
        'valid' => true,
        'donation' => [
            'code' => $code,
            'amount' => number_format((float)$donation->montant, 2, ',', ' ') . ' TND',
            'event' => $eventTitle,
            'date' => $donation->date_don->format('d/m/Y H:i'),
            'method' => $donation->moyen_paiement,
        ]
    ]);
})->name('donation.verify');