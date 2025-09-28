<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationReceived extends Notification
{
    use Queueable;

    public function __construct(public Donation $donation)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $eventLabels = [1 => 'Organic Campaign', 2 => 'Ecosystem', 3 => 'Recycling', 4 => 'Awareness Day'];
        $eventTitle = $this->donation->evenement_id ? ($eventLabels[$this->donation->evenement_id] ?? ('Event #'.$this->donation->evenement_id)) : 'General Donation';
        $code = 'TV-'.strtoupper(substr(hash('xxh128', $this->donation->id.'|'.$this->donation->transaction_id.'|'.$this->donation->date_don), 0, 10));
        return (new MailMessage)
            ->subject('Merci pour votre don')
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('Nous avons bien reçu votre don de ' . number_format((float)$this->donation->montant, 2, ',', ' ') . ' TND.')
            ->line('Événement: '.$eventTitle)
            ->line('Code reçu: '.$code)
            ->line('Moyen: ' . $this->donation->moyen_paiement)
            ->line('Date: ' . $this->donation->date_don->format('d/m/Y H:i'))
            ->line('Merci pour votre soutien à Tunivert !');
    }
}
