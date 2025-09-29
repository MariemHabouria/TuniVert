<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';
    public const CREATED_AT = 'date_creation';
    public const UPDATED_AT = 'date_mise_a_jour';

    protected $fillable = [
        'utilisateur_id', 'is_anonymous', 'evenement_id', 'montant',
        'moyen_paiement', 'transaction_id', 'date_don',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_don' => 'datetime',
        'is_anonymous' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'evenement_id');
    }

}
