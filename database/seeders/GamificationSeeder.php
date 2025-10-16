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
            DB::table('badges')->updateOrInsert(
                ['slug'=>$b['slug']], 
                $b + ['updated_at'=>now(), 'created_at'=>now()]
            );
        }
    }
}
