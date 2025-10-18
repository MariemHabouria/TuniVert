<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier et corriger toutes les colonnes nécessaires
        $columnsToCheck = [
            'statut' => function (Blueprint $table) {
                $table->enum('statut', ['en_cours', 'resolue'])->default('en_cours');
            },
            'zone_geographique' => function (Blueprint $table) {
                $table->string('zone_geographique')->nullable();
            },
            'lat' => function (Blueprint $table) {
                $table->decimal('lat', 10, 7)->nullable();
            },
            'lng' => function (Blueprint $table) {
                $table->decimal('lng', 10, 7)->nullable();
            },
            'nombre_vues' => function (Blueprint $table) {
                $table->integer('nombre_vues')->default(0);
            },
            'nombre_partages' => function (Blueprint $table) {
                $table->integer('nombre_partages')->default(0);
            },
        ];

        foreach ($columnsToCheck as $column => $closure) {
            if (!Schema::hasColumn('alertes_forum', $column)) {
                Schema::table('alertes_forum', $closure);
            }
        }
    }

    public function down()
    {
        // Ne pas supprimer les colonnes en rollback
    }
};