<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Site\AboutUsRequest;
use App\Models\AboutUs;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();
        return view('admin.site.about.index', compact('about'));
    }

    public function create()
    {
        // لا يوجد سجل: جهّز أعمدة الميزات فاضية
        $col1 = $col2 = [];
        return view('admin.site.about.create', compact('col1','col2'));
    }

    public function store(AboutUsRequest $request)
    {
        $data = $this->payload($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('site', 'public');
        }

        AboutUs::create($data);

        return redirect()->route('admin.about.index')->with('success','تم الإنشاء بنجاح.');
    }

    public function edit(AboutUs $about)
    {
        [$col1, $col2] = $this->splitFeatures($about->features);
        return view('admin.site.about.edit', compact('about','col1','col2'));
    }

    public function update(AboutUsRequest $request, AboutUs $about)
    {
        $data = $this->payload($request);

        if ($request->hasFile('image')) {
            if ($about->image && Storage::disk('public')->exists($about->image)) {
                Storage::disk('public')->delete($about->image);
            }
            $data['image'] = $request->file('image')->store('site', 'public');
        }

        $about->update($data);

        return redirect()->route('admin.about.index')->with('success','تم التحديث بنجاح.');
    }

    // ===== Helpers =====

    private function payload(AboutUsRequest $request): array
    {
        $v = $request->validated();

        $col1 = $this->lines($v['features_col1'] ?? null);
        $col2 = $this->lines($v['features_col2'] ?? null);

        return [
            'title'       => $v['title'],
            'subtitle'    => $v['subtitle'] ?? null,
            'paragraph1'  => $v['paragraph1'],
            'paragraph2'  => $v['paragraph2'] ?? null,
            // نخزّن Array — Laravel يحوله JSON تلقائيًا بفضل $casts
            'features'    => [$col1, $col2],
        ];
    }

    private function lines(?string $text): array
    {
        if (!$text) return [];
        return array_values(array_filter(array_map(
            fn($l) => trim($l),
            preg_split("/\r\n|\n|\r/", $text)
        )));
    }

    private function splitFeatures($features): array
    {
        if (is_string($features)) {
            $arr = json_decode($features, true) ?: [];
        } elseif (is_array($features)) {
            $arr = $features;
        } else {
            $arr = [];
        }
        return [$arr[0] ?? [], $arr[1] ?? []];
    }
}
