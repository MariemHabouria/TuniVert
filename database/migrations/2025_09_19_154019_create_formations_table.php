<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('categorie')->nullable();

            // type: formation | atelier | conférence | webinaire
            $table->enum('type', ['formation','atelier','conférence','webinaire'])->default('formation');

            $table->unsignedInteger('capacite')->default(0);
            $table->foreignId('organisateur_id')->constrained('users')->cascadeOnDelete();

            $table->string('image')->nullable();      // chemin d’affiche (optionnel)
            $table->string('lien_visio')->nullable(); // lien visioconférence (optionnel)

            // statut: ouverte | suspendue | terminee
            $table->enum('statut', ['ouverte','suspendue','terminee'])->default('ouverte');

            $table->timestamps(); // date_creation & date_mise_a_jour
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
