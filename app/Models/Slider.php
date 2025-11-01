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

    public function getBgImageUrlAttribute(): string
    {
        $img = $this->bg_image;

        // إذا الحقل مصفوفة (زي لما يجي من الفورم upload)
        if (is_array($img)) {
            $img = reset($img); // خذ أول قيمة
        }

        // لو الحقل فاضي أو null
        if (empty($img) || !is_string($img)) {
            return asset('assets/site/images/placeholder.webp');
        }

        // الآن نتحقق من المسار
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }
        if (str_starts_with($img, 'assets/') || str_starts_with($img, 'public/')) {
            return asset($img);
        }
        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }

        // الحالة الافتراضية: موجود داخل storage/app/public
        return asset('storage/' . $img);
    }

    public function getBulletsArrayAttribute(): array
    {
        $val = $this->attributes['bullets'] ?? null;

        // نتأكد إنها ليست string فيها JSON
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            return is_array($decoded) ? $decoded : [];
        }

        // إذا Laravel cast عمل array خلاص نرجعها
        return is_array($val) ? $val : [];
    }
}
