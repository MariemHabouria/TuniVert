<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mettre à jour les alertes existantes avec une zone par défaut
        DB::table('alertes_forum')
            ->whereNull('zone_geographique')
            ->update([
                'zone_geographique' => DB::raw('COALESCE(localisation, "Zone non spécifiée")')
            ]);
    }

    public function down()
    {
        // Cette migration ne peut pas être annulée sans perte de données
    }
};