<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContactChannel extends Model
{
    protected $table = 'site_contact_channels';
    protected $fillable = [
        'site_setting_id', 'position', 'label', 'email', 'phone', 'address_ar'
    ];

    public function setting()
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }
}
