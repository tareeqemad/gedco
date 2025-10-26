<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model {
    protected $fillable = [
        'footer_title_ar',
        'logo_white_path',
        'contact_email',
        'contact_phone',
        'contact_address',
    ];
}
