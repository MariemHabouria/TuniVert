<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('donations') && !Schema::hasColumn('donations', 'is_anonymous')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->boolean('is_anonymous')->default(false)->after('utilisateur_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('donations') && Schema::hasColumn('donations', 'is_anonymous')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropColumn('is_anonymous');
            });
        }
    }
};
