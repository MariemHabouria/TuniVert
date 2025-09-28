<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('statistiques_donations')) {
            return; // already exists
        }
        Schema::create('statistiques_donations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evenement_id')->nullable();
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->unsignedInteger('nb_recurrents')->default(0);
            $table->unsignedInteger('nb_ponctuels')->default(0);
            $table->timestamp('date_generation')->useCurrent();

            $table->index('evenement_id');
            $table->index('date_generation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistiques_donations');
    }
};
