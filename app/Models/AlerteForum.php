<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlerteForum extends Model
{
    use HasFactory;

    // Nom exact de la table
    protected $table = 'alertes_forum';

    // Colonnes modifiables
    protected $fillable = [
        'utilisateur_id',
        'titre',
        'description',
        'gravite',
    ];

    /**
     * Relation avec l'utilisateur (auteur de l'alerte)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
