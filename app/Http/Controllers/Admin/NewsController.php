<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'        => ['required', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'status'       => ['nullable', 'in:draft,published'],
            'featured'     => ['nullable', 'boolean'],
            'cover'        => ['nullable', 'image', 'max:2048'],
            'pdf'          => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'body'         => ['nullable', 'string'],
        ]);

        $coverPath = $r->hasFile('cover') ? $r->file('cover')->store('news/covers', 'public') : null;
        $pdfPath   = $r->hasFile('pdf')   ? $r->file('pdf')->store('news/files', 'public') : null;

        News::create([
            'title'        => $data['title'],
            'slug'         => null, // يُولد تلقائيًا
            'body'         => $data['body'] ?? null,
            'cover_path'   => $coverPath,
            'pdf_path'     => $pdfPath,
            'status'       => $data['status'] ?? 'published',
            'featured'     => (bool)($data['featured'] ?? false),
            'published_at' => $data['published_at'] ?? now(),
            'created_by'   => Auth::id(),
            'updated_by'   => Auth::id(),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'تم إنشاء الخبر بنجاح',
            'redirect' => route('admin.news.index')
        ]);
    }

    public function show(News $news)
    {
        return view('admin.site.news.show', ['item' => $news->load('creator', 'updater')]);
    }

    public function edit(News $news)
    {
        return view('admin.site.news.edit', ['item' => $news]);
    }

    public function update(Request $r, News $news)
    {
        $data = $r->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', 'unique:news,slug,' . $news->id],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'body'         => ['nullable', 'string'],
            'pdf'          => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'cover'        => ['nullable', 'image', 'max:2048'],
            'status'       => ['nullable', 'in:draft,published'],
            'featured'     => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'remove_current_pdf'   => ['nullable', 'in:1'],
            'remove_current_cover' => ['nullable', 'in:1'],
        ]);

        $payload = [
            'title'        => $data['title'],
            'slug'         => $data['slug'] ?? $news->slug,
            'excerpt'      => $data['excerpt'] ?? $news->excerpt,
            'body'         => $data['body'] ?? $news->body,
            'status'       => $data['status'] ?? $news->status,
            'featured'     => isset($data['featured']) ? (bool)$data['featured'] : $news->featured,
            'published_at' => $data['published_at'] ?? $news->published_at,
            'updated_by'   => Auth::id(),
        ];

        if ($r->boolean('remove_current_pdf') && $news->pdf_path) {
            Storage::disk('public')->delete($news->pdf_path);
            $payload['pdf_path'] = null;
        }
        if ($r->boolean('remove_current_cover') && $news->cover_path) {
            Storage::disk('public')->delete($news->cover_path);
            $payload['cover_path'] = null;
        }

        if ($r->hasFile('pdf')) {
            if ($news->pdf_path) Storage::disk('public')->delete($news->pdf_path);
            $payload['pdf_path'] = $r->file('pdf')->store('news/files', 'public');
        }
        if ($r->hasFile('cover')) {
            if ($news->cover_path) Storage::disk('public')->delete($news->cover_path);
            $payload['cover_path'] = $r->file('cover')->store('news/covers', 'public');
        }

        $news->update($payload);

        return redirect()->route('admin.news.index')->with('success', 'تم تحديث الخبر بنجاح');
    }

    public function destroy(News $news)
    {
        if ($news->pdf_path)   Storage::disk('public')->delete($news->pdf_path);
        if ($news->cover_path) Storage::disk('public')->delete($news->cover_path);

        $news->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الخبر']);
    }
}
