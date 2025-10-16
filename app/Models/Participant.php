<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
    ];

    // Relation vers l'événement
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relation vers l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
