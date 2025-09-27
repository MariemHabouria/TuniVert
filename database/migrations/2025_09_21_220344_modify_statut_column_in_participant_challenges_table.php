<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('participant_challenges', function (Blueprint $table) {
            $table->enum('statut', ['inscrit','en_cours','complété'])->default('inscrit')->change();

        });
    }

    public function down(): void {
        Schema::table('participant_challenges', function (Blueprint $table) {
            $table->string('statut', 10)->default('inscrit')->change();
        });
    }
};
