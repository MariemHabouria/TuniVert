<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CommentSentimentService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.huggingface.api_key');
        $this->model = config('services.huggingface.model');
    }

    public function analyseSentiment(string $texte): ?string
    {
        if (!$this->apiKey || !$this->model) {
            \Log::info("HF_API_KEY ou HF_MODEL non défini, utilisation du fallback 'en_attente'. API Key exists: " . ($this->apiKey ? 'YES' : 'NO') . ", Model exists: " . ($this->model ? 'YES' : 'NO'));
            return 'en_attente'; // Indique que l'analyse est en attente de configuration
        }

        try {
            $response = Http::withoutVerifying()->timeout(30)->withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("https://api-inference.huggingface.co/models/{$this->model}", [
                'inputs' => $texte,
            ]);

            $data = $response->json();

            // 🔹 Log pour debug
            \Log::info("Réponse Hugging Face - Status: " . $response->status() . ", Body: " . json_encode($data));

            // Vérifier si le modèle est en cours de chargement
            if (isset($data['error']) && str_contains($data['error'], 'loading')) {
                \Log::info("Modèle Hugging Face en cours de chargement.");
                return 'en_attente';
            }

            if (!isset($data[0]) || !is_array($data[0])) {
                \Log::warning("Structure de réponse HF inattendue, analyse en attente. Data: " . json_encode($data));
                return 'en_attente'; // Structure inattendue, probablement encore en chargement
            }

            // Trouver le label avec le score le plus élevé
            $scores = collect($data[0]);
            $max = $scores->sortByDesc('score')->first();
            $label = $max['label'] ?? null;

            if (!$label) return null;

            // Convertir en positif / neutre / négatif
            if (Str::startsWith($label, ['5', '4'])) return 'positif';
            if (Str::startsWith($label, '3')) return 'neutre';
            if (Str::startsWith($label, ['2', '1'])) return 'negatif';

            return 'en_attente'; // fallback pour analyse en attente
        } catch (\Exception $e) {
            \Log::error("Erreur Hugging Face : " . $e->getMessage());
            
            // Fallback simple basé sur des mots-clés si l'API échoue
            return $this->fallbackSentimentAnalysis($texte);
        }
    }

    /**
     * Analyse de sentiment simple basée sur des mots-clés comme fallback
     */
    private function fallbackSentimentAnalysis(string $texte): string
    {
        $texte = mb_strtolower($texte);
        
        $positive = ['super', 'excellent', 'fantastique', 'génial', 'parfait', 'formidable', 'merveilleux', 'très bien', 'bravo', 'magnifique'];
        $negative = ['nul', 'horrible', 'terrible', 'mauvais', 'décevant', 'affreux', 'catastrophe', 'inadmissible'];
        
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($positive as $word) {
            if (mb_strpos($texte, $word) !== false) {
                $positiveCount++;
            }
        }
        
        foreach ($negative as $word) {
            if (mb_strpos($texte, $word) !== false) {
                $negativeCount++;
            }
        }
        
        if ($positiveCount > $negativeCount) {
            return 'positif';
        } elseif ($negativeCount > $positiveCount) {
            return 'negatif';
        } else {
            return 'neutre';
        }
    }
}