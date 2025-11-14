<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class StaffProfile extends Model
{
    protected $fillable = [
        'full_name','birth_date','employee_number','national_id','job_title','location',
        'department','directorate','section','marital_status','family_members_count',
        'has_family_incidents','family_notes','original_address','house_status','status',
        'current_address','housing_type','mobile','mobile_alt','whatsapp','telegram','gmail',
        'readiness','readiness_notes',
        // إدارة التعديل
        'password_hash','edits_allowed','edits_remaining','last_edited_at',
    ];

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
        'last_edited_at'   => 'datetime',
        'edits_allowed'    => 'integer',
        'edits_remaining'  => 'integer',
    ];

    /* ==========================
     | العلاقات
     | ========================== */
    public function dependents(): HasMany
    {
        return $this->hasMany(StaffDependent::class);
    }

    /* ==========================
     | كلمة المرور والتحكم بالتعديل
     | ========================== */

    /**
     * التحقق من كلمة المرور عند التعديل.
     */
    public function verifyPassword(string $password): bool
    {
        if (empty($this->password_hash)) {
            return false;
        }
        return Hash::check($password, $this->password_hash);
    }

    /**
     * تخزين كلمة مرور جديدة (مشفّرة).
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Hash::make($password);
        $this->save();
    }

    /**
     * هل ما زال مسموحًا له التعديل؟
     */
    public function canEdit(): bool
    {
        return (int) $this->edits_remaining > 0;
    }

    /**
     * خصم محاولة تعديل واحدة وتحديث وقت آخر تعديل.
     */
    public function decrementEdit(): void
    {
        if ($this->edits_remaining > 0) {
            // decrement يحفظ الحقل، لكن نحتاج أيضًا تحديث last_edited_at
            $this->decrement('edits_remaining');
            $this->last_edited_at = now();
            $this->save();
        }
    }

    /* ==========================
     | مُساعِدات اختيارية (للاستخدام السريع)
     | ========================== */

    /**
     * إعادة ضبط عداد التعديلات (مثلاً من لوحة التحكم).
     */
    public function resetEdits(int $allowed): void
    {
        $this->edits_allowed   = max(0, $allowed);
        $this->edits_remaining = max(0, $allowed);
        $this->save();
    }
}
