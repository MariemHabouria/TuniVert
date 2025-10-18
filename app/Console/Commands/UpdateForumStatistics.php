<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ForumStatistique;

class UpdateForumStatistics extends Command
{
    protected $signature = 'forum:update-stats';
    protected $description = 'Mettre à jour les statistiques des forums';

    public function handle()
    {
        $this->info('📊 Mise à jour des statistiques forums...');
        
        $stats = ForumStatistique::statsDuJour();
        $stats->mettreAJourStats();
        
        $this->info("✅ Statistiques mises à jour pour " . now()->format('Y-m-d'));
        $this->info("📝 Forums aujourd'hui: " . $stats->nb_forums_24h);
        $this->info("🚨 Alertes aujourd'hui: " . $stats->nb_alertes_24h);
        $this->info("👥 Utilisateurs actifs: " . $stats->nb_utilisateurs_actifs);
        
        return Command::SUCCESS;
    }
}