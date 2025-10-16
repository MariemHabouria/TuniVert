<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Ajouter un commentaire
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $event->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté ✅');
    }

    // Modifier un commentaire
    public function update(Request $request, Comment $comment)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if ($comment->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier ce commentaire.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Commentaire modifié ✅');
    }

    // Supprimer un commentaire
    public function destroy(Comment $comment)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if ($comment->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé ❌');
    }
}
