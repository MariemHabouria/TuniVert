<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_formation_user_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('formation_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('inscrit_at')->useCurrent();
            $table->unique(['formation_id','user_id']); // un seul enregistrement par (formation, user)
        });
    }
    public function down(): void {
        Schema::dropIfExists('formation_user');
    }
};
