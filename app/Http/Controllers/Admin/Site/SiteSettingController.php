<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SiteContactChannel;
use App\Http\Requests\Admin\Site\UpdateSiteSettingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends Controller
{
    public function edit(string $id)
    {
        $setting = SiteSetting::with(['contactChannels' => fn($q) => $q->orderBy('position')])
            ->findOrFail($id);

        return view('admin.site.settings.edit', compact('setting'));
    }

    public function update(UpdateSiteSettingRequest $request, string $id)
    {
        $setting = SiteSetting::findOrFail($id);

        $data = $request->validated();
        $channels = $data['channels'] ?? [];
        unset($data['channels']);

        DB::transaction(function () use ($setting, $data, $channels) {
            // 1) تحديث الحقول العامة
            $setting->update($data);

            // 2) مزامنة القنوات
            $this->syncContactChannels($setting, $channels);
        });

        // 3) تنظيف/تسخين الكاش للفوتر
        Cache::forget('footer:data');
        Cache::rememberForever('footer:data', function () use ($setting) {
            $setting->load(['contactChannels' => fn($q) => $q->orderBy('position')]);
            return [
                'footer_title_ar' => $setting->footer_title_ar,
                'footer_title_en' => $setting->footer_title_en,
                'logo_white_path_ar' => $setting->logo_white_path_ar,
                'logo_white_path_en' => $setting->logo_white_path_en,
                'contact_us_title_ar' => $setting->contact_us_title_ar,
                'contact_us_title_en' => $setting->contact_us_title_en,
                'channels' => $setting->contactChannels->map(fn($c) => [
                    'label'      => $c->label,
                    'email'      => $c->email,
                    'phone'      => $c->phone_formatted ?? $c->phone,
                    'address_ar' => $c->address_ar,
                    'address_en' => $c->address_en,
                ])->values()->all(),
            ];
        });

        return back()->with('success', __('admin.settings.updated_successfully'));
    }

    private function syncContactChannels(SiteSetting $setting, array $channels): void
    {
        $normalized = collect($channels)
            ->map(function ($row, $i) {
                return [
                    'id'         => $row['id']         ?? null,
                    'position'   => $row['position']   ?? ($i + 1),
                    'label'      => $row['label']      ?? null,
                    'email'      => $row['email']      ?? null,
                    'phone'      => $row['phone']      ?? null,
                    'address_ar' => $row['address_ar'] ?? null,
                    'address_en' => $row['address_en'] ?? null,
                ];
            })
            ->filter(fn ($r) => $r['email'] || $r['phone'] || $r['address_ar'] || $r['address_en'])
            ->slice(0, 2)
            ->values();

        $keepIds = [];

        foreach ($normalized as $row) {
            if (!empty($row['id'])) {
                $model = $setting->contactChannels()->whereKey($row['id'])->first();
                if ($model) {
                    $model->update([
                        'position'   => (int) $row['position'],
                        'label'      => $row['label'],
                        'email'      => $row['email'],
                        'phone'      => $row['phone'],
                        'address_ar' => $row['address_ar'],
                        'address_en' => $row['address_en'],
                    ]);
                    $keepIds[] = $model->id;
                    continue;
                }
            }

            $new = $setting->contactChannels()->create([
                'position'   => (int) $row['position'],
                'label'      => $row['label'],
                'email'      => $row['email'],
                'phone'      => $row['phone'],
                'address_ar' => $row['address_ar'],
                'address_en' => $row['address_en'],
            ]);
            $keepIds[] = $new->id;
        }

        $setting->contactChannels()->whereNotIn('id', $keepIds)->delete();
    }
}
