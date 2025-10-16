<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormationChatMessage extends Model
{
    protected $fillable = [
        'formation_id',
        'user_id',
        'role',           // 'user' | 'assistant' | 'system'
        'content',
        'feedback',       // -1, 0, 1
        'feedback_reason'
    ];

    public function formation()
    {
        return $this->belongsTo(\App\Models\Formation::class, 'formation_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
