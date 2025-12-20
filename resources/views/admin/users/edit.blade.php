{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')
@section('title', __('admin.users.edit_user_title') . ': ' . $user->name)

@section('content')
    <!-- Breadcrumbs -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.users.edit_user') }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">{{ __('admin.menu.users') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('admin.users.edit') }}: {{ Str::limit($user->name, 20) }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg me-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle" width="60">
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $user->name }}</h5>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('admin.users.back') }}
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf @method('PUT')

                        <!-- Name and Email -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.users.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.users.email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                {{ __('admin.users.password') }}
                                <small class="text-muted">({{ __('admin.users.password_leave_empty') }})</small>
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>

                        <!-- Main Role -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('admin.users.main_role') }}</label>
                            <select name="role_id" class="form-select">
                                <option value="">{{ __('admin.users.no_role') }}</option>
                                @foreach($roles as $role)
                                    @if(auth()->user()->hasRole('super-admin') || $role->name !== 'super-admin')
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $userRole?->id ?? '') == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @if($userRole)
                                <small class="text-muted d-block mt-1">
                                    {{ __('admin.users.current_role') }}
                                    <span class="badge bg-{{ $userRole->name === 'super-admin' ? 'danger' : 'primary' }}">
                                    {{ ucfirst(str_replace('-', ' ', $userRole->name)) }}
                                </span>
                                </small>
                            @endif
                        </div>

                        <!-- Additional Permissions -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0 fw-bold">
                                    {{ __('admin.users.additional_permissions') }}
                                    <span class="badge bg-info ms-2" id="permissionsCount">{{ count(old('permissions', $userPermissionIds ?? [])) }}</span>
                                </label>
                                <div>
                                    <input type="text" id="permissionSearch" class="form-control form-control-sm d-inline-block"
                                           placeholder="{{ __('admin.users.search_permissions') }}" style="width: 220px;">
                                </div>
                            </div>

                            @php
                                $selectedPermissions = old('permissions', $userPermissionIds);
                            @endphp

                            <div class="permissions-container border rounded bg-white">
                                @forelse($permissions as $guard => $perms)
                                    @php
                                        // تجميع الصلاحيات حسب المجموعة (القسم الأول قبل النقطة)
                                        $guardGroups = [];
                                        foreach($perms as $perm) {
                                            $parts = explode('.', $perm->name);
                                            $group = $parts[0] ?? 'other';
                                            if (!isset($guardGroups[$group])) {
                                                $guardGroups[$group] = [];
                                            }
                                            $guardGroups[$group][] = $perm;
                                        }
                                        $guardName = $guard === 'web' ? __('admin.users.dashboard_web') : ucfirst($guard);
                                        $guardKey = ucfirst(str_replace([' ', '-'], '', $guard));
                                        $guardSelectedCount = 0;
                                        foreach($perms as $perm) {
                                            if(in_array($perm->id, $selectedPermissions)) $guardSelectedCount++;
                                        }
                                    @endphp
                                    
                                    <div class="accordion accordion-flush" id="accordionPermissions{{ $guardKey }}">
                                        <div class="accordion-item border-0 border-bottom">
                                            <h2 class="accordion-header" id="heading{{ $guardKey }}">
                                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse{{ $guardKey }}" aria-expanded="false" 
                                                        aria-controls="collapse{{ $guardKey }}">
                                                    <i class="bi bi-shield-lock-fill me-2 text-primary"></i>
                                                    {{ $guardName }}
                                                    <span class="badge bg-primary rounded-pill ms-2">{{ $perms->count() }}</span>
                                                    <span class="badge bg-success rounded-pill ms-2" id="selectedCount{{ $guardKey }}">
                                                        {{ $guardSelectedCount }} محدد
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $guardKey }}" class="accordion-collapse collapse" 
                                                 aria-labelledby="heading{{ $guardKey }}" 
                                                 data-bs-parent="#accordionPermissions{{ $guardKey }}">
                                                <div class="accordion-body bg-light">
                                                    <!-- أزرار التحكم -->
                                                    <div class="d-flex justify-content-end gap-2 mb-3 pb-2 border-bottom">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-success select-all-group" 
                                                                data-guard="{{ $guardKey }}">
                                                            <i class="bi bi-check-all me-1"></i>{{ __('admin.users.select_all') }}
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger deselect-all-group" 
                                                                data-guard="{{ $guardKey }}">
                                                            <i class="bi bi-x-circle me-1"></i>{{ __('admin.users.deselect_all') }}
                                                        </button>
                                                    </div>

                                                    <!-- تجميع الصلاحيات حسب المجموعة -->
                                                    @foreach($guardGroups as $groupName => $groupPerms)
                                                        @php
                                                            $groupKey = $groupName . $guardKey;
                                                            $groupSelectedCount = 0;
                                                            foreach($groupPerms as $perm) {
                                                                if(in_array($perm->id, $selectedPermissions)) $groupSelectedCount++;
                                                            }
                                                        @endphp
                                                        <div class="permission-subgroup mb-4">
                                                            <h6 class="text-secondary fw-semibold mb-2 d-flex align-items-center">
                                                                <i class="bi bi-folder-fill me-2 text-warning"></i>
                                                                {{ ucfirst(str_replace(['-', '_'], ' ', $groupName)) }}
                                                                <span class="badge bg-secondary ms-2">{{ count($groupPerms) }}</span>
                                                                <span class="badge bg-success ms-2 group-selected-count" 
                                                                      data-group="{{ $groupKey }}">
                                                                    {{ $groupSelectedCount }} محدد
                                                                </span>
                                                            </h6>
                                                            <div class="row g-2">
                                                                @foreach($groupPerms as $perm)
                                                                    <div class="col-md-6 col-lg-4 permission-item">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input permission-checkbox" 
                                                                                   type="checkbox" 
                                                                                   name="permissions[]" 
                                                                                   value="{{ $perm->id }}" 
                                                                                   id="perm{{ $perm->id }}"
                                                                                   data-guard="{{ $guardKey }}"
                                                                                   data-group="{{ $groupKey }}"
                                                                                {{ in_array($perm->id, $selectedPermissions) ? 'checked' : '' }}>
                                                                            <label class="form-check-label text-capitalize" for="perm{{ $perm->id }}">
                                                                                <small>{{ ucfirst(str_replace(['.', '_'], ' ', $perm->name)) }}</small>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-shield-lock fs-1"></i>
                                        <p class="mt-2">{{ __('admin.users.no_permissions') }}</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Statistics -->
                            <div class="mt-3 p-3 bg-light rounded border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <strong id="totalPermissionsCount">{{ $user->getAllPermissions()->count() }}</strong> {{ __('admin.users.total_permissions') }}
                                    </small>
                                    <small class="text-muted">
                                        <span id="selectedPermissionsCount">{{ count($selectedPermissions) }}</span> صلاحية محددة حالياً
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ __('admin.users.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('permissionSearch');
            const items = document.querySelectorAll('.permission-item');
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const permissionsCountBadge = document.getElementById('permissionsCount');
            const selectedPermissionsCount = document.getElementById('selectedPermissionsCount');

            // تحديث العدد
            function updateCounts() {
                const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                if (permissionsCountBadge) {
                    permissionsCountBadge.textContent = checkedCount;
                }
                if (selectedPermissionsCount) {
                    selectedPermissionsCount.textContent = checkedCount;
                }

                // تحديث عدد المحدد لكل guard
                const guardElements = document.querySelectorAll('[id^="selectedCount"]');
                guardElements.forEach(badge => {
                    const guardId = badge.id.replace('selectedCount', '');
                    const guardChecked = document.querySelectorAll(`.permission-checkbox[data-guard="${guardId}"]:checked`).length;
                    badge.textContent = `${guardChecked} محدد`;
                });

                // تحديث عدد المحدد لكل مجموعة
                document.querySelectorAll('.group-selected-count').forEach(badge => {
                    const group = badge.getAttribute('data-group');
                    const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                    const groupChecked = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]:checked`).length;
                    badge.textContent = `${groupChecked} محدد`;
                });
            }

            // البحث
            searchInput?.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                let hasResults = false;

                items.forEach(item => {
                    const label = item.textContent.toLowerCase();
                    const matches = term === '' || label.includes(term);
                    item.style.display = matches ? '' : 'none';
                    if (matches) hasResults = true;
                });

                // فتح accordions التي تحتوي على نتائج
                if (term !== '') {
                    document.querySelectorAll('.accordion-collapse').forEach(collapse => {
                        const visibleItems = collapse.querySelectorAll('.permission-item[style=""]').length;
                        if (visibleItems > 0) {
                            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
                            bsCollapse.show();
                        }
                    });
                }
            });

            // تحديد الكل لمجموعة guard محددة
            document.querySelectorAll('.select-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    const guard = this.getAttribute('data-guard');
                    document.querySelectorAll(`.permission-checkbox[data-guard="${guard}"]`).forEach(cb => {
                        cb.checked = true;
                    });
                    updateCounts();
                });
            });

            // إلغاء الكل لمجموعة guard محددة
            document.querySelectorAll('.deselect-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    const guard = this.getAttribute('data-guard');
                    document.querySelectorAll(`.permission-checkbox[data-guard="${guard}"]`).forEach(cb => {
                        cb.checked = false;
                    });
                    updateCounts();
                });
            });

            // تحديث العدد عند تغيير checkbox
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateCounts);
            });

            // تحديث العدد عند التحميل
            updateCounts();
        });
    </script>
@endpush
