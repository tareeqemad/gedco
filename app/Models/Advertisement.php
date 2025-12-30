<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Advertisement extends Model
{
    protected $table = 'advertisements';
    protected $primaryKey = 'ID_ADVER';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'TITLE','TITLE_E','DATE_NEWS','BODY','BODY_E','PDF',
        'INSERT_USER','UPDATE_USER','INSERT_DATE','UPDATE_DATE','WORD','DATE_NEWS1',
    ];

    protected $casts = [
        'DATE_NEWS'   => 'datetime',
        'INSERT_DATE' => 'datetime',
        'UPDATE_DATE' => 'datetime', // مهم!
        'DATE_NEWS1'  => 'datetime',
    ];

    public function getDateNewsForAdminAttribute(): ?string
    {
        return $this->DATE_NEWS
            ? Carbon::parse($this->DATE_NEWS)->timezone('Asia/Hebron')->format('Y-m-d H:i')
            : null;
    }

    /**
     * استخراج الصورة من محتوى الإعلان (BODY أو BODY_E)
     * Extract image from advertisement content (BODY or BODY_E)
     */
    public function getImageAttribute(): ?string
    {
        // جرب أولاً BODY (العربي)، ثم BODY_E (الإنجليزية)
        // Try BODY first (Arabic), then BODY_E (English)
        $content = $this->BODY ?: $this->BODY_E;
        
        if (!$content) {
            return null;
        }

        // البحث عن أول صورة في المحتوى
        // Search for first image in content
        preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);
        
        if (!empty($matches[1])) {
            $imageUrl = $matches[1];
            // إذا كانت الصورة من storage، أعد المسار الكامل
            // If image is from storage, return full path
            if (strpos($imageUrl, 'storage/') !== false || strpos($imageUrl, 'advertisements/') !== false) {
                return $imageUrl;
            }
            // إذا كانت base64 أو URL خارجي، أعدها كما هي
            // If base64 or external URL, return as is
            return $imageUrl;
        }

        return null;
    }
}
