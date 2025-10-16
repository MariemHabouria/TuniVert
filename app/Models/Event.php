<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Champs qu’on autorise au remplissage en masse
    protected $fillable = [
        'title',
        'location',
        'date',
        'category',
        'details',
        'image',
        'organizer_id'
    ];

        protected $casts = [
        'date' => 'date',
    ];

    // Relation : un événement appartient à un utilisateur (organisateur)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function participants()
{
    return $this->hasMany(Participant::class);
}
public function comments()
{
    return $this->hasMany(Comment::class);
}
}
