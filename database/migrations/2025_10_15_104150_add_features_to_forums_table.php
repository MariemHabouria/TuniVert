<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forums', function (Blueprint $table) {
            // Ajouter seulement si la colonne n'existe pas
            if (!Schema::hasColumn('forums', 'nb_vues')) {
                $table->integer('nb_vues')->default(0);
            }
            if (!Schema::hasColumn('forums', 'nb_reponses')) {
                $table->integer('nb_reponses')->default(0);
            }
            if (!Schema::hasColumn('forums', 'popularite_score')) {
                $table->integer('popularite_score')->default(0);
            }
            if (!Schema::hasColumn('forums', 'tags')) {
                $table->string('tags')->nullable();
            }
            
            // Ajouter les index seulement s'ils n'existent pas
            if (!Schema::hasIndex('forums', 'forums_popularite_score_index')) {
                $table->index(['popularite_score']);
            }
            if (!Schema::hasIndex('forums', 'forums_created_at_index')) {
                $table->index(['created_at']);
            }
        });

        Schema::table('alertes_forum', function (Blueprint $table) {
            if (!Schema::hasColumn('alertes_forum', 'statut')) {
                $table->enum('statut', ['active', 'resolue', 'fausse'])->default('active');
            }
            if (!Schema::hasColumn('alertes_forum', 'localisation')) {
                $table->string('localisation')->nullable();
            }
            
            if (!Schema::hasIndex('alertes_forum', 'alertes_forum_gravite_created_at_index')) {
                $table->index(['gravite', 'created_at']);
            }
        });
    }

    public function down()
    {
        // Laisser le down() vide ou le modifier prudemment
        // pour éviter de supprimer des colonnes utilisées
    }
};