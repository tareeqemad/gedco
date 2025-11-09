<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->get('q'));
        $user     = trim((string) $request->get('user'));
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $perPage  = (int) ($request->get('per_page') ?? 20);
        $sort     = $request->get('sort', 'DATE_NEWS');
        $dir      = $request->get('dir', 'desc');

        $query = Advertisement::query();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('TITLE', 'like', "%{$q}%")
                    ->orWhere('BODY', 'like', "%{$q}%")
                    ->orWhere('PDF', 'like', "%{$q}%");
            });
        }

        if ($user !== '') {
            $query->where(function ($w) use ($user) {
                $w->where('INSERT_USER', 'like', "%{$user}%")
                    ->orWhere('UPDATE_USER', 'like', "%{$user}%");
            });
        }

        if ($dateFrom) $query->whereDate('DATE_NEWS', '>=', $dateFrom);
        if ($dateTo)   $query->whereDate('DATE_NEWS', '<=', $dateTo);

        $allowedSorts = ['DATE_NEWS', 'INSERT_DATE', 'UPDATE_DATE', 'ID_ADVER'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'DATE_NEWS';
        $dir  = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sort, $dir)->orderBy('ID_ADVER', 'desc');

        $ads = $query->paginate($perPage)->appends($request->query());

        $distinctUsers = Advertisement::query()
            ->select('INSERT_USER')
            ->whereNotNull('INSERT_USER')
            ->distinct()
            ->pluck('INSERT_USER')
            ->filter()
            ->values();

        // === دعم AJAX ===
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.site.advertisements.partials.table', compact('ads'))->render(),
                'pagination' => view('admin.site.advertisements.partials.pagination', compact('ads'))->render(),
                'total' => $ads->total(),
            ]);
        }

        return view('admin.site.advertisements.index', compact(
            'ads', 'q', 'user', 'dateFrom', 'dateTo', 'perPage', 'sort', 'dir', 'distinctUsers'
        ));
    }

    public function create()
    {
        return view('admin.site.advertisements.create');
    }

    public function store(Request $request)
    {
        // 1) Validate
        $validated = $request->validate([
            'TITLE'     => 'required|string|max:255',
            'BODY'      => 'nullable|string',
            'PDF'       => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'DATE_NEWS' => 'required|date',
        ]);

        // 2) Sanitize Body (اختياري بس أنصح فيه)
        $body = $validated['BODY'] ?? '';
        // لو عندك Purifier:
        // $body = Purifier::clean($body);
        // أو فلترة بسيطة (اختياري جداً):
        // $body = strip_tags($body, '<p><br><b><strong><i><u><a><ul><ol><li><blockquote><code><pre><h1><h2><h3><img>');

        // 3) Store PDF (اختياري)
        $pdfPath = null;
        if ($request->hasFile('PDF')) {
            try {
                $file = $request->file('PDF');
                if (!$file->isValid()) {
                    return $this->storeErrorResponse($request, ['PDF' => 'الملف غير صالح أو لم يُرفع بشكل صحيح']);
                }

                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $pdfPath = $file->storeAs('advertisements', $filename, 'public');

                if (!Storage::disk('public')->exists($pdfPath)) {
                    Log::error('Failed to store PDF', ['path' => $pdfPath]);
                    return $this->storeErrorResponse($request, ['PDF' => 'فشل في حفظ الملف']);
                }
            } catch (\Throwable $e) {
                Log::error('PDF upload error: '.$e->getMessage());
                return $this->storeErrorResponse($request, ['PDF' => 'حدث خطأ أثناء رفع الملف']);
            }
        }

        // 4) Create (ثبّت المنطقة الزمنية)
        $user = Auth::user();
        $ad = Advertisement::create([
            'TITLE'       => $validated['TITLE'],
            'BODY'        => $body,
            'PDF'         => $pdfPath,
            'DATE_NEWS'   => \Carbon\Carbon::parse($validated['DATE_NEWS'], 'Asia/Hebron'),
            'INSERT_USER' => $user?->name ?? $user?->email ?? 'system',
            'INSERT_DATE' => now('Asia/Hebron'),
        ]);

        // 5) Response: JSON لـ AJAX، Redirect لـ non-AJAX
        $redirect = route('admin.advertisements.index');
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok'       => true,
                'id'       => $ad->ID_ADVER ?? $ad->id ?? null,
                'redirect' => $redirect,
                'message'  => 'تم إضافة الإعلان بنجاح',
            ]);
        }

        return redirect($redirect)->with('success', 'تم إضافة الإعلان بنجاح');
    }

    /**
     * Helper لارجاع أخطاء التحقق / رفع الملف
     */
    private function storeErrorResponse(Request $request, array $errors)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'validation_error',
                'errors'  => $errors,
            ], 422);
        }
        return back()->withErrors($errors)->withInput();
    }

    public function show($id)
    {
        $ad = Advertisement::findOrFail($id);
        return view('admin.site.advertisements.show', compact('ad'));
    }

    public function edit($id)
    {
        $ad = Advertisement::findOrFail($id);
        return view('admin.site.advertisements.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);

        // فاليديشن متوافق مع الواجهة (remove_pdf بدلاً من remove_current_pdf)
        $validated = $request->validate([
            'TITLE'     => 'required|string|max:255',
            'BODY'      => 'nullable|string',
            'PDF'       => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'DATE_NEWS' => 'required|date',
            'remove_pdf'=> 'nullable|in:1',
        ]);

        // (اختياري) تنظيف الـ HTML لو مركّب Purifier
        $body = $validated['BODY'] ?? '';
        // $body = Purifier::clean($body);

        // جهّز بيانات التحديث
        $data = [
            'TITLE'       => $validated['TITLE'],
            'BODY'        => $body,
            'DATE_NEWS'   => Carbon::parse($validated['DATE_NEWS'], 'Asia/Hebron'),
            'UPDATE_USER' => Auth::user()?->name ?? Auth::user()?->email ?? 'system',
            'UPDATE_DATE' => now('Asia/Hebron'),
        ];

        // حذف المرفق الحالي لو طُلِب
        if ($request->filled('remove_pdf') && $request->input('remove_pdf') === '1') {
            if ($ad->PDF) {
                try { Storage::disk('public')->delete($ad->PDF); } catch (\Throwable $e) {
                    Log::warning('Failed to delete old PDF', ['id' => $ad->id, 'path' => $ad->PDF, 'err' => $e->getMessage()]);
                }
            }
            $data['PDF'] = null;
        }

        // استبدال/رفع PDF جديد
        if ($request->hasFile('PDF')) {
            $file = $request->file('PDF');
            if (! $file->isValid()) {
                return $this->updateErrorResponse($request, ['PDF' => 'الملف غير صالح أو لم يُرفع بشكل صحيح']);
            }

            // احذف القديم أولاً (لو موجود)
            if ($ad->PDF) {
                try { Storage::disk('public')->delete($ad->PDF); } catch (\Throwable $e) {
                    Log::warning('Failed to delete old PDF before replace', ['id' => $ad->id, 'path' => $ad->PDF, 'err' => $e->getMessage()]);
                }
            }

            try {
                // خزّن الجديد داخل مجلد الإعلانات
                $storedPath = $file->store('advertisements', 'public');
                if (! $storedPath || ! Storage::disk('public')->exists($storedPath)) {
                    return $this->updateErrorResponse($request, ['PDF' => 'فشل في حفظ الملف']);
                }
                $data['PDF'] = $storedPath;
            } catch (\Throwable $e) {
                Log::error('PDF upload error on update: '.$e->getMessage(), ['id' => $ad->id]);
                return $this->updateErrorResponse($request, ['PDF' => 'حدث خطأ أثناء رفع الملف']);
            }
        }

        // حفظ
        $ad->update($data);

        // لو AJAX → JSON؛ غير كذا → Redirect
        $redirect = route('admin.advertisements.index');
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok'       => true,
                'id'       => $ad->ID_ADVER ?? $ad->id,
                'redirect' => $redirect,
                'message'  => 'تم التحديث بنجاح',
            ]);
        }

        return redirect($redirect)->with('success', 'تم التحديث بنجاح');
    }

    /**
     * Helper لإرجاع أخطاء JSON 422 مع بقاء الـ non-AJAX على back()->withErrors()
     */
    private function updateErrorResponse(Request $request, array $errors)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'validation_error',
                'errors'  => $errors,
            ], 422);
        }
        return back()->withErrors($errors)->withInput();
    }

    public function pdf($id)
    {
        $ad = Advertisement::findOrFail($id);
        abort_unless($ad->PDF && Storage::disk('public')->exists($ad->PDF), 404);

        $path = Storage::disk('public')->path($ad->PDF);
        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }
    public function destroy(int $ID_ADVER)
    {
        $ad = Advertisement::findOrFail($ID_ADVER);
        if ($ad->PDF && Storage::disk('public')->exists($ad->PDF)) {
            Storage::disk('public')->delete($ad->PDF);
        }
        $ad->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإعلان بنجاح'
        ]);
    }
}
