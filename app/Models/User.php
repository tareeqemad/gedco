<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'name_en', 'email', 'password', 'avatar',
    ];

    /**
     * Get the display name based on current locale.
     * Returns name_en in LTR mode, name (Arabic) in RTL mode.
     * Falls back to name if name_en is empty.
     */
    public function getDisplayNameAttribute(): string
    {
        $direction = session('direction', 'rtl');
        if ($direction === 'ltr' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name;
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    // أجمد accessor في التاريخ
    public function getAvatarUrlAttribute(): string
    {
        if (empty($this->avatar)) {
            return asset('assets/admin/images/profile/profile.png');
        }

        // لو رابط خارجي
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        // الحالة الطبيعية: avatars/xxx.jpg
        return Storage::url($this->avatar);
    }

    /**
     * العلاقة مع كلمات المرور المؤقتة
     */
    public function temporaryPasswords()
    {
        return $this->hasMany(UserTemporaryPassword::class)->orderBy('created_at', 'desc');
    }

    /**
     * الحصول على آخر كلمة مرور مؤقتة غير منتهية
     */
    public function getLatestTemporaryPassword()
    {
        return $this->temporaryPasswords()
            ->where('expires_at', '>', now())
            ->where('viewed', false)
            ->first();
    }
}
