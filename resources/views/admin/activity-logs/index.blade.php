@extends('layouts.admin')
@section('title', 'سجل الأنشطة')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">سجل الأنشطة</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">سجل الأنشطة</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- إحصائيات -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-primary text-white rounded-circle me-3">
                            <i class="bi bi-activity"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">إجمالي الأنشطة</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_activities']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-success text-white rounded-circle me-3">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">أنشطة اليوم</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['today_activities']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-info text-white rounded-circle me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">مستخدمون نشطون اليوم</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['unique_users_today'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-warning text-white rounded-circle me-3">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">مستخدمون هذا الشهر</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['unique_users_month'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات أكثر المستخدمين والعمليات -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">أكثر المستخدمين نشاطاً</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($topUsers as $topUser)
                        @if($topUser->user)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs bg-primary text-white rounded-circle me-2">
                                        {{ strtoupper(substr($topUser->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $topUser->user->name }}</div>
                                        <small class="text-muted">{{ $topUser->user->email }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary">{{ number_format($topUser->count) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">أكثر العمليات تكراراً</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($topActions as $action)
                        @php
                            $badgeColor = match($action->action) {
                                'login' => 'success',
                                'logout' => 'secondary',
                                'create' => 'primary',
                                'update' => 'warning',
                                'delete' => 'danger',
                                'view' => 'info',
                                default => 'dark',
                            };
                        @endphp
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $badgeColor }}">{{ $action->action }}</span>
                            <strong>{{ number_format($action->count) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        سجل الأنشطة
                    </h5>
                    <a href="{{ route('admin.activity-logs.active-users') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-check me-1"></i>
                        المستخدمون المتصلون
                    </a>
                </div>

                <!-- Filters -->
                <div class="card-body border-bottom bg-light">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">المستخدم</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">جميع المستخدمين</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">العملية</label>
                            <select name="action" class="form-select form-select-sm">
                                <option value="">جميع العمليات</option>
                                @foreach($actions as $act)
                                    <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>
                                        {{ $act }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">من تاريخ</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">إلى تاريخ</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">الراوت</label>
                            <input type="text" name="route" class="form-control form-control-sm" placeholder="بحث..." value="{{ request('route') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="المستخدم الذي قام بالنشاط">
                                        المستخدم
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="نوع العملية - مرر الماوس على البادج لمعرفة التفاصيل">
                                        العملية
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="وصف مختصر للنشاط الذي تم تنفيذه">
                                        الوصف
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="مسار الصفحة أو الراوت الذي تم الوصول إليه">
                                        الراوت
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="عنوان IP الخاص بالمستخدم">
                                        IP
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="تاريخ ووقت تنفيذ النشاط">
                                        التاريخ
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="عرض جميع أنشطة هذا المستخدم">
                                        إجراءات
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs bg-primary text-white rounded-circle me-2">
                                                {{ substr($log->user->name ?? 'N', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $log->user->name ?? 'Unknown' }}</div>
                                                <small class="text-muted">{{ $log->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeColor = match($log->action) {
                                                'login' => 'success',
                                                'logout' => 'secondary',
                                                'create' => 'primary',
                                                'update' => 'warning',
                                                'delete' => 'danger',
                                                'view' => 'info',
                                                default => 'dark',
                                            };
                                            $actionLabel = __('admin.activity_logs.actions.' . $log->action, [], null, $log->action);
                                            $actionDescription = __('admin.activity_logs.action_descriptions.' . $log->action, [], null, '');
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }}" 
                                              data-bs-toggle="tooltip" 
                                              data-bs-placement="top" 
                                              title="{{ $actionDescription }} - {{ $log->description }}">
                                            {{ $actionLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <small data-bs-toggle="tooltip" data-bs-placement="top" title="وصف النشاط الذي تم تنفيذه">
                                            {{ $log->description }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="مسار الصفحة: {{ $log->route_uri }}">
                                            {{ $log->route_name ?? $log->route_uri }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="عنوان IP الخاص بالمستخدم">
                                            {{ $log->ip_address }}
                                        </small>
                                    </td>
                                    <td>
                                        <small data-bs-toggle="tooltip" data-bs-placement="top" title="تاريخ ووقت النشاط: {{ $log->created_at->format('Y-m-d H:i:s') }}">
                                            {{ $log->created_at->format('Y-m-d H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.activity-logs.user', $log->user_id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="left" 
                                           title="عرض جميع أنشطة المستخدم: {{ $log->user->name ?? 'Unknown' }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        لا توجد أنشطة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Legend/Help Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        شرح أنواع العمليات
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-info">عرض صفحة</span>
                                <small class="text-muted">عرض/فتح صفحة أو محتوى</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary">إضافة جديد</span>
                                <small class="text-muted">إضافة سجل جديد</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning">تعديل</span>
                                <small class="text-muted">تعديل سجل موجود</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger">حذف</span>
                                <small class="text-muted">حذف سجل</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success">تسجيل دخول</span>
                                <small class="text-muted">تسجيل دخول المستخدم</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary">تسجيل خروج</span>
                                <small class="text-muted">تسجيل خروج المستخدم</small>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>نصيحة:</strong> مرر الماوس على أي عنصر في الجدول للحصول على معلومات إضافية. كما يمكنك استخدام الفلاتر أعلاه للبحث عن أنشطة محددة.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
