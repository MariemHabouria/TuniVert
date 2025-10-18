<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'utilisateur_id',
        'nb_vues',
        'nb_reponses',
        'popularite_score',
        'tags'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // === AJOUTEZ CETTE RELATION POUR LES RÉPONSES ===
    public function reponses()
    {
        return $this->hasMany(ReponseForum::class, 'forum_id');
    }

    // === SCOPES POUR LE TRI INTELLIGENT ===
    
    public function scopePopulaire($query)
    {
        return $query->orderBy('popularite_score', 'desc')
                    ->orderBy('nb_vues', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeActif($query)
    {
        return $query->orderBy('updated_at', 'desc')
                    ->orderBy('nb_reponses', 'desc');
    }

    public function scopeAvecRecherche($query, $motCle)
    {
        return $query->where(function($q) use ($motCle) {
            $q->where('titre', 'LIKE', "%{$motCle}%")
              ->orWhere('contenu', 'LIKE', "%{$motCle}%")
              ->orWhere('tags', 'LIKE', "%{$motCle}%");
        });
    }

    // === MÉTHODES POUR GESTION POPULARITÉ ===
    
    public function incrementerVues()
    {
        $this->increment('nb_vues');
        $this->calculerPopularite();
    }

    public function incrementerReponses()
    {
        $this->increment('nb_reponses');
        $this->calculerPopularite();
    }

    public function calculerPopularite()
    {
        $popularite = $this->nb_vues + ($this->nb_reponses * 5);
        $this->update(['popularite_score' => $popularite]);
    }
}