<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('donations') && !Schema::hasColumn('donations', 'transaction_id')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->string('transaction_id')->nullable()->after('moyen_paiement');
                $table->index('transaction_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('donations') && Schema::hasColumn('donations', 'transaction_id')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropIndex(['transaction_id']);
                $table->dropColumn('transaction_id');
            });
        }
    }
};
