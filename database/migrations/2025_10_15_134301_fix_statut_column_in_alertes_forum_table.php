<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier le type de colonne actuel
        $columnInfo = DB::select("SHOW COLUMNS FROM alertes_forum WHERE Field = 'statut'");
        
        if (count($columnInfo) > 0) {
            $columnType = $columnInfo[0]->Type;
            
            // Si la colonne n'est pas un ENUM ou a un problème, la modifier
            if (strpos(strtolower($columnType), 'enum') === false) {
                Schema::table('alertes_forum', function (Blueprint $table) {
                    $table->enum('statut', ['en_cours', 'resolue'])->default('en_cours')->change();
                });
            }
        } else {
            // Si la colonne n'existe pas, la créer
            Schema::table('alertes_forum', function (Blueprint $table) {
                $table->enum('statut', ['en_cours', 'resolue'])->default('en_cours');
            });
        }
    }

    public function down()
    {
        // En cas de rollback, on ne supprime pas la colonne pour éviter de perdre des données
        // Schema::table('alertes_forum', function (Blueprint $table) {
        //     $table->dropColumn('statut');
        // });
    }
};