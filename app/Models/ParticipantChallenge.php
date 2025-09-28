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

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function score()
    {
        return $this->hasOne(ScoreChallenge::class, 'participant_challenge_id');
    }

    public function getPointsAttribute()
    {
        return $this->score ? $this->score->points : 0;
    }

    public function getBadgeAttribute()
    {
        return $this->score ? $this->score->badge : null;
    }
}
