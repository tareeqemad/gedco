<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title','subtitle','button_text','button_url',
        'bg_image','bullets','sort_order','is_active'
    ];

    protected $casts = [
        'bullets'   => 'array',
        'is_active' => 'boolean',
    ];
}
