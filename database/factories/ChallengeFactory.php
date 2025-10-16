<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IAProofValidator
{
    public static function analyze(string $filePath, string $categorie): array
    {
        $absolutePath = storage_path('app/public/' . $filePath);
        
        if (!file_exists($absolutePath)) {
            Log::error('Fichier introuvable pour analyse IA', ['path' => $absolutePath]);
            return ['valide' => false, 'explication' => 'Fichier introuvable'];
        }

        $mime = mime_content_type($absolutePath);
        $base64 = base64_encode(file_get_contents($absolutePath));

        $model = "microsoft/resnet-50";

        try {
            Log::info('Appel API HuggingFace', [
                'file' => $filePath,
                'categorie' => $categorie,
                'model' => $model
            ]);

            $response = Http::withToken(env('HUGGINGFACE_API_KEY'))
                ->timeout(60)
                ->post("https://api-inference.huggingface.co/models/$model", [
                    "inputs" => "data:$mime;base64,$base64"
                ]);

            if ($response->failed()) {
                Log::error('Erreur IA HuggingFace', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return ['valide' => false, 'explication' => 'Erreur de service IA'];
            }

            $data = $response->json();

            if (empty($data) || !is_array($data)) {
                Log::warning('Réponse IA vide ou invalide', ['data' => $data]);
                return ['valide' => false, 'explication' => 'Réponse IA invalide'];
            }

            $firstPrediction = $data[0] ?? null;
            
            if (!$firstPrediction || !isset($firstPrediction['label']) || !isset($firstPrediction['score'])) {
                Log::warning('Structure de prédiction IA invalide', ['data' => $data]);
                return ['valide' => false, 'explication' => 'Structure de prédiction invalide'];
            }

            $topPrediction = $firstPrediction['label'];
            $score = $firstPrediction['score'];

            Log::info('Prédiction IA extraite', [
                'prediction' => $topPrediction,
                'score' => $score
            ]);

            // CATÉGORIES ALIGNÉES AVEC LA FACTORY
            $motsCles = match($categorie) {
                // Recyclage
                'Recyclage' => [
                    'recycle', 'trash', 'garbage', 'plastic', 'waste', 'bin', 
                    'bottle', 'can', 'recycling', 'rubbish', 'container',
                    'disposal', 'landfill', 'compost', 'sorting'
                ],
                
                // Énergie renouvelable
                'Énergie renouvelable' => [
                    'solar', 'wind', 'energy', 'panel', 'power', 'electric', 
                    'renewable', 'turbine', 'generator', 'battery', 'sustainable',
                    'clean energy', 'photovoltaic', 'windmill'
                ],
                
                // Nettoyage de quartier
                'Nettoyage de quartier' => [
                    'broom', 'clean', 'soap', 'mop', 'bucket', 'cleaning', 
                    'hygiene', 'brush', 'cleanup', 'sweep', 'street', 'park',
                    'public', 'community', 'volunteer', 'garbage', 'litter'
                ],
                
                // Sensibilisation environnementale
                'Sensibilisation environnementale' => [
                    'poster', 'crowd', 'people', 'sign', 'group', 'audience', 
                    'meeting', 'education', 'awareness', 'environment', 'eco', 
                    'nature', 'planet', 'bee', 'apiary', 'insect', 'pollination', 
                    'biodiversity', 'wildlife', 'sustainability', 'ecological', 
                    'green', 'earth', 'climate', 'conservation', 'presentation',
                    'conference', 'workshop', 'information'
                ],
                
                // Plantation d'arbres
                'Plantation d\'arbres' => [
                    'plant', 'tree', 'garden', 'leaf', 'flower', 'forest', 
                    'wood', 'green', 'planting', 'gardening', 'seedling',
                    'sapling', 'forestry', 'reforestation', 'nature', 'park',
                    'landscape', 'environment'
                ],
                
                default => [
                    'nature', 'environment', 'green', 'earth', 'eco', 
                    'sustainable', 'ecological', 'planet', 'wildlife',
                    'conservation', 'climate', 'biodiversity'
                ],
            };

            Log::info('Mots-clés recherchés', [
                'categorie' => $categorie,
                'mots_cles' => $motsCles
            ]);

            // Vérifier si la prédiction correspond aux mots-clés
            $predictionLower = strtolower($topPrediction);
            $valide = false;
            
            foreach ($motsCles as $mot) {
                if (str_contains($predictionLower, strtolower($mot))) {
                    $valide = true;
                    Log::info('Mot-clé trouvé', [
                        'mot_cle' => $mot,
                        'prediction' => $predictionLower
                    ]);
                    break;
                }
            }

            // Seuil de confiance ajustable
            $seuilMinimal = 0.3;

            $resultat = [
                'valide' => $valide && $score > $seuilMinimal,
                'score' => round($score, 3),
                'prediction' => $topPrediction,
                'explication' => "L'image semble représenter '$topPrediction' (confiance: " . round($score * 100, 1) . "%)",
                'mots_cles_recherches' => $motsCles,
                'seuil_atteint' => $score > $seuilMinimal,
                'mot_cle_trouve' => $valide
            ];

            Log::info('Résultat final IA', $resultat);

            return $resultat;

        } catch (\Throwable $e) {
            Log::error('Erreur IAProofValidator', [
                'error' => $e->getMessage(),
                'file' => $filePath,
                'categorie' => $categorie
            ]);
            return ['valide' => false, 'explication' => 'Service IA temporairement indisponible'];
        }
    }
}