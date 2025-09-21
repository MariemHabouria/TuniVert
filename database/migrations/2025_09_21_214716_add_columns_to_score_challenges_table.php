<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('score_challenges', function (Blueprint $table) {
            // Ajouter la colonne participant_challenge_id
            $table->unsignedBigInteger('participant_challenge_id')->after('id');
            $table->foreign('participant_challenge_id')
                  ->references('id')
                  ->on('participant_challenges')
                  ->onDelete('cascade');

            // Ajouter les autres colonnes nécessaires
            $table->integer('points')->default(0);
            $table->integer('rang')->nullable();
            $table->string('badge')->nullable();
            $table->timestamp('date_maj')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('score_challenges', function (Blueprint $table) {
            $table->dropForeign(['participant_challenge_id']);
            $table->dropColumn(['participant_challenge_id', 'points', 'rang', 'badge', 'date_maj']);
        });
    }
};
