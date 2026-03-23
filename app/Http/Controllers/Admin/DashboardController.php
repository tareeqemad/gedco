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
use App\Models\ContactMessage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات KPI
        $stats = [
            'users' => [
                'total' => User::count(),
                'today' => User::whereDate('created_at', today())->count(),
            ],
            'news' => [
                'total' => News::count(),
                'published' => News::where('status', 'published')->count(),
                'draft' => News::where('status', 'draft')->count(),
            ],
            'sliders' => [
                'total' => Slider::count(),
                'active' => Slider::where('is_active', true)->count(),
            ],
            'advertisements' => [
                'total' => Advertisement::count(),
            ],
            'tenders' => [
                'total' => Tender::count(),
            ],
            'impact_stats' => [
                'total' => ImpactStat::count(),
                'active' => ImpactStat::where('is_active', true)->count(),
            ],
            'staff_profiles' => [
                'total' => StaffProfile::count(),
            ],
            'activities' => [
                'today' => ActivityLog::whereDate('created_at', today())->count(),
                'active_users_today' => ActivityLog::whereDate('created_at', today())
                    ->distinct('user_id')
                    ->count('user_id'),
            ],
        ];

        // Hero insights - بيانات ذكية
        $heroInsights = [
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'draft_news' => $stats['news']['draft'],
            'today_activities' => $stats['activities']['today'],
            'active_users' => $stats['activities']['active_users_today'],
        ];

        // عناصر تحتاج متابعة
        $pendingItems = collect();
        if ($heroInsights['unread_messages'] > 0) {
            $pendingItems->push([
                'icon' => 'bi-envelope-fill',
                'text' => $heroInsights['unread_messages'] . ' رسالة جديدة غير مقروءة',
                'route' => route('admin.contact-messages.index'),
                'color' => '#DC2626',
            ]);
        }
        if ($heroInsights['draft_news'] > 0) {
            $pendingItems->push([
                'icon' => 'bi-file-earmark-text',
                'text' => $heroInsights['draft_news'] . ' خبر مسودة بانتظار النشر',
                'route' => route('admin.news.index'),
                'color' => '#D97706',
            ]);
        }

        // آخر الأخبار
        $recentNews = News::with('creator:id,name')
            ->latest()
            ->limit(5)
            ->get(['id', 'title', 'status', 'published_at', 'created_at', 'created_by']);

        // آخر الأنشطة
        $recentActivities = auth()->user()->can('activity-logs.view')
            ? ActivityLog::with(['user' => function($q) {
                $q->select('id', 'name', 'email');
            }])
                ->latest()
                ->limit(8)
                ->get(['id', 'user_id', 'action', 'description', 'route_name', 'ip_address', 'created_at'])
            : collect();

        // الأنشطة حسب اليوم (آخر 7 أيام)
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

        return view('admin.dashboard', compact(
            'stats',
            'heroInsights',
            'pendingItems',
            'recentNews',
            'recentActivities',
            'activitiesChart'
        ));
    }
}
