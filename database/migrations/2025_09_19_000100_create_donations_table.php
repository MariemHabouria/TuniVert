<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('donations')) {
            return; // already exists
        }
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            // Relations (FK optional to avoid breaking if events table not present)
            $table->unsignedBigInteger('utilisateur_id');
            $table->unsignedBigInteger('evenement_id')->nullable();

            $table->decimal('montant', 10, 2);
                // $table->enum('type_don', ['ponctuel', 'recurrent']);
            // Include all supported methods from day one so sqlite/check constraints don't block factories
            $table->enum('moyen_paiement', ['carte', 'paypal', 'virement_bancaire', 'paymee', 'test']);
            $table->dateTime('date_don');

            // Custom timestamps (French names)
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('date_mise_a_jour')->useCurrent();

            // Indexes
            $table->index('utilisateur_id');
            $table->index('evenement_id');
            $table->index('date_don');
            $table->index('moyen_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
