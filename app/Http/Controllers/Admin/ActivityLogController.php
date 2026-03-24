<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    /**
     * عرض صفحة سجل الأنشطة الرئيسية
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // البحث حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // البحث حسب العملية
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // البحث حسب الراوت
        if ($request->filled('route')) {
            $query->where('route_name', 'like', "%{$request->route}%");
        }

        // البحث حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50)->appends($request->query());

        // إحصائيات
        $stats = [
            'total_activities' => ActivityLog::count(),
            'today_activities' => ActivityLog::whereDate('created_at', today())->count(),
            'unique_users_today' => ActivityLog::whereDate('created_at', today())->distinct('user_id')->count('user_id'),
            'unique_users_month' => ActivityLog::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->distinct('user_id')->count('user_id'),
        ];

        // أكثر العمليات تكراراً
        $topActions = ActivityLog::select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // أكثر المستخدمين نشاطاً
        $topUsers = ActivityLog::select('user_id', DB::raw('count(*) as count'))
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $actions = ActivityLog::distinct('action')->pluck('action');

        // AJAX response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.activity-logs.partials.table', compact('logs'))->render(),
                'pagination' => view('admin.activity-logs.partials.pagination', compact('logs'))->render(),
                'total' => $logs->total(),
            ]);
        }

        return view('admin.activity-logs.index', compact('logs', 'stats', 'topActions', 'topUsers', 'users', 'actions'));
    }

    /**
     * عرض المستخدمين المتصلين حالياً
     */
    public function activeUsers(Request $request)
    {
        // المستخدمون الذين لديهم أنشطة في آخر 15 دقيقة
        $activeThreshold = Carbon::now()->subMinutes(15);
        
        $query = ActivityLog::select('user_id', DB::raw('MAX(created_at) as last_activity'))
            ->with(['user:id,name,email'])
            ->where('created_at', '>=', $activeThreshold)
            ->groupBy('user_id');

        // البحث بالاسم أو البريد الإلكتروني
        if ($search = $request->input('search')) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $activeUsers = $query->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($log) {
                $user = $log->user;
                if (!$user) return null;

                // عدد الأنشطة اليوم
                $todayCount = ActivityLog::where('user_id', $user->id)
                    ->whereDate('created_at', today())
                    ->count();

                // أول تسجيل دخول اليوم
                $firstLoginToday = ActivityLog::where('user_id', $user->id)
                    ->where('action', 'login')
                    ->whereDate('created_at', today())
                    ->orderBy('created_at', 'asc')
                    ->first();

                // تحميل الأدوار
                $user->load('roles:id,name');

                return [
                    'user' => $user,
                    'last_activity' => $log->last_activity,
                    'today_count' => $todayCount,
                    'first_login_today' => $firstLoginToday?->created_at,
                ];
            })
            ->filter()
            ->values();

        // AJAX response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.activity-logs.partials.active-users-table', compact('activeUsers'))->render(),
                'count' => $activeUsers->count(),
            ]);
        }

        return view('admin.activity-logs.active-users', compact('activeUsers'));
    }

    /**
     * عرض أنشطة مستخدم معين
     */
    public function userActivity(User $user, Request $request)
    {
        $query = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // فلترة حسب العملية
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(50)->appends($request->query());

        // إحصائيات المستخدم
        $stats = [
            'total_activities' => ActivityLog::where('user_id', $user->id)->count(),
            'today_activities' => ActivityLog::where('user_id', $user->id)
                ->whereDate('created_at', today())->count(),
            'first_activity' => ActivityLog::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')->first()?->created_at,
            'last_activity' => ActivityLog::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')->first()?->created_at,
        ];

        // أكثر الصفحات زيارة
        $topPages = ActivityLog::where('user_id', $user->id)
            ->select('route_name', 'route_uri', DB::raw('count(*) as count'))
            ->groupBy('route_name', 'route_uri')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // أكثر العمليات
        $topActions = ActivityLog::where('user_id', $user->id)
            ->select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.activity-logs.user', compact('user', 'logs', 'stats', 'topPages', 'topActions'));
    }
}