<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'type',
        'titre',
        'contenu',
        'lien',
        'est_lue'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class);
    }

    public function marquerCommeLue()
    {
        $this->update(['est_lue' => true]);
    }

    public static function creerNotificationAlerte($alerte)
    {
        $graviteLabels = [
            'feu' => '🔥 URGENT',
            'haute' => '⚠️ HAUTE',
            'moyenne' => '📢 Moyenne',
            'basse' => '💬 Basse'
        ];

        return self::create([
            'utilisateur_id' => 1, // Admin ou utilisateurs concernés
            'type' => 'nouvelle_alerte',
            'titre' => $graviteLabels[$alerte->gravite] . " - " . $alerte->titre,
            'contenu' => $alerte->description,
            'lien' => "/alertes/{$alerte->id}"
        ]);
    }
}