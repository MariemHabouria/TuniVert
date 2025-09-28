<?php

namespace App\Http\Controllers;

use App\Models\AlerteForum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlerteForumController extends Controller
{
    /**
     * Afficher la liste des alertes
     */
    public function index()
    {
        $alertes = AlerteForum::with('user')->latest()->paginate(10);
        return view('alertes.index', compact('alertes'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('alertes.create');
    }

    /**
     * Enregistrer une nouvelle alerte
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'gravite'     => 'required|in:basse,moyenne,haute,feu',
        ]);

     AlerteForum::create([
    'utilisateur_id' => Auth::id() , // si connecté → ID, sinon NULL
    'titre'          => $request->titre,
    'description'    => $request->description,
    'gravite'        => $request->gravite,
]);


        return redirect()->route('alertes.index')->with('success', '🚨 Alerte créée avec succès !');
    }

    /**
     * Afficher une alerte spécifique
     */
    public function show(string $id)
    {
        $alerte = AlerteForum::with('user')->findOrFail($id);
        return view('alertes.show', compact('alerte'));
    }

    /**
     * Formulaire d’édition d’une alerte
     */
    public function edit(string $id)
    {
        $alerte = AlerteForum::findOrFail($id);

        if ($alerte->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Action non autorisée.');
        }

        return view('alertes.edit', compact('alerte'));
    }

    /**
     * Mettre à jour une alerte
     */
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
        ]);

        $alerte->update([
            'titre'       => $request->titre,
            'description' => $request->description,
            'gravite'     => $request->gravite,
        ]);

        return redirect()->route('alertes.show', $alerte->id)->with('success', '✏️ Alerte mise à jour avec succès.');
    }

    /**
     * Supprimer une alerte
     */
    public function destroy(string $id)
    {
        $alerte = AlerteForum::findOrFail($id);

        if ($alerte->utilisateur_id !== Auth::id() && (!Auth::user() || !Auth::user()->is_admin)) {
            abort(403, '🚫 Action non autorisée.');
        }

        $alerte->delete();

        return redirect()->route('alertes.index')->with('success', '🗑️ Alerte supprimée avec succès.');
    }
}
