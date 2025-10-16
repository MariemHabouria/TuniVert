<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['formation_id','title','difficulty','question_count','meta'];
    protected $casts = ['meta'=>'array'];

    public function formation(){ return $this->belongsTo(Formation::class); }
    public function questions(){ return $this->hasMany(QuizQuestion::class)->orderBy('order'); }
    public function attempts(){ return $this->hasMany(QuizAttempt::class); }
}
