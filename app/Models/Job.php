<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job_listings';
    protected $fillable = [
        'title','slug','image','link','description','sort','is_active'
    ];
}
