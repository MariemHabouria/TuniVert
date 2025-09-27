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
    
public function challengesIndex()
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    $challenges = Challenge::withCount('participants')->get();

    return view('admin.challenges.index', compact('challenges'));
}
}
