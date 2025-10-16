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

    // Relation avec le challenge
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    // Relation avec l'utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // Relation avec le score de ce challenge
    public function score()
    {
        return $this->hasOne(ScoreChallenge::class, 'participant_challenge_id');
    }

    // Accesseur points
    public function getPointsAttribute()
    {
        return $this->score?->points ?? 0;
    }

    // Accesseur badge
    public function getBadgeAttribute()
    {
        return $this->score?->badge ?? null;
    }

    // Score total pour cet utilisateur toutes participations confondues
    public function totalScoreUser()
    {
        return ScoreChallenge::whereHas('participant', function($q){
            $q->where('utilisateur_id', $this->utilisateur_id)
              ->where('statut', 'valide');
        })->sum('points');
    }
}
