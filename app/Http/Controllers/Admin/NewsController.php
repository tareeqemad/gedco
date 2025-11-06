<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $req)
    {
        $q        = $req->string('q')->toString();
        $status   = $req->get('status');
        $featured = $req->has('featured') ? $req->boolean('featured') : null;
        $dateFrom = $req->get('date_from');
        $dateTo   = $req->get('date_to');
        $perPage  = max(6, min(60, (int)($req->get('per_page') ?? 18)));
        $sort     = $req->get('sort', 'published_at');
        $dir      = $req->get('dir', 'desc');

        $items = News::query()
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
            'items', 'q', 'status', 'featured', 'dateFrom', 'dateTo', 'perPage', 'sort', 'dir'
        ));
    }

    public function create()
    {
        return view('admin.site.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required','string','max:255'],
            'published_at' => ['nullable','date'],
            'status'       => ['required','in:published,draft'],
            'featured'     => ['nullable','boolean'],
            'body'         => ['required','string'],
            'cover'        => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],   // 2MB
            'pdf'          => ['nullable','mimes:pdf','max:10240'], // 10MB
        ]);

        $data = [
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']).'-'.Str::random(5),
            'published_at' => $validated['published_at'] ?? now(),
            'status'       => $validated['status'],
            'featured'     => (bool)($validated['featured'] ?? false),
            'body'         => $validated['body'],

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
    public function update(Request $request, News $news)
    {
        // 1) Validation (no FormRequest, no Purifier)
        $v = Validator::make($request->all(), [
            'title'        => ['required','string','min:5','max:200'],
            'published_at' => ['required','date'],
            'status'       => ['required','in:draft,published'],
            'featured'     => ['nullable','boolean'],
            'body'         => ['required','string'], // نحفظه كما هو
            'cover'        => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048','dimensions:min_width=300,min_height=200'],
            'pdf'          => ['nullable','mimetypes:application/pdf','max:10240'],
            'remove_cover' => ['nullable','boolean'],
            'remove_pdf'   => ['nullable','boolean'],
        ], [
            'body.required' => 'محتوى الخبر مطلوب.',
        ]);

        if ($v->fails()) {
            return $request->expectsJson()
                ? response()->json(['errors' => $v->errors()], 422)
                : back()->withErrors($v)->withInput();
        }

        // 2) قواعد بسيطة على الـ body (بدون أي parsing)
        $raw = (string) $request->input('body', '');

        // ممنوع Base64 داخل الصور
        if (stripos($raw, 'src="data:') !== false || stripos($raw, "src='data:") !== false) {
            return $this->validationError('body', 'الصور بصيغة base64 غير مسموح بها.');
        }

        // حد أقصى 4 صور داخل المحتوى
        $imgCount = preg_match_all('/<img\b[^>]*>/i', $raw);
        if ($imgCount > 4) {
            return $this->validationError('body', 'عدد الصور في المحتوى يتجاوز الحد (4).');
        }


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

        // 4) حفظ البيانات
        $news->title        = (string) $request->input('title');
        $news->published_at = $request->date('published_at');
        $news->status       = (string) $request->input('status');
        $news->featured     = $request->boolean('featured');
        $news->body         = $raw; // ← زي ما هو

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
