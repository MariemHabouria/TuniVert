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
        'categorie',        // Nouveau champ
        'difficulte',       // Nouveau champ
        'objectif'         // Nouveau champ
    ];

    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id'); // Vérifie que le modèle utilisateur est User
    }

    public function participants()
    {
        return $this->hasMany(ParticipantChallenge::class);
    }
}
