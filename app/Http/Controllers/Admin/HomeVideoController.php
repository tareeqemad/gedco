<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use Illuminate\Http\Request;

class HomeVideoController extends Controller
{
    public function edit()
    {
        return view('admin.site.video', [
            'enabled' => (bool) SiteConfig::get('home_video_enabled', 0),
            'videoId' => SiteConfig::get('home_video_id', ''),
            'caption' => SiteConfig::get('home_video_caption', 'شاهد فيديو تعريفي عن خدماتنا')
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'video_url' => ['nullable','string','max:255'],
            'caption'   => ['nullable','string','max:255'],
            'enabled'   => ['nullable','boolean'],
        ]);

        $enabled = (bool) ($data['enabled'] ?? 0);
        $caption = $data['caption'] ?? '';

        // استخراج ID
        $videoId = $this->extractYoutubeId($data['video_url'] ?? '') ?? '';

        SiteConfig::set('home_video_enabled', $enabled ? 1 : 0);
        SiteConfig::set('home_video_id', $videoId);
        SiteConfig::set('home_video_caption', $caption);
        
        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();

        return back()->with('success', 'تم تحديث فيديو الصفحة الرئيسية بنجاح');
    }

    private function extractYoutubeId(?string $url): ?string
    {
        if (!$url) return null;
        if (preg_match('/^[A-Za-z0-9_-]{11}$/',$url)) return $url;

        foreach ([
                     '/youtu\.be\/([A-Za-z0-9_-]{11})/i',
                     '/[?&]v=([A-Za-z0-9_-]{11})/i',
                     '/embed\/([A-Za-z0-9_-]{11})/i',
                     '/shorts\/([A-Za-z0-9_-]{11})/i',
                 ] as $p) if (preg_match($p,$url,$m)) return $m[1];

        return null;
    }
}
