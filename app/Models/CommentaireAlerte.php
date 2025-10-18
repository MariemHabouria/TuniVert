<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentaireAlerte extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenu',
        'alerte_id', // ASSUREZ-VOUS QUE C'EST INCLUS
        'user_id'
    ];

    public function alerte()
    {
        return $this->belongsTo(AlerteForum::class, 'alerte_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}