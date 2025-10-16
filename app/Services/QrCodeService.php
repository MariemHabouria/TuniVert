<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR code for donation receipt
     */
    public function generateDonationQrCode(Donation $donation): array
    {
        $eventLabels = [
            1 => 'Organic Campaign', 
            2 => 'Ecosystem', 
            3 => 'Recycling', 
            4 => 'Awareness Day'
        ];
        
        $eventTitle = $donation->evenement_id 
            ? ($eventLabels[$donation->evenement_id] ?? ('Event #'.$donation->evenement_id))
            : 'General Donation';
            
        $code = 'TV-'.strtoupper(substr(hash('xxh128', $donation->id.'|'.$donation->transaction_id.'|'.$donation->date_don), 0, 10));
        
        $qrData = [
            'code' => $code,
            'donation_id' => $donation->id,
            'amount' => $donation->montant,
            'currency' => 'TND',
            'event' => $eventTitle,
            'method' => $donation->moyen_paiement,
            'txn' => $donation->transaction_id,
            'date' => $donation->date_don->format('Y-m-d H:i:s'),
            'verify_url' => route('donation.verify', ['code' => $code])
        ];
        
        return [
            'code' => $code,
            'data' => $qrData,
            'event_title' => $eventTitle
        ];
    }
    
    /**
     * Generate QR code as PNG base64 data URI
     */
    public function generateQrCodeImage(array $qrData): string
    {
        try {
            // Use SVG format which doesn't require imagick
            $qrImage = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate(json_encode($qrData));
                
            return 'data:image/svg+xml;base64,' . base64_encode($qrImage);
            
        } catch (\Exception $e) {
            // Simple fallback text if QR generation fails
            $text = "QR Code: " . json_encode($qrData);
            return 'data:text/plain;base64,' . base64_encode($text);
        }
    }
    
    /**
     * Generate QR code as SVG file for email attachment
     */
    public function generateQrCodeFile(array $qrData, string $filename = null): array
    {
        try {
            $qrImage = QrCode::format('svg')
                ->size(400)
                ->margin(3)
                ->generate(json_encode($qrData));
                
            $filename = $filename ?: 'qr-donation-'.time().'.svg';
            
            return [
                'content' => $qrImage,
                'filename' => $filename,
                'mime' => 'image/svg+xml'
            ];
            
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate QR code file: ' . $e->getMessage());
        }
    }
    
    /**
     * Save QR code to storage for later use
     */
    public function saveQrCodeToStorage(Donation $donation): string
    {
        $qrInfo = $this->generateDonationQrCode($donation);
        $qrFile = $this->generateQrCodeFile($qrInfo['data'], 'donation-'.$donation->id.'-'.$qrInfo['code'].'.png');
        
        $path = 'qr-codes/' . $qrFile['filename'];
        Storage::disk('public')->put($path, $qrFile['content']);
        
        return $path;
    }
    
    /**
     * Generate complete QR code data for email
     */
    public function prepareEmailQrCode(Donation $donation): array
    {
        $qrInfo = $this->generateDonationQrCode($donation);
        
        return [
            'code' => $qrInfo['code'],
            'event_title' => $qrInfo['event_title'],
            'data_uri' => $this->generateQrCodeImage($qrInfo['data']),
            'attachment' => $this->generateQrCodeFile($qrInfo['data'], 'qr-'.$qrInfo['code'].'.png')
        ];
    }
}