@extends('layouts.admin')
@section('title', __('admin.menu.roles'))


@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.menu.roles') }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">{{ __('admin.menu.roles') }}</a>
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
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">إجمالي الأدوار</h6>
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
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">المستخدمون</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_users']) }}</h3>
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
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">الصلاحيات المتاحة</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_permissions']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- البحث -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.roles.index') }}" class="row g-3">
                        <div class="col-md-11">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="ابحث عن دور..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- قائمة الأدوار -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2 text-primary"></i>
                        {{ __('admin.menu.roles') }}
                        <span class="badge bg-primary ms-2">{{ $roles->total() }}</span>
                    </h5>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        {{ __('admin.menu.add_role') }}
                    </a>
                </div>

                <div class="card-body p-0">
                    @forelse($rolesWithDetails as $item)
                        @php $role = $item['role']; @endphp
                        <div class="role-card border-bottom p-4">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle mx-auto">
                                        {{ $loop->iteration + ($roles->currentPage()-1)*$roles->perPage() }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <span class="role-name-badge {{ $role->name === 'super-admin' ? 'super-admin-badge' : '' }}">
                                            {{ $role->name }}
                                        </span>
                                        @if($role->name === 'super-admin')
                                            <span class="badge bg-danger ms-2" data-bs-toggle="tooltip" title="دور محمي">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-shield-check me-1"></i>
                                            {{ $item['permissions_count'] }} {{ $item['permissions_count'] == 1 ? 'صلاحية' : 'صلاحيات' }}
                                        </small>
                                        @if(count($item['permissions']) > 0)
                                            <div class="permissions-list">
                                                @foreach($item['permissions'] as $perm)
                                                    <span class="permission-badge">{{ $perm }}</span>
                                                @endforeach
                                                @if($item['permissions_count'] > 5)
                                                    <span class="permission-badge">+{{ $item['permissions_count'] - 5 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                لا توجد صلاحيات
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-people me-1"></i>
                                            {{ $role->users_count }} {{ $role->users_count == 1 ? 'مستخدم' : 'مستخدمين' }}
                                        </small>
                                        @if($role->users_count > 0)
                                            <div class="users-list">
                                                @foreach($item['users'] as $user)
                                                    <div class="user-avatar" 
                                                         data-bs-toggle="tooltip" 
                                                         title="{{ $user->name }}">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endforeach
                                                @if($role->users_count > 5)
                                                    <div class="user-avatar" data-bs-toggle="tooltip" title="والمزيد...">
                                                        +{{ $role->users_count - 5 }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                لا يوجد مستخدمين
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                           class="btn btn-sm btn-outline-warning"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="تعديل الدور">
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
                                                        class="btn btn-sm btn-outline-danger {{ $role->users_count > 0 ? 'disabled' : '' }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $role->users_count > 0 ? 'لا يمكن الحذف - يوجد مستخدمين' : 'حذف الدور' }}"
                                                        @if($role->users_count > 0) disabled @endif>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-person-badge fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">لا توجد أدوار</h5>
                            <p class="text-muted mb-4">ابدأ بإضافة دور جديد</p>
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>
                                {{ __('admin.menu.add_role') }}
                            </a>
                        </div>
                    @endforelse
                </div>

                @if($roles->hasPages())
                    <div class="card-footer bg-white">
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
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