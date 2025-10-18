<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Services\CommentSentimentService; // Service pour analyser le sentiment

class CommentController extends Controller
{
    protected $sentimentService;

    public function __construct(
        CommentSentimentService $sentimentService,
    ) {
        $this->sentimentService = $sentimentService;
    }

    private function checkCommentText(string $text): bool
    {
        $defaults = 'spam,scam,fake,cheat,bully,insulte,haine';
        $source = env('COMMENT_BLOCKED_KEYWORDS', $defaults);
        $parts = array_map('trim', preg_split('/[,;|]/', (string) $source) ?: []);
        $blocked = array_values(array_unique(array_map(function ($w) {
            return mb_strtolower($w);
        }, array_filter($parts, fn($w) => $w !== ''))));
        $haystack = mb_strtolower($text);
        foreach ($blocked as $word) {
            if ($word !== '' && mb_strpos($haystack, $word) !== false) {
                return true;
            }
        }
        return false;
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
        $isBlocked = $this->checkCommentText($content);
        if ($isBlocked) {
            return redirect()->back()->with('error', 'Votre commentaire contient des propos inappropriés ❌')->withFragment('comments');
        }

        // 3️⃣ Création du commentaire
        $event->comments()->create([
            'user_id' => Auth::id(),
            'content' => $content,
            'sentiment' => $sentiment,
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté ✅')->withFragment('comments');
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
        $isBlocked = $this->checkCommentText($content);
        if ($isBlocked) {
            return redirect()->back()->with('error', 'Votre commentaire contient des propos inappropriés ❌')->withFragment('comments');
        }

        $comment->update([
            'content' => $content,
            'sentiment' => $sentiment,
        ]);

        return redirect()->back()->with('success', 'Commentaire modifié ✅')->withFragment('comments');
    }

    // Supprimer un commentaire
    public function destroy(Comment $comment)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if ($comment->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé ❌')->withFragment('comments');
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

    return redirect()->back()->with('success', 'Le commentaire a été ré-analysé ✅')->withFragment('comments');
}

}