@php
    $breadcrumbTitle = __('admin.activity_logs.title');
    $breadcrumbParent = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.activity_logs.title'))

@section('content')
    <div class="container-fluid p-0">

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
                            <h6 class="mb-0 text-muted small">{{ __('admin.activity_logs.total_activities') }}</h6>
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
                            <h6 class="mb-0 text-muted small">{{ __('admin.activity_logs.today_activities') }}</h6>
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
                            <h6 class="mb-0 text-muted small">{{ __('admin.activity_logs.active_users_today') }}</h6>
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
                    <h6 class="mb-0">{{ __('admin.activity_logs.most_active_users') }}</h6>
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

        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-list-check"
                title="{{ __('admin.activity_logs.title') }}">
                <x-slot:actions>
                    <a href="{{ route('admin.activity-logs.active-users') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-person-check me-1"></i>
                        <span class="d-none d-md-inline">{{ __('admin.activity_logs.active_users') }}</span>
                    </a>
                </x-slot:actions>
            </x-admin.card-header-index>

                <!-- Filters -->
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark mb-1">{{ __('admin.activity_logs.user') }}</label>
                            <select name="user_id" class="form-select rounded-3">
                                <option value="">{{ __('admin.activity_logs.all_users') }}</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark mb-1">العملية</label>
                            <select name="action" class="form-select rounded-3">
                                <option value="">{{ __('admin.activity_logs.all_operations') }}</option>
                                @foreach($actions as $act)
                                    <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>
                                        {{ $act }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark mb-1">{{ __('admin.activity_logs.date_from') }}</label>
                            <input type="date" name="date_from" class="form-control rounded-3" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark mb-1">{{ __('admin.activity_logs.date_to') }}</label>
                            <input type="date" name="date_to" class="form-control rounded-3" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark mb-1">الراوت</label>
                            <input type="text" name="route" class="form-control rounded-3" placeholder="بحث..." value="{{ request('route') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label mb-1">&nbsp;</label>
                            <button type="submit" class="btn btn-outline-primary w-100 rounded-3">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.query') }}
                            </button>
                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-danger w-100 rounded-3 mt-1">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.actions.clear') }}
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('admin.activity_logs.user_activity_tooltip') }}">
                                        {{ __('admin.activity_logs.user') }}
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
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('admin.activity_logs.ip_tooltip') }}">
                                        {{ __('admin.activity_logs.ip_address') }}
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('admin.activity_logs.date_tooltip') }}">
                                        {{ __('admin.activity_logs.date') }}
                                    </span>
                                </th>
                                <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('admin.activity_logs.view_user_activities') }}">
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
                                               title="{{ __('admin.activity_logs.ip_tooltip') }}">
                                            {{ $log->ip_address }}
                                        </small>
                                    </td>
                                    <td>
                                        <small data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('admin.activity_logs.date_tooltip') }}: {{ $log->created_at->format('Y-m-d H:i:s') }}">
                                            {{ $log->created_at->format('Y-m-d H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.activity-logs.user', $log->user_id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="left"
                                           title="{{ __('admin.activity_logs.view_user_activities') }} {{ $log->user->name ?? 'Unknown' }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        {{ __('admin.activity_logs.no_activities') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            عرض {{ $logs->firstItem() }} - {{ $logs->lastItem() }} من {{ $logs->total() }}
                        </div>
                        {{ $logs->links() }}
                    </div>
                </div>
                @endif
        </x-admin.card>

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
                                <small class="text-muted">{{ __('admin.activity_logs.login') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary">تسجيل خروج</span>
                                <small class="text-muted">{{ __('admin.activity_logs.logout') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>{{ __('admin.activity_logs.tip') }}</strong>
                    </div>
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
