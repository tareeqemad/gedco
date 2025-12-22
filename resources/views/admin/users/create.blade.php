@php
    $breadcrumbTitle     = __('admin.users.add_new_user');
    $breadcrumbParent    = __('admin.users.users_list');
    $breadcrumbParentUrl = route('admin.users.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.users.add_user'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 py-md-3 px-3 px-md-4">
                <div class="d-flex justify-content-between align-items-center w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-person-plus-fill me-2"></i>{{ __('admin.users.add_new_user') }}
                            </h5>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-arrow-left me-2"></i>{{ __('admin.common.back') }}
                    </a>
                </div>
            </div>
        </div>


        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white users-card">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                    @csrf

                    <!-- Basic Information Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-person-lines-fill text-primary"></i>
                            {{ __('admin.users.basic_information') }}
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-person text-primary"></i>
                                    {{ __('admin.users.name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control rounded-3 border-0 bg-light @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       required
                                       placeholder="{{ __('admin.users.name_placeholder') }}"
                                       style="height: 45px; font-size: 1rem;">
                                @error('name')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-envelope text-primary"></i>
                                    {{ __('admin.users.email') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control rounded-3 border-0 bg-light @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="{{ __('admin.users.email_placeholder') }}"
                                       style="height: 45px; font-size: 1rem;">
                                @error('email')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock text-primary"></i>
                            {{ __('admin.users.password') }}
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-key text-primary"></i>
                                    {{ __('admin.users.password') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password" 
                                           id="passwordInput"
                                           class="form-control rounded-3 border-0 bg-light @error('password') is-invalid @enderror" 
                                           required
                                           minlength="8"
                                           placeholder="{{ __('admin.users.password_placeholder') }}"
                                           style="height: 45px; font-size: 1rem;">
                                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('passwordInput', this)" style="height: 45px;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ __('admin.users.password_hint') }}
                                </small>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-primary"></i>
                            {{ __('admin.users.role') }}
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-person-badge text-primary"></i>
                                    {{ __('admin.users.role') }}
                                </label>
                                <select name="role_id" class="form-select rounded-3 border-0 bg-light @error('role_id') is-invalid @enderror" style="height: 45px; font-size: 1rem;">
                                    <option value="">{{ __('admin.users.no_role') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Permissions Section -->
                    <div class="mb-4">
                        <div class="card border-0 shadow-sm rounded-4 bg-white">
                            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3 rounded-top-4">
                                <div class="d-flex justify-content-between align-items-center w-100" style="gap: 0.75rem;">
                                    <h6 class="fw-bold text-white mb-0 d-flex align-items-center gap-2" style="font-size: 0.95rem; line-height: 1.3;">
                                        <i class="bi bi-shield-lock-fill"></i>
                                        {{ __('admin.users.additional_permissions') }}
                                        <span class="badge bg-info ms-2" id="permissionsCount">0</span>
                                    </h6>
                                    <div class="d-flex gap-2 align-items-center" style="flex: 0 0 auto;">
                                        <div class="input-group" style="max-width: 200px;">
                                            <span class="input-group-text bg-white border-0" style="height: 32px; padding: 0.25rem 0.5rem;">
                                                <i class="bi bi-search text-muted" style="font-size: 0.875rem;"></i>
                                            </span>
                                            <input type="text" 
                                                   id="permissionSearch" 
                                                   class="form-control border-0 bg-white rounded-end-3" 
                                                   placeholder="{{ __('admin.users.search_permissions') }}"
                                                   style="height: 32px; font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @php $selectedPermissions = old('permissions', []); @endphp

                                <div class="permissions-container border-0 rounded-3 bg-white" style="max-height: 480px; overflow-y: auto;">
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
                                            <strong id="totalPermissionsCount">{{ collect($permissions)->sum(fn($perms) => $perms->count()) }}</strong> {{ __('admin.users.total_permissions') }}
                                        </small>
                                        <small class="text-muted">
                                            <span id="selectedPermissionsCount">{{ count($selectedPermissions) }}</span> صلاحية محددة حالياً
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-5 py-2 rounded-3" style="min-width: 150px; font-weight: 600;">
                            <i class="bi bi-x-circle me-2"></i>{{ __('admin.common.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 shadow-sm" id="submitBtn" style="min-width: 150px; font-weight: 600;">
                            <i class="bi bi-check-lg me-2"></i>{{ __('admin.users.save_user') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/users.css') }}">
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('permissionSearch');
            const items = document.querySelectorAll('.permission-item');
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const permissionsCountBadge = document.getElementById('permissionsCount');
            const selectedPermissionsCount = document.getElementById('selectedPermissionsCount');
            const form = document.getElementById('createUserForm');
            const submitBtn = document.getElementById('submitBtn');

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

            // Toggle password visibility
            window.togglePassword = function(inputId, btn) {
                const input = document.getElementById(inputId);
                const icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            };

            // Form submission with loading state
            form?.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري الحفظ...';
            });
        });
    </script>
@endpush
