<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReponseForum extends Model
{
    use HasFactory;

    protected $table = 'reponses_forum';

    protected $fillable = [
        'forum_id',
        'utilisateur_id',
        'contenu',
        'score',
        'est_resolution'
    ];

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Marquer comme solution
     */
    public function marquerCommeSolution()
    {
        // Démarrer une transaction
        \DB::transaction(function () {
            // Retirer le statut de solution des autres réponses
            self::where('forum_id', $this->forum_id)
                ->where('id', '!=', $this->id)
                ->update(['est_resolution' => false]);
            
            // Marquer cette réponse comme solution
            $this->update(['est_resolution' => true]);
        });
    }

    /**
     * Incrémenter le score (votes)
     */
    public function incrementerScore()
    {
        $this->increment('score');
    }
}