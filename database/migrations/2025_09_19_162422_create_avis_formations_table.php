<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('avis_formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('note'); // 1-5
            $table->text('commentaire')->nullable();
            $table->timestamps();
            $table->unique(['formation_id','user_id']); // 1 avis par user par formation
        });
    }
    public function down(): void {
        Schema::dropIfExists('avis_formations');
    }
};