<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ÉTAPE 1: Voir ce qu'il y a dans la colonne
        $results = DB::select("SELECT DISTINCT statut, COUNT(*) as count FROM alertes_forum GROUP BY statut");
        
        foreach ($results as $row) {
            echo "Valeur: '{$row->statut}' - Count: {$row->count}\n";
        }

        // ÉTAPE 2: Nettoyer les données
        DB::statement("UPDATE alertes_forum SET statut = 'en_cours' WHERE statut IS NULL OR statut NOT IN ('en_cours', 'resolue')");

        // ÉTAPE 3: Modifier la colonne
        DB::statement("ALTER TABLE alertes_forum MODIFY COLUMN statut ENUM('en_cours', 'resolue') NOT NULL DEFAULT 'en_cours'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE alertes_forum MODIFY COLUMN statut VARCHAR(20) DEFAULT 'en_cours'");
    }
};