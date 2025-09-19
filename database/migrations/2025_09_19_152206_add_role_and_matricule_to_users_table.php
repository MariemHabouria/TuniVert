<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // "user" par défaut (utilisateur classique)
            $table->string('role', 30)->default('user')->after('password');
            // matricule RNE unique, nullable (pour les comptes non-associations)
            $table->string('matricule', 10)->nullable()->unique()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'matricule']);
        });
    }
};
