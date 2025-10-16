<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = ['quiz_id','question','choices','answer','explanation','order'];
    protected $casts = ['choices'=>'array'];

    public function quiz(){ return $this->belongsTo(Quiz::class); }
}
