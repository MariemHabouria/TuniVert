<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter la colonne 'role' seulement si elle n'existe pas
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('password');
            });
        }

        // Ajouter la colonne 'matricule' seulement si elle n'existe pas
        if (!Schema::hasColumn('users', 'matricule')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('matricule')->nullable()->after('role');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'matricule')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('matricule');
            });
        }

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
