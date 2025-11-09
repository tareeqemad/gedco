<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';
    protected $fillable = [
        'footer_title_ar', 'logo_white_path',

        'email','phone','address_ar','contact_email','contact_phone','contact_address',
    ];

    public function contactChannels()
    {
        return $this->hasMany(SiteContactChannel::class)->orderBy('position');
    }

    /** تنظيف رقم الهاتف قبل التخزين */
    public function setPhoneAttribute($value)
    {
        if ($value === null) { $this->attributes['phone'] = null; return; }
        // احذف المسافات وكل + ما عدا أول واحد
        $v = preg_replace('/\s+/', '', $value);
        $v = preg_replace('/(?!^)\+/', '', $v);
        $this->attributes['phone'] = $v;
    }

    /** صيغة عرض منسقة (للإظهار فقط) */
    public function getPhoneFormattedAttribute(): ?string
    {
        $v = $this->phone;
        if (!$v) return null;

        // نمط فلسطين: +970 59X XXX XXX
        if (str_starts_with($v, '+970') && preg_match('/^\+970(59\d)(\d{3})(\d{3})$/', $v, $m)) {
            return "+970 {$m[1]} {$m[2]} {$m[3]}";
        }

        // fallback: تقسيم كل 3 أرقام
        $clean = ltrim($v, '+');
        $chunked = trim(chunk_split($clean, 3, ' '));
        return ($v[0] === '+') ? '+'.$chunked : $chunked;
    }
}
