<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'is_association')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_association')->default(false)->after('role');
            });
        }

        if (!Schema::hasColumn('users', 'matricule_association')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('matricule_association')->nullable()->after('is_association');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'matricule_association')) {
                $table->dropColumn('matricule_association');
            }
            if (Schema::hasColumn('users', 'is_association')) {
                $table->dropColumn('is_association');
            }
        });
    }
};
