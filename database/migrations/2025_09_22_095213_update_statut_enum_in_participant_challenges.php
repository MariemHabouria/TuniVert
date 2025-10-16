<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participant_challenges', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'complet', 'annule'])->default('en_cours')->change();
        });

        // Optionnel : mettre à jour les anciennes données
        \DB::table('participant_challenges')
            ->where('statut', 'complété')
            ->update(['statut' => 'complet']);
    }

    public function down(): void
    {
        Schema::table('participant_challenges', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'complété', 'annule'])->default('en_cours')->change();
        });
    }
};
