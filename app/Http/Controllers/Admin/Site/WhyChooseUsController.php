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
        return redirect()->route('admin.why.index')->with('success','تم التحديث بنجاح.');
    }

    public function destroy(WhyChooseUs $why)
    {
        $why->delete();
        return back()->with('success','تم الحذف.');
    }

    private function payload(WhyChooseUsRequest $request): array
    {
        $v = $request->validated();

        $titles = $v['feature_title'] ?? [];
        $texts  = $v['feature_text']  ?? [];
        $icons  = $v['feature_icon']  ?? [];

        $features = [];
        $count = max(count($titles), count($texts), count($icons));
        for ($i=0; $i<$count; $i++) {
            $t = trim($titles[$i] ?? '');
            $p = trim($texts[$i] ?? '');
            $ic = trim($icons[$i] ?? '');
            if ($t === '' && $p === '' && $ic === '') continue;
            $features[] = ['title'=>$t, 'text'=>$p, 'icon'=>$ic ?: 'bi bi-lightning-charge-fill'];
        }

        return [
            'badge'       => $v['badge'],
            'tagline'     => $v['tagline'],
            'description' => $v['description'] ?? null,
            'features'    => $features,
            'is_active'   => (bool)($v['is_active'] ?? true),
        ];
    }
}
