<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatistiquesDonationsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing statistics
        DB::table('statistiques_donations')->truncate();
        
        // Generate statistics from existing donations
        $eventStats = DB::table('donations')
            ->select([
                'evenement_id',
                DB::raw('SUM(montant) as montant_total'),
                DB::raw('0 as nb_recurrents'), // No recurrent type in current schema
                DB::raw('COUNT(*) as nb_ponctuels') // All donations are considered one-time
            ])
            ->groupBy('evenement_id')
            ->get();
            
        foreach ($eventStats as $stat) {
            DB::table('statistiques_donations')->insert([
                'evenement_id' => $stat->evenement_id,
                'montant_total' => $stat->montant_total,
                'nb_recurrents' => $stat->nb_recurrents ?? 0,
                'nb_ponctuels' => $stat->nb_ponctuels ?? 0,
                'date_generation' => now(),
            ]);
        }
        
        $this->command->info('Statistics generated for ' . $eventStats->count() . ' events/categories');
    }
}