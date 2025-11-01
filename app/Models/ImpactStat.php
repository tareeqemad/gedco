<?php

// app/Models/ImpactStat.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpactStat extends Model
{
    protected $fillable = ['title_ar', 'amount_usd', 'sort_order', 'is_active'];
    protected $casts = ['amount_usd' => 'float', 'is_active' => 'boolean'];


    // رقم مخصص للـ data-to (بالملايين)
    public function getMillionsForCounterAttribute(): string
    {
        $m = $this->amount_usd / 1_000_000;
        // منزلة واحدة إذا فيه كسور، وإلا رقم صحيح
        return fmod($m, 1.0) == 0.0 ? number_format($m, 0) : number_format($m, 1, '.', '');
    }

    // Scope جاهز
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

}
