<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $forums = Forum::with('utilisateur')->latest()->paginate(10);
        return view('forums.index', compact('forums'));
    }

    public function create()
    {
        return view('forums.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'   => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

      Forum::create([
    'titre'          => $request->titre,
    'contenu'        => $request->contenu,
    'utilisateur_id' => Auth::id(), // doit correspondre à un user existant
]);

        return redirect()->route('forums.index')->with('success', '✅ Sujet créé avec succès !');
    }

    public function show(string $id)
    {
        $forum = Forum::with('utilisateur')->findOrFail($id);
        return view('forums.show', compact('forum'));
    }

    public function edit(string $id)
    {
        $forum = Forum::findOrFail($id);

        if ($forum->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Action non autorisée.');
        }

        return view('forums.edit', compact('forum'));
    }

    public function update(Request $request, string $id)
    {
        $forum = Forum::findOrFail($id);

        if ($forum->utilisateur_id !== Auth::id()) {
            abort(403, '🚫 Action non autorisée.');
        }

        $request->validate([
            'titre'   => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        $forum->update([
            'titre'   => $request->titre,
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('forums.show', $forum->id)->with('success', '✏️ Sujet mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $forum = Forum::findOrFail($id);

        if ($forum->utilisateur_id !== Auth::id() && (!Auth::user() || !Auth::user()->is_admin)) {
            abort(403, '🚫 Action non autorisée.');
        }

        $forum->delete();

        return redirect()->route('forums.index')->with('success', '🗑️ Sujet supprimé avec succès.');
    }
}
