<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Attachment;

class DonationReceiptWithAttachment extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
        //
    }

    public function build(): self
    {
        $don = $this->donation;
        $eventLabels = [1 => 'Organic Campaign', 2 => 'Ecosystem', 3 => 'Recycling', 4 => 'Awareness Day'];
        $eventTitle = $don->evenement_id ? ($eventLabels[$don->evenement_id] ?? ('Event #'.$don->evenement_id)) : 'General Donation';
        $code = 'TV-'.strtoupper(substr(hash('xxh128', $don->id.'|'.$don->transaction_id.'|'.$don->date_don), 0, 10));

        // Generate QR code as PNG file and attach it
        $qrData = json_encode([
            'code' => $code,
            'donation_id' => $don->id,
            'amount' => $don->montant,
            'currency' => 'TND',
            'event' => $eventTitle,
            'method' => $don->moyen_paiement,
            'txn' => $don->transaction_id,
        ]);
        
        $qrPng = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qrData);

        $amountStr = number_format((float)$don->montant, 2, ',', ' ').' TND';

        return $this->subject('Reçu de don – '.$amountStr)
            ->view('emails.donations.receipt-simple')
            ->with([
                'donation' => $don,
                'eventTitle' => $eventTitle,
                'code' => $code,
                'amountStr' => $amountStr,
            ])
            ->attachData($qrPng, 'qr-code-'.$code.'.png', [
                'mime' => 'image/png',
            ]);
    }
}