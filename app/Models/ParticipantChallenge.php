<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantChallenge extends Model
{
    protected $fillable = ['challenge_id', 'utilisateur_id', 'statut', 'preuve'];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}

