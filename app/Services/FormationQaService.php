<?php

// app/Services/FormationQaService.php
namespace App\Services;

use App\Models\Formation;
use Illuminate\Support\Str;

class FormationQaService
{
    /**
     * Retourne une réponse à partir du contenu de la formation (sans LLM).
     * Recherche naïve mots-clés + sélection d’extraits pertinents.
     */
    public function answerFromLocalContext(Formation $formation, string $question): ?string
    {
        $haystacks = [];

        // 1) Contenu principal
        foreach (['title','titre','description','objectif','objectifs','programme','contenu'] as $field) {
            if (!empty($formation->{$field})) {
                $haystacks[] = $formation->{$field};
            }
        }

        // 2) Champs usuels
        foreach (['prerequis','public','duree','debut','fin','lieu','format'] as $field) {
            if (!empty($formation->{$field})) {
                $haystacks[] = ucfirst($field).": ".$formation->{$field};
            }
        }

        // 3) Ressources associées (si relation existe: $formation->ressources)
        if (method_exists($formation, 'ressources')) {
            foreach ($formation->ressources as $r) {
                $haystacks[] = trim(($r->titre ?? '')."\n".($r->description ?? '')."\n".($r->url ?? ''));
            }
        }

        // Scoring très simple: compter occurrences des mots de la question
        $qTokens = collect(preg_split('/\W+/u', Str::lower($question), -1, PREG_SPLIT_NO_EMPTY))
            ->reject(fn($t) => mb_strlen($t) < 3);

        $scored = collect($haystacks)->map(function ($txt) use ($qTokens) {
            $ltxt = Str::lower($txt);
            $score = 0;
            foreach ($qTokens as $t) {
                $score += substr_count($ltxt, $t);
            }
            return ['text'=>$txt, 'score'=>$score];
        })->sortByDesc('score')->values();

        if ($scored->isEmpty() || $scored->first()['score'] === 0) {
            return null; // rien de pertinent
        }

        // Prendre 2-3 meilleurs extraits et formater
        $top = $scored->take(3)->pluck('text')->implode("\n\n—\n\n");

        return "Voici ce que je peux te dire d’après la fiche de cette formation :\n\n".$top;
    }
}
 