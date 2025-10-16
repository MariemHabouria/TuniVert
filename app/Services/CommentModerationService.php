<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class CommentModerationService
{
    public function checkComment(string $texte): bool
    {
        try {
            $response = OpenAI::moderations()->create([
                'model' => 'omni-moderation-latest',
                'input' => $texte,
            ]);

            return $response->results[0]->flagged ?? false;
        } catch (\Exception $e) {
            \Log::error("Erreur modération OpenAI : " . $e->getMessage());
            return false; // Ne bloque pas en cas d'erreur serveur
        }
    }
}
