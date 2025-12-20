<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Site\WhyChooseUsRequest;
use App\Models\WhyChooseUs;

class WhyChooseUsController extends Controller
{
    public function index()
    {
        $why = WhyChooseUs::first();
        return view('admin.site.why.index', compact('why'));
    }

    public function create()
    {
        $items = []; // [] of features for the form
        return view('admin.site.why.create', compact('items'));
    }

    public function store(WhyChooseUsRequest $request)
    {
        $data = $this->payload($request);
        WhyChooseUs::create($data);
        
        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();
        
        return redirect()->route('admin.why.index')->with('success','تم الإنشاء بنجاح.');
    }

    public function edit(WhyChooseUs $why)
    {
        $items = $why->features ?? [];
        return view('admin.site.why.edit', compact('why','items'));
    }

    public function update(WhyChooseUsRequest $request, WhyChooseUs $why)
    {
        $why->update($this->payload($request));
        
        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();
        
        return redirect()->route('admin.why.index')->with('success','تم التحديث بنجاح.');
    }

    public function destroy(WhyChooseUs $why)
    {
        $why->delete();
        
        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();
        
        return back()->with('success','تم الحذف.');
    }

    private function payload(WhyChooseUsRequest $request): array
    {
        $v = $request->validated();

        $titles = $v['feature_title'] ?? [];
        $texts  = $v['feature_text']  ?? [];
        $titlesEn = $v['feature_title_en'] ?? [];
        $textsEn  = $v['feature_text_en']  ?? [];
        $icons  = $v['feature_icon']  ?? [];

        $features = [];
        $count = max(count($titles), count($texts), count($titlesEn), count($textsEn), count($icons));
        for ($i=0; $i<$count; $i++) {
            $t = trim($titles[$i] ?? '');
            $p = trim($texts[$i] ?? '');
            $tEn = trim($titlesEn[$i] ?? '');
            $pEn = trim($textsEn[$i] ?? '');
            $ic = trim($icons[$i] ?? '');
            if ($t === '' && $p === '' && $tEn === '' && $pEn === '' && $ic === '') continue;
            $features[] = [
                'title' => $t, 
                'text' => $p,
                'title_en' => $tEn ?: null,
                'text_en' => $pEn ?: null,
                'icon' => $ic ?: 'bi bi-lightning-charge-fill'
            ];
        }

        return [
            'badge'          => $v['badge'],
            'badge_en'       => $v['badge_en'] ?? null,
            'tagline'        => $v['tagline'],
            'tagline_en'     => $v['tagline_en'] ?? null,
            'description'    => $v['description'] ?? null,
            'description_en' => $v['description_en'] ?? null,
            'features'       => $features,
            'is_active'      => (bool)($v['is_active'] ?? true),
        ];
    }
}
