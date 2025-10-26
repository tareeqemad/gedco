<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Http\Requests\Admin\Site\UpdateSiteSettingRequest;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function edit(string $id)
    {
        $setting = \App\Models\SiteSetting::findOrFail($id);
        return view('admin.site.settings.edit', compact('setting'));
    }

    public function update(UpdateSiteSettingRequest $request, string $id)
    {
        $setting = \App\Models\SiteSetting::findOrFail($id);
        $setting->update($request->validated());

        \Cache::forget('footer:data'); // حتى ينعكس بالفوتر
        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
