<?php

namespace App\Http\Controllers;

use App\Notifications\NouvelleAlerteNotification;
use App\Services\ModerationService;
use App\Models\AlerteForum;
use App\Models\CommentaireAlerte;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AlerteForumController extends Controller
{
    public function index(Request $request)
    {
        $query = AlerteForum::with('user');
        
        // FILTRE PAR GRAVITÉ
        if ($request->has('gravite') && $request->gravite != 'tous') {
            $query->where('gravite', $request->gravite);
        }

        // CORRECTION : Supprimer le bloc problématique avec 'est_urgent'
        // et le remplacer par un tri simple
        $query->orderBy('created_at', 'desc');

        $alertes = $query->paginate(10);
        
        // STATISTIQUES ALERTES
        $statsAlertes = $this->getStatsAlertes();

        return view('alertes.index', compact('alertes', 'statsAlertes'));
    }

    public function create()
    {
        return view('alertes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'gravite' => 'required|in:basse,moyenne,haute,feu',
            'zone_geographique' => 'required|string|max:255',
        ]);

        // 🛡️ MODÉRATION AUTOMATIQUE du titre et description
        $moderationTitre = ModerationService::analyseTexte($request->titre);
        $moderationDescription = ModerationService::analyseTexte($request->description);

        // Vérifier si le contenu est inapproprié
        if (ModerationService::shouldBlockComment($request->titre) || 
            ModerationService::shouldBlockComment($request->description)) {
            
            Log::warning('Tentative de création d\'alerte avec contenu inapproprié', [
                'user_id' => Auth::id(),
                'titre' => $request->titre,
                'titre_flagged' => $moderationTitre['flagged'],
                'description_flagged' => $moderationDescription['flagged']
            ]);

            return back()->withErrors([
                'titre' => 'Votre alerte contient du contenu inapproprié et ne peut pas être publiée. Raison: ' . 
                          ($moderationTitre['reason'] ?? $moderationDescription['reason'])
            ]);
        }

        $alerte = AlerteForum::create([
            'utilisateur_id' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'gravite' => $request->gravite,
            'zone_geographique' => $request->zone_geographique,
            'localisation' => $request->localisation,
            'statut' => 'en_cours',
            // Stocker les résultats de modération pour audit
            'moderation_flagged' => false, // Déjà vérifié ci-dessus
            'moderation_score' => max($moderationTitre['score'], $moderationDescription['score']),
        ]);

        // 🔔 NOTIFICATION AUTOMATIQUE POUR ALERTES URGENTES
        if (in_array($request->gravite, ['feu', 'haute'])) {
            $this->notifierUtilisateursConcernes($alerte);
        }

        return redirect()->route('alertes.show', $alerte->id)
            ->with('success', '🚨 Alerte créée avec succès !');
    }

    private function notifierUtilisateursConcernes($alerte)
    {
        try {
            // Notifie tous les utilisateurs sauf le créateur
            $utilisateurs = \App\Models\User::where('id', '!=', Auth::id())->get();

            foreach ($utilisateurs as $user) {
                $user->notify(new \App\Notifications\NouvelleAlerteNotification($alerte));
            }

            Log::info("✅ Notifications envoyées pour l'alerte #{$alerte->id} à {$utilisateurs->count()} utilisateurs");
        } catch (\Exception $e) {
            Log::error('❌ Erreur envoi notifications : ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $alerte = AlerteForum::with(['user', 'commentaires.user'])->findOrFail($id);
        
        // Incrémenter le compteur de vues si la colonne existe
        if (Schema::hasColumn('alertes_forum', 'nombre_vues')) {
            $alerte->increment('nombre_vues');
        }

        return view('alertes.show', compact('alerte'));
    }

    public function edit(string $id)
    {
        $alerte = AlerteForum::findOrFail($id);

        if ($alerte->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Action non autorisée.');
        }

        return view('alertes.edit', compact('alerte'));
    }

    public function update(Request $request, string $id)
    {
        $alerte = AlerteForum::findOrFail($id);

        if ($alerte->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Action non autorisée.');
        }

        $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'gravite'     => 'required|in:basse,moyenne,haute,feu',
            'zone_geographique' => 'nullable|string|max:255',
        ]);

        // 🛡️ MODÉRATION AUTOMATIQUE des modifications
        $moderationTitre = ModerationService::analyseTexte($request->titre);
        $moderationDescription = ModerationService::analyseTexte($request->description);

        if (ModerationService::shouldBlockComment($request->titre) || 
            ModerationService::shouldBlockComment($request->description)) {
            
            return back()->withErrors([
                'titre' => 'Votre modification contient du contenu inapproprié. Raison: ' . 
                          ($moderationTitre['reason'] ?? $moderationDescription['reason'])
            ]);
        }

        $alerte->update([
            'titre'       => $request->titre,
            'description' => $request->description,
            'gravite'     => $request->gravite,
            'zone_geographique' => $request->zone_geographique,
            'moderation_score' => max($moderationTitre['score'], $moderationDescription['score']),
        ]);

        return redirect()->route('alertes.show', $alerte->id)->with('success', '✏️ Alerte mise à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $alerte = AlerteForum::findOrFail($id);

        // Utiliser la même logique que la vue
        if ($alerte->utilisateur_id !== Auth::id() && (!Auth::user() || !Auth::user()->is_admin)) {
            abort(403, '🚫 Action non autorisée.');
        }

        $alerte->delete();

        return redirect()->route('alertes.index')->with('success', '🗑️ Alerte supprimée avec succès.');
    }

    public function marquerResolue($id)
    {
        $alerte = AlerteForum::findOrFail($id);
        
        // Même logique de permission
        if ($alerte->utilisateur_id !== Auth::id() && (!Auth::user() || !Auth::user()->is_admin)) {
            abort(403, '🚫 Action non autorisée.');
        }

        $alerte->update([
            'statut' => 'resolue',
            'date_resolution' => now(),
            'resolue_par' => Auth::id()
        ]);

        return back()->with('success', '✅ Alerte marquée comme résolue !');
    }

    /**
     * Ajouter un commentaire à une alerte avec modération IA avancée
     */
    public function ajouterCommentaire(Request $request, $id)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000'
        ]);

        $alerte = AlerteForum::findOrFail($id);

        // 🔍 Vérifier le contenu avec OpenAI - Version avancée
        $moderationResult = ModerationService::analyseTexte($request->contenu);

        // Utiliser le seuil configurable pour bloquer les commentaires inappropriés
        if (ModerationService::shouldBlockComment($request->contenu)) {
            
            Log::warning('Commentaire bloqué par modération IA', [
                'user_id' => Auth::id(),
                'alerte_id' => $alerte->id,
                'contenu' => substr($request->contenu, 0, 100),
                'score' => $moderationResult['score'],
                'reason' => $moderationResult['reason'],
                'categories' => $moderationResult['categories']
            ]);

            return back()->with('error', 
                '❌ Votre commentaire contient du contenu inapproprié et a été bloqué. ' .
                'Raison: ' . $moderationResult['reason']
            );
        }

        $commentaire = CommentaireAlerte::create([
            'contenu' => $request->contenu,
            'alerte_id' => $alerte->id,
            'user_id' => Auth::id(),
            // Stocker les résultats de modération pour audit
            'moderation_flagged' => $moderationResult['flagged'],
            'moderation_score' => $moderationResult['score'],
            'moderation_reason' => $moderationResult['reason'],
        ]);

        Log::info('Nouveau commentaire ajouté avec modération', [
            'commentaire_id' => $commentaire->id,
            'score' => $moderationResult['score'],
            'flagged' => $moderationResult['flagged']
        ]);

        return back()->with('success', '💬 Commentaire ajouté avec succès !');
    }

    public function destroyCommentaire($id)
    {
        $commentaire = CommentaireAlerte::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire du commentaire ou admin
        if ($commentaire->user_id !== Auth::id() && (!Auth::user() || !Auth::user()->is_admin)) {
            abort(403, 'Action non autorisée.');
        }

        $commentaire->delete();

        return back()->with('success', 'Commentaire supprimé avec succès.');
    }

    /**
     * Partager une alerte
     */
    public function partager($id)
    {
        $alerte = AlerteForum::findOrFail($id);
        
        // Incrémenter le compteur de partages si la colonne existe
        if (Schema::hasColumn('alertes_forum', 'nombre_partages')) {
            $alerte->increment('nombre_partages');
        }

        return back()->with('success', '📤 Merci d\'avoir partagé cette alerte !');
    }

    /**
     * Afficher la carte des alertes
     */
    public function carte()
    {
        $alertes = AlerteForum::where('statut', '!=', 'resolue')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('alertes.carte', compact('alertes'));
    }

    /**
     * Afficher les statistiques détaillées
     */
    public function statistiques()
    {
        $stats = $this->getStatsDetaillees();
        return view('alertes.statistiques', compact('stats'));
    }

    /**
     * Page d'administration de la modération
     */
    public function moderationAdmin()
    {
        // Vérifier les droits admin
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $commentairesModeres = CommentaireAlerte::where('moderation_score', '>', 0)
            ->orderBy('moderation_score', 'desc')
            ->with(['user', 'alerte'])
            ->paginate(20);

        $statsModeration = [
            'total_commentaires' => CommentaireAlerte::count(),
            'commentaires_moderes' => CommentaireAlerte::where('moderation_score', '>', 0)->count(),
            'commentaires_bloques' => CommentaireAlerte::where('moderation_flagged', true)->count(),
            'score_moyen' => CommentaireAlerte::where('moderation_score', '>', 0)->avg('moderation_score') ?? 0,
        ];

        return view('alertes.moderation-admin', compact('commentairesModeres', 'statsModeration'));
    }

    // === STATISTIQUES ALERTES ===
    private function getStatsAlertes()
    {
        try {
            return [
                'total' => AlerteForum::count(),
                'en_cours' => AlerteForum::where('statut', '!=', 'resolue')->orWhereNull('statut')->count(),
                'resolues' => AlerteForum::where('statut', 'resolue')->count(),
                'urgentes' => AlerteForum::whereIn('gravite', ['feu', 'haute'])->count(),
                'today' => AlerteForum::whereDate('created_at', today())->count(),
            ];
        } catch (\Exception $e) {
            // Fallback en cas d'erreur
            return [
                'total' => 0,
                'en_cours' => 0,
                'resolues' => 0,
                'urgentes' => 0,
                'today' => 0
            ];
        }
    }
// AlerteController.php
public function statsAlertes()
{
    $total = \App\Models\Alerte::count();
    $par_gravite = [
        'feu' => \App\Models\Alerte::where('gravite', 'feu')->count(),
        'haute' => \App\Models\Alerte::where('gravite', 'haute')->count(),
        'moyenne' => \App\Models\Alerte::where('gravite', 'moyenne')->count(),
        'basse' => \App\Models\Alerte::where('gravite', 'basse')->count(),
    ];

    return response()->json([
        'total' => $total,
        '24h' => \App\Models\Alerte::where('created_at', '>=', now()->subDay())->count(),
        '7j' => \App\Models\Alerte::where('created_at', '>=', now()->subDays(7))->count(),
        '30j' => \App\Models\Alerte::where('created_at', '>=', now()->subDays(30))->count(),
        'par_gravite' => $par_gravite,
    ]);
}





    /**
     * Statistiques détaillées pour la page statistiques
     */
    private function getStatsDetaillees()
    {
        try {
            return [
                'total_alertes' => AlerteForum::count(),
                'alertes_mois' => AlerteForum::whereMonth('created_at', now()->month)->count(),
                'taux_resolution' => AlerteForum::count() > 0 ? 
                    round((AlerteForum::where('statut', 'resolue')->count() / AlerteForum::count()) * 100, 2) : 0,
                'gravite_stats' => AlerteForum::select('gravite', DB::raw('count(*) as total'))
                    ->groupBy('gravite')
                    ->get()
                    ->pluck('total', 'gravite'),
                'top_zones' => AlerteForum::select('zone_geographique', DB::raw('count(*) as total'))
                    ->groupBy('zone_geographique')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get(),
                'evolution_mensuelle' => AlerteForum::select(
                        DB::raw('MONTH(created_at) as mois'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->whereYear('created_at', now()->year)
                    ->groupBy('mois')
                    ->get(),
                // Statistiques de modération
                'moderation_stats' => [
                    'commentaires_total' => CommentaireAlerte::count(),
                    'commentaires_moderes' => CommentaireAlerte::where('moderation_score', '>', 0)->count(),
                    'taux_blocage' => CommentaireAlerte::count() > 0 ? 
                        round((CommentaireAlerte::where('moderation_flagged', true)->count() / CommentaireAlerte::count()) * 100, 2) : 0,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'total_alertes' => 0,
                'alertes_mois' => 0,
                'taux_resolution' => 0,
                'gravite_stats' => collect(),
                'top_zones' => collect(),
                'evolution_mensuelle' => collect(),
                'moderation_stats' => [
                    'commentaires_total' => 0,
                    'commentaires_moderes' => 0,
                    'taux_blocage' => 0
                ]
            ];
        }
    }
}