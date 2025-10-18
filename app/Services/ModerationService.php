<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Exception;
use Illuminate\Support\Facades\Log;

class ModerationService
{
    public static function analyseTexte($texte)
    {
        // Nettoyer le texte
        $texteNettoye = self::nettoyerTexte($texte);
        
        if (empty($texteNettoye)) {
            return [
                'flagged' => false,
                'categories' => [],
                'score' => 0,
                'reason' => 'Texte vide'
            ];
        }

        try {
            // Utiliser l'API de modération OpenAI
            $response = OpenAI::moderations()->create([
                'model' => 'omni-moderation-latest',
                'input' => $texteNettoye,
            ]);

            $result = $response->results[0];
            
            return [
                'flagged' => $result->flagged,
                'categories' => $result->categories,
                'category_scores' => $result->category_scores,
                'score' => max((array)$result->category_scores) ?? 0,
                'reason' => self::getReason($result->categories, $result->category_scores)
            ];
            
        } catch (Exception $e) {
            Log::error('Erreur modération OpenAI: ' . $e->getMessage());
            
            // Fallback vers une analyse basique si l'API échoue
            return self::fallbackModeration($texteNettoye);
        }
    }

    private static function nettoyerTexte($texte)
    {
        // Supprimer les espaces multiples
        $texte = preg_replace('/\s+/', ' ', $texte);
        // Supprimer les caractères spéciaux dangereux mais garder le texte
        $texte = strip_tags($texte);
        // Limiter la longueur pour l'API
        return substr(trim($texte), 0, 2000);
    }

    private static function getReason($categories, $scores)
    {
        $reasons = [];
        foreach ($categories as $category => $flagged) {
            if ($flagged) {
                $score = $scores->$category ?? 0;
                $reasons[] = "$category (score: " . round($score * 100, 1) . "%)";
            }
        }
        
        return empty($reasons) ? 'Aucun problème détecté' : implode(', ', $reasons);
    }

    private static function fallbackModeration($texte)
    {
        // Fallback basique uniquement si l'API échoue
        $bannedWords = [
            'insulte', 'raciste', 'haine', 'violence', 'harcèlement',
            'spam', 'arnaque', 'fraude', 'discrimination'
        ];
        
        $lowerText = strtolower($texte);
        $flagged = false;
        $categories = [];
        
        foreach ($bannedWords as $word) {
            if (str_contains($lowerText, $word)) {
                $flagged = true;
                $categories['harassment'] = true;
                break;
            }
        }
        
        return [
            'flagged' => $flagged,
            'categories' => $categories,
            'score' => $flagged ? 0.8 : 0,
            'reason' => $flagged ? 'Mot interdit détecté' : 'Aucun problème détecté'
        ];
    }
    
    public static function shouldBlockComment($texte, $seuil = 0.7)
    {
        $result = self::analyseTexte($texte);
        
        // Bloquer si flagged OU si le score dépasse le seuil
        return $result['flagged'] || $result['score'] > $seuil;
    }
}