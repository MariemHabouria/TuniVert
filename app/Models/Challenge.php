<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory; // indispensable pour les factories

    protected $fillable = [
        'titre', 'description', 'organisateur_id', 'date_debut', 
        'date_fin', 'categorie', 'difficulte', 'objectif'
    ];

    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }

    public function participants()
    {
        return $this->hasMany(ParticipantChallenge::class);
    }
}
