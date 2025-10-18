<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la table existe
        if (!Schema::hasTable('notifications')) {
            // Créer la table correctement
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable'); // crée notifiable_type et notifiable_id
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });

            // Ajouter l'index si nécessaire
            Schema::table('notifications', function (Blueprint $table) {
                $table->index(['notifiable_type', 'notifiable_id']);
            });

            echo "Table notifications créée avec succès !\n";
        } else {
            // Vérifier et ajouter les colonnes manquantes
            if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->string('notifiable_type')->after('type');
                });
                echo "Colonne notifiable_type ajoutée !\n";
            }

            if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->unsignedBigInteger('notifiable_id')->after('notifiable_type');
                });
                echo "Colonne notifiable_id ajoutée !\n";
            }

            // Ajouter l'index si nécessaire
            $indexExists = DB::select(
                "SHOW INDEX FROM notifications WHERE Key_name = 'notifications_notifiable_type_notifiable_id_index'"
            );

            if (empty($indexExists)) {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->index(['notifiable_type', 'notifiable_id']);
                });
                echo "Index notifications_notifiable_type_notifiable_id_index ajouté !\n";
            } else {
                echo "Index notifications_notifiable_type_notifiable_id_index déjà existant.\n";
            }
        }
    }

    public function down()
    {
        // Ne pas supprimer la table en production
        // Schema::dropIfExists('notifications');
    }
};
