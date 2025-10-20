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
            $table->string('phone')->nullable()->after('email');
            $table->text('bio')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('matricule_fiscale')->nullable()->after('avatar');
            $table->text('adresse')->nullable()->after('matricule_fiscale');
            $table->boolean('is_active')->default(true)->after('adresse');
            $table->integer('points')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'bio', 'avatar', 'matricule_fiscale', 
                'adresse', 'is_active', 'points'
            ]);
        });
    }
};
