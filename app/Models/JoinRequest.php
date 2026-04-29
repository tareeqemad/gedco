<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    public const SOURCES = [
        'friend_employee',
        'social_media',
        'website',
        'advertisement',
        'other',
    ];

    protected $fillable = [
        'applicant_name',
        'applicant_phone',
        'applicant_email',
        'company_name',
        'source',
        'referrer_name',
        'locale',
        'ip_address',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sourceLabel(): string
    {
        return __('admin.join_requests.sources.' . $this->source);
    }
}
