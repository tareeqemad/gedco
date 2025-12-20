<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     * Logs all admin panel activities.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // فقط في لوحة التحكم
        if (!$request->is('admin/*')) {
            return $next($request);
        }

        // تخطي بعض الروابط
        $routeName = $request->route()?->getName();
        if ($routeName && (str_starts_with($routeName, 'admin.activity-logs'))) {
            return $next($request);
        }

        // فقط للمستخدمين المسجلين
        if (!Auth::check()) {
        return $next($request);
        }

        $user = Auth::user();
        $method = $request->method();
        
        // تحديد نوع العملية
        $action = $this->determineAction($request, $method);
        
        // تحديد الموديل والـ ID من الراوت
        $modelInfo = $this->extractModelInfo($request);
        
        // تجميع البيانات
        $logData = [
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $modelInfo['type'],
            'model_id' => $modelInfo['id'],
            'route_name' => $routeName,
            'route_uri' => $request->path(),
            'method' => $method,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => $this->generateDescription($action, $modelInfo, $request),
        ];

        // بيانات الطلب (فقط للـ POST/PUT/PATCH/DELETE)
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $logData['request_data'] = $this->sanitizeRequestData($request);
        }

        // حفظ السجل بعد تنفيذ الطلب
        $response = $next($request);

        // حفظ اللوج (بعد نجاح العملية)
        if ($response->getStatusCode() < 400) {
            ActivityLog::create($logData);
        }

        return $response;
    }

    /**
     * تحديد نوع العملية من الراوت والـ Method
     */
    private function determineAction(Request $request, string $method): string
    {
        $routeName = $request->route()?->getName() ?? '';
        
        // Login/Logout
        if (str_contains($routeName, 'login')) {
            return 'login';
        }
        if (str_contains($routeName, 'logout')) {
            return 'logout';
        }

        // حسب HTTP Method
        return match($method) {
            'GET' => 'view',
            'POST' => str_contains($routeName, '.update') ? 'update' : 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'access',
        };
    }

    /**
     * استخراج معلومات الموديل من الراوت
     */
    private function extractModelInfo(Request $request): array
    {
        $route = $request->route();
        if (!$route) {
            return ['type' => null, 'id' => null];
        }

        $parameters = $route->parameters();
        
        foreach ($parameters as $key => $value) {
            // تخطي بعض المعاملات
            if (in_array($key, ['admin', 'id'], true)) {
                continue;
            }

            // إذا كان الموديل موجود في المعاملات
            if (is_object($value) && method_exists($value, 'getMorphClass')) {
                return [
                    'type' => get_class($value),
                    'id' => $value->getKey(),
                ];
            }

            // إذا كان ID فقط
            if ($key === 'id' || $key === 'news' || $key === 'slider' || $key === 'advertisement') {
                return [
                    'type' => $this->guessModelType($route->getName()),
                    'id' => is_object($value) ? $value->getKey() : $value,
                ];
            }
        }

        return ['type' => null, 'id' => null];
    }

    /**
     * تخمين نوع الموديل من اسم الراوت
     */
    private function guessModelType(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }

        $models = [
            'news' => \App\Models\News::class,
            'slider' => \App\Models\Slider::class,
            'sliders' => \App\Models\Slider::class,
            'advertisement' => \App\Models\Advertisement::class,
            'advertisements' => \App\Models\Advertisement::class,
            'tender' => \App\Models\Tender::class,
            'tenders' => \App\Models\Tender::class,
            'about' => \App\Models\AboutUs::class,
            'impact-stat' => \App\Models\ImpactStat::class,
            'impact-stats' => \App\Models\ImpactStat::class,
            'user' => \App\Models\User::class,
            'users' => \App\Models\User::class,
        ];

        foreach ($models as $key => $modelClass) {
            if (str_contains($routeName, $key)) {
                return $modelClass;
            }
        }

        return null;
    }

    /**
     * توليد وصف مختصر للعملية
     */
    private function generateDescription(string $action, array $modelInfo, Request $request): string
    {
        $routeName = $request->route()?->getName() ?? '';
        
        // تحديد النموذج
        $modelName = $modelInfo['type'] ? class_basename($modelInfo['type']) : 'Unknown';
        
        return match($action) {
            'login' => 'تسجيل الدخول',
            'logout' => 'تسجيل الخروج',
            'view' => "عرض صفحة: {$routeName}",
            'create' => "إضافة جديد: {$modelName}",
            'update' => "تعديل: {$modelName} #{$modelInfo['id']}",
            'delete' => "حذف: {$modelName} #{$modelInfo['id']}",
            default => "عملية: {$action} على {$routeName}",
        };
    }

    /**
     * تنظيف بيانات الطلب (إزالة الحساسة)
     */
    private function sanitizeRequestData(Request $request): array
    {
        $data = $request->except([
            '_token',
            '_method',
            'password',
            'password_confirmation',
            'current_password',
        ]);

        // تقليل حجم البيانات (أول 500 حرف)
        return array_map(function ($value) {
            if (is_string($value) && strlen($value) > 500) {
                return substr($value, 0, 500) . '...';
            }
            return $value;
        }, $data);
    }
}