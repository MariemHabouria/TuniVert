<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParticipantChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'utilisateur_id',
        'statut',
        'preuve',
    ];

    /**
     * Relation avec le challenge
     */
    public function challenge()
    {
        return $this->belongsTo(\App\Models\Challenge::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(\App\Models\User::class, 'utilisateur_id');
    }

    /**
     * Relation vers ScoreChallenge
     */
    public function score()
    {
        return $this->hasOne(\App\Models\ScoreChallenge::class, 'participant_challenge_id');
    }

    /**
     * Accesseur pour obtenir les points directement
     */
    public function getPointsAttribute()
    {
        return $this->score ? $this->score->points : 0;
    }

    /**
     * Accesseur pour obtenir le badge facilement
     */
    public function getBadgeAttribute()
    {
        return $this->score ? $this->score->badge : null;
    }
}
