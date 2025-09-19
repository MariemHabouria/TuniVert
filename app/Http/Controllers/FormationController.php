<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\RessourceFormation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

}
