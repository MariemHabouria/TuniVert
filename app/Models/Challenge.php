<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 
        'description', 
        'organisateur_id', 
        'date_debut', 
        'date_fin', 
        'categorie', 
        'difficulte', 
        'objectif',
        'actif', // nouveau champ pour bloquer/débloquer
    ];

    /**
     * Relation avec l'organisateur (utilisateur)
     */
    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }

    /**
     * Relation avec les participants
     */
    public function participants()
    {
        return $this->hasMany(ParticipantChallenge::class);
    }

    /**
     * Vérifie si le challenge est actif
     */
    public function isActif()
    {
        return $this->actif;
    }
}
