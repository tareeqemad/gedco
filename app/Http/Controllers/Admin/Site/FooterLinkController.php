<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use App\Http\Requests\Admin\Site\StoreFooterLinkRequest;
use App\Http\Requests\Admin\Site\UpdateFooterLinkRequest;
use Illuminate\Support\Facades\Cache;

class FooterLinkController extends Controller
{
    public function index()
    {
        $q = FooterLink::query();
        if ($group = request('group')) $q->where('group', $group);
        if ($search = request('q')) {
            $q->where(function($w) use ($search) {
                $w->where('label_ar','like',"%$search%")
                    ->orWhere('route_name','like',"%$search%")
                    ->orWhere('url','like',"%$search%");
            });
        }
        $links = $q->orderBy('group')->orderBy('sort_order')->paginate(20);
        return view('admin.site.footer_links.index', compact('links'));
    }

    public function create()
    {
        return view('admin.site.footer_links.create');
    }

    public function store(StoreFooterLinkRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        FooterLink::create($data);

        Cache::forget('footer:data');
        return redirect()->route('admin.footer-links.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit(FooterLink $footer_link)
    {
        return view('admin.site.footer_links.edit', ['link' => $footer_link]);
    }

    public function update(UpdateFooterLinkRequest $request, FooterLink $footer_link)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $footer_link->update($data);

        Cache::forget('footer:data');
        return redirect()->route('admin.footer-links.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(FooterLink $footer_link)
    {
        $footer_link->delete();
        Cache::forget('footer:data');
        return redirect()->route('admin.footer-links.index')->with('success', 'تم الحذف بنجاح');
    }
}
