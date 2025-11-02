<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Advertisement extends Model
{
    protected $table = 'advertisements';
    protected $primaryKey = 'ID_ADVER';
    public $incrementing = true;        // المفتاح أوتو (AUTO_INCREMENT)
    protected $keyType = 'int';
    public $timestamps = false;

    // لا تمرّر ID_ADVER مع الإنشاء — خليه يتولّد من الداتابيز
    protected $fillable = [
        'TITLE','TITLE_E','DATE_NEWS','BODY','BODY_E','PDF',
        'INSERT_USER','UPDATE_USER','INSERT_DATE','UPDATE_DATE','WORD','DATE_NEWS1',
    ];

    protected $casts = [
        'DATE_NEWS'   => 'datetime',
        'INSERT_DATE' => 'datetime',
        'UPDATE_DATE' => 'datetime',
        'DATE_NEWS1'  => 'datetime',
    ];

    public function getDateNewsForAdminAttribute(): ?string
    {
        return $this->DATE_NEWS
            ? Carbon::parse($this->DATE_NEWS)->timezone('Asia/Hebron')->format('Y-m-d H:i')
            : null;
    }
}
