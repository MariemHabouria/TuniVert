<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participant_challenges', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'en_attente', 'valide', 'rejete', 'complet', 'annule'])->default('en_cours')->change();
        });
    }

    public function down(): void
    {
        Schema::table('participant_challenges', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'complet', 'annule'])->default('en_cours')->change();
        });
    }
};