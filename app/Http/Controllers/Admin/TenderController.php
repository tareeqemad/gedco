<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->get('q'));
        $user     = trim((string) $request->get('user'));
        $dateFrom = $request->get('date_from'); // نخزن as-is (string) لكن نقدر نفلتر نصياً
        $dateTo   = $request->get('date_to');
        $perPage  = (int) ($request->get('per_page') ?? 20);
        $sort     = $request->get('sort', 'id');
        $dir      = $request->get('dir', 'desc');

        // حد آمن للترقيم
        if ($perPage < 5)  $perPage = 5;
        if ($perPage > 200) $perPage = 200;

        $query = Tender::query();

        // بحث عام
        if ($q !== '') {
            $like = "%{$q}%";
            $query->where(function ($w) use ($like) {
                $w->where('column_name_1', 'like', $like)
                    ->orWhere('event_1', 'like', $like)
                    ->orWhere('the_user_1', 'like', $like)
                    ->orWhere('old_value_1', 'like', $like)
                    ->orWhere('new_value_1', 'like', $like)
                    ->orWhere('the_date_1', 'like', $like)   // لأننا مخزنينها كنص
                    ->orWhere('mnews_id', 'like', $like)
                    ->orWhere('coulm_serial', 'like', $like);
            });
        }

        // فلترة حسب المستخدم (المدخل في ملف الإكسل)
        if ($user !== '') {
            $query->where(function ($w) use ($user) {
                $w->where('the_user_1', 'like', "%{$user}%");
            });
        }

        // فلترة نطاق التاريخ (نصية لأن الحقل string)
        // هنعتبر المدخل yyyy-mm-dd أو أي substring—نطبق like بسيط
        if ($dateFrom) $query->where('the_date_1', '>=', $dateFrom);
        if ($dateTo)   $query->where('the_date_1', '<=', $dateTo);

        // الفرز المسموح
        $allowedSorts = ['id', 'mnews_id', 'the_date_1', 'created_at', 'updated_at'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'id';
        $dir  = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sort, $dir)->orderBy('id', 'desc');

        $tenders = $query->paginate($perPage)->appends($request->query());

        // لائحة مميزة بالمستخدمين الموجودين في البيانات
        $distinctUsers = Tender::query()
            ->select('the_user_1')
            ->whereNotNull('the_user_1')
            ->distinct()
            ->pluck('the_user_1')
            ->filter()
            ->values();

        // === دعم AJAX: يرجّع HTML للجدول + الباجينج ===
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.site.tenders.partials.table', compact('tenders'))->render(),
                'pagination' => view('admin.site.tenders.partials.pagination', compact('tenders'))->render(),
                'total' => $tenders->total(),
            ]);
        }

        return view('admin.site.tenders.index', compact(
            'tenders', 'q', 'user', 'dateFrom', 'dateTo', 'perPage', 'sort', 'dir', 'distinctUsers'
        ));
    }

    public function create()
    {
        return view('admin.site.tenders.create');
    }

    public function store(Request $request)
    {
        // نفس السكيمة: كل شيء optional (nullable) لأن المصدر Excel متنوّع
        $data = $request->validate([
            'mnews_id'      => ['nullable','integer'],
            'column_name_1' => ['nullable','string','max:255'],
            'old_value_1'   => ['nullable','string'], // HTML or long text
            'new_value_1'   => ['nullable','string'],
            'the_date_1'    => ['nullable','string','max:50'], // as-is
            'event_1'       => ['nullable','string','max:255'],
            'the_user_1'    => ['nullable','string','max:255'],
            'coulm_serial'  => ['nullable','integer'],
        ]);

        // لا نمرر id -> AUTO_INCREMENT
        Tender::create($data);

        return redirect()->route('admin.tenders.index')->with('success', 'Tender created');
    }

    public function show(int $id)
    {
        $tender = Tender::findOrFail($id);
        return view('admin.site.tenders.show', compact('tender'));
    }

    public function edit(int $id)
    {
        $tender = Tender::findOrFail($id);
        return view('admin.site.tenders.edit', compact('tender'));
    }

    public function update(Request $request, int $id)
    {
        $tender = Tender::findOrFail($id);

        $data = $request->validate([
            'mnews_id'      => ['nullable','integer'],
            'column_name_1' => ['nullable','string','max:255'],
            'old_value_1'   => ['nullable','string'],
            'new_value_1'   => ['nullable','string'],
            'the_date_1'    => ['nullable','string','max:50'],
            'event_1'       => ['nullable','string','max:255'],
            'the_user_1'    => ['nullable','string','max:255'],
            'coulm_serial'  => ['nullable','integer'],
        ]);

        $tender->update($data);

        return redirect()->route('admin.tenders.index')->with('success', 'Tender updated');
    }

    public function destroy(int $id)
    {
        $tender = Tender::findOrFail($id);
        $tender->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tender deleted',
        ]);
    }
}
