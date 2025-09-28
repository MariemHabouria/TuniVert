<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RessourceFormation extends Model
{
    use HasFactory;

    protected $table = 'ressources_formations';

    protected $fillable = ['formation_id','titre','type','url'];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

public function urlPublic(): ?string {
    return $this->path ? \Illuminate\Support\Facades\Storage::url($this->path) : ($this->url ?: null);
}

}



