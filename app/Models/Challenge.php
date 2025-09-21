<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $fillable = [
        'titre', 
        'description', 
        'organisateur_id', 
        'date_debut', 
        'date_fin', 
        'categorie', 
        'difficulte', 
        'objectif'
    ];

    public function organisateur()
    {
        return $this->belongsTo(\App\Models\User::class, 'organisateur_id'); 
    }

    public function participants()
    {
        return $this->hasMany(\App\Models\ParticipantChallenge::class);
    }
}
