<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationStatistic extends Model
{
    use HasFactory;

    protected $table = 'statistiques_donations';
    public $timestamps = false; // has own generation timestamp

    protected $fillable = [
        'evenement_id', 'montant_total', 'nb_recurrents', 'nb_ponctuels', 'date_generation'
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'date_generation' => 'datetime',
    ];
}
