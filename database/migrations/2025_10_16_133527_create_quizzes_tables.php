<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Quiz par formation
        Schema::create('quizzes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $t->string('title')->default('Quiz de la formation');
            $t->unsignedTinyInteger('difficulty')->default(2); // 1..3
            $t->unsignedSmallInteger('question_count')->default(10);
            $t->json('meta')->nullable();
            $t->timestamps();
        });

        // Questions
        Schema::create('quiz_questions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $t->text('question');
            $t->json('choices');              // ["A","B","C","D"]
            $t->unsignedTinyInteger('answer'); // index correct 0..3
            $t->text('explanation')->nullable();
            $t->unsignedSmallInteger('order')->default(0);
            $t->timestamps();
        });

        // Tentatives utilisateur
        Schema::create('quiz_attempts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->unsignedSmallInteger('score')->default(0); // sur 100
            $t->unsignedSmallInteger('total')->default(0); // nb questions
            $t->timestamps();
        });

        // Réponses d’une tentative
        Schema::create('quiz_attempt_answers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
            $t->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $t->unsignedTinyInteger('selected')->nullable(); // 0..3 ou null
            $t->boolean('is_correct')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
