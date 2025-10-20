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
        'actif',
        'slug',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];

    // Relation avec l'organisateur
    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }

    // Relation avec les participations
    public function participants()
    {
        return $this->hasMany(ParticipantChallenge::class);
    }

    // Vérifie si le challenge est actif
    public function isActif()
    {
        return $this->actif;
    }

    // Retourne le nombre total de participants validés
    public function participantsValides()
    {
        return $this->participants()->where('statut', 'valide');
    }
}