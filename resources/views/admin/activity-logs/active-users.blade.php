@php
    $breadcrumbTitle = __('admin.activity_logs_ui.online_title');
    $breadcrumbParent = __('admin.activity_logs.title');
    $breadcrumbParentUrl = route('admin.activity-logs.index');

    $totalToday = $activeUsers->sum('today_count');
    $firstLoginsToday = $activeUsers->filter(fn($u) => $u['first_login_today'])->count();
@endphp
@extends('layouts.admin')
@section('title', __('admin.activity_logs_ui.online_title'))

@section('content')
    <div class="container-fluid p-0">

        {{-- KPI Stats Row --}}
        <div class="row mb-4">
            <div class="col-6 col-lg-4 mb-3">
                <div class="dash-kpi">
                    <div class="dash-kpi-icon" style="background: rgba(22, 163, 74, 0.08); color: #16A34A;">
                        <i class="bi bi-broadcast"></i>
                    </div>
                    <div>
                        <div class="dash-kpi-value" id="kpiActiveCount">{{ $activeUsers->count() }}</div>
                        <div class="dash-kpi-label">{{ __('admin.activity_logs_ui.active_now') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4 mb-3">
                <div class="dash-kpi">
                    <div class="dash-kpi-icon" style="background: rgba(2, 132, 199, 0.08); color: #0284C7;">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div>
                        <div class="dash-kpi-value">{{ number_format($totalToday) }}</div>
                        <div class="dash-kpi-label">{{ __('admin.activity_logs_ui.activities_today') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 mb-3">
                <div class="dash-kpi">
                    <div class="dash-kpi-icon" style="background: rgba(217, 119, 6, 0.08); color: #D97706;">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <div>
                        <div class="dash-kpi-value">{{ $firstLoginsToday }}</div>
                        <div class="dash-kpi-label">{{ __('admin.activity_logs_ui.first_logins_today') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-broadcast"
                :title="__('admin.activity_logs_ui.online_title')">
                <x-slot:badge>
                    <span class="stat-chip stat-chip-header">
                        <span class="auto-refresh-dot" id="refreshDot"></span>
                        <span id="headerCount">{{ $activeUsers->count() }}</span> {{ __('admin.activity_logs_ui.active_now') }}
                    </span>
                </x-slot:badge>
                <x-slot:actions>
                    <label class="auto-refresh-toggle text-white">
                        <input type="checkbox" class="form-check-input" id="autoRefreshToggle" checked>
                        {{ __('admin.activity_logs_ui.auto_refresh') }}
                    </label>
                </x-slot:actions>
            </x-admin.card-header-index>

            {{-- Search --}}
            <div class="card-body p-3">
                <form id="activeUsersFilterForm">
                    <div class="d-flex gap-2 align-items-center">
                        <div style="flex: 1 1 auto;">
                            <input type="text" name="search" id="activeUsersSearch"
                                   class="form-control rounded-3"
                                   placeholder="{{ __('admin.activity_logs_ui.search_name_email') }}"
                                   value="{{ request('search') }}">
                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="submit" class="btn btn-primary rounded-3" id="searchBtn">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Loading --}}
            <div class="active-users-loading" id="activeUsersLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('admin.ui.loading') }}</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table active-users-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.activity_logs_ui.user_col') }}</th>
                            <th>{{ __('admin.activity_logs_ui.role_col') }}</th>
                            <th>{{ __('admin.activity_logs_ui.last_activity_col') }}</th>
                            <th class="text-center">{{ __('admin.activity_logs_ui.today_col') }}</th>
                            <th class="text-center">{{ __('admin.activity_logs_ui.first_login_today') }}</th>
                        </tr>
                    </thead>
                    <tbody id="activeUsersTableBody">
                        @include('admin.activity-logs.partials.active-users-table', ['activeUsers' => $activeUsers])
                    </tbody>
                </table>
            </div>

            {{-- Last update footer --}}
            <div class="card-footer bg-white border-top py-2 px-3">
                <small class="text-muted" style="font-size: 0.72rem;">
                    <i class="bi bi-clock me-1"></i>
                    {{ __('admin.activity_logs_ui.last_update') }} <span id="lastUpdateTime">{{ now()->format('h:i:s A') }}</span>
                </small>
            </div>
        </x-admin.card>
    </div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/active-users.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm  = document.getElementById('activeUsersFilterForm');
    const searchInput = document.getElementById('activeUsersSearch');
    const searchBtn   = document.getElementById('searchBtn');
    const tableBody   = document.getElementById('activeUsersTableBody');
    const loading     = document.getElementById('activeUsersLoading');
    const headerCount = document.getElementById('headerCount');
    const kpiCount    = document.getElementById('kpiActiveCount');
    const lastUpdate  = document.getElementById('lastUpdateTime');
    const refreshToggle = document.getElementById('autoRefreshToggle');
    const refreshDot  = document.getElementById('refreshDot');

    let isLoading = false;
    let refreshInterval = null;

    function showLoading() {
        isLoading = true;
        if (loading) loading.classList.add('active');
        if (tableBody) tableBody.style.opacity = '0.4';
    }

    function hideLoading() {
        isLoading = false;
        if (loading) loading.classList.remove('active');
        if (tableBody) tableBody.style.opacity = '1';
    }

    function fetchUsers(url) {
        if (isLoading) return;
        showLoading();

        fetch(url || '{{ route('admin.activity-logs.active-users') }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (tableBody) tableBody.innerHTML = data.html;
                if (headerCount) headerCount.textContent = data.count;
                if (kpiCount) kpiCount.textContent = data.count;
                if (lastUpdate) lastUpdate.textContent = new Date().toLocaleTimeString();
            }
        })
        .catch(err => console.error('Fetch error:', err))
        .finally(() => hideLoading());
    }

    // Search form
    filterForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        const search = searchInput?.value?.trim();
        const params = search ? `?search=${encodeURIComponent(search)}` : '';
        fetchUsers(`{{ route('admin.activity-logs.active-users') }}${params}`);
    });

    // Auto-refresh every 30s
    function startAutoRefresh() {
        stopAutoRefresh();
        refreshInterval = setInterval(() => fetchUsers(), 30000);
        if (refreshDot) refreshDot.classList.remove('paused');
    }

    function stopAutoRefresh() {
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = null;
        if (refreshDot) refreshDot.classList.add('paused');
    }

    refreshToggle?.addEventListener('change', function() {
        this.checked ? startAutoRefresh() : stopAutoRefresh();
    });

    // Start auto-refresh by default
    startAutoRefresh();
});
</script>
@endpush
@endsection
