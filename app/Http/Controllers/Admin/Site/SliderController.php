<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Slider\StoreSliderRequest;
use App\Http\Requests\Admin\Slider\UpdateSliderRequest;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        // Filter by current admin panel language
        $adminDirection = session('direction', 'rtl');
        $currentLanguage = $adminDirection === 'rtl' ? 'ar' : 'en';
        
        $sliders = Slider::where('language', $currentLanguage)
            ->orderBy('sort_order')
            ->paginate(20);
            
        return view('admin.site.sliders.index', compact('sliders', 'currentLanguage'));
    }

    public function create()
    {
        // نحسب آخر ترتيب للسلايدرات النشطة في نفس اللغة
        $adminDirection = session('direction', 'rtl');
        $currentLanguage = $adminDirection === 'rtl' ? 'ar' : 'en';
        
        $nextOrder = Slider::where('language', $currentLanguage)
            ->where('is_active', true)
            ->max('sort_order');
        $nextOrder = is_null($nextOrder) ? 0 : $nextOrder + 1;

        return view('admin.site.sliders.create', compact('nextOrder'));
    }

    public function store(StoreSliderRequest $request)
    {
        $data = $request->validated();

        // تحديد اللغة من اتجاه لوحة التحكم
        $adminDirection = session('direction', 'rtl');
        $defaultLanguage = $adminDirection === 'rtl' ? 'ar' : 'en';
        $data['language'] = $data['language'] ?? $defaultLanguage;

        // رفع الصورة
        if ($request->hasFile('bg_image')) {
            $data['bg_image'] = $request->file('bg_image')->store('sliders', 'public');
        }

        // ترميز bullets
        $data['bullets'] = array_values(array_filter($data['bullets'] ?? []));
        $data['is_active'] = (bool) $request->boolean('is_active');

        // ✅ تحديد الترتيب تلقائياً بناءً على آخر سجل في نفس اللغة
        $language = $data['language'] ?? $defaultLanguage;
        $lastOrder = Slider::where('language', $language)
            ->where('is_active', true)
            ->max('sort_order');
        $data['sort_order'] = is_null($lastOrder) ? 0 : $lastOrder + 1;

        // إنشاء السجل
        \App\Models\Slider::create($data);

        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'تمت الإضافة بنجاح.');
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
        
        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();

        return back()->with('success','تم التحديث');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->bg_image && Storage::disk('public')->exists($slider->bg_image)) {
            Storage::disk('public')->delete($slider->bg_image);
        }
        $slider->delete();
        
        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();

        return back()->with('success','تم الحذف');
    }
    public function deactivateImage(Slider $slider)
    {
        // فقط نُعطّل الصورة (نضع null في الداتابيز)
        $slider->bg_image = null;
        $slider->save();
        
        // تنظيف الكاش للصفحة الرئيسية
        clear_home_cache();

        return back()->with('success', 'تم إخفاء الصورة بنجاح');
    }
}
