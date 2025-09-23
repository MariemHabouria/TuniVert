<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('badges')) {
            Schema::create('badges', function (Blueprint $t) {
                $t->id();
                $t->string('slug')->unique();
                $t->string('name');
                $t->string('icon')->nullable();
                $t->text('description')->nullable();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('user_badges')) {
            Schema::create('user_badges', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
                $t->timestamp('awarded_at')->useCurrent();
                $t->unique(['user_id','badge_id']);
            });
        }

        if (!Schema::hasTable('points_ledger')) {
            Schema::create('points_ledger', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->integer('points');
                $t->string('reason', 64);
                $t->foreignId('donation_id')->nullable()->constrained('donations')->nullOnDelete();
                $t->json('metadata')->nullable();
                $t->timestamps();
                $t->index(['user_id','created_at']);
            });
        }

        if (!Schema::hasTable('challenges')) {
            Schema::create('challenges', function (Blueprint $t) {
                $t->id();
                $t->string('slug')->unique();
                $t->string('title');
                $t->dateTime('period_start');
                $t->dateTime('period_end');
                $t->enum('metric', ['amount_tnd','donations_count','trees_planted'])->default('amount_tnd');
                $t->unsignedInteger('target_value');
                $t->enum('scope_type', ['global','event','campaign'])->default('global');
                $t->string('scope_value')->nullable();
                $t->unsignedInteger('reward_points')->default(0);
                $t->foreignId('badge_id')->nullable()->constrained('badges')->nullOnDelete();
                $t->timestamps();
            });
        }

        if (!Schema::hasTable('challenge_participations')) {
            Schema::create('challenge_participations', function (Blueprint $t) {
                $t->id();
                $t->foreignId('challenge_id')->constrained('challenges')->cascadeOnDelete();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->unsignedInteger('progress_value')->default(0);
                $t->timestamp('completed_at')->nullable();
                $t->timestamps();
                $t->unique(['challenge_id','user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_participations');
        Schema::dropIfExists('challenges');
        Schema::dropIfExists('points_ledger');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};
