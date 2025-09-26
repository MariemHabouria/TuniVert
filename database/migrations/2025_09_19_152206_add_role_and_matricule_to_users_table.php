<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ne crée que si ça n'existe pas déjà
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 30)->default('user')->after('password');
            }

            if (!Schema::hasColumn('users', 'matricule')) {
                $table->string('matricule', 8)->nullable()->unique()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'matricule')) {
                try { $table->dropUnique('users_matricule_unique'); } catch (\Throwable $e) {}
                $table->dropColumn('matricule');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
