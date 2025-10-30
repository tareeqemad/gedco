<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title','subtitle','button_text','button_url',
        'bg_image','bullets','sort_order','is_active'
    ];

    protected $casts = [
        'bullets'   => 'array',
        'is_active' => 'boolean',
    ];

    public function getBgImageUrlAttribute(): string
    {
        $img = (string) $this->bg_image;

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }
        if (str_starts_with($img, 'assets/') || str_starts_with($img, 'public/')) {
            return asset($img);
        }
        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }
        return asset('storage/'.$img);
    }

    public function getBulletsArrayAttribute(): array
    {
        if (is_array($this->bullets)) return $this->bullets;
        $arr = json_decode($this->bullets ?? '[]', true);
        return is_array($arr) ? $arr : [];
    }

}
