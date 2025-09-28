<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDonationStatistics extends Command
{
    protected $signature = 'donations:update-stats';
    protected $description = 'Update donation statistics for the dashboard';

    public function handle()
    {
        $this->info('Updating donation statistics...');
        
        // Clear existing statistics
        DB::table('statistiques_donations')->truncate();
        
        // Generate fresh statistics from donations table
        $eventStats = DB::table('donations')
            ->select([
                'evenement_id',
                DB::raw('SUM(montant) as montant_total'),
                DB::raw('0 as nb_recurrents'), // No recurrent donations in current schema
                DB::raw('COUNT(*) as nb_ponctuels')
            ])
            ->groupBy('evenement_id')
            ->get();
            
        foreach ($eventStats as $stat) {
            DB::table('statistiques_donations')->insert([
                'evenement_id' => $stat->evenement_id,
                'montant_total' => $stat->montant_total,
                'nb_recurrents' => $stat->nb_recurrents,
                'nb_ponctuels' => $stat->nb_ponctuels,
                'date_generation' => now(),
            ]);
        }
        
        $this->info("Statistics updated for {$eventStats->count()} events/categories");
        
        // Show summary
        $totalAmount = DB::table('statistiques_donations')->sum('montant_total');
        $totalDonations = DB::table('statistiques_donations')->sum('nb_ponctuels');
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Amount', number_format($totalAmount, 2) . ' TND'],
                ['Total Donations', $totalDonations],
                ['Categories/Events', $eventStats->count()]
            ]
        );
        
        return 0;
    }
}