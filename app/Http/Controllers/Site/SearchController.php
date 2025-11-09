<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function suggestions(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'status' => 'ok',
                'suggestions' => [],
            ]);
        }

        $suggestions = $this->compileResults($query)->take(8)->values();

        return response()->json([
            'status' => 'ok',
            'suggestions' => $suggestions,
        ]);
    }

    public function resolve(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'يرجى إدخال كلمتين على الأقل.',
            ], 422);
        }

        $match = $this->compileResults($query)->first();

        if ($match) {
            return response()->json([
                'status' => 'ok',
                'url' => $match['url'],
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'لم يتم العثور على نتائج مطابقة.',
        ]);
    }

    protected function compileResults(string $query): Collection
    {
        $normalized = mb_strtolower($query);
        $results = collect();

        $ads = Advertisement::query()
            ->select(['ID_ADVER', 'TITLE', 'WORD', 'DATE_NEWS'])
            ->where(function ($builder) use ($query) {
                $builder->where('TITLE', 'like', "%{$query}%")
                    ->orWhere('BODY', 'like', "%{$query}%")
                    ->orWhere('WORD', 'like', "%{$query}%");
            })
            ->orderByDesc('DATE_NEWS')
            ->limit(10)
            ->get();

        foreach ($ads as $ad) {
            $results->push([
                'label' => $ad->TITLE,
                'type' => 'إعلان',
                'url' => route('site.advertisements.show', $ad->ID_ADVER),
            ]);
        }

        $jobs = Job::query()
            ->where('is_active', true)
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('sort')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        foreach ($jobs as $job) {
            $results->push([
                'label' => $job->title,
                'type' => 'وظيفة',
                'url' => $job->link ?: route('site.jobs'),
            ]);
        }

        $staticPages = [
            'site.home' => ['label' => 'الرئيسية', 'keywords' => ['home', 'الرئيسية', 'الصفحة الرئيسية']],
            'site.services' => ['label' => 'الخدمات', 'keywords' => ['خدمات', 'service', 'services']],
            'site.about' => ['label' => 'من نحن', 'keywords' => ['about', 'عن الشركة', 'من نحن']],
            'site.contact' => ['label' => 'اتصل بنا', 'keywords' => ['contact', 'اتصال', 'تواصل']],
            'site.jobs' => ['label' => 'إعلانات الوظائف', 'keywords' => ['jobs', 'job', 'وظيفة', 'وظائف']],
            'site.advertisements.index' => ['label' => 'الإعلانات', 'keywords' => ['إعلان', 'اعلان', 'advertisement', 'إعلانات']],
            'site.tenders' => ['label' => 'العطاءات', 'keywords' => ['tender', 'عطاء', 'العطاءات']],
        ];

        foreach ($staticPages as $route => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (Str::contains($normalized, mb_strtolower($keyword))) {
                    $results->push([
                        'label' => $data['label'],
                        'type' => 'صفحة',
                        'url' => route($route),
                    ]);
                    break;
                }
            }
        }

        return $results->unique('url');
    }
}

