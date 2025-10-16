<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    /**
     * Champs autorisés à l’insertion/mise à jour
     */
    protected $fillable = [
        'titre',
        'contenu',
        'utilisateur_id',
    ];

    /**
     * Relation avec l'utilisateur (auteur du forum)
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
