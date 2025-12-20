<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\News;
use App\Models\Slider;
use App\Models\Advertisement;
use App\Models\Tender;
use App\Models\ImpactStat;
use App\Models\ActivityLog;
use App\Models\StaffProfile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات عامة
        $stats = [
            'users' => [
                'total' => User::count(),
                'today' => User::whereDate('created_at', today())->count(),
            ],
            'news' => [
                'total' => News::count(),
                'published' => News::where('status', 'published')->count(),
                'draft' => News::where('status', 'draft')->count(),
                'today' => News::whereDate('created_at', today())->count(),
            ],
            'sliders' => [
                'total' => Slider::count(),
                'active' => Slider::where('is_active', true)->count(),
            ],
            'advertisements' => [
                'total' => Advertisement::count(),
                'today' => Advertisement::whereDate('INSERT_DATE', today())->count(),
            ],
            'tenders' => [
                'total' => Tender::count(),
                'today' => Tender::whereDate('created_at', today())->count(),
            ],
            'impact_stats' => [
                'total' => ImpactStat::count(),
                'active' => ImpactStat::where('is_active', true)->count(),
            ],
            'staff_profiles' => [
                'total' => StaffProfile::count(),
                'working' => StaffProfile::where('readiness', 'working')->count(),
                'ready' => StaffProfile::where('readiness', 'ready')->count(),
            ],
            'activities' => [
                'today' => ActivityLog::whereDate('created_at', today())->count(),
                'this_week' => ActivityLog::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
                'active_users_today' => ActivityLog::whereDate('created_at', today())
                    ->distinct('user_id')
                    ->count('user_id'),
            ],
        ];

        // آخر الأخبار
        $recentNews = News::with('creator:id,name')
            ->latest()
            ->limit(5)
            ->get(['id', 'title', 'status', 'published_at', 'created_at', 'created_by']);

        // آخر الأنشطة (فقط للمستخدمين الذين لديهم صلاحية activity-logs.view)
        $recentActivities = auth()->user()->can('activity-logs.view') 
            ? ActivityLog::with(['user' => function($q) {
                $q->select('id', 'name', 'email');
            }])
                ->latest()
                ->limit(10)
                ->get(['id', 'user_id', 'action', 'description', 'route_name', 'ip_address', 'created_at'])
            : collect();

        // الأنشطة حسب اليوم (لآخر 7 أيام) - للإحصائيات
        $activitiesChart = ActivityLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->date)->format('Y-m-d') => $item->count];
            });

        // أكثر المستخدمين نشاطاً (آخر 7 أيام)
        $topActiveUsers = ActivityLog::select('user_id', DB::raw('COUNT(*) as activity_count'))
            ->with(['user' => function($q) {
                $q->select('id', 'name', 'email');
            }])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('activity_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentNews',
            'recentActivities',
            'activitiesChart',
            'topActiveUsers'
        ));
    }
}