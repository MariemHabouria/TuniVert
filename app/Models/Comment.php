<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Correct


class Comment extends Model
{

     use HasFactory;

    protected $fillable = [
        'content',
        'user_id',   // <-- Ajoute cette ligne
        'event_id',  // si nécessaire
          'sentiment', // <-- Ajouté pour que Laravel puisse enregistrer le sentiment

    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

public function event()
{
    return $this->belongsTo(Event::class);
}

}
