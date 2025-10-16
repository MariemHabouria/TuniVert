<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';

    // On accepte formation_id ou quiz_id selon ton schéma
    protected $fillable = [
        'formation_id',
        'quiz_id',
        'user_id',
        'score',
        'total',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Présent si tu as une colonne formation_id sur quiz_attempts (Schéma A)
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    // Présent si tu as une table quizzes + colonne quiz_id sur quiz_attempts (Schéma B)
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
