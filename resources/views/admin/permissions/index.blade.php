@php
    $breadcrumbTitle     = __('admin.permissions.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.permissions.title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Main Card -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-shield-lock"
                :title="__('admin.permissions.title')"
                :create-route="route('admin.permissions.create')"
                :create-label="__('admin.permissions.add_new')" />

            <!-- البحث والفلترة -->
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="stat-chip">{{ __('admin.permissions.total') }}: <strong>{{ number_format($stats['total']) }}</strong></span>
                    <span class="stat-chip">Web: <strong>{{ number_format($stats['web']) }}</strong></span>
                    @if($stats['api'] > 0)
                        <span class="stat-chip">API: <strong>{{ number_format($stats['api']) }}</strong></span>
                    @endif
                </div>
                <form method="GET" action="{{ route('admin.permissions.index') }}">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div style="flex: 1 1 50%;">
                            <input type="text"
                                   name="search"
                                   class="form-control rounded-3"
                                   placeholder="{{ __('admin.permissions.search_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>
                        <div style="flex: 0 1 25%;">
                            <select name="guard" class="form-select rounded-3">
                                <option value="">{{ __('admin.permissions.all_guards') }}</option>
                                <option value="web" {{ request('guard') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ request('guard') == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="submit" class="btn btn-primary rounded-3">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.search') }}
                            </button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.actions.reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- قائمة الصلاحيات - Desktop Table -->
            <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>{{ __('admin.permissions.permission_name') }}</th>
                            <th class="text-center" style="width: 100px;">{{ __('admin.permissions.guard') }}</th>
                            <th class="text-center" style="width: 180px;">{{ __('admin.permissions.roles') }}</th>
                            <th class="text-center" style="width: 100px;">{{ __('admin.actions.actions') }}</th>
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
                                    <span class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $perm->name }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="stat-chip" style="font-size: 0.7rem;">{{ $perm->guard_name }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item['roles_count'] > 0)
                                        <div class="d-flex gap-1 flex-wrap justify-content-center">
                                            @foreach(array_slice($item['roles'], 0, 2) as $role)
                                                <span class="stat-chip" style="font-size: 0.68rem;">{{ $role }}</span>
                                            @endforeach
                                            @if($item['roles_count'] > 2)
                                                <span class="stat-chip" style="font-size: 0.68rem; color: #8A99AB;">+{{ $item['roles_count'] - 2 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <small class="text-muted">—</small>
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
                <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
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
            <div class="d-md-none p-3">
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
                                    <span class="stat-chip" style="font-size: 0.68rem;">{{ $perm->guard_name }}</span>
                                </div>
                                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.85rem;">{{ $perm->name }}</h6>
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
                <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            عرض {{ $permissions->firstItem() }} - {{ $permissions->lastItem() }} من {{ $permissions->total() }}
                        </div>
                        {{ $permissions->links() }}
                    </div>
                </div>
            @endif
            </div>

            <!-- مجموعات الصلاحيات -->
            @if($grouped->count() > 0)
            <div style="border-top: 1px solid #E6ECF2;">
                <div class="px-3 py-3">
                    <h6 class="mb-2 fw-bold d-flex align-items-center gap-2" style="font-size: 0.88rem; color: #24364A;">
                        <i class="bi bi-folder" style="color: #5B7088;"></i>
                        الصلاحيات حسب المجموعة
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
