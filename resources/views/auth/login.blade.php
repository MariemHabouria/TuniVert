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
    public function formationsOrganisees()
{
    return $this->hasMany(\App\Models\Formation::class, 'organisateur_id');
}

public function formationsInscrites() {
    return $this->belongsToMany(\App\Models\Formation::class, 'formation_user')
                ->withPivot('inscrit_at');
                
}

}