<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alertes_forum', function (Blueprint $table) {
            // Champs existants (vérifiez s'ils existent déjà)
            if (!Schema::hasColumn('alertes_forum', 'zone_geographique')) {
                $table->string('zone_geographique')->nullable()->after('description');
            }
            if (!Schema::hasColumn('alertes_forum', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('zone_geographique');
            }
            if (!Schema::hasColumn('alertes_forum', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }
            if (!Schema::hasColumn('alertes_forum', 'statut')) {
                $table->enum('statut', ['en_cours', 'resolue'])->default('en_cours')->after('lng');
            }
            if (!Schema::hasColumn('alertes_forum', 'date_resolution')) {
                $table->timestamp('date_resolution')->nullable()->after('statut');
            }
            if (!Schema::hasColumn('alertes_forum', 'resolue_par')) {
                $table->foreignId('resolue_par')->nullable()->constrained('users')->after('date_resolution');
            }
            if (!Schema::hasColumn('alertes_forum', 'nombre_vues')) {
                $table->integer('nombre_vues')->default(0)->after('resolue_par');
            }
            if (!Schema::hasColumn('alertes_forum', 'nombre_partages')) {
                $table->integer('nombre_partages')->default(0)->after('nombre_vues');
            }
        });
    }

    public function down()
    {
        Schema::table('alertes_forum', function (Blueprint $table) {
            $table->dropColumn([
                'zone_geographique',
                'lat', 
                'lng',
                'statut',
                'date_resolution',
                'resolue_par',
                'nombre_vues',
                'nombre_partages'
            ]);
        });
    }
};