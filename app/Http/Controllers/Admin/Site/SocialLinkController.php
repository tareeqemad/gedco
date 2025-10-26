<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use App\Http\Requests\Admin\Site\StoreSocialLinkRequest;
use App\Http\Requests\Admin\Site\UpdateSocialLinkRequest;
use Illuminate\Support\Facades\Cache;

class SocialLinkController extends Controller
{
    public function index()
    {
        $q = SocialLink::query();
        if ($search = request('q')) {
            $q->where('platform','like',"%$search%")
                ->orWhere('icon_class','like',"%$search%")
                ->orWhere('url','like',"%$search%");
        }
        $links = $q->orderBy('sort_order')->paginate(20);
        return view('admin.site.social_links.index', compact('links'));
    }

    public function create()
    {
        return view('admin.site.social_links.create');
    }

    public function store(StoreSocialLinkRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        SocialLink::create($data);

        Cache::forget('footer:data');
        return redirect()->route('admin.social-links.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit(SocialLink $social_link)
    {
        return view('admin.site.social_links.edit', ['link' => $social_link]);
    }

    public function update(UpdateSocialLinkRequest $request, SocialLink $social_link)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $social_link->update($data);

        Cache::forget('footer:data');
        return redirect()->route('admin.social-links.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(SocialLink $social_link)
    {
        $social_link->delete();
        Cache::forget('footer:data');
        return redirect()->route('admin.social-links.index')->with('success', 'تم الحذف بنجاح');
    }
}
