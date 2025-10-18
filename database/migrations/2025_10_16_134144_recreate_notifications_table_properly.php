<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Sauvegarder les données existantes si nécessaire
        $hasData = false;
        if (Schema::hasTable('notifications')) {
            $count = DB::table('notifications')->count();
            $hasData = $count > 0;
            
            if ($hasData) {
                // Créer une table temporaire pour sauvegarder les données
                if (!Schema::hasTable('notifications_backup')) {
                    Schema::create('notifications_backup', function (Blueprint $table) {
                        $table->increments('id');
                        $table->text('original_data');
                        $table->timestamps();
                    });
                }
                
                // Sauvegarder les données existantes
                $notifications = DB::table('notifications')->get();
                foreach ($notifications as $notification) {
                    DB::table('notifications_backup')->insert([
                        'original_data' => json_encode($notification),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                echo "Données sauvegardées dans notifications_backup\n";
            }
            
            // Supprimer l'ancienne table
            Schema::dropIfExists('notifications');
        }

        // Recréer la table correctement
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['notifiable_type', 'notifiable_id']);
        });
        
        echo "Table notifications recréée correctement!\n";
        
        if ($hasData) {
            echo "⚠️  Les anciennes données ont été sauvegardées dans notifications_backup\n";
            echo "Vous devrez peut-être les réimporter manuellement si nécessaire.\n";
        }
    }

    public function down()
    {
        // En rollback, supprimer la nouvelle table et restaurer l'ancienne si elle existait
        Schema::dropIfExists('notifications');
        
        if (Schema::hasTable('notifications_backup')) {
            // Vous pourriez restaurer les données ici si nécessaire
            Schema::dropIfExists('notifications_backup');
        }
    }
};