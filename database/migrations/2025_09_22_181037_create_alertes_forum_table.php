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
        Schema::create('alertes_forum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade'); 
            $table->string('titre'); // Titre de l'alerte
            $table->text('description'); // Détails de l'alerte
            $table->enum('gravite', ['basse', 'moyenne', 'haute', 'feu'])->default('basse'); // Niveau d'urgence
            $table->timestamps(); // date_creation et date_mise_a_jour
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes_forum');
    }
};
