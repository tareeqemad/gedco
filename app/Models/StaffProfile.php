<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffProfile extends Model
{
    protected $fillable = [
        'full_name','birth_date','employee_number','national_id','job_title','location',
        'department','directorate','section','marital_status','family_members_count',
        'has_family_incidents','family_notes','original_address','house_status','status',
        'current_address','housing_type','mobile','mobile_alt','whatsapp','telegram','gmail',
        'readiness','readiness_notes'
    ];

    public function dependents(): HasMany
    {
        return $this->hasMany(StaffDependent::class);
    }
}
