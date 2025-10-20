<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RessourceFormation extends Model
{
    use HasFactory;

    protected $table = 'ressources_formations';

    protected $fillable = ['formation_id','titre','type','url','path'];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

public function urlPublic(): ?string {
    // If we have a stored path, generate URL from it (most reliable)
    if ($this->path) {
        // For your setup, we'll use a relative URL that works with your current port
        return '/storage/' . $this->path;
    }
    
    // Otherwise, use the stored URL (for external links or legacy data)
    return $this->url ?: null;
}

}



