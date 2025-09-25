<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role','matricule'];

    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // <-- auto-hash du mot de passe
        ];
    }

    // ---------------------------
    // Méthodes pour vérifier le rôle
    // ---------------------------
    public function isAssociation(): bool
    {
        return $this->role === 'association';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function formationsOrganisees()
    {
        return $this->hasMany(\App\Models\Formation::class, 'organisateur_id');
    }

    public function formationsInscrites() 
    {
        return $this->belongsToMany(\App\Models\Formation::class, 'formation_user')
                    ->withPivot('inscrit_at');
    }

    // ---------------------------
    // Challenges
    // ---------------------------

    // Challenges créés par l'association
    public function challengesOrganises()
    {
        return $this->hasMany(\App\Models\Challenge::class, 'organisateur_id');
    }

    // Participation aux challenges
    public function participations()
    {
        return $this->hasMany(\App\Models\ParticipantChallenge::class, 'utilisateur_id');
    }

    // Scores cumulés pour badges / rang
    public function scoresChallenges()
    {
        return $this->hasManyThrough(
            \App\Models\ScoreChallenge::class, 
            \App\Models\ParticipantChallenge::class, 
            'utilisateur_id',              // clé étrangère dans participant_challenges
            'participant_challenge_id',    // clé étrangère dans score_challenges
            'id',                          // clé locale dans users
            'id'                           // clé locale dans participant_challenges
        );
    }
    public function isAdmin(): bool
{
    return $this->role === 'admin';
}
}
