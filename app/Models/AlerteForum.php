<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlerteForum extends Model
{
    use HasFactory;

    // 🔥 CORRECTION : Spécifiez le nom exact de la table
    protected $table = 'alertes_forum';

    protected $fillable = [
        'titre',
        'description', 
        'gravite',
        'user_id',
        'utilisateur_id', // Selon votre structure actuelle
        'zone_geographique',
        'lat',
        'lng',
        'statut',
        'date_resolution',
        'resolue_par',
        'nombre_vues',
        'nombre_partages'
    ];

    protected $casts = [
        'date_resolution' => 'datetime',
    ];

    // Relation avec l'utilisateur créateur
    public function user()
    {
        // Utilisez 'user_id' ou 'utilisateur_id' selon votre table
        return $this->belongsTo(User::class, 'user_id'); 
    }

    // Relation avec l'utilisateur qui a résolu l'alerte
    public function resolveur()
    {
        return $this->belongsTo(User::class, 'resolue_par');
    }

    // Relation avec les commentaires
    public function commentaires()
    {
        return $this->hasMany(CommentaireAlerte::class, 'alerte_id');
    }

    // Scopes utiles
    public function scopeEnCours($query)
    {
        return $query->where('statut', '!=', 'resolue')->orWhereNull('statut');
    }

    public function scopeResolues($query)
    {
        return $query->where('statut', 'resolue');
    }

    public function scopeUrgentes($query)
    {
        return $query->where('gravite', 'haute')->orWhere('gravite', 'feu');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Méthode pour marquer comme résolue
    public function marquerResolue($userId)
    {
        $this->update([
            'statut' => 'resolue',
            'date_resolution' => now(),
            'resolue_par' => $userId
        ]);
    }

    // Méthode pour incrémenter les vues
    public function incrementerVues()
    {
        $this->increment('nombre_vues');
    }

    // Méthode pour incrémenter les partages
    public function incrementerPartages()
    {
        $this->increment('nombre_partages');
    }

    // Accessor pour le statut formaté
    public function getStatutFormateAttribute()
    {
        return $this->statut === 'resolue' ? '✅ Résolue' : '🟡 En cours';
    }

    // Accessor pour la gravité avec emoji
    public function getGraviteAvecEmojiAttribute()
    {
        $emojis = [
            'basse' => '🟢',
            'moyenne' => '🟡', 
            'haute' => '🔴',
            'feu' => '🔥'
        ];
        
        return ($emojis[$this->gravite] ?? '⚪') . ' ' . ucfirst($this->gravite);
    }
}