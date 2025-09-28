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

    /**
     * Relation inverse vers ParticipantChallenge
     */
    public function participant()
    {
        return $this->belongsTo(\App\Models\ParticipantChallenge::class, 'participant_challenge_id');
    }
}
