<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    protected $table = 'why_choose_us';

    protected $fillable = ['badge','badge_en','tagline','tagline_en','description','description_en','features','is_active'];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];
}
