@php
    $breadcrumbTitle = __('admin.activity_logs.title');
    $breadcrumbParent = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.activity_logs.title'))

@section('content')
    <div class="container-fluid p-0">

        {{-- KPI Stats Row --}}
        <div class="row mb-4">
            <div class="col-6 col-lg-3 mb-3">
                <div class="dash-kpi">
                    <div class="dash-kpi-icon" style="background: rgba(2, 132, 199, 0.08); color: #0284C7;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="dash-kpi-value">{{ number_format($stats['total_activities']) }}</div>
                        <div class="dash-kpi-label">{{ __('admin.activity_logs.total_activities') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="dash-kpi">
                    <div class="dash-kpi-icon" style="background: rgba(22, 163, 74, 0.08); color: #16A34A;">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div>
                        <div class="dash-kpi-value">{{ number_format($stats['today_activities']) }}</div>
                        <div class="dash-kpi-label">{{ __('admin.activity_logs.today_activities') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="dash-kpi">
                    <div class="dash-kpi-icon" style="background: rgba(217, 119, 6, 0.08); color: #D97706;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="dash-kpi-value">{{ $stats['unique_users_today'] }}</div>
                        <div class="dash-kpi-label">{{ __('admin.activity_logs.active_users_today') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="dash-kpi">
                    <div class="dash-kpi-icon" style="background: rgba(139, 92, 246, 0.08); color: #8B5CF6;">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <div class="dash-kpi-value">{{ $stats['unique_users_month'] }}</div>
                        <div class="dash-kpi-label">{{ __('admin.activity_logs_ui.users_this_month') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Insights Row --}}
        <div class="row mb-4">
            @if($topUsers->count())
            <div class="col-12 col-lg-6 mb-3">
                <x-admin.card class="h-100 mb-0">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #24364A; font-size: 0.85rem;">
                            <i class="bi bi-trophy" style="color: #D97706;"></i>
                            {{ __('admin.activity_logs.most_active_users') }}
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($topUsers->take(8) as $tu)
                                @if($tu->user)
                                    <a href="{{ route('admin.activity-logs.user', $tu->user_id) }}" class="text-decoration-none">
                                        <span class="stat-chip" style="font-size: 0.72rem;">
                                            <strong>{{ $tu->user->display_name }}</strong>
                                            <span class="stat-chip stat-chip-primary" style="font-size: 0.6rem; padding: 0.1rem 0.5rem;">{{ $tu->count }}</span>
                                        </span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </x-admin.card>
            </div>
            @endif

            @if($topActions->count())
            <div class="col-12 col-lg-6 mb-3">
                <x-admin.card class="h-100 mb-0">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #24364A; font-size: 0.85rem;">
                            <i class="bi bi-bar-chart" style="color: #0284C7;"></i>
                            {{ __('admin.activity_logs_ui.most_frequent_ops') }}
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($topActions as $ta)
                                @php
                                    $badgeClass = match($ta->action) {
                                        'login' => 'activity-badge-login',
                                        'logout' => 'activity-badge-logout',
                                        'create' => 'activity-badge-create',
                                        'update' => 'activity-badge-update',
                                        'delete' => 'activity-badge-delete',
                                        'view' => 'activity-badge-view',
                                        default => '',
                                    };
                                @endphp
                                <span class="stat-chip {{ $badgeClass }}" style="font-size: 0.72rem;">
                                    {{ $ta->action }} <strong>{{ number_format($ta->count) }}</strong>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </x-admin.card>
            </div>
            @endif
        </div>

        {{-- Main Logs Card --}}
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-clock-history"
                :title="__('admin.activity_logs.title')">
                <x-slot:badge>
                    <span class="stat-chip stat-chip-header" id="logsHeaderCount">
                        <i class="bi bi-list-ul"></i>
                        <strong>{{ number_format($logs->total()) }}</strong>
                    </span>
                </x-slot:badge>
            </x-admin.card-header-index>

            {{-- Filters --}}
            <div class="card-body p-3 admin-filters">
                <form id="logsFilterForm">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div class="filter-field-wide">
                            <select name="user_id" id="filterUser" class="form-select rounded-3">
                                <option value="">{{ __('admin.activity_logs.all_users') }}</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field-medium">
                            <select name="action" id="filterAction" class="form-select rounded-3">
                                <option value="">{{ __('admin.activity_logs.all_operations') }}</option>
                                @foreach($actions as $a)
                                    <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field-date">
                            <label class="date-field-label">{{ __('admin.activity_logs.date_from') }}</label>
                            <input type="date" name="date_from" id="filterDateFrom"
                                   class="form-control rounded-3"
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="filter-field-date">
                            <label class="date-field-label">{{ __('admin.activity_logs.date_to') }}</label>
                            <input type="date" name="date_to" id="filterDateTo"
                                   class="form-control rounded-3"
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="filter-field-auto">
                            <button type="submit" class="btn btn-primary rounded-3" id="logsSearchBtn">
                                <i class="bi bi-search me-1"></i> {{ __('admin.ui.query') }}
                            </button>
                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.ui.clear') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Loading --}}
            <div class="ajax-loading" id="logsLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('admin.ui.loading') }}</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table permissions-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.activity_logs.user') }}</th>
                            <th>{{ __('admin.activity_logs_ui.operation') }}</th>
                            <th>{{ __('admin.activity_logs_ui.description') }}</th>
                            <th>{{ __('admin.activity_logs_ui.route') }}</th>
                            <th>{{ __('admin.activity_logs.ip_address') }}</th>
                            <th>{{ __('admin.activity_logs.date') }}</th>
                            <th class="text-end">{{ __('admin.activity_logs_ui.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="logsTableBody">
                        @include('admin.activity-logs.partials.table', ['logs' => $logs])
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div id="logsPaginationContainer">
                        @include('admin.activity-logs.partials.pagination', ['logs' => $logs])
                    </div>
                </div>
            @endif

            {{-- Operations Legend --}}
            <div style="border-top: 1px solid #E6ECF2;">
                <div class="px-3 py-3">
                    <h6 class="mb-2 fw-bold d-flex align-items-center gap-2" style="font-size: 0.85rem; color: #24364A;">
                        <i class="bi bi-info-circle" style="color: #5B7088;"></i>
                        {{ __('admin.activity_logs_ui.operations_legend') }}
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $ops = [
                                ['key' => 'view', 'class' => 'activity-badge-view', 'icon' => 'bi-eye'],
                                ['key' => 'create', 'class' => 'activity-badge-create', 'icon' => 'bi-plus-circle'],
                                ['key' => 'update', 'class' => 'activity-badge-update', 'icon' => 'bi-pencil'],
                                ['key' => 'delete', 'class' => 'activity-badge-delete', 'icon' => 'bi-trash'],
                                ['key' => 'login', 'class' => 'activity-badge-login', 'icon' => 'bi-box-arrow-in-right'],
                                ['key' => 'logout', 'class' => 'activity-badge-logout', 'icon' => 'bi-box-arrow-right'],
                            ];
                        @endphp
                        @foreach($ops as $op)
                            <span class="stat-chip {{ $op['class'] }}" style="font-size: 0.72rem;" title="{{ __('admin.activity_logs_ui.op_' . $op['key'] . '_desc') }}">
                                <i class="bi {{ $op['icon'] }}"></i>
                                {{ __('admin.activity_logs_ui.op_' . $op['key']) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm   = document.getElementById('logsFilterForm');
    const searchBtn    = document.getElementById('logsSearchBtn');
    const tableBody    = document.getElementById('logsTableBody');
    const paginationEl = document.getElementById('logsPaginationContainer');
    const loading      = document.getElementById('logsLoading');
    const headerCount  = document.getElementById('logsHeaderCount');

    let isLoading = false;

    function showLoading() {
        isLoading = true;
        if (loading) loading.classList.add('active');
        if (tableBody) tableBody.style.opacity = '0.4';
        if (paginationEl) paginationEl.style.opacity = '0.4';
    }

    function hideLoading() {
        isLoading = false;
        if (loading) loading.classList.remove('active');
        if (tableBody) tableBody.style.opacity = '1';
        if (paginationEl) paginationEl.style.opacity = '1';
    }

    function performSearch() {
        if (isLoading) return;

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        showLoading();
        if (searchBtn) {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin.ui.searching') }}';
        }

        fetch(`{{ route('admin.activity-logs.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (tableBody) tableBody.innerHTML = data.html;
                if (paginationEl) paginationEl.innerHTML = data.pagination;
                if (headerCount) headerCount.innerHTML = '<i class="bi bi-list-ul"></i> <strong>' + data.total.toLocaleString() + '</strong>';
                window.history.pushState({}, '', `{{ route('admin.activity-logs.index') }}?${params.toString()}`);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            if (window.adminNotifications) window.adminNotifications.error('{{ __('admin.ui.search_error') }}');
        })
        .finally(() => {
            hideLoading();
            if (searchBtn) {
                searchBtn.disabled = false;
                searchBtn.innerHTML = '<i class="bi bi-search me-1"></i> {{ __('admin.ui.query') }}';
            }
        });
    }

    filterForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    document.addEventListener('click', function(e) {
        const link = e.target.closest('#logsPaginationContainer .pagination a');
        if (!link) return;
        e.preventDefault();

        showLoading();
        fetch(link.href, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (tableBody) tableBody.innerHTML = data.html;
                if (paginationEl) paginationEl.innerHTML = data.pagination;
                if (headerCount) headerCount.innerHTML = '<i class="bi bi-list-ul"></i> <strong>' + data.total.toLocaleString() + '</strong>';
                window.history.pushState({}, '', link.href);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        })
        .catch(() => window.location.href = link.href)
        .finally(() => hideLoading());
    });
});
</script>
@endpush
@endsection
