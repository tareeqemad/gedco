@php
    $breadcrumbTitle     = __('admin.permissions.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.permissions.title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 0.75rem;">
                    <div class="d-flex align-items-center gap-2 flex-wrap" style="flex: 1 1 auto;">
                        <i class="bi bi-shield-lock fs-5"></i>
                        <h5 class="mb-0 fw-bold text-white" style="font-size: 1.1rem; line-height: 1.2;">
                            {{ __('admin.permissions.title') }}
                        </h5>
                    </div>
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        <span class="d-none d-md-inline">{{ __('admin.permissions.add_new') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- إحصائيات -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">إجمالي الصلاحيات</small>
                                <h5 class="mb-0 fw-bold text-dark">{{ number_format($stats['total']) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-globe"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">Web Guard</small>
                                <h5 class="mb-0 fw-bold text-dark">{{ number_format($stats['web']) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-code-slash"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">API Guard</small>
                                <h5 class="mb-0 fw-bold text-dark">{{ number_format($stats['api']) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- البحث والفلترة -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-3 p-md-4">
                <form method="GET" action="{{ route('admin.permissions.index') }}" class="row g-3">
                    <div class="col-12 col-lg-8">
                        <label class="form-label fw-semibold text-dark mb-1">البحث</label>
                        <input type="text" 
                               name="search" 
                               class="form-control rounded-3 border-0 bg-light" 
                               style="height: 45px;"
                               placeholder="ابحث عن صلاحية..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-8 col-lg-3">
                        <label class="form-label fw-semibold text-dark mb-1">Guard</label>
                        <select name="guard" class="form-select rounded-3 border-0 bg-light" style="height: 45px;">
                            <option value="">جميع الـ Guards</option>
                            <option value="web" {{ request('guard') == 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ request('guard') == 'api' ? 'selected' : '' }}>API</option>
                        </select>
                    </div>
                    <div class="col-4 col-lg-1">
                        <label class="form-label mb-1">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm" style="height: 45px;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- قائمة الصلاحيات - Desktop Table -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>اسم الصلاحية</th>
                            <th class="text-center" style="width: 120px;">Guard</th>
                            <th class="text-center" style="width: 200px;">الأدوار</th>
                            <th class="text-center" style="width: 120px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissionsWithRoles as $item)
                            @php $perm = $item['permission']; @endphp
                            <tr class="permission-row">
                                <td class="text-center text-muted">
                                    <strong>{{ $loop->iteration + ($permissions->currentPage()-1)*$permissions->perPage() }}</strong>
                                </td>
                                <td>
                                    @php
                                        $group = explode('.', $perm->name)[0] ?? 'other';
                                    @endphp
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem; width: fit-content;">
                                            {{ ucfirst($group) }}
                                        </span>
                                        <span class="fw-semibold text-dark">{{ $perm->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $perm->guard_name === 'web' ? 'info' : 'success' }} text-white rounded-pill px-3 py-1">
                                        {{ strtoupper($perm->guard_name) }}
                                    </span>
                                </td>
                                <td>
                                    @if($item['roles_count'] > 0)
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <small class="text-muted">
                                                {{ $item['roles_count'] }} {{ $item['roles_count'] == 1 ? 'دور' : 'أدوار' }}
                                            </small>
                                            <div class="d-flex gap-1 flex-wrap justify-content-center">
                                                @foreach(array_slice($item['roles'], 0, 3) as $role)
                                                    <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $role }}</span>
                                                @endforeach
                                                @if($item['roles_count'] > 3)
                                                    <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 0.7rem;">+{{ $item['roles_count'] - 3 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted text-center d-block">لا توجد أدوار</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.permissions.edit', $perm->id) }}" 
                                           class="btn btn-sm btn-outline-warning rounded-3"
                                           title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.permissions.destroy', $perm->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('{{ __('admin.permissions.delete_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger rounded-3"
                                                    title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-shield-x display-4 text-muted opacity-50 d-block mb-3"></i>
                                    <h5 class="text-muted mb-2">لا توجد صلاحيات</h5>
                                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary rounded-3 shadow-sm">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        {{ __('admin.permissions.add_new') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($permissions->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            عرض {{ $permissions->firstItem() }} - {{ $permissions->lastItem() }} من {{ $permissions->total() }}
                        </div>
                        {{ $permissions->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- قائمة الصلاحيات - Mobile Cards -->
        <div class="d-md-none">
            @forelse($permissionsWithRoles as $item)
                @php $perm = $item['permission']; @endphp
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-3">
                    <div class="card-body p-3">
                        @php
                            $group = explode('.', $perm->name)[0] ?? 'other';
                        @endphp
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <div class="mb-1">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                        {{ ucfirst($group) }}
                                    </span>
                                    <span class="badge bg-{{ $perm->guard_name === 'web' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1 ms-1" style="font-size: 0.7rem;">
                                        {{ strtoupper($perm->guard_name) }}
                                    </span>
                                </div>
                                <h6 class="mb-0 fw-semibold text-dark">{{ $perm->name }}</h6>
                            </div>
                            <span class="text-muted small">#{{ $loop->iteration + ($permissions->currentPage()-1)*$permissions->perPage() }}</span>
                        </div>
                        
                        @if($item['roles_count'] > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $item['roles_count'] }} {{ $item['roles_count'] == 1 ? 'دور' : 'أدوار' }}
                                </small>
                                <div class="d-flex gap-1 flex-wrap">
                                    @foreach(array_slice($item['roles'], 0, 3) as $role)
                                        <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $role }}</span>
                                    @endforeach
                                    @if($item['roles_count'] > 3)
                                        <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 0.7rem;">+{{ $item['roles_count'] - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    لا توجد أدوار
                                </small>
                            </div>
                        @endif
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.permissions.edit', $perm->id) }}" 
                               class="btn btn-sm btn-outline-warning rounded-3 flex-fill">
                                <i class="bi bi-pencil me-1"></i>
                                تعديل
                            </a>
                            <form action="{{ route('admin.permissions.destroy', $perm->id) }}" 
                                  method="POST" 
                                  class="d-inline flex-fill"
                                  onsubmit="return confirm('{{ __('admin.permissions.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger rounded-3 w-100">
                                    <i class="bi bi-trash me-1"></i>
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-shield-x display-4 text-muted opacity-50 d-block mb-3"></i>
                        <h5 class="text-muted mb-2">لا توجد صلاحيات</h5>
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary rounded-3 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            {{ __('admin.permissions.add_new') }}
                        </a>
                    </div>
                </div>
            @endforelse

            @if($permissions->hasPages())
                <div class="card border-0 shadow-sm rounded-4 bg-white mt-3">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                            <div class="text-muted">
                                عرض {{ $permissions->firstItem() }} - {{ $permissions->lastItem() }} من {{ $permissions->total() }}
                            </div>
                            {{ $permissions->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- مجموعات الصلاحيات -->
        @if($grouped->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 bg-white mt-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-3 px-3">
                <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
                    <i class="bi bi-folder"></i>
                    الصلاحيات حسب المجموعة
                </h6>
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    @foreach($grouped as $group => $perms)
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-light rounded-4 p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 text-capitalize" style="font-size: 0.9rem;">{{ $group }}</h6>
                                        <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;">{{ $perms->count() }} صلاحية</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('styles')
    <style>
        .permission-row:hover {
            background-color: #f8f9fa;
        }
        
        .table thead th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        @media (max-width: 767.98px) {
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
    </style>
    @endpush
@endsection
