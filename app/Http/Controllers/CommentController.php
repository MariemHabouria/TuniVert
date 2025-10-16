<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Services\CommentSentimentService; // Service pour analyser le sentiment
use App\Services\CommentModerationService; // Service pour modération automatique

class CommentController extends Controller
{
    protected $sentimentService;
    protected $moderationService;

    public function __construct(
        CommentSentimentService $sentimentService,
        CommentModerationService $moderationService
    ) {
        $this->sentimentService = $sentimentService;
        $this->moderationService = $moderationService;
    }

    // Ajouter un commentaire
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $content = $request->content;

        // 1️⃣ Analyse du sentiment
        $sentiment = $this->sentimentService->analyseSentiment($content);

        // 2️⃣ Vérification modération
        $isBlocked = $this->moderationService->checkComment($content);
        if ($isBlocked) {
            return redirect()->back()->with('error', 'Votre commentaire contient des propos inappropriés ❌');
        }

        // 3️⃣ Création du commentaire
        $event->comments()->create([
            'user_id' => Auth::id(),
            'content' => $content,
            'sentiment' => $sentiment,
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

        $content = $request->content;

        // Ré-analyse du sentiment
        $sentiment = $this->sentimentService->analyseSentiment($content);

        // Vérification modération
        $isBlocked = $this->moderationService->checkComment($content);
        if ($isBlocked) {
            return redirect()->back()->with('error', 'Votre commentaire contient des propos inappropriés ❌');
        }

        $comment->update([
            'content' => $content,
            'sentiment' => $sentiment,
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
    public function reanalyse(Comment $comment)
{
    // Vérifier que l'utilisateur est le propriétaire
    if ($comment->user_id != Auth::id()) {
        return redirect()->back()->with('error', 'Vous ne pouvez pas ré-analyser ce commentaire.');
    }

    // Ré-analyse du sentiment via le service
    $sentiment = $this->sentimentService->analyseSentiment($comment->content);

    $comment->update([
        'sentiment' => $sentiment,
    ]);

    return redirect()->back()->with('success', 'Le commentaire a été ré-analysé ✅');
}

}
