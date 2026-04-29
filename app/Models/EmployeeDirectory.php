<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDirectory extends Model
{
    protected $table = 'employee_directory';

    protected $fillable = [
        'employee_number',
        'national_id',
        'full_name',
        'job_title',
        'mobile',
        'email',
        'spouse_name',
        'spouse_national_id',
        'family_members_count',
        'governorate',
        'employment_status',
    ];
}
