@php
    $breadcrumbTitle     = __('admin.menu.roles');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.menu.roles'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 0.75rem;">
                    <div class="d-flex align-items-center gap-2 flex-wrap" style="flex: 1 1 auto;">
                        <i class="bi bi-person-badge fs-5"></i>
                        <h5 class="mb-0 fw-bold text-white" style="font-size: 1.1rem; line-height: 1.2;">
                            {{ __('admin.menu.roles') }}
                        </h5>
                    </div>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        <span class="d-none d-md-inline">{{ __('admin.menu.add_role') }}</span>
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
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">إجمالي الأدوار</small>
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
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">المستخدمون</small>
                                <h5 class="mb-0 fw-bold text-dark">{{ number_format($stats['total_users']) }}</h5>
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
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">الصلاحيات المتاحة</small>
                                <h5 class="mb-0 fw-bold text-dark">{{ number_format($stats['total_permissions']) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- البحث -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-3 p-md-4">
                <form method="GET" action="{{ route('admin.roles.index') }}" class="row g-3">
                    <div class="col-12 col-lg-11">
                        <label class="form-label fw-semibold text-dark mb-1">البحث</label>
                        <input type="text" 
                               name="search" 
                               class="form-control rounded-3 border-0 bg-light" 
                               style="height: 45px;"
                               placeholder="ابحث عن دور..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-12 col-lg-1">
                        <label class="form-label mb-1">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm" style="height: 45px;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- قائمة الأدوار - Desktop Table -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>اسم الدور</th>
                            <th class="text-center" style="width: 200px;">الصلاحيات</th>
                            <th class="text-center" style="width: 200px;">المستخدمون</th>
                            <th class="text-center" style="width: 120px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rolesWithDetails as $item)
                            @php $role = $item['role']; @endphp
                            <tr class="role-row">
                                <td class="text-center text-muted">
                                    <strong>{{ $loop->iteration + ($roles->currentPage()-1)*$roles->perPage() }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold text-dark">{{ $role->name }}</span>
                                        @if($role->name === 'super-admin')
                                            <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;" title="دور محمي">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($item['permissions_count'] > 0)
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <small class="text-muted">
                                                {{ $item['permissions_count'] }} {{ $item['permissions_count'] == 1 ? 'صلاحية' : 'صلاحيات' }}
                                            </small>
                                            <div class="d-flex gap-1 flex-wrap justify-content-center">
                                                @foreach(array_slice($item['permissions'], 0, 3) as $perm)
                                                    <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $perm }}</span>
                                                @endforeach
                                                @if($item['permissions_count'] > 3)
                                                    <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 0.7rem;">+{{ $item['permissions_count'] - 3 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted text-center d-block">لا توجد صلاحيات</small>
                                    @endif
                                </td>
                                <td>
                                    @if($role->users_count > 0)
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <small class="text-muted">
                                                {{ $role->users_count }} {{ $role->users_count == 1 ? 'مستخدم' : 'مستخدمين' }}
                                            </small>
                                            <div class="d-flex gap-1 justify-content-center">
                                                @foreach($item['users']->take(3) as $user)
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: bold;"
                                                         title="{{ $user->name }}">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endforeach
                                                @if($role->users_count > 3)
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: bold;"
                                                         title="والمزيد...">
                                                        +{{ $role->users_count - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted text-center d-block">لا يوجد مستخدمين</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                           class="btn btn-sm btn-outline-warning rounded-3"
                                           title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($role->name !== 'super-admin')
                                            <form action="{{ route('admin.roles.destroy', $role->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('{{ __('admin.roles.delete_confirm') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger rounded-3 {{ $role->users_count > 0 ? 'disabled' : '' }}"
                                                        title="{{ $role->users_count > 0 ? 'لا يمكن الحذف - يوجد مستخدمين' : 'حذف' }}"
                                                        @if($role->users_count > 0) disabled @endif>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-person-badge display-4 text-muted opacity-50 d-block mb-3"></i>
                                    <h5 class="text-muted mb-2">لا توجد أدوار</h5>
                                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary rounded-3 shadow-sm">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        {{ __('admin.menu.add_role') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($roles->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            عرض {{ $roles->firstItem() }} - {{ $roles->lastItem() }} من {{ $roles->total() }}
                        </div>
                        {{ $roles->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- قائمة الأدوار - Mobile Cards -->
        <div class="d-md-none">
            @forelse($rolesWithDetails as $item)
                @php $role = $item['role']; @endphp
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="mb-0 fw-semibold text-dark">{{ $role->name }}</h6>
                                    @if($role->name === 'super-admin')
                                        <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;" title="دور محمي">
                                            <i class="bi bi-shield-lock"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-muted small">#{{ $loop->iteration + ($roles->currentPage()-1)*$roles->perPage() }}</span>
                        </div>
                        
                        @if($item['permissions_count'] > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-shield-check me-1"></i>
                                    {{ $item['permissions_count'] }} {{ $item['permissions_count'] == 1 ? 'صلاحية' : 'صلاحيات' }}
                                </small>
                                <div class="d-flex gap-1 flex-wrap">
                                    @foreach(array_slice($item['permissions'], 0, 3) as $perm)
                                        <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $perm }}</span>
                                    @endforeach
                                    @if($item['permissions_count'] > 3)
                                        <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 0.7rem;">+{{ $item['permissions_count'] - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    لا توجد صلاحيات
                                </small>
                            </div>
                        @endif
                        
                        @if($role->users_count > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $role->users_count }} {{ $role->users_count == 1 ? 'مستخدم' : 'مستخدمين' }}
                                </small>
                                <div class="d-flex gap-1">
                                    @foreach($item['users']->take(3) as $user)
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: bold;"
                                             title="{{ $user->name }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($role->users_count > 3)
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: bold;"
                                             title="والمزيد...">
                                            +{{ $role->users_count - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    لا يوجد مستخدمين
                                </small>
                            </div>
                        @endif
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.roles.edit', $role->id) }}" 
                               class="btn btn-sm btn-outline-warning rounded-3 flex-fill">
                                <i class="bi bi-pencil me-1"></i>
                                تعديل
                            </a>
                            @if($role->name !== 'super-admin')
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" 
                                      method="POST" 
                                      class="d-inline flex-fill"
                                      onsubmit="return confirm('{{ __('admin.roles.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger rounded-3 w-100 {{ $role->users_count > 0 ? 'disabled' : '' }}"
                                            @if($role->users_count > 0) disabled @endif>
                                        <i class="bi bi-trash me-1"></i>
                                        حذف
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-person-badge display-4 text-muted opacity-50 d-block mb-3"></i>
                        <h5 class="text-muted mb-2">لا توجد أدوار</h5>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary rounded-3 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            {{ __('admin.menu.add_role') }}
                        </a>
                    </div>
                </div>
            @endforelse

            @if($roles->hasPages())
                <div class="card border-0 shadow-sm rounded-4 bg-white mt-3">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                            <div class="text-muted">
                                عرض {{ $roles->firstItem() }} - {{ $roles->lastItem() }} من {{ $roles->total() }}
                            </div>
                            {{ $roles->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        .role-row:hover {
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
