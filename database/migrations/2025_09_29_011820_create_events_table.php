<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('location');
                $table->date('date');
                $table->string('category')->nullable();
                $table->unsignedBigInteger('organizer_id')->nullable();
                $table->text('details')->nullable();
                $table->timestamps();

                $table->foreign('organizer_id')
                      ->references('id')->on('users')
                      ->onDelete('cascade');
            });
        } else {
            // Ensure columns exist if an earlier stub created the table
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'title')) $table->string('title')->nullable();
                if (!Schema::hasColumn('events', 'location')) $table->string('location')->nullable();
                if (!Schema::hasColumn('events', 'date')) $table->date('date')->nullable();
                if (!Schema::hasColumn('events', 'category')) $table->string('category')->nullable();
                if (!Schema::hasColumn('events', 'organizer_id')) $table->unsignedBigInteger('organizer_id')->nullable();
                if (!Schema::hasColumn('events', 'details')) $table->text('details')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
