<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('ressources_formations', function (Blueprint $table) {
            $table->string('path')->nullable()->after('url'); // chemin local (storage/app/public/...)
        });
    }
    public function down(): void {
        Schema::table('ressources_formations', function (Blueprint $table) {
            $table->dropColumn('path');
        });
    }
};