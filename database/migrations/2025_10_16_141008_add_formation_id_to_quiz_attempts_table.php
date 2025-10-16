<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_attempts', 'formation_id')) {
                $table->foreignId('formation_id')->nullable()->constrained('formations')->cascadeOnDelete();
            }
        });
    }
    public function down(): void {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_attempts', 'formation_id')) {
                $table->dropConstrainedForeignId('formation_id');
            }
        });
    }
};
