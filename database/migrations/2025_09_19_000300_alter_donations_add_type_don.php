<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('donations')) {
            return; // nothing to do
        }
        if (!Schema::hasColumn('donations', 'type_don')) {
            Schema::table('donations', function (Blueprint $table) {
                // Add after montant for readability when supported
                $table->enum('type_don', ['ponctuel','recurrent'])->default('ponctuel')->after('montant');
                $table->index('type_don');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('donations') && Schema::hasColumn('donations', 'type_don')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropIndex(['type_don']);
                $table->dropColumn('type_don');
            });
        }
    }
};
