@php
    $breadcrumbTitle = __('admin.activity_logs.user_activities') . ': ' . $user->display_name;
    $breadcrumbParent = __('admin.activity_logs.title');
    $breadcrumbParentUrl = route('admin.activity-logs.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.activity_logs.user_activities'))

@section('content')
    <div class="container-fluid p-0">

    <!-- معلومات المستخدم -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-lg bg-primary text-white rounded-circle me-3">
                            {{ substr($user->display_name, 0, 1) }}
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $user->display_name }}</h5>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                    @if($user->roles->count() > 0)
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">{{ __('admin.activity_logs_ui.permissions') }}</small>
                            @foreach($user->roles as $role)
                                <span class="badge bg-info me-1">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($stats['total_activities']) }}</h3>
                            <small class="text-muted">{{ __('admin.activity_logs.total_activities') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="mb-0 fw-bold text-success">{{ number_format($stats['today_activities']) }}</h3>
                            <small class="text-muted">{{ __('admin.activity_logs.today_activities') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="mb-0 text-muted small">{{ __('admin.activity_logs_ui.first_activity') }}</h6>
                            <small class="fw-semibold">{{ $stats['first_activity']?->format('Y-m-d') ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="mb-0 text-muted small">{{ __('admin.activity_logs_ui.last_activity') }}</h6>
                            <small class="fw-semibold">{{ $stats['last_activity']?->diffForHumans() ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">{{ __('admin.activity_logs_ui.most_visited_pages') }}</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($topPages as $page)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <small class="fw-semibold d-block">{{ $page->route_name ?? $page->route_uri }}</small>
                                <small class="text-muted">{{ $page->route_uri }}</small>
                            </div>
                            <span class="badge bg-primary">{{ $page->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">{{ __('admin.activity_logs_ui.most_operations') }}</h6>
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
                            <strong>{{ $action->count }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- سجل الأنشطة -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-list-check"
                title="{{ __('admin.activity_logs.title') }}" />

                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.activity-logs.user', $user->id) }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark mb-1">{{ __('admin.activity_logs_ui.operation') }}</label>
                            <select name="action" class="form-select rounded-3">
                                <option value="">{{ __('admin.activity_logs.all_operations') }}</option>
                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>{{ __('admin.activity_logs_ui.filter_login') }}</option>
                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>{{ __('admin.activity_logs_ui.filter_logout') }}</option>
                                <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>{{ __('admin.activity_logs_ui.filter_view') }}</option>
                                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>{{ __('admin.activity_logs_ui.filter_create') }}</option>
                                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>{{ __('admin.activity_logs_ui.filter_update') }}</option>
                                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>{{ __('admin.activity_logs_ui.filter_delete') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark mb-1">{{ __('admin.activity_logs.date_from') }}</label>
                            <input type="date" name="date_from" class="form-control rounded-3" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark mb-1">{{ __('admin.activity_logs.date_to') }}</label>
                            <input type="date" name="date_to" class="form-control rounded-3" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1">&nbsp;</label>
                            <button type="submit" class="btn btn-outline-primary w-100 rounded-3">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.query') }}
                            </button>
                            <a href="{{ route('admin.activity-logs.user', $user->id) }}" class="btn btn-outline-danger w-100 rounded-3 mt-1">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.actions.clear') }}
                            </a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('admin.activity_logs_ui.operation') }}</th>
                                <th>{{ __('admin.activity_logs_ui.description') }}</th>
                                <th>{{ __('admin.activity_logs_ui.route') }}</th>
                                <th>{{ __('admin.activity_logs.ip_address') }}</th>
                                <th>{{ __('admin.activity_logs.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
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
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $badgeColor }}">{{ $log->action }}</span>
                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td><small class="text-muted">{{ $log->route_name ?? $log->route_uri }}</small></td>
                                    <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                                    <td><small>{{ $log->created_at->format('Y-m-d H:i:s') }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
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
                            {{ __('admin.activity_logs_ui.showing_range', ['first' => $logs->firstItem(), 'last' => $logs->lastItem(), 'total' => $logs->total()]) }}
                        </div>
                        {{ $logs->links() }}
                    </div>
                </div>
                @endif
        </x-admin.card>
    </div>
@endsection
