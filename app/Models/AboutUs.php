<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';

    protected $fillable = [
        'title',          // title_ar (العربي)
        'subtitle',       // subtitle_ar
        'paragraph1',     // paragraph1_ar
        'paragraph2',     // paragraph2_ar
        'title_en',       // العنوان الإنجليزي
        'subtitle_en',    // العبارة الإنجليزية
        'paragraph1_en',  // الفقرة الأولى الإنجليزية
        'paragraph2_en',  // الفقرة الثانية الإنجليزية
        'features',       // Features العربية (JSON)
        'features_en',    // Features الإنجليزية (JSON)
        'image',        // الصورة العربية
        'image_en',     // الصورة الإنجليزية
        'language',
    ];

     protected $casts = [
        'features' => 'array',
        'features_en' => 'array',
    ];

    /**
     * Get AboutUs record (there should be only one record now).
     * Language scope kept for backward compatibility but returns first record.
     */
    public function scopeLanguage($query, ?string $lang = null)
    {
        // الآن نرجع أول سجل (يجب أن يكون سجل واحد فقط)
        return $query;
    }
}
