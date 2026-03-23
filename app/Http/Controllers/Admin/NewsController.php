<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\StoreNewsRequest;
use App\Http\Requests\Admin\News\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $req)
    {
        // Filter by current admin panel language
        $adminDirection = session('direction', 'rtl');
        $currentLanguage = $adminDirection === 'rtl' ? 'ar' : 'en';
        
        $q        = $req->string('q')->toString();
        $status   = $req->get('status');
        $featured = $req->has('featured') ? $req->boolean('featured') : null;
        $dateFrom = $req->get('date_from');
        $dateTo   = $req->get('date_to');
        $perPage  = max(6, min(60, (int)($req->get('per_page') ?? 18)));
        $sort     = $req->get('sort', 'published_at');
        $dir      = $req->get('dir', 'desc');

        $items = News::query()
            ->language($currentLanguage) // Filter by current admin panel language
            ->search($q)
            ->status($status)
            ->featured($featured)
            ->betweenDates($dateFrom, $dateTo)
            ->sortSmart($sort, $dir)
            ->paginate($perPage)
            ->appends($req->query());

        if ($req->ajax() || $req->wantsJson()) {
            return response()->json([
                'html'       => view('admin.site.news.partials.cards', compact('items'))->render(),
                'pagination' => view('admin.site.news.partials.pagination', compact('items'))->render(),
                'total'      => $items->total(),
            ]);
        }

        return view('admin.site.news.index', compact(
            'items', 'q', 'status', 'featured', 'dateFrom', 'dateTo', 'perPage', 'sort', 'dir', 'currentLanguage'
        ));
    }

    public function create()
    {
        return view('admin.site.news.create');
    }

    public function store(StoreNewsRequest $request)
    {
        // Determine language from current admin direction
        $adminDirection = session('direction', 'rtl');
        $defaultLanguage = $adminDirection === 'rtl' ? 'ar' : 'en';

        $data = [
            'title'        => $request->validated()['title'],
            'slug'         => Str::slug($request->validated()['title']).'-'.Str::random(5),
            'published_at' => $request->validated()['published_at'] ?? now(),
            'status'       => $request->validated()['status'],
            'featured'     => (bool)($request->validated()['featured'] ?? false),
            'body'         => $request->validated()['body'],
            'language'     => $request->validated()['language'] ?? $defaultLanguage,
            'created_by'   => auth()->id(),
        ];

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('news/cover', 'public');
        }

        if ($request->hasFile('pdf')) {
            $data['pdf_path'] = $request->file('pdf')->store('news/pdf', 'public');
        }

        $news = News::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'ok'       => true,
                'id'       => $news->id,
                'redirect' => route('admin.news.index'),
            ]);
        }

        return redirect()->route('admin.news.index')->with('success', 'تم إنشاء الخبر');
    }

    public function show(News $news)
    {
        return view('admin.site.news.show', ['item' => $news->load('creator', 'updater')]);
    }

    // ====== VIEWS ======
    public function edit(News $news)
    {
        return view('admin.site.news.edit', compact('news'));
    }

    // ====== UPDATE ======
    public function update(UpdateNewsRequest $request, News $news)
    {
        // قواعد بسيطة على الـ body
        $raw = (string) $request->validated()['body'];

        // ممنوع Base64 داخل الصور
        if (stripos($raw, 'src="data:') !== false || stripos($raw, "src='data:") !== false) {
            return $this->validationError('body', 'الصور بصيغة base64 غير مسموح بها.');
        }

        // حد أقصى 8 صور داخل المحتوى
        $imgCount = preg_match_all('/<img\b[^>]*>/i', $raw);
        if ($imgCount > 8) {
            return $this->validationError('body', 'عدد الصور في المحتوى يتجاوز الحد (8).');
        }

        // ممنوع script tags
        if (preg_match('/<\s*script\b/i', $raw)) {
            return $this->validationError('body', 'وسم <script> غير مسموح.');
        }


        if ($request->boolean('remove_cover') && $news->cover_path) {
            Storage::disk('public')->delete($news->cover_path);
            $news->cover_path = null;
        }
        if ($request->hasFile('cover')) {
            if ($news->cover_path) Storage::disk('public')->delete($news->cover_path);
            $news->cover_path = $request->file('cover')->storePublicly('news/covers/'.date('Y/m'), 'public');
        }

        if ($request->boolean('remove_pdf') && $news->pdf_path) {
            Storage::disk('public')->delete($news->pdf_path);
            $news->pdf_path = null;
        }
        if ($request->hasFile('pdf')) {
            if ($news->pdf_path) Storage::disk('public')->delete($news->pdf_path);
            $news->pdf_path = $request->file('pdf')->storePublicly('news/pdfs/'.date('Y/m'), 'public');
        }

        // حفظ البيانات
        $validated = $request->validated();
        $news->title        = (string) $validated['title'];
        $news->published_at = $request->date('published_at');
        $news->status       = (string) $validated['status'];
        $news->featured     = $request->boolean('featured');
        $news->body         = $raw;
        $news->language     = $validated['language'] ?? 'ar';

        if ($request->user()) {
            $news->updated_by = $request->user()->id;
        }

        $news->save();

        // 5) استجابة مناسبة للـ fetch (JSON أو Redirect)
        $routeShow = route($request->routeIs('admin.*') ? 'admin.news.show' : 'news.show', $news);

        return $request->expectsJson()
            ? response()->json(['redirect' => $routeShow])
            : redirect()->to($routeShow);
    }

    // ====== Helpers ======
    private function validationError(string $field, string $message)
    {
        return response()->json(['errors' => [$field => [$message]]], 422);
    }

    public function destroy(News $news)
    {
        if ($news->pdf_path)   Storage::disk('public')->delete($news->pdf_path);
        if ($news->cover_path) Storage::disk('public')->delete($news->cover_path);

        $news->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الخبر']);
    }
}
