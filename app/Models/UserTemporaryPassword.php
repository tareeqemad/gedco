<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class UserTemporaryPassword extends Model
{
    protected $fillable = ['user_id', 'password', 'expires_at', 'viewed'];

    protected $casts = [
        'expires_at' => 'datetime',
        'viewed' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * فك تشفير كلمة المرور
     */
    public function decryptPassword(): string
    {
        try {
            return Crypt::decryptString($this->password);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * تشفير وحفظ كلمة المرور
     */
    public static function storeForUser($userId, $plainPassword, $hoursUntilExpiry = 24): self
    {
        // حذف الكلمات القديمة للمستخدم
        self::where('user_id', $userId)->delete();

        return self::create([
            'user_id' => $userId,
            'password' => Crypt::encryptString($plainPassword),
            'expires_at' => now()->addHours($hoursUntilExpiry),
            'viewed' => false,
        ]);
    }
}
