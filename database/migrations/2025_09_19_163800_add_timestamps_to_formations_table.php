<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_timestamps_to_formations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('formations', function (Blueprint $table) {
            if (!Schema::hasColumn('formations', 'created_at')) {
                $table->timestamps(); // ajoute created_at et updated_at
            }
        });
    }
    public function down(): void {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn(['created_at','updated_at']);
        });
    }
};
