@extends('layouts.admin')
@section('title', __('admin.permissions.title'))


@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.permissions.title') }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.permissions.index') }}">{{ __('admin.menu.permissions') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-primary text-white rounded-circle me-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">إجمالي الصلاحيات</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-info text-white rounded-circle me-3">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">Web Guard</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['web']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-success text-white rounded-circle me-3">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">API Guard</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['api']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- البحث والفلترة -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.permissions.index') }}" class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small">البحث</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="ابحث عن صلاحية..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Guard</label>
                            <select name="guard" class="form-select">
                                <option value="">جميع الـ Guards</option>
                                <option value="web" {{ request('guard') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ request('guard') == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- قائمة الصلاحيات -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check me-2 text-primary"></i>
                        {{ __('admin.permissions.title') }}
                        <span class="badge bg-primary ms-2">{{ $permissions->total() }}</span>
                    </h5>
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        {{ __('admin.permissions.add_new') }}
                    </a>
                </div>

                <div class="card-body p-0">
                    @forelse($permissionsWithRoles as $item)
                        @php $perm = $item['permission']; @endphp
                        <div class="permission-card border-bottom p-4">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle mx-auto">
                                        {{ $loop->iteration + ($permissions->currentPage()-1)*$permissions->perPage() }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        @php
                                            $group = explode('.', $perm->name)[0] ?? 'other';
                                        @endphp
                                        <span class="permission-group">{{ ucfirst($group) }}</span>
                                    </div>
                                    <div class="permission-name">
                                        {{ $perm->name }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="guard-badge bg-{{ $perm->guard_name === 'web' ? 'info' : 'success' }} text-white">
                                        <i class="bi bi-{{ $perm->guard_name === 'web' ? 'globe' : 'code-slash' }} me-1"></i>
                                        {{ strtoupper($perm->guard_name) }}
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    @if($item['roles_count'] > 0)
                                        <div class="mb-1">
                                            <small class="text-muted d-block mb-1">
                                                <i class="bi bi-people me-1"></i>
                                                {{ $item['roles_count'] }} {{ $item['roles_count'] == 1 ? 'دور' : 'أدوار' }}
                                            </small>
                                            <div class="roles-list">
                                                @foreach(array_slice($item['roles'], 0, 3) as $role)
                                                    <span class="role-badge">{{ $role }}</span>
                                                @endforeach
                                                @if($item['roles_count'] > 3)
                                                    <span class="role-badge">+{{ $item['roles_count'] - 3 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            لا توجد أدوار
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.permissions.edit', $perm->id) }}" 
                                           class="btn btn-sm btn-outline-warning"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="تعديل الصلاحية">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.permissions.destroy', $perm->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('{{ __('admin.permissions.delete_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="حذف الصلاحية">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-shield-x fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">لا توجد صلاحيات</h5>
                            <p class="text-muted mb-4">ابدأ بإضافة صلاحية جديدة</p>
                            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>
                                {{ __('admin.permissions.add_new') }}
                            </a>
                        </div>
                    @endforelse
                </div>

                @if($permissions->hasPages())
                    <div class="card-footer bg-white">
                        {{ $permissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- مجموعات الصلاحيات -->
    @if($grouped->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-folder me-2 text-primary"></i>
                        الصلاحيات حسب المجموعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($grouped as $group => $perms)
                            <div class="col-md-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2 text-capitalize">{{ $group }}</h6>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="badge bg-primary">{{ $perms->count() }} صلاحية</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush