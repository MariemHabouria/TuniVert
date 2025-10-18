<?php

namespace App\Notifications;

use App\Models\AlerteForum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NouvelleAlerteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $alerte;

    public function __construct(AlerteForum $alerte)
    {
        $this->alerte = $alerte;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/alertes/' . $this->alerte->id);

        return (new MailMessage)
            ->subject('🚨 Nouvelle alerte : ' . $this->alerte->titre)
            ->line('Une nouvelle alerte a été signalée dans votre zone.')
            ->line('**Titre :** ' . $this->alerte->titre)
            ->line('**Gravité :** ' . $this->alerte->gravite)
            ->line('**Zone :** ' . $this->alerte->zone_geographique)
            ->action('Voir l\'alerte', $url)
            ->line('Merci de votre vigilance !');
    }

    public function toArray($notifiable)
    {
        return [
            'id' => (string) Str::uuid(), // ✅ permet de recréer une notification unique à chaque fois
            'alerte_id' => $this->alerte->id,
            'titre' => $this->alerte->titre,
            'gravite' => $this->alerte->gravite,
            'zone' => $this->alerte->zone_geographique,
            'message' => 'Nouvelle alerte : ' . $this->alerte->titre,
            'url' => '/alertes/' . $this->alerte->id,
            'icon' => '🚨',
        ];
    }
}
