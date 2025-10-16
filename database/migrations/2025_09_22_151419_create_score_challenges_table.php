<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('score_challenges', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers participant_challenges
            $table->foreignId('participant_challenge_id')
                  ->constrained('participant_challenges')
                  ->onDelete('cascade');

            $table->integer('points')->default(0);
            $table->integer('rang')->nullable();
            $table->string('badge')->nullable();
            $table->timestamp('date_maj')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('score_challenges');
    }
};
