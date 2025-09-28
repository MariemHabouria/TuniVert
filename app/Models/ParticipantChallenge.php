<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <- Ajouté

class ParticipantChallenge extends Model
{
    use HasFactory; // <- Ajouté

    protected $fillable = ['challenge_id', 'utilisateur_id', 'statut', 'preuve'];

    public function challenge()
    {
        return $this->belongsTo(\App\Models\Challenge::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(\App\Models\User::class, 'utilisateur_id');
    }

    public function score()
    {
        return $this->hasOne(\App\Models\ScoreChallenge::class, 'participant_challenge_id');
    }
}