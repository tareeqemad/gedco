<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $table = 'social_links';

    protected $fillable = [
        'platform', 'icon_class', 'url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'bool',
        'sort_order' => 'int',
    ];


    public function scopeActive($q)  { return $q->where('is_active', 1); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order'); }
}
