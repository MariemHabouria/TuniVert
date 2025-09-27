<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ressources_formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->string('titre');
            // pdf | ppt | video | lien
            $table->enum('type', ['pdf','ppt','video','lien']);
            $table->string('url');
            $table->timestamps(); // date_creation inclus
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressources_formations');
    }
};
