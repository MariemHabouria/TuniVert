<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer la colonne existante si elle a un problème
        if (Schema::hasColumn('alertes_forum', 'statut')) {
            Schema::table('alertes_forum', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }

        // Recréer la colonne correctement
        Schema::table('alertes_forum', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'resolue'])->default('en_cours');
        });
    }

    public function down()
    {
        Schema::table('alertes_forum', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};