<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Site\ImpactStatRequest;
use App\Models\ImpactStat;
use Illuminate\Http\Request;

class ImpactStatController extends Controller
{
    /**
     * عرض القائمة
     */
    public function index()
    {
        $items = ImpactStat::orderBy('sort_order')->get();
        return view('admin.site.impact_stats.index', compact('items'));
    }


    /**
     * إضافة جديدة عبر AJAX
     */
    public function store(ImpactStatRequest $request)
    {
        $data = $request->validated();

        $data['is_active'] = $request->has('is_active') && $request->boolean('is_active');
        $data['sort_order'] = ImpactStat::max('sort_order') + 1;

        $item = ImpactStat::create($data);

        return response()->json([
            'success' => true,
            'message' => 'تمت الإضافة بنجاح',
            'item' => $item->only(['id', 'title_ar', 'amount_usd', 'is_active', 'sort_order'])
        ]);
    }

    public function update(ImpactStatRequest $request, ImpactStat $impactStat)
    {
        $data = $request->validated();


        $data['is_active'] = $request->has('is_active') && $request->boolean('is_active');

        $impactStat->update($data);

        return response()->json([
            'success' => true,
            'item' => $impactStat->fresh()
        ]);
    }

    /**
     * حذف عبر AJAX
     */
    public function destroy(ImpactStat $impactStat)
    {
        $impactStat->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم الحذف بنجاح'
        ]);
    }

    /**
     * تبديل الحالة (مفعل/معطل) عبر AJAX
     */
    public function toggle(ImpactStat $impactStat)
    {
        $impactStat->update(['is_active' => !$impactStat->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $impactStat->is_active,
            'message' => $impactStat->is_active ? 'تم التفعيل' : 'تم الإيقاف'
        ]);
    }

    /**
     * إعادة ترتيب بالسحب والإفلات
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer'
        ]);

        foreach ($request->order as $id => $sort_order) {
            ImpactStat::where('id', $id)->update(['sort_order' => (int) $sort_order]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الترتيب'
        ]);
    }
}
