<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre','description','categorie','type','capacite',
        'organisateur_id','image','lien_visio','statut',
    ];

    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }

    public function ressources()
    {
        return $this->hasMany(RessourceFormation::class);
    }

    // app/Models/Formation.php
public function inscrits() {
    return $this->belongsToMany(User::class, 'formation_user')
                ->withPivot('inscrit_at')
                ->withTimestamps();
}
public function placesRestantes(): int {
    $cap = $this->capacite ?? 0;
    return max(0, $cap - $this->inscrits()->count());
}
public function estComplete(): bool {
    return $this->capacite !== null && $this->inscrits()->count() >= $this->capacite;
}

public function avis() { return $this->hasMany(\App\Models\AvisFormation::class); }
public function noteMoyenne(): ?float {
    $avg = $this->avis()->avg('note');
    return $avg ? round($avg, 1) : null;
}
public function quizAttempts() {
    return $this->hasMany(\App\Models\QuizAttempt::class);
}

}
