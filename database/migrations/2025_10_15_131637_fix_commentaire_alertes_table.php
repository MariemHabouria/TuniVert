<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la table existe
        if (Schema::hasTable('commentaire_alertes')) {
            // Vérifier si la colonne alerte_id existe
            if (!Schema::hasColumn('commentaire_alertes', 'alerte_id')) {
                Schema::table('commentaire_alertes', function (Blueprint $table) {
                    $table->unsignedBigInteger('alerte_id')->after('id');
                    
                    // Ajouter la clé étrangère si la table alertes_forum existe
                    if (Schema::hasTable('alertes_forum')) {
                        $table->foreign('alerte_id')
                              ->references('id')
                              ->on('alertes_forum')
                              ->onDelete('cascade');
                    }
                });
            }
        } else {
            // Créer la table si elle n'existe pas
            Schema::create('commentaire_alertes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('alerte_id');
                $table->text('contenu');
                $table->foreignId('user_id')->constrained();
                $table->timestamps();

                if (Schema::hasTable('alertes_forum')) {
                    $table->foreign('alerte_id')
                          ->references('id')
                          ->on('alertes_forum')
                          ->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        // Ne pas supprimer la table en rollback pour éviter de perdre des données
        // Schema::dropIfExists('commentaire_alertes');
    }
};