<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffDependent extends Model
{
    protected $fillable = [
        'staff_profile_id',
        'name',
        'relation',
        'birth_date',
        'is_student',
    ];

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
        'is_student' => 'boolean',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'staff_profile_id');
    }


}
