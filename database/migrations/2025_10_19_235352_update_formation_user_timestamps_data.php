<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records where created_at is null
        DB::table('formation_user')
            ->whereNull('created_at')
            ->update([
                'created_at' => DB::raw('inscrit_at'),
                'updated_at' => DB::raw('inscrit_at')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here as we're just updating data
    }
};
