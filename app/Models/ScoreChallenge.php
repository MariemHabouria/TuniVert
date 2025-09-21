<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreChallenge extends Model
{
    protected $fillable = [
        'participant_challenge_id', 
        'points', 
        'rang', 
        'badge', 
        'date_maj'
    ];

    public function participant()
    {
        return $this->belongsTo(\App\Models\ParticipantChallenge::class, 'participant_challenge_id');
    }
}
