<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participant_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')                     // Référence au challenge
                  ->constrained('challenges')
                  ->onDelete('cascade');
            $table->foreignId('utilisateur_id')                  // Référence à l'utilisateur
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->enum('statut', ['inscrit', 'terminé'])->default('inscrit'); // Statut
            $table->string('preuve')->nullable();               // Chemin de la preuve (photo/vidéo)
            $table->integer('score')->default(0);              // Score du participant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_challenges');
    }
};
