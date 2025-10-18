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
        Schema::create('forum_statistiques', function (Blueprint $table) {
            $table->id();
            
            // Date de référence pour les stats
            $table->date('date_jour')->unique();
            
            // Statistiques forums
            $table->integer('nb_forums_total')->default(0);
            $table->integer('nb_forums_24h')->default(0);
            $table->integer('nb_forums_7j')->default(0);
            $table->integer('nb_forums_30j')->default(0);
            $table->integer('forums_populaires')->default(0); // score > 100
            
            // Statistiques alertes
            $table->integer('nb_alertes_total')->default(0);
            $table->integer('nb_alertes_24h')->default(0);
            $table->integer('nb_alertes_feu')->default(0);
            $table->integer('nb_alertes_haute')->default(0);
            $table->integer('nb_alertes_moyenne')->default(0);
            $table->integer('nb_alertes_basse')->default(0);
            
            // Statistiques utilisateurs
            $table->integer('nb_utilisateurs_actifs')->default(0);
            $table->integer('nb_utilisateurs_nouveaux')->default(0);
            
            // Métriques d'engagement
            $table->integer('total_vues')->default(0);
            $table->integer('total_reponses')->default(0);
            $table->decimal('taux_engagement', 5, 2)->default(0); // (réponses/vues)*100
            
            // Timestamps
            $table->timestamps();
            
            // Index pour performances
            $table->index('date_jour');
            $table->index('created_at');
        });
        
        // Table pour le suivi des vues par utilisateur (pour stats avancées)
        Schema::create('forum_vues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained()->onDelete('cascade');
            $table->foreignId('utilisateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Éviter les doublons
            $table->unique(['forum_id', 'utilisateur_id', 'ip_address']);
            $table->index(['forum_id', 'created_at']);
        });
        
        // Table pour les réponses aux forums
        Schema::create('reponses_forum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained()->onDelete('cascade');
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->text('contenu');
            $table->integer('score')->default(0); // Pour système de votes futur
            $table->boolean('est_resolution')->default(false); // Si c'est une réponse acceptée
            $table->timestamps();
            
            $table->index(['forum_id', 'created_at']);
            $table->index(['utilisateur_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reponses_forum');
        Schema::dropIfExists('forum_vues');
        Schema::dropIfExists('forum_statistiques');
    }
};