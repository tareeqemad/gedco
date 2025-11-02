<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        // التحقق من البيانات
        $validated = $request->validate([
            'TITLE'     => 'required|string|max:255',
            'BODY'      => 'nullable|string',
            'PDF'       => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'DATE_NEWS' => 'required|date',
        ]);

        // تهيئة مسار الـ PDF
        $pdfPath = null;

        // لو في ملف، يُخزن
        if ($request->hasFile('PDF')) {
            try {
                $file = $request->file('PDF');

                // تحقق من أن الملف صالح
                if (!$file->isValid()) {
                    return back()->withErrors(['PDF' => 'الملف غير صالح أو لم يُرفع بشكل صحيح'])->withInput();
                }

                // تخزين الملف مع اسم فريد
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $pdfPath = $file->storeAs('advertisements', $filename, 'public');

                // تحقق من النجاح
                if (!Storage::disk('public')->exists($pdfPath)) {
                    \Log::error('Failed to store PDF', ['path' => $pdfPath]);
                    return back()->withErrors(['PDF' => 'فشل في حفظ الملف'])->withInput();
                }

                \Log::info('PDF stored successfully', ['path' => $pdfPath]);
            } catch (\Exception $e) {
                \Log::error('PDF upload error: ' . $e->getMessage());
                return back()->withErrors(['PDF' => 'حدث خطأ أثناء رفع الملف'])->withInput();
            }
        }

        // إنشاء الإعلان
        Advertisement::create([
            'TITLE'       => $validated['TITLE'],
            'BODY'        => $validated['BODY'] ?? '',
            'PDF'         => $pdfPath,
            'DATE_NEWS'   => $validated['DATE_NEWS'],
            'INSERT_USER' => Auth::user()->name ?? Auth::user()->email,
            'INSERT_DATE' => now(),
        ]);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'تم إضافة الإعلان بنجاح');
    }

    public function show(int $ID_ADVER)
    {
        $ad = Advertisement::findOrFail($ID_ADVER);
        return view('admin.site.advertisements.show', compact('ad'));
    }

    public function edit($id)
    {
        $ad = Advertisement::findOrFail($id); // ← مهم: تحويل الـ ID للكائن
        return view('admin.site.advertisements.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id); // ← مهم جداً

        $validated = $request->validate([
            'TITLE'     => 'required|string|max:255',
            'BODY'      => 'nullable|string',
            'PDF'       => 'nullable|file|mimes:pdf|max:10240',
            'DATE_NEWS' => 'required|date',
        ]);

        $data = $request->only(['TITLE', 'BODY', 'DATE_NEWS']);

        // رفع ملف جديد
        if ($request->hasFile('PDF')) {
            // حذف القديم
            if ($ad->PDF) {
                Storage::disk('public')->delete($ad->PDF);
            }
            $data['PDF'] = $request->file('PDF')->store('advertisements', 'public');
        }

        $ad->update($data);

        return redirect()->route('admin.advertisements.index')->with('success', 'تم التحديث بنجاح');
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
