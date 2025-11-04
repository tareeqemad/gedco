<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteConfig;

class HomeVideoSeeder extends Seeder
{
    public function run(): void
    {

        $videoId = '02WimCJ02V8';

        SiteConfig::set('home_video_enabled', 1);
        SiteConfig::set('home_video_id', $videoId);
        SiteConfig::set('home_video_caption', 'شاهد فيديو تعريفي عن خدماتنا');
    }
}
