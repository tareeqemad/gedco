<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $table = 'tenders';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'mnews_id','column_name_1','old_value_1','new_value_1',
        'the_date_1','event_1','the_user_1','coulm_serial'
    ];
}
