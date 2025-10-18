<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commentaire_alertes', function (Blueprint $table) {
            $table->id();
            $table->text('contenu');
            $table->unsignedBigInteger('alerte_id'); // Changez selon votre table
            $table->foreignId('user_id')->constrained();
            $table->timestamps();

            // Ajoutez cette ligne SEULEMENT si la table alerte_forums existe
            if (Schema::hasTable('alerte_forums')) {
                $table->foreign('alerte_id')
                      ->references('id')
                      ->on('alerte_forums')
                      ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentaire_alertes');
    }
};