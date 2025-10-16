<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'type', 'name', 'icon', 'icon_path', 'description', 'active', 'sort_order', 'meta',
        'color_primary', 'color_secondary', 'button_text', 'custom_form_fields', 'custom_css', 'instructions_html'
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
        'meta' => 'array',
        'custom_form_fields' => 'array',
    ];
}
