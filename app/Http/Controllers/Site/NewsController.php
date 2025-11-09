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

        $query = News::query()
            ->published();

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

        $news->increment('views');

        $recentNews = News::query()
            ->published()
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


