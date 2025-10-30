<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    protected $table = 'why_choose_us';

    protected $fillable = ['badge','tagline','description','features','is_active'];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];
}
