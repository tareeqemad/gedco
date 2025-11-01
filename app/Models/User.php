<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // 👈 فعّل HasRoles


    // accessor للصورة
    public function getAvatarUrlAttribute(): string
    {
        $v = $this->avatar;

        // لو جت كمصفوفة بالخطأ من الفورم
        if (is_array($v)) {
            $v = reset($v) ?: '';
        }
        $v = (string) ($v ?? '');

        // لا يوجد صورة => الافتراضية من public/assets/...
        if ($v === '') {
            return asset('assets/admin/images/profile/profile.png');
        }

        // لو رابط مباشر
        if (str_starts_with($v, 'http://') || str_starts_with($v, 'https://')) {
            return $v;
        }

        // لو من public مباشرة
        if (str_starts_with($v, 'assets/') || str_starts_with($v, 'public/')) {
            return asset($v);
        }

        // لو المخزن بدأ بـ storage/
        if (str_starts_with($v, 'storage/')) {
            return asset($v);
        }

        // الحالة المثالية: مخزّن على disk public مثل avatars/xxx.jpg
        return asset('storage/'.$v);
    }

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
