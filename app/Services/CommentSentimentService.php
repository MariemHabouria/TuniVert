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
        $this->apiKey = env('HF_API_KEY');
        $this->model = env('HF_MODEL');
    }

    public function analyseSentiment(string $texte): ?string
    {
        if (!$this->apiKey || !$this->model) {
            \Log::error("HF_API_KEY ou HF_MODEL non défini.");
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("https://api-inference.huggingface.co/models/{$this->model}", [
                'inputs' => $texte,
            ]);

            $data = $response->json();

            // 🔹 Log pour debug
            \Log::info("Réponse Hugging Face brute : " . json_encode($data));

            if (!isset($data[0]) || !is_array($data[0])) {
                return null; // Structure inattendue
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

            return null; // fallback
        } catch (\Exception $e) {
            \Log::error("Erreur Hugging Face : " . $e->getMessage());
            return null;
        }
    }
}
