<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumVue extends Model
{
    use HasFactory;

    protected $table = 'forum_vues';

    protected $fillable = [
        'forum_id',
        'utilisateur_id',
        'ip_address',
        'user_agent'
    ];

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Enregistrer une vue
     */
    public static function enregistrerVue($forumId, $utilisateurId = null)
    {
        $ip = request()->ip();
        
        // Vérifier si la vue existe déjà (éviter les doublons)
        $vueExistante = self::where('forum_id', $forumId)
                           ->where(function($query) use ($utilisateurId, $ip) {
                               if ($utilisateurId) {
                                   $query->where('utilisateur_id', $utilisateurId);
                               } else {
                                   $query->where('ip_address', $ip);
                               }
                           })
                           ->first();

        if (!$vueExistante) {
            return self::create([
                'forum_id' => $forumId,
                'utilisateur_id' => $utilisateurId,
                'ip_address' => $ip,
                'user_agent' => request()->userAgent()
            ]);
        }

        return $vueExistante;
    }
}