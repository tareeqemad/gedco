@php
    $breadcrumbTitle     = __('admin.users.edit_user');
    $breadcrumbParent    = __('admin.users.users_list');
    $breadcrumbParentUrl = route('admin.users.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.users.edit_user_title') . ': ' . $user->name)

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card class="users-card">
            <x-admin.card-header-form
                icon="bi-person-gear"
                :title="__('admin.users.edit_user')"
                :back-route="route('admin.users.index')"
                :back-label="__('admin.common.back')">
                <x-slot:subtitle>{{ $user->name }} - {{ $user->email }}</x-slot:subtitle>
            </x-admin.card-header-form>

            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                    @csrf @method('PUT')

                    {{-- المعلومات الأساسية --}}
                    <h6 class="section-title">
                        <i class="bi bi-person-lines-fill"></i>
                        {{ __('admin.users.basic_information') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                {{ __('admin.users.name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                   class="form-control rounded-3 @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required
                                   placeholder="{{ __('admin.users.name_placeholder') }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                {{ __('admin.users.email') }} <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email"
                                   class="form-control rounded-3 @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required
                                   placeholder="{{ __('admin.users.email_placeholder') }}">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- كلمة المرور والدور --}}
                    <h6 class="section-title">
                        <i class="bi bi-shield-lock"></i>
                        الأمان والدور
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('admin.users.password') }}</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput"
                                       class="form-control rounded-start-3 @error('password') is-invalid @enderror"
                                       minlength="8"
                                       placeholder="{{ __('admin.users.password_leave_empty') }}">
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('passwordInput', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle me-1"></i>{{ __('admin.users.password_leave_empty') }}
                            </small>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('admin.users.main_role') }}</label>
                            <select name="role_id" class="form-select rounded-3 @error('role_id') is-invalid @enderror">
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
                                <small class="text-muted mt-1 d-block">
                                    <i class="bi bi-info-circle me-1"></i>{{ __('admin.users.current_role') }}:
                                    <span class="badge bg-{{ $userRole->name === 'super-admin' ? 'danger' : 'primary' }} ms-1">
                                        {{ ucfirst(str_replace('-', ' ', $userRole->name)) }}
                                    </span>
                                </small>
                            @endif
                            @error('role_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- الصلاحيات الإضافية --}}
                    <div class="mb-4">
                        <div class="perm-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-lock-fill" style="color: #5B7088;"></i>
                                    <h6 class="mb-0 fw-bold" style="font-size: 0.92rem; color: #24364A;">{{ __('admin.users.additional_permissions') }}</h6>
                                    <span class="perm-badge" id="permissionsCount">{{ count(old('permissions', $userPermissionIds ?? [])) }}</span>
                                </div>
                                <div class="perm-search">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="permissionSearch"
                                           placeholder="{{ __('admin.users.search_permissions') }}">
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            @php $selectedPermissions = old('permissions', $userPermissionIds); @endphp
                            <div class="permissions-container rounded-3" style="max-height: 480px; overflow-y: auto;">
                                @forelse($permissions as $guard => $perms)
                                    @php
                                        $guardGroups = [];
                                        foreach($perms as $perm) {
                                            $parts = explode('.', $perm->name);
                                            $group = $parts[0] ?? 'other';
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
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $guardKey }}">
                                                    <div class="d-flex align-items-center gap-2 w-100 me-3">
                                                        <i class="bi bi-folder-fill" style="color: #5B7088;"></i>
                                                        <span>{{ $guardName }}</span>
                                                        <span class="badge bg-light text-dark rounded-pill px-2" style="font-size: 0.75rem;">{{ $perms->count() }}</span>
                                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 ms-auto" style="font-size: 0.75rem;"
                                                              id="selectedCount{{ $guardKey }}">{{ $guardSelectedCount }} محدد</span>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $guardKey }}" class="accordion-collapse collapse"
                                                 data-bs-parent="#accordionPermissions{{ $guardKey }}">
                                                <div class="accordion-body">
                                                    <div class="d-flex justify-content-end gap-2 mb-3 pb-2 border-bottom">
                                                        <button type="button" class="btn btn-sm btn-outline-success select-all-group" data-guard="{{ $guardKey }}">
                                                            <i class="bi bi-check-all me-1"></i>{{ __('admin.users.select_all') }}
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger deselect-all-group" data-guard="{{ $guardKey }}">
                                                            <i class="bi bi-x-circle me-1"></i>{{ __('admin.users.deselect_all') }}
                                                        </button>
                                                    </div>

                                                    @foreach($guardGroups as $groupName => $groupPerms)
                                                        @php
                                                            $groupKey = $groupName . $guardKey;
                                                            $groupSelectedCount = 0;
                                                            foreach($groupPerms as $perm) {
                                                                if(in_array($perm->id, $selectedPermissions)) $groupSelectedCount++;
                                                            }
                                                        @endphp
                                                        <div class="permission-subgroup mb-3">
                                                            <h6 class="text-muted fw-semibold mb-2 d-flex align-items-center" style="font-size: 0.85rem;">
                                                                <i class="bi bi-folder me-2" style="color: #8A99AB;"></i>
                                                                {{ ucfirst(str_replace(['-', '_'], ' ', $groupName)) }}
                                                                <span class="badge bg-light text-muted rounded-pill ms-2" style="font-size: 0.7rem;">{{ count($groupPerms) }}</span>
                                                                <span class="badge bg-success-subtle text-success rounded-pill ms-1 group-selected-count" style="font-size: 0.7rem;"
                                                                      data-group="{{ $groupKey }}">{{ $groupSelectedCount }} محدد</span>
                                                            </h6>
                                                            <div class="row g-2">
                                                                @foreach($groupPerms as $perm)
                                                                    <div class="col-md-6 col-lg-4 permission-item">
                                                                        <div class="form-check p-2 bg-white rounded-3 border d-flex align-items-center" style="min-height: 40px;">
                                                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                                                   name="permissions[]" value="{{ $perm->id }}" id="perm{{ $perm->id }}"
                                                                                   data-guard="{{ $guardKey }}" data-group="{{ $groupKey }}"
                                                                                   {{ in_array($perm->id, $selectedPermissions) ? 'checked' : '' }}>
                                                                            <label class="form-check-label flex-grow-1 mb-0 ms-2" for="perm{{ $perm->id }}" style="cursor: pointer; font-size: 0.85rem;">
                                                                                {{ ucfirst(str_replace(['.', '_'], ' ', $perm->name)) }}
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
                                        <i class="bi bi-shield-lock fs-1 opacity-50"></i>
                                        <p class="mt-2">{{ __('admin.users.no_permissions') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- أزرار الإجراءات --}}
                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                            <i class="bi bi-x-circle me-1"></i>{{ __('admin.common.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm" id="submitBtn">
                            <i class="bi bi-check-lg me-1"></i>{{ __('admin.users.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </x-admin.card>
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

            function updateCounts() {
                const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                if (permissionsCountBadge) permissionsCountBadge.textContent = checkedCount;

                document.querySelectorAll('[id^="selectedCount"]').forEach(badge => {
                    const guardId = badge.id.replace('selectedCount', '');
                    const guardChecked = document.querySelectorAll(`.permission-checkbox[data-guard="${guardId}"]:checked`).length;
                    badge.textContent = `${guardChecked} محدد`;
                });

                document.querySelectorAll('.group-selected-count').forEach(badge => {
                    const group = badge.getAttribute('data-group');
                    const groupChecked = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]:checked`).length;
                    badge.textContent = `${groupChecked} محدد`;
                });
            }

            searchInput?.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                items.forEach(item => {
                    item.style.display = (term === '' || item.textContent.toLowerCase().includes(term)) ? '' : 'none';
                });
                if (term !== '') {
                    document.querySelectorAll('.accordion-collapse').forEach(collapse => {
                        if (collapse.querySelectorAll('.permission-item:not([style*="none"])').length > 0) {
                            bootstrap.Collapse.getOrCreateInstance(collapse).show();
                        }
                    });
                }
            });

            document.querySelectorAll('.select-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll(`.permission-checkbox[data-guard="${this.dataset.guard}"]`).forEach(cb => cb.checked = true);
                    updateCounts();
                });
            });

            document.querySelectorAll('.deselect-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll(`.permission-checkbox[data-guard="${this.dataset.guard}"]`).forEach(cb => cb.checked = false);
                    updateCounts();
                });
            });

            checkboxes.forEach(cb => cb.addEventListener('change', updateCounts));
            updateCounts();

            window.togglePassword = function(inputId, btn) {
                const input = document.getElementById(inputId);
                const icon = btn.querySelector('i');
                input.type = input.type === 'password' ? 'text' : 'password';
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            };

            document.getElementById('editUserForm')?.addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...';
            });
        });
    </script>
@endpush
