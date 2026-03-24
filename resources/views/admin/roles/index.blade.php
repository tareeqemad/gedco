@php
    $breadcrumbTitle     = __('admin.menu.roles');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.menu.roles'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Main Card -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-person-badge"
                :title="__('admin.menu.roles')"
                :create-route="route('admin.roles.create')"
                :create-label="__('admin.menu.add_role')" />

            <!-- Stat Chips -->
            <div class="d-flex align-items-center gap-2 px-3 py-2" style="border-bottom: 1px solid #E6ECF2;">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    <i class="bi bi-person-badge me-1"></i>{{ __('admin.roles.total_roles') }}: {{ number_format($stats['total']) }}
                </span>
                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                    <i class="bi bi-people me-1"></i>{{ __('admin.roles.users_count') }}: {{ number_format($stats['total_users']) }}
                </span>
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                    <i class="bi bi-shield-check me-1"></i>{{ __('admin.roles.total_permissions', ['count' => number_format($stats['total_permissions'])]) }}
                </span>
            </div>

            <!-- البحث -->
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.roles.index') }}" class="row g-3">
                    <div class="col-12 col-lg-11">
                        <input type="text"
                               name="search"
                               class="form-control rounded-3"
                                                              placeholder="{{ __('admin.roles.search_placeholder') }}"
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-12 col-lg-1">
                        <button type="submit" class="btn btn-outline-secondary w-100 rounded-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- قائمة الأدوار - Desktop Table -->
            <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>{{ __('admin.roles.role_name') }}</th>
                            <th class="text-center" style="width: 200px;">{{ __('admin.roles.permissions_label') }}</th>
                            <th class="text-center" style="width: 200px;">{{ __('admin.roles.users') }}</th>
                            <th class="text-center" style="width: 120px;">{{ __('admin.actions.actions') }}</th>
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
                                            <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;" title="{{ __('admin.roles.protected_role') }}">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($item['permissions_count'] > 0)
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <small class="text-muted">
                                                {{ $item['permissions_count'] }} {{ $item['permissions_count'] == 1 ? __('admin.roles.permission_count_singular') : __('admin.roles.permission_count_plural') }}
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
                                        <small class="text-muted text-center d-block">{{ __('admin.roles.no_permissions') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($role->users_count > 0)
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <small class="text-muted">
                                                {{ $role->users_count }} {{ $role->users_count == 1 ? __('admin.roles.user_count_singular') : __('admin.roles.user_count_plural') }}
                                            </small>
                                            <div class="d-flex gap-1 justify-content-center">
                                                @foreach($item['users']->take(3) as $user)
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: bold;"
                                                         title="{{ $user->display_name }}">
                                                        {{ mb_strtoupper(mb_substr($user->display_name, 0, 1)) }}
                                                    </div>
                                                @endforeach
                                                @if($role->users_count > 3)
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: bold;"
                                                         title="{{ __('admin.roles.and_more') }}">
                                                        +{{ $role->users_count - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted text-center d-block">{{ __('admin.roles.no_users') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                           class="btn btn-sm btn-outline-warning rounded-3"
                                           title="{{ __('admin.actions.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($role->name !== 'super-admin')
                                            <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-3 {{ $role->users_count > 0 ? 'disabled' : '' }}"
                                                        title="{{ $role->users_count > 0 ? __('admin.roles.cannot_delete_has_users') : __('admin.actions.delete') }}"
                                                        @if($role->users_count > 0) disabled @endif
                                                        data-confirm-delete>
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
                                    <h5 class="text-muted mb-2">{{ __('admin.roles.no_roles') }}</h5>
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
                <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            {{ __('admin.ui.showing_range', ['first' => $roles->firstItem(), 'last' => $roles->lastItem(), 'total' => $roles->total()]) }}
                        </div>
                        {{ $roles->links() }}
                    </div>
                </div>
            @endif
            </div>

            <!-- قائمة الأدوار - Mobile Cards -->
            <div class="d-md-none p-3">
            @forelse($rolesWithDetails as $item)
                @php $role = $item['role']; @endphp
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="mb-0 fw-semibold text-dark">{{ $role->name }}</h6>
                                    @if($role->name === 'super-admin')
                                        <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;" title="{{ __('admin.roles.protected_role') }}">
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
                                    {{ $item['permissions_count'] }} {{ $item['permissions_count'] == 1 ? __('admin.roles.permission_count_singular') : __('admin.roles.permission_count_plural') }}
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
                                    {{ __('admin.roles.no_permissions') }}
                                </small>
                            </div>
                        @endif
                        
                        @if($role->users_count > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $role->users_count }} {{ $role->users_count == 1 ? __('admin.roles.user_count_singular') : __('admin.roles.user_count_plural') }}
                                </small>
                                <div class="d-flex gap-1">
                                    @foreach($item['users']->take(3) as $user)
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: bold;"
                                             title="{{ $user->display_name }}">
                                            {{ mb_strtoupper(mb_substr($user->display_name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($role->users_count > 3)
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: bold;"
                                             title="{{ __('admin.roles.and_more') }}">
                                            +{{ $role->users_count - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ __('admin.roles.no_users') }}
                                </small>
                            </div>
                        @endif
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.roles.edit', $role->id) }}"
                               class="btn btn-sm btn-outline-warning rounded-3 flex-fill">
                                <i class="bi bi-pencil me-1"></i>
                                {{ __('admin.actions.edit') }}
                            </a>
                            @if($role->name !== 'super-admin')
                                <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                      method="POST"
                                      class="d-inline flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-3 w-100 {{ $role->users_count > 0 ? 'disabled' : '' }}"
                                            @if($role->users_count > 0) disabled @endif
                                            data-confirm-delete>
                                        <i class="bi bi-trash me-1"></i>
                                        {{ __('admin.actions.delete') }}
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
                        <h5 class="text-muted mb-2">{{ __('admin.roles.no_roles') }}</h5>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary rounded-3 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            {{ __('admin.menu.add_role') }}
                        </a>
                    </div>
                </div>
            @endforelse

            @if($roles->hasPages())
                <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            {{ __('admin.ui.showing_range', ['first' => $roles->firstItem(), 'last' => $roles->lastItem(), 'total' => $roles->total()]) }}
                        </div>
                        {{ $roles->links() }}
                    </div>
                </div>
            @endif
            </div>
        </x-admin.card>
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
