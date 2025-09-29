<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'nom', 'title', 'description', 'date_debut', 'date_fin', 
        'lieu', 'capacite_max', 'prix', 'image', 'status'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'prix' => 'decimal:2',
        'capacite_max' => 'integer',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'evenement_id');
    }
}
