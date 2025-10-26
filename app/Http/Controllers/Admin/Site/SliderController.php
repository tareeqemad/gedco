<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Slider\StoreSliderRequest;
use App\Http\Requests\Admin\Slider\UpdateSliderRequest;
use App\Models\Slider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->paginate(20);
        return view('admin.site.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.site.sliders.create');
    }

    public function store(StoreSliderRequest $request)
    {
        $data = $request->validated();
        // رفع صورة
        if ($request->hasFile('bg_image')) {
            $data['bg_image'] = $request->file('bg_image')->store('sliders', 'public');
        }
        // ترميز bullets
        $data['bullets'] = array_values(array_filter($data['bullets'] ?? []));
        $data['is_active'] = (bool) $request->boolean('is_active');

        Slider::create($data);
        Cache::forget('home:sliders');

        return redirect()->route('admin.sliders.index')->with('success','تمت الإضافة');
    }

    public function edit(Slider $slider)
    {
        return view('admin.site.sliders.edit', compact('slider'));
    }

    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        $data = $request->validated();

        if ($request->hasFile('bg_image')) {
            // حذف القديم
            if ($slider->bg_image && Storage::disk('public')->exists($slider->bg_image)) {
                Storage::disk('public')->delete($slider->bg_image);
            }
            $data['bg_image'] = $request->file('bg_image')->store('sliders', 'public');
        }

        $data['bullets'] = array_values(array_filter($data['bullets'] ?? []));
        $data['is_active'] = (bool) $request->boolean('is_active');

        $slider->update($data);
        Cache::forget('home:sliders');

        return back()->with('success','تم التحديث');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->bg_image && Storage::disk('public')->exists($slider->bg_image)) {
            Storage::disk('public')->delete($slider->bg_image);
        }
        $slider->delete();
        Cache::forget('home:sliders');

        return back()->with('success','تم الحذف');
    }
}
