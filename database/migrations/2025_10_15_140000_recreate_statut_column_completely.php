<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Sauvegarder temporairement les données importantes
        if (Schema::hasColumn('alertes_forum', 'statut')) {
            // Créer une colonne temporaire pour sauvegarder les données
            if (!Schema::hasColumn('alertes_forum', 'statut_backup')) {
                Schema::table('alertes_forum', function (Blueprint $table) {
                    $table->string('statut_backup')->nullable();
                });
            }
            
            // Copier les données
            DB::table('alertes_forum')->update(['statut_backup' => DB::raw('statut')]);
            
            // Supprimer l'ancienne colonne
            Schema::table('alertes_forum', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }

        // Recréer la colonne correctement
        Schema::table('alertes_forum', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'resolue'])->default('en_cours');
        });

        // Restaurer les données valides
        DB::table('alertes_forum')
            ->whereIn('statut_backup', ['en_cours', 'resolue'])
            ->update(['statut' => DB::raw('statut_backup')]);

        // Pour les données invalides, mettre 'en_cours' par défaut
        DB::table('alertes_forum')
            ->whereNotIn('statut_backup', ['en_cours', 'resolue'])
            ->orWhereNull('statut_backup')
            ->update(['statut' => 'en_cours']);

        // Supprimer la colonne de sauvegarde
        if (Schema::hasColumn('alertes_forum', 'statut_backup')) {
            Schema::table('alertes_forum', function (Blueprint $table) {
                $table->dropColumn('statut_backup');
            });
        }
    }

    public function down()
    {
        // En rollback, recréer en VARCHAR
        Schema::table('alertes_forum', function (Blueprint $table) {
            $table->string('statut')->default('en_cours')->change();
        });
    }
};