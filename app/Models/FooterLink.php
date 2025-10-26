<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model {
    protected $fillable = ['group','label_ar','route_name','url','sort_order','is_active'];
}
