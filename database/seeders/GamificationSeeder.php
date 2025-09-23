<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamificationSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['slug'=>'donor_bronze','name'=>'Donateur Bronze','icon'=>'🥉','description'=>'A atteint 50 TND de dons'],
            ['slug'=>'donor_silver','name'=>'Donateur Argent','icon'=>'🥈','description'=>'A atteint 200 TND de dons'],
            ['slug'=>'donor_gold','name'=>'Donateur Or','icon'=>'🥇','description'=>'A atteint 500 TND de dons'],
            ['slug'=>'protector_oceans','name'=>'Protecteur des Océans','icon'=>'🌊','description'=>'A soutenu la cause Écosystème (≥100 TND)'],
        ];
        foreach ($badges as $b) {
            DB::table('badges')->updateOrInsert(['slug'=>$b['slug']], $b + ['updated_at'=>now(), 'created_at'=>now()]);
        }

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $badgeId = DB::table('badges')->where('slug','protector_oceans')->value('id');

        DB::table('challenges')->updateOrInsert(
            ['slug' => 'monthly-ecosystem-target-'. $monthStart->format('Ym')],
            [
                'title' => 'Défi du mois: Objectif Écosystème',
                'period_start' => $monthStart,
                'period_end' => $monthEnd,
                'metric' => 'amount_tnd',
                'target_value' => 1000,
                'scope_type' => 'event',
                'scope_value' => '2',
                'reward_points' => 100,
                'badge_id' => $badgeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
