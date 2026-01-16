<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        // إذا كان AJAX request (بحث أو pagination)
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            // قراءة من JSON body أو form data أو query string
            $search = request()->input('search', request()->json('search', request()->query('search', '')));
            $filter = request()->input('filter', request()->json('filter', request()->query('filter', 'all')));
            $page = request()->input('page', request()->json('page', request()->query('page', 1)));
            
            // حفظ في session
            session([
                'news_search' => $search,
                'news_filter' => $filter,
            ]);
            
            // جلب النتائج مع الصفحة المطلوبة
            $result = $this->getNewsItems($search, $filter, $page);
            
            return response()->json([
                'html' => view('site.news.partials.grid', ['newsItems' => $result['newsItems']])->render(),
                'pagination' => $result['newsItems']->hasPages() 
                    ? $result['newsItems']->onEachSide(1)->links()->render() 
                    : '',
                'total' => $result['newsItems']->total(),
            ]);
        }

        // إذا كان POST (فلتر فقط)، احفظ في session وأعد التوجيه
        if (request()->isMethod('post')) {
            $search = request('search', '');
            $filter = request('filter', 'all');
            
            session([
                'news_search' => $search,
                'news_filter' => $filter,
            ]);
            
            return redirect()->route('site.news');
        }

        // قراءة من session أو request
        $filter = session('news_filter', request('filter', 'all'));
        $search = session('news_search', request('search', ''));

        $result = $this->getNewsItems($search, $filter);

        return view('site.news.index', [
            'newsItems'    => $result['newsItems'],
            'activeFilter' => $filter,
            'searchTerm'   => $search,
        ]);
    }

    private function getNewsItems($search = '', $filter = 'all', $page = 1)
    {
        // Determine language from session direction - بسيط: عربي = ar، إنجليزي = en
        $direction = session('direction', 'rtl');
        $language = $direction === 'rtl' ? 'ar' : 'en';

        // Query بسيط: أخبار منشورة + نفس اللغة فقط - يبحث في كل الصفحات
        $query = News::query()
            ->where('status', 'published')
            ->where('language', $language);

        // البحث - يدور في كل الأخبار (كل الصفحات)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // الفلاتر
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

        // الترتيب حسب تاريخ الخبر desc والـpagination
        $newsItems = $query
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9, ['*'], 'page', $page);

        return [
            'newsItems' => $newsItems,
        ];
    }

    public function show(News $news)
    {
        // بسيط: تحقق من الحالة واللغة فقط
        $direction = session('direction', 'rtl');
        $expectedLang = $direction === 'rtl' ? 'ar' : 'en';
        
        if ($news->status !== 'published' || $news->language !== $expectedLang) {
            abort(404);
        }

        $news->increment('views');

        // أخبار مشابهة - نفس اللغة فقط
        $recentNews = News::query()
            ->where('status', 'published')
            ->where('language', $expectedLang)
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


