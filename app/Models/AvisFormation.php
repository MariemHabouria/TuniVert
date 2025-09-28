<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvisFormation extends Model
{
    protected $fillable = ['formation_id','user_id','note','commentaire'];

    public function formation(){ return $this->belongsTo(Formation::class); }
    public function user(){ return $this->belongsTo(User::class); }
}