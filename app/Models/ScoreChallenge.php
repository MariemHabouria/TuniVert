<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScoreChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_challenge_id', 
        'points', 
        'rang', 
        'badge', 
        'date_maj'
    ];

    // Relation avec la participation
    public function participant()
    {
        return $this->belongsTo(ParticipantChallenge::class, 'participant_challenge_id');
    }

    // Accesseur pour obtenir l'utilisateur directement
    public function utilisateur()
    {
        return $this->participant?->utilisateur();
    }

    // Déterminer automatiquement le badge selon les points
    public static function determinerBadge(int $points): ?string
    {
        if ($points >= 200) return 'Or';
        if ($points >= 100) return 'Argent';
        if ($points >= 50)  return 'Bronze';
        return null;
    }
}
