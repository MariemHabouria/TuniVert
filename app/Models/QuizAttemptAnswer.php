<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    protected $fillable = ['attempt_id','question_id','selected','is_correct'];

    public function attempt(){ return $this->belongsTo(QuizAttempt::class, 'attempt_id'); }
    public function question(){ return $this->belongsTo(QuizQuestion::class, 'question_id'); }
}
