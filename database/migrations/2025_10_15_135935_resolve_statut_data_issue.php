<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Étape 1: Vérifier quelles valeurs existent actuellement dans statut
        $existingValues = DB::table('alertes_forum')
            ->select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();
        
        echo "Valeurs actuelles dans statut:\n";
        foreach ($existingValues as $value) {
            echo "- '{$value->statut}': {$value->count} enregistrements\n";
        }

        // Étape 2: Mettre à jour les valeurs invalides vers 'en_cours'
        DB::table('alertes_forum')
            ->whereNotIn('statut', ['en_cours', 'resolue'])
            ->update(['statut' => 'en_cours']);

        // Étape 3: Maintenant modifier la colonne en ENUM
        DB::statement("ALTER TABLE alertes_forum MODIFY COLUMN statut ENUM('en_cours', 'resolue') DEFAULT 'en_cours' NOT NULL");

        // Étape 4: Vérifier que toutes les valeurs sont valides
        $invalidCount = DB::table('alertes_forum')
            ->whereNotIn('statut', ['en_cours', 'resolue'])
            ->count();
        
        if ($invalidCount > 0) {
            throw new Exception("Il reste {$invalidCount} enregistrements avec des valeurs invalides dans statut");
        }

        echo "Colonne statut corrigée avec succès!\n";
    }

    public function down()
    {
        // En rollback, on ne peut pas revenir en arrière facilement
        // On change le type en VARCHAR pour être plus permissif
        DB::statement("ALTER TABLE alertes_forum MODIFY COLUMN statut VARCHAR(20) DEFAULT 'en_cours'");
    }
};