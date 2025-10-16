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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('titre');                               // Titre du challenge
            $table->text('description');                           // Description complète
            $table->string('slug')->nullable()->unique();          // Slug pour URL, optionnel
            $table->string('categorie')->nullable();               // Catégorie optionnelle
            $table->enum('difficulte', ['facile', 'moyen', 'difficile'])->nullable(); // Difficulté
            $table->integer('objectif')->nullable();               // Objectif optionnel
            $table->foreignId('organisateur_id')                  // Organisateur
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->dateTime('date_debut');                        // Date de début
            $table->dateTime('date_fin');                          // Date de fin
            $table->boolean('actif')->default(true);               // Actif ou non
            $table->timestamps();

            $table->index('organisateur_id');                     // Index pour organiser les requêtes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
