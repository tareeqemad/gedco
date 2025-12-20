<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $filter = request('filter', 'all');
        $search = request('search');

        // Determine language from session direction
        $direction = session('direction', 'rtl');
        $language = $direction === 'rtl' ? 'ar' : 'en';

        $query = News::query()
            ->published()
            ->language($language); // Filter by current language

        if ($search) {
            $query->search($search);
        }

        switch ($filter) {
            case 'featured':
                $query->where('featured', true);
                break;
            case 'fresh':
                $query->whereDate('published_at', today());
                break;
            case 'week':
                $query->whereDate('published_at', '>=', now()->subDays(6)->startOfDay());
                break;
        }

        $newsItems = $query
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        return view('site.news.index', [
            'newsItems'    => $newsItems,
            'activeFilter' => $filter,
            'searchTerm'   => $search,
        ]);
    }

    public function show(News $news)
    {
        if (!$news->is_published) {
            abort(404);
        }

        // Check if news language matches current direction
        $direction = session('direction', 'rtl');
        $expectedLang = $direction === 'rtl' ? 'ar' : 'en';
        if ($news->language !== $expectedLang) {
            abort(404); // Don't show news in wrong language
        }

        $news->increment('views');

        $recentNews = News::query()
            ->published()
            ->language($expectedLang) // Show recent news in same language
            ->where('id', '!=', $news->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('site.news.show', [
            'news'       => $news,
            'recentNews' => $recentNews,
        ]);
    }
}


