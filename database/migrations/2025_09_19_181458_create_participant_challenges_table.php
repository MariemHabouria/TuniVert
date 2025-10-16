<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participant_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')
                  ->constrained('challenges')
                  ->onDelete('cascade');
            $table->foreignId('utilisateur_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->enum('statut', ['en_cours', 'valide', 'rejete'])->default('en_cours');
            $table->string('preuve')->nullable(); // Chemin de la preuve (photo, PDF, etc.)
            $table->timestamps();

            $table->unique(['challenge_id', 'utilisateur_id']); // Un utilisateur ne peut participer qu'une fois
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_challenges');
    }
};
