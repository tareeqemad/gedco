@php
    $breadcrumbTitle     = __('admin.permissions.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.permissions.title'))

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-shield-lock"
                :title="__('admin.permissions.title')"
                :create-route="route('admin.permissions.create')"
                :create-label="__('admin.permissions.add_new')">
                <x-slot:badge>
                    <span class="stat-chip stat-chip-header">
                        <i class="bi bi-shield-check"></i>
                        {{ __('admin.permissions.total') }}: <strong id="permsTotalCount">{{ number_format($stats['total']) }}</strong>
                    </span>
                    <span class="stat-chip stat-chip-header">Web: <strong>{{ number_format($stats['web']) }}</strong></span>
                    @if($stats['api'] > 0)
                        <span class="stat-chip stat-chip-header">API: <strong>{{ number_format($stats['api']) }}</strong></span>
                    @endif
                </x-slot:badge>
            </x-admin.card-header-index>

            {{-- Search & Filter --}}
            <div class="card-body p-3">
                <form id="permsFilterForm">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div style="flex: 1 1 50%;">
                            <input type="text" name="search" id="permsSearchInput"
                                   class="form-control rounded-3"
                                   placeholder="{{ __('admin.permissions.search_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>
                        <div style="flex: 0 1 25%;">
                            <select name="guard" id="permsGuardSelect" class="form-select rounded-3">
                                <option value="">{{ __('admin.permissions.all_guards') }}</option>
                                <option value="web" {{ request('guard') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ request('guard') == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="submit" class="btn btn-primary rounded-3" id="permsSearchBtn">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.search') }}
                            </button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.actions.reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Loading --}}
            <div class="permissions-loading" id="permsLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('admin.ui.loading') }}</span>
                </div>
            </div>

            {{-- Desktop Table --}}
            <div class="permissions-table-wrap">
                <div class="table-responsive">
                    <table class="table permissions-table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>{{ __('admin.permissions.permission_name') }}</th>
                                <th class="text-center" style="width: 100px;">{{ __('admin.permissions.guard') }}</th>
                                <th style="width: 220px;">{{ __('admin.permissions.roles') }}</th>
                                <th class="text-end" style="width: 100px;">{{ __('admin.actions.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="permsTableContainer">
                            @include('admin.permissions.partials.table', ['permissionsWithRoles' => $permissionsWithRoles, 'permissions' => $permissions])
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($permissions->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div id="permsPaginationContainer">
                        @include('admin.permissions.partials.pagination', ['permissions' => $permissions])
                    </div>
                </div>
            @endif

            {{-- Grouped Permissions --}}
            @if($grouped->count() > 0)
                <div style="border-top: 1px solid #E6ECF2;">
                    <div class="px-3 py-3">
                        <h6 class="mb-2 fw-bold d-flex align-items-center gap-2" style="font-size: 0.88rem; color: #24364A;">
                            <i class="bi bi-folder" style="color: #5B7088;"></i>
                            {{ __('admin.permissions.permissions_by_group') }}
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($grouped as $group => $perms)
                                <span class="stat-chip text-capitalize">
                                    {{ $group }}
                                    <strong>{{ $perms->count() }}</strong>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </x-admin.card>
    </div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/permissions.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm       = document.getElementById('permsFilterForm');
    const searchInput      = document.getElementById('permsSearchInput');
    const guardSelect      = document.getElementById('permsGuardSelect');
    const searchBtn        = document.getElementById('permsSearchBtn');
    const tableContainer   = document.getElementById('permsTableContainer');
    const paginationContainer = document.getElementById('permsPaginationContainer');
    const loading          = document.getElementById('permsLoading');
    const totalCount       = document.getElementById('permsTotalCount');

    let isLoading = false;

    function showLoading() {
        isLoading = true;
        if (loading) loading.classList.add('active');
        if (tableContainer) tableContainer.style.opacity = '0.4';
        if (paginationContainer) paginationContainer.style.opacity = '0.4';
    }

    function hideLoading() {
        isLoading = false;
        if (loading) loading.classList.remove('active');
        if (tableContainer) tableContainer.style.opacity = '1';
        if (paginationContainer) paginationContainer.style.opacity = '1';
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

        fetch(`{{ route('admin.permissions.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (tableContainer) tableContainer.innerHTML = data.html;
                if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                if (totalCount && data.total !== undefined) totalCount.textContent = data.total;

                window.history.pushState({}, '', `{{ route('admin.permissions.index') }}?${params.toString()}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (window.adminNotifications) {
                window.adminNotifications.error('{{ __('admin.ui.search_error') }}');
            }
        })
        .finally(() => {
            hideLoading();
            if (searchBtn) {
                searchBtn.disabled = false;
                searchBtn.innerHTML = '<i class="bi bi-search me-1"></i> {{ __('admin.actions.search') }}';
            }
        });
    }

    // Form submit
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
    }

    // Pagination click delegation
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#permsPaginationContainer .pagination a');
        if (!link) return;
        e.preventDefault();

        const url = new URL(link.href);
        searchInput.value = url.searchParams.get('search') || '';
        guardSelect.value = url.searchParams.get('guard') || '';

        showLoading();

        fetch(link.href, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (tableContainer) tableContainer.innerHTML = data.html;
                if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                if (totalCount && data.total !== undefined) totalCount.textContent = data.total;
                window.history.pushState({}, '', link.href);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = link.href;
        })
        .finally(() => hideLoading());
    });
});
</script>
@endpush
@endsection
