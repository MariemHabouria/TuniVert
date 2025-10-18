<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\AlerteForum;
use App\Models\Notification;
use App\Models\ForumVue;
use App\Models\ReponseForum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Afficher la liste des forums avec tri et recherche
     */
    public function index(Request $request)
    {
        $query = Forum::with('utilisateur');
        
        // RECHERCHE
        if ($request->has('recherche') && $request->recherche != '') {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'LIKE', "%{$request->recherche}%")
                  ->orWhere('contenu', 'LIKE', "%{$request->recherche}%")
                  ->orWhere('tags', 'LIKE', "%{$request->recherche}%");
            });
        }

        // TRI INTELLIGENT
        $typeTri = $request->get('tri', 'recent');
        switch ($typeTri) {
            case 'populaire':
                $query->orderBy('popularite_score', 'desc')
                      ->orderBy('nb_vues', 'desc');
                break;
            case 'actif':
                $query->orderBy('updated_at', 'desc')
                      ->orderBy('nb_reponses', 'desc');
                break;
            case 'recent':
            default:
                $query->latest();
                break;
        }

        $forums = $query->paginate(10);
        
        // STATISTIQUES TEMPS RÉEL
        $stats = $this->getStatsForums();

        return view('forums.index', compact('forums', 'stats', 'typeTri'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('forums.create');
    }

    /**
     * Enregistrer un nouveau forum
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre'   => 'required|string|max:255',
            'contenu' => 'required|string',
            'tags'    => 'nullable|string|max:255',
        ]);

        $forum = Forum::create([
            'titre'          => $request->titre,
            'contenu'        => $request->contenu,
            'utilisateur_id' => Auth::id(),
            'tags'           => $request->tags,
        ]);

        return redirect()->route('forums.index')->with('success', '✅ Sujet créé avec succès !');
    }

    /**
     * Afficher un forum spécifique
     */
   public function show(string $id)
{
    // Vérifier si le modèle ReponseForum existe avant de charger la relation
    if (class_exists(\App\Models\ReponseForum::class)) {
        $forum = Forum::with(['utilisateur', 'reponses.utilisateur'])->findOrFail($id);
    } else {
        $forum = Forum::with('utilisateur')->findOrFail($id);
    }
    
    // ENREGISTRER LA VUE ET METTRE À JOUR LES STATS
    if (class_exists(\App\Models\ForumVue::class)) {
        \App\Models\ForumVue::enregistrerVue($id, Auth::id());
    }
    
    $forum->increment('nb_vues');
    
    // CALCULER LA POPULARITÉ SI LA MÉTHODE EXISTE
    if (method_exists($forum, 'calculerPopularite')) {
        $forum->calculerPopularite();
    }

    return view('forums.show', compact('forum'));
}
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(string $id)
    {
        $forum = Forum::findOrFail($id);

        if ($forum->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Action non autorisée.');
        }

        return view('forums.edit', compact('forum'));
    }

    /**
     * Mettre à jour un forum
     */
    public function update(Request $request, string $id)
    {
        $forum = Forum::findOrFail($id);

        if ($forum->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Action non autorisée.');
        }

        $request->validate([
            'titre'   => 'required|string|max:255',
            'contenu' => 'required|string',
            'tags'    => 'nullable|string|max:255',
        ]);

        $forum->update([
            'titre'   => $request->titre,
            'contenu' => $request->contenu,
            'tags'    => $request->tags,
        ]);

        return redirect()->route('forums.show', $forum->id)->with('success', '✏️ Sujet mis à jour avec succès.');
    }

    /**
     * Supprimer un forum
     */
    public function destroy(string $id)
    {
        $forum = Forum::findOrFail($id);

        if ($forum->utilisateur_id !== Auth::id() && (!Auth::user() || !Auth::user()->is_admin)) {
            abort(403, '🚫 Action non autorisée.');
        }

        $forum->delete();

        return redirect()->route('forums.index')->with('success', '🗑️ Sujet supprimé avec succès.');
    }

    // =========================================================================
    // NOUVELLES FONCTIONNALITÉS AVANCÉES
    // =========================================================================

    /**
     * RECHERCHE AVANCÉE UNIFIÉE
     */
    public function rechercheAvancee(Request $request)
    {
        $motCle = $request->get('q', '');
        $type = $request->get('type', 'tous');
        $gravite = $request->get('gravite', 'tous');
        $dateFiltre = $request->get('date', 'tous');

        $resultatsForums = collect();
        $resultatsAlertes = collect();

        // RECHERCHE FORUMS
        if ($type === 'tous' || $type === 'forums') {
            $queryForum = Forum::with('utilisateur')
                ->where(function($q) use ($motCle) {
                    $q->where('titre', 'LIKE', "%{$motCle}%")
                      ->orWhere('contenu', 'LIKE', "%{$motCle}%")
                      ->orWhere('tags', 'LIKE', "%{$motCle}%");
                });
            
            if ($dateFiltre !== 'tous') {
                $queryForum->where('created_at', '>=', $this->getDateFiltre($dateFiltre));
            }
            
            $resultatsForums = $queryForum->limit(10)->get();
        }

        // RECHERCHE ALERTES
        if ($type === 'tous' || $type === 'alertes') {
            $queryAlerte = AlerteForum::with('user')
                ->where(function($q) use ($motCle) {
                    $q->where('titre', 'LIKE', "%{$motCle}%")
                      ->orWhere('description', 'LIKE', "%{$motCle}%")
                      ->orWhere('localisation', 'LIKE', "%{$motCle}%");
                });
            
            if ($gravite !== 'tous') {
                $queryAlerte->where('gravite', $gravite);
            }
            
            if ($dateFiltre !== 'tous') {
                $queryAlerte->where('created_at', '>=', $this->getDateFiltre($dateFiltre));
            }
            
            $resultatsAlertes = $queryAlerte->limit(10)->get();
        }

        return view('recherche.index', compact(
            'resultatsForums', 
            'resultatsAlertes', 
            'motCle', 
            'type', 
            'gravite', 
            'dateFiltre'
        ));
    }

    /**
     * GESTION DES RÉPONSES AUX FORUMS
     */
    public function storeReponse(Request $request, $forumId)
    {
        // Vérifier si le modèle ReponseForum existe
        if (!class_exists(ReponseForum::class)) {
            return redirect()->back()->with('error', 'Fonctionnalité des réponses non disponible.');
        }

        $request->validate([
            'contenu' => 'required|string|min:10',
        ]);

        $forum = Forum::findOrFail($forumId);

        $reponse = ReponseForum::create([
            'forum_id' => $forumId,
            'utilisateur_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        // METTRE À JOUR LE COMPTEUR DE RÉPONSES
        $forum->increment('nb_reponses');
        
        // CALCULER LA POPULARITÉ
        if (method_exists($forum, 'calculerPopularite')) {
            $forum->calculerPopularite();
        }

      

        return redirect()->route('forums.show', $forumId)
                       ->with('success', '💬 Réponse ajoutée avec succès !');
    }
public function suggestionIA(Request $request, $forumId)
{
    $request->validate([
        'texte_courant' => 'required|string|min:5',
    ]);

    $forum = Forum::findOrFail($forumId);

    $client = new \GuzzleHttp\Client();

    try {
        $response = $client->post('http://127.0.0.1:5000/suggestion', [
            'json' => [
                'forum_contenu' => $forum->contenu,
                'texte_courant' => $request->texte_courant,
            ]
        ]);

        $suggestion = json_decode($response->getBody()->getContents(), true)['suggestion'] ?? '';
    } catch (\Exception $e) {
        $suggestion = '';
    }

    return response()->json(['suggestion' => $suggestion]);
}

    /**
     * MARQUER UNE RÉPONSE COMME SOLUTION
     */
    public function marquerSolution($forumId, $reponseId)
    {
        if (!class_exists(ReponseForum::class)) {
            return redirect()->back()->with('error', 'Fonctionnalité des solutions non disponible.');
        }

        $forum = Forum::findOrFail($forumId);
        $reponse = ReponseForum::findOrFail($reponseId);

        // VÉRIFIER QUE L'UTILISATEUR EST L'AUTEUR DU FORUM
        if ($forum->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Seul l\'auteur du sujet peut marquer une réponse comme solution.');
        }

        // MARQUER COMME SOLUTION
        \DB::transaction(function () use ($reponse, $forum) {
            // RETIRER LE STATUT DES AUTRES RÉPONSES
            ReponseForum::where('forum_id', $forum->id)
                ->where('id', '!=', $reponse->id)
                ->update(['est_resolution' => false]);
            
            // MARQUER CETTE RÉPONSE COMME SOLUTION
            $reponse->update(['est_resolution' => true]);
            
            // NOTIFICATION (SI DISPONIBLE)
            if (class_exists(Notification::class)) {
                Notification::creerNotificationSolution($reponse);
            }
        });

        return redirect()->back()->with('success', '✅ Réponse marquée comme solution !');
    }

    /**
     * STATISTIQUES AVANCÉES (POUR ADMIN)
     */
    public function statistiquesAvancees()
    {
        // VÉRIFIER LES PERMISSIONS ADMIN
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, '🚫 Accès réservé aux administrateurs.');
        }

        $stats = $this->getStatsForumsAvancees();
        
        return view('admin.statistiques', compact('stats'));
    }

    // =========================================================================
    // MÉTHODES PRIVÉES
    // =========================================================================

    /**
     * STATISTIQUES TEMPS RÉEL SIMPLES
     */
    private function getStatsForums()
    {
        return [
            'total' => Forum::count(),
            'aujourdhui' => Forum::whereDate('created_at', today())->count(),
            'populaires' => Forum::where('popularite_score', '>', 100)->count(),
            'alertes_urgentes' => AlerteForum::whereIn('gravite', ['feu', 'haute'])->count(),
        ];
    }

    /**
     * STATISTIQUES AVANCÉES POUR ADMIN
     */
    private function getStatsForumsAvancees()
    {
        return [
            // FORUMS
            'forums_total' => Forum::count(),
            'forums_24h' => Forum::where('created_at', '>=', now()->subDay())->count(),
            'forums_7j' => Forum::where('created_at', '>=', now()->subDays(7))->count(),
            'forums_30j' => Forum::where('created_at', '>=', now()->subDays(30))->count(),
            'forums_populaires' => Forum::where('popularite_score', '>', 100)->count(),
            
            // ALERTES
            'alertes_total' => AlerteForum::count(),
            'alertes_24h' => AlerteForum::where('created_at', '>=', now()->subDay())->count(),
            'alertes_feu' => AlerteForum::where('gravite', 'feu')->count(),
            'alertes_haute' => AlerteForum::where('gravite', 'haute')->count(),
            'alertes_moyenne' => AlerteForum::where('gravite', 'moyenne')->count(),
            'alertes_basse' => AlerteForum::where('gravite', 'basse')->count(),
            
            // ENGAGEMENT
            'total_vues' => Forum::sum('nb_vues'),
            'total_reponses' => Forum::sum('nb_reponses'),
            'utilisateurs_actifs' => \App\Models\User::whereHas('forums')->orWhereHas('alertes')->count(),
        ];
    }

    /**
     * FILTRE DE DATE POUR RECHERCHE
     */
    private function getDateFiltre($dateFiltre)
    {
        switch ($dateFiltre) {
            case '24h': return now()->subDay();
            case '7j': return now()->subDays(7);
            case '30j': return now()->subDays(30);
            default: return now()->subYears(100); // Très ancienne date
        }
    }
}