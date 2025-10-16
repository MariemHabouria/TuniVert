<?php

// database/migrations/2025_01_01_000000_create_formation_chat_messages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('formation_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('role', ['user','assistant']);
            $table->text('message');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('formation_chat_messages');
    }
};
