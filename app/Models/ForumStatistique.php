<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumStatistique extends Model
{
    use HasFactory;

    protected $table = 'forum_statistiques';

    protected $fillable = [
        'date_jour',
        'nb_forums_total',
        'nb_forums_24h',
        'nb_forums_7j',
        'nb_forums_30j',
        'forums_populaires',
        'nb_alertes_total',
        'nb_alertes_24h',
        'nb_alertes_feu',
        'nb_alertes_haute',
        'nb_alertes_moyenne',
        'nb_alertes_basse',
        'nb_utilisateurs_actifs',
        'nb_utilisateurs_nouveaux',
        'total_vues',
        'total_reponses',
        'taux_engagement'
    ];

    protected $casts = [
        'date_jour' => 'date',
        'taux_engagement' => 'decimal:2'
    ];

    /**
     * Récupérer ou créer les stats du jour
     */
    public static function statsDuJour()
    {
        $today = now()->format('Y-m-d');
        
        return self::firstOrCreate(
            ['date_jour' => $today],
            [
                'nb_forums_total' => Forum::count(),
                'nb_alertes_total' => AlerteForum::count(),
                'total_vues' => Forum::sum('nb_vues'),
                'total_reponses' => Forum::sum('nb_reponses'),
            ]
        );
    }

    /**
     * Mettre à jour les stats en temps réel
     */
    public function mettreAJourStats()
    {
        $this->update([
            'nb_forums_total' => Forum::count(),
            'nb_forums_24h' => Forum::where('created_at', '>=', now()->subDay())->count(),
            'nb_forums_7j' => Forum::where('created_at', '>=', now()->subDays(7))->count(),
            'nb_forums_30j' => Forum::where('created_at', '>=', now()->subDays(30))->count(),
            'forums_populaires' => Forum::where('popularite_score', '>', 100)->count(),
            
            'nb_alertes_total' => AlerteForum::count(),
            'nb_alertes_24h' => AlerteForum::where('created_at', '>=', now()->subDay())->count(),
            'nb_alertes_feu' => AlerteForum::where('gravite', 'feu')->count(),
            'nb_alertes_haute' => AlerteForum::where('gravite', 'haute')->count(),
            'nb_alertes_moyenne' => AlerteForum::where('gravite', 'moyenne')->count(),
            'nb_alertes_basse' => AlerteForum::where('gravite', 'basse')->count(),
            
            'nb_utilisateurs_actifs' => \App\Models\User::whereHas('forums')->orWhereHas('alertes')->count(),
            'nb_utilisateurs_nouveaux' => \App\Models\User::where('created_at', '>=', now()->subDay())->count(),
            
            'total_vues' => Forum::sum('nb_vues'),
            'total_reponses' => Forum::sum('nb_reponses'),
            'taux_engagement' => $this->calculerTauxEngagement()
        ]);
    }

    private function calculerTauxEngagement()
    {
        $totalVues = Forum::sum('nb_vues');
        $totalReponses = Forum::sum('nb_reponses');
        
        if ($totalVues > 0) {
            return ($totalReponses / $totalVues) * 100;
        }
        
        return 0;
    }

    /**
     * Récupérer les stats sur une période
     */
    public static function statsPeriode($jours = 30)
    {
        return self::where('date_jour', '>=', now()->subDays($jours))
                  ->orderBy('date_jour')
                  ->get();
    }
}