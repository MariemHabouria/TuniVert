<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'matricule'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAssociation(): bool { return $this->role === 'association'; }
    public function isUser(): bool { return $this->role === 'user'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }

    public function formationsOrganisees() { return $this->hasMany(\App\Models\Formation::class, 'organisateur_id'); }
    public function formationsInscrites() { return $this->belongsToMany(\App\Models\Formation::class, 'formation_user')->withPivot('inscrit_at'); }
    public function challengesOrganises() { return $this->hasMany(\App\Models\Challenge::class, 'organisateur_id'); }
    public function participations() { return $this->hasMany(\App\Models\ParticipantChallenge::class, 'utilisateur_id'); }
    public function scoresChallenges() { return $this->hasManyThrough(\App\Models\ScoreChallenge::class, \App\Models\ParticipantChallenge::class, 'utilisateur_id', 'participant_challenge_id', 'id', 'id'); }
    public function donations() { return $this->hasMany(Donation::class, 'utilisateur_id'); }
}
