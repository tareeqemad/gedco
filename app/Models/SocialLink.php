<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model {
    protected $fillable = ['platform','icon_class','url','sort_order','is_active'];
}
