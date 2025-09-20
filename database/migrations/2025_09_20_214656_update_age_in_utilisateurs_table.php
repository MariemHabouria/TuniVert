<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajout du champ role (par défaut "user")
            $table->string('role')->default('user')->after('password');

            // Ajout du champ matricule (optionnel, peut être NULL)
            $table->string('matricule')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les colonnes si rollback
            $table->dropColumn(['role', 'matricule']);
        });
    }
};
