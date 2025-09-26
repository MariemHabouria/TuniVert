<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\RessourceFormation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FormationController extends Controller
{
      // Catalogue public avec filtres
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $categorie = $request->string('categorie')->toString();
        $type = $request->string('type')->toString();
        $statut = $request->string('statut')->toString();

        $formations = Formation::query()
            ->when($q, fn($query) => $query->where(function($qq) use ($q) {
                $qq->where('titre', 'like', "%$q%")
                   ->orWhere('description', 'like', "%$q%");
            }))
            ->when($categorie, fn($query) => $query->where('categorie', $categorie))
            ->when($type, fn($query) => $query->where('type', $type))
            ->when($statut, fn($query) => $query->where('statut', $statut))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        // Pour peupler les filtres (liste distincte)
        $categories = Formation::query()->select('categorie')->distinct()->pluck('categorie');

        return view('formations.index', compact('formations', 'categories', 'q', 'categorie', 'type', 'statut'));
    }

    public function show(Formation $formation)
    {
        $formation->load('organisateur','ressources');
        return view('formations.show', compact('formation'));
    }

    // ===== Organisateur (association) =====
    public function create()
    {
        $this->authorizeAssociation();
        return view('formations.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAssociation();

        $data = $request->validate([
            'titre'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'categorie'   => ['nullable','string','max:120'],
            'type'        => ['required','in:formation,atelier,conférence,webinaire'],
            'capacite'    => ['required','integer','min:0'],
            'lien_visio'  => ['nullable','url'],
            'statut'      => ['required','in:ouverte,suspendue,terminee'],
            'image'       => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        // upload optionnel
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('formations', 'public');
            $data['image'] = $path;
        }

        $data['organisateur_id'] = Auth::id();

        $formation = Formation::create($data);

        return redirect()->route('formations.show', $formation)
            ->with('status','Formation créée avec succès.');
    }

    // Ajout simple de ressource
    public function storeResource(Request $request, Formation $formation)
    {
        $this->authorizeAssociation();
        abort_unless($formation->organisateur_id === Auth::id(), 403);

        $validated = $request->validate([
            'titre' => ['required','string','max:255'],
            'type'  => ['required','in:pdf,ppt,video,lien'],
            'url'   => ['required','url'],
        ]);

        $validated['formation_id'] = $formation->id;

        RessourceFormation::create($validated);

        return back()->with('status','Ressource ajoutée.');
    }

    private function authorizeAssociation(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'association') {
            abort(403, 'Accès réservé aux associations.');
        }
    }
public function participants()
{
    // ⚠️ PAS de ->withTimestamps()
    return $this->belongsToMany(\App\Models\User::class, 'formation_user')
                ->withPivot('inscrit_at'); // idem
}

public function edit(Formation $formation)
{
    // Autoriser uniquement l’organisateur
    if ((int) $formation->organisateur_id !== (int) Auth::id()) {
        abort(403, 'Action non autorisée.');
    }

    return view('formations.edit', compact('formation'));
}

public function update(Request $request, Formation $formation)
{
    if ((int) $formation->organisateur_id !== (int) Auth::id()) {
        abort(403, 'Action non autorisée.');
    }

    $data = $request->validate([
        'titre'       => ['required','string','max:255'],
        'description' => ['required','string'],
        'categorie'   => ['nullable','string','max:255'],
        'type'        => ['required', Rule::in(['formation','atelier','conférence','webinaire'])],
        'capacite'    => ['required','integer','min:1'],
        'lien_visio'  => ['nullable','string','max:2000'],
        'statut'      => ['required', Rule::in(['ouverte','suspendue','terminee'])],
        'image'       => ['nullable','image','max:4096'], // 4 Mo
    ]);

    // Image (optionnelle)
    if ($request->hasFile('image')) {
        // supprimer l’ancienne si elle existe et provient du disk public
        if ($formation->image) {
            // si tu stockes des chemins "formations/xxx.jpg", use disk public
            $oldPath = str_starts_with($formation->image, 'formations/')
                ? $formation->image
                : str_replace('/storage/', '', $formation->image);
            Storage::disk('public')->delete($oldPath);
        }

        $path = $request->file('image')->store('formations', 'public');
        // tu peux garder juste le chemin, ou l’URL publique :
        $data['image'] = $path; // (ou Storage::disk('public')->url($path))
    }

    $formation->update($data);

    return redirect()
        ->route('formations.show', $formation)
        ->with('status', 'Formation mise à jour avec succès.');
}

public function destroy(Formation $formation)
{
    if ((int) $formation->organisateur_id !== (int) Auth::id()) {
        abort(403, 'Action non autorisée.');
    }

    // Detach inscriptions (si tu as la relation)
    if (method_exists($formation, 'participants')) {
        $formation->participants()->detach();
    }

    // Supprimer ressources liées
    if (method_exists($formation, 'ressources')) {
        // supprimer aussi les fichiers physiques si ce sont des uploads
        foreach ($formation->ressources as $res) {
            // si url locale genre /storage/formations/ressources/xxx.pdf
            if ($res->type !== 'lien' && $res->url && str_contains($res->url, '/storage/')) {
                $file = str_replace('/storage/', '', $res->url);
                Storage::disk('public')->delete($file);
            }
            $res->delete();
        }
    }

    // Supprimer l’image d’affiche si présente
    if ($formation->image) {
        $imgPath = str_starts_with($formation->image, 'formations/')
            ? $formation->image
            : str_replace('/storage/', '', $formation->image);
        Storage::disk('public')->delete($imgPath);
    }

    $formation->delete();

    return redirect()
        ->route('formations.index')
        ->with('status', 'Formation supprimée.');
}

public function dashboard()
{
    $user = \Auth::user();
    abort_unless($user && $user->role === 'association', 403);

    $q = \App\Models\Formation::where('organisateur_id', $user->id);

    $kpis = [
        'total'       => (clone $q)->count(),
        'ouvertes'    => (clone $q)->where('statut', 'ouverte')->count(),
        'suspendues'  => (clone $q)->where('statut', 'suspendue')->count(),
        'terminees'   => (clone $q)->where('statut', 'terminee')->count(),
        'capacite'    => (clone $q)->sum('capacite'),
    ];

    // Inscriptions totales sur toutes les formations de l’association
    $inscriptionsTotal = \DB::table('formation_user')
        ->join('formations', 'formations.id', '=', 'formation_user.formation_id')
        ->where('formations.organisateur_id', $user->id)
        ->count();

    // Taux de remplissage moyen = inscriptions / capacité totale
    $remplissageMoyen = $kpis['capacite'] > 0
        ? round(($inscriptionsTotal / $kpis['capacite']) * 100, 1)
        : 0;

    // Répartition par catégorie / type
    $parCategorie = (clone $q)
        ->selectRaw('categorie, COUNT(*) as total')
        ->groupBy('categorie')
        ->pluck('total', 'categorie');

    $parType = (clone $q)
        ->selectRaw('type, COUNT(*) as total')
        ->groupBy('type')
        ->pluck('total', 'type');

    // Top 5 formations par nombre d’inscrits
    $topFormations = (clone $q)
        ->leftJoin('formation_user', 'formations.id', '=', 'formation_user.formation_id')
        ->select('formations.id','formations.titre','formations.capacite','formations.statut',
            \DB::raw('COUNT(formation_user.user_id) as inscrits'))
        ->groupBy('formations.id','formations.titre','formations.capacite','formations.statut')
        ->orderByDesc('inscrits')
        ->limit(5)
        ->get();

    // Ressources totales
    $ressourcesTotales = \DB::table('ressources_formations')
        ->join('formations','formations.id','=','ressources_formations.formation_id')
        ->where('formations.organisateur_id', $user->id)
        ->count();

    // Inscriptions / jour sur 30j
    $inscriptionsParJour = \DB::table('formation_user')
        ->join('formations', 'formations.id', '=', 'formation_user.formation_id')
        ->where('formations.organisateur_id', $user->id)
        ->where('formation_user.inscrit_at', '>=', now()->subDays(30))
        ->selectRaw('DATE(formation_user.inscrit_at) as jour, COUNT(*) as total')
        ->groupBy('jour')
        ->orderBy('jour')
        ->pluck('total', 'jour');

    // Moyenne des notes par formation (si table avis_formations existe)
    $notesMoyennes = [];
    if (\Schema::hasTable('avis_formations')) {
        $notesMoyennes = \DB::table('avis_formations')
            ->join('formations','formations.id','=','avis_formations.formation_id')
            ->where('formations.organisateur_id',$user->id)
            ->select('avis_formations.formation_id', \DB::raw('ROUND(AVG(note),2) as note_moy'))
            ->groupBy('avis_formations.formation_id')
            ->pluck('note_moy','formation_id');
    }

    return view('formations.dashboard', compact(
        'kpis','inscriptionsTotal','remplissageMoyen',
        'parCategorie','parType','topFormations',
        'ressourcesTotales','inscriptionsParJour','notesMoyennes'
    ));
}

}
