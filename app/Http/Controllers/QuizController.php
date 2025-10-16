<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Vue principale (affiche quiz s’il existe, bouton générer sinon)
    public function show(Formation $formation)
    {
        $quiz = Quiz::where('formation_id',$formation->id)
            ->with('questions')
            ->first();

        return view('formations.quiz', compact('formation','quiz'));
    }

    // Génération via LLM (organisateur uniquement)
    public function generate(Formation $formation, Request $request)
    {
        $this->authorizeOrOwner($formation); // simple garde

        $data = $request->validate([
            'question_count' => 'nullable|integer|min:5|max:20',
            'difficulty'     => 'nullable|integer|min:1|max:3',
        ]);
        $count = $data['question_count'] ?? 10;
        $difficulty = $data['difficulty'] ?? 2;

        // crée (ou remplace) le quiz
        $quiz = Quiz::updateOrCreate(
            ['formation_id'=>$formation->id],
            ['title'=>'Quiz de la formation', 'question_count'=>$count, 'difficulty'=>$difficulty]
        );
        // purge anciennes questions
        $quiz->questions()->delete();

        // prompt IA -> JSON
        $json = $this->generateQuestionsWithLLM($formation, $count, $difficulty);

        // sécurité : structure attendue
        if (!isset($json['questions']) || !is_array($json['questions']) || empty($json['questions'])) {
            return back()->with('status', "⚠️ Échec de génération IA. Réessaie.");
        }

        $order=1;
        foreach ($json['questions'] as $q) {
            // normalisation
            $choices = array_values($q['choices'] ?? []);
            if (count($choices) < 2) continue;

            QuizQuestion::create([
                'quiz_id'     => $quiz->id,
                'question'    => (string)($q['question'] ?? ''),
                'choices'     => $choices,
                'answer'      => (int)($q['answer_index'] ?? 0),
                'explanation' => $q['explanation'] ?? null,
                'order'       => $order++,
            ]);
        }

        return redirect()->route('quiz.show', $formation)->with('status','✅ Quiz généré.');
    }

    // Soumission du quiz
    public function submit(Formation $formation, Request $request)
    {
        $quiz = Quiz::where('formation_id',$formation->id)->with('questions')->firstOrFail();

        $data = $request->validate([
            'answers' => 'required|array', // [question_id => selectedIndex]
        ]);

        $answers = $data['answers'];
        $total   = $quiz->questions->count();
        $correct = 0;

        $attempt = QuizAttempt::create([
             'formation_id' => $formation->id,
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'score'   => 0, 'total' => $total,
        ]);

        foreach ($quiz->questions as $q) {
            $sel = isset($answers[$q->id]) ? (int)$answers[$q->id] : null;
            $ok  = ($sel !== null) && ($sel === (int)$q->answer);
            if ($ok) $correct++;

            QuizAttemptAnswer::create([
                'attempt_id'  => $attempt->id,
                'question_id' => $q->id,
                'selected'    => $sel,
                'is_correct'  => $ok,
            ]);
        }

        $score = $total ? (int) round($correct * 100 / $total) : 0;
        $attempt->update(['score'=>$score]);

        return redirect()->route('quiz.show', $formation)
            ->with('quiz_result', [
                'score' => $score,
                'correct' => $correct,
                'total' => $total,
                'attempt_id' => $attempt->id,
            ]);
    }

    // Historique utilisateur
    public function history(Formation $formation)
    {
        $quiz = Quiz::where('formation_id',$formation->id)->firstOrFail();
        $attempts = QuizAttempt::where('quiz_id',$quiz->id)
            ->where('user_id', auth()->id())
            ->orderByDesc('id')->limit(10)->get();

        return response()->json(['attempts'=>$attempts]);
    }

    /* ------------------ Helpers ------------------ */

    private function authorizeOrOwner(Formation $formation): void
    {
        // autorise si user est l’organisateur
        if (!auth()->check() || auth()->id() !== (int)($formation->organisateur_id)) {
            abort(403);
        }
    }

    // Appel LLM qui retourne un JSON structuré
    private function generateQuestionsWithLLM(Formation $formation, int $count, int $difficulty): array
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        $model  = config('services.openai.model', env('OPENAI_MODEL', 'gpt-4o-mini'));

        $context = trim(implode("\n", array_filter([
            "Titre: ".($formation->titre ?? ''),
            "Type: ".($formation->type ?? ''),
            "Description: ".($formation->description ?? ''),
            "Objectifs (si connus): ".(method_exists($formation,'objectifs') ? implode(', ', (array)$formation->objectifs) : ''),
        ])));

        $system = "Tu es un générateur de quiz en français. 
Retourne STRICTEMENT un JSON valide (pas de texte avant/après).
Chaque question doit avoir:
- 'question' (string)
- 'choices' (array de 3 à 5 propositions courtes)
- 'answer_index' (index entier de la bonne réponse dans 'choices')
- 'explanation' (phrase courte expliquant la réponse).
Questions adaptées au niveau {$difficulty} (1 facile, 2 intermédiaire, 3 avancé).";

        $user = "Génère $count questions QCM à partir du contexte de la formation.
Contexte:
$context

Format attendu:
{
  \"questions\": [
    {
      \"question\": \"...\",
      \"choices\": [\"...\",\"...\",\"...\",\"...\"],
      \"answer_index\": 1,
      \"explanation\": \"...\"
    }
  ]
}";

        try{
            if (!$apiKey) throw new \Exception('missing api key');

            $client = \OpenAI::client($apiKey);
            $res = $client->chat()->create([
                'model' => $model,
                'messages' => [
                    ['role'=>'system','content'=>$system],
                    ['role'=>'user','content'=>$user],
                ],
                'temperature' => 0.3,
                'response_format' => ['type'=>'json_object'], // force JSON
            ]);

            $json = json_decode($res->choices[0]->message->content ?? 'null', true);
            return is_array($json) ? $json : [];
        } catch (\Throwable $e) {
            \Log::error('Quiz LLM error: '.$e->getMessage());
            // Fallback minimal si l’IA échoue
            return [
                'questions' => [[
                    'question' => "Cette formation porte sur quel thème principal ?",
                    'choices' => ['Sujet A','Sujet B','Sujet C','Sujet D'],
                    'answer_index' => 0,
                    'explanation' => "Réponse factice (fallback).",
                ]]
            ];
        }
    }
}
