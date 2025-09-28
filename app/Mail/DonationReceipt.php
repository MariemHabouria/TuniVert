<?php

namespace App\Mail;

use App\Models\Donation;
use App\Services\QrCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationReceipt extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
        //
    }

    public function build(): self
    {
        $qrService = new QrCodeService();
        $qrData = $qrService->prepareEmailQrCode($this->donation);
        
        $amountStr = number_format((float)$this->donation->montant, 2, ',', ' ').' TND';

        return $this->subject('Reçu de don – '.$amountStr)
            ->view('emails.donations.receipt')
            ->with([
                'donation' => $this->donation,
                'eventTitle' => $qrData['event_title'],
                'code' => $qrData['code'],
                'amountStr' => $amountStr,
                'qrDataUri' => $qrData['data_uri'],
            ])
            ->attachData(
                (string) $qrData['attachment']['content'], 
                $qrData['attachment']['filename'], 
                ['mime' => $qrData['attachment']['mime']]
            );
    }
}
