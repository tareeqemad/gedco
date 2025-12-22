@php
    $breadcrumbTitle     = __('admin.roles.edit_title');
    $breadcrumbParent    = __('admin.menu.roles');
    $breadcrumbParentUrl = route('admin.roles.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.roles.edit_title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 0.75rem;">
                    <div class="d-flex align-items-center gap-2 flex-wrap" style="flex: 1 1 auto;">
                        <i class="bi bi-person-badge fs-5"></i>
                        <h5 class="mb-0 fw-bold text-white" style="font-size: 1.1rem; line-height: 1.2;">
                            {{ __('admin.roles.edit_title') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- الجانب الأيسر: اسم الدور والأزرار -->
                <div class="col-lg-4">
                    <!-- اسم الدور -->
                    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                        <div class="card-header bg-light border-0 py-3 px-4">
                            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-tag text-primary"></i>
                                معلومات الدور
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-0">
                                <label class="form-label fw-semibold text-dark mb-2">
                                    {{ __('admin.roles.form_name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control rounded-3 border-0 bg-light @error('name') is-invalid @enderror" 
                                       style="height: 45px;"
                                       placeholder="{{ __('admin.roles.form_name_placeholder') }}"
                                       value="{{ old('name', $role->name) }}"
                                       {{ $role->name === 'super-admin' ? 'readonly' : '' }}
                                       required>
                                @if($role->name === 'super-admin')
                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        لا يمكن تعديل اسم دور Super Admin
                                    </small>
                                @endif
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- الأزرار -->
                    <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top" style="top: 20px;">
                        <div class="card-body p-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 py-2">
                                    <i class="bi bi-check-circle"></i>
                                    {{ __('admin.common.form_save') }}
                                </button>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary rounded-3 shadow-sm py-2">
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الجانب الأيمن: الصلاحيات -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 1rem;">
                                <h6 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-lock-fill"></i>
                                    {{ __('admin.roles.form_permissions') }}
                                    <span class="badge bg-white text-primary rounded-pill px-3 py-1" id="permissionsCount">0</span>
                                </h6>
                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                    <div class="input-group" style="max-width: 250px;">
                                        <span class="input-group-text bg-white border-0 rounded-start-3">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               id="permissionSearch" 
                                               class="form-control border-0 bg-white rounded-end-3" 
                                               placeholder="ابحث عن صلاحية..."
                                               style="height: 38px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @php $selectedPermissions = old('permissions', $rolePermissionIds); @endphp

                            <div class="permissions-container" style="max-height: 600px; overflow-y: auto; padding: 0.5rem;">
                                <div class="accordion" id="permissionsAccordion">
                                    @foreach($permissions as $group => $groupPermissions)
                                        @php
                                            $groupKey = str_replace([' ', '-'], '', $group);
                                            $groupSelectedCount = 0;
                                            $groupChecked = false;
                                            foreach($groupPermissions as $perm) {
                                                if(in_array($perm->id, $selectedPermissions)) {
                                                    $groupSelectedCount++;
                                                    $groupChecked = true;
                                                }
                                            }
                                        @endphp
                                        <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm overflow-hidden">
                                            <h2 class="accordion-header" id="heading{{ $groupKey }}">
                                                <button class="accordion-button {{ $groupChecked ? '' : 'collapsed' }} fw-semibold" type="button" data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse{{ $groupKey }}" aria-expanded="{{ $groupChecked ? 'true' : 'false' }}" 
                                                        aria-controls="collapse{{ $groupKey }}">
                                                    <div class="d-flex align-items-center justify-content-between w-100 me-3">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bi bi-folder-fill text-primary"></i>
                                                            <span>{{ ucfirst(str_replace(['-', '_'], ' ', $group)) }}</span>
                                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                                {{ $groupPermissions->count() }}
                                                            </span>
                                                        </div>
                                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 group-selected-count" 
                                                              data-group="{{ $groupKey }}" style="font-size: 0.75rem;">
                                                            {{ $groupSelectedCount }} محدد
                                                        </span>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $groupKey }}" class="accordion-collapse collapse {{ $groupChecked ? 'show' : '' }}" 
                                                 aria-labelledby="heading{{ $groupKey }}" 
                                                 data-bs-parent="#permissionsAccordion">
                                                <div class="accordion-body bg-light-subtle p-4">
                                                    <!-- أزرار التحكم -->
                                                    <div class="d-flex justify-content-end gap-2 mb-4 pb-3 border-bottom">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-success select-all-group-btn rounded-3" 
                                                                data-group="{{ $groupKey }}">
                                                            <i class="bi bi-check-all me-1"></i>تحديد الكل
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger deselect-all-group-btn rounded-3" 
                                                                data-group="{{ $groupKey }}">
                                                            <i class="bi bi-x-circle me-1"></i>إلغاء تحديد الكل
                                                        </button>
                                                    </div>

                                                    <!-- الصلاحيات -->
                                                    <div class="row g-3">
                                                        @foreach($groupPermissions as $permission)
                                                            <div class="col-md-6 col-lg-4 permission-item">
                                                                <div class="form-check p-2 bg-white rounded-3 border border-light shadow-sm mb-0 d-flex align-items-center" style="min-height: 45px;">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[]" 
                                                                           value="{{ $permission->id }}" 
                                                                           id="perm{{ $permission->id }}"
                                                                           data-group="{{ $groupKey }}"
                                                                           {{ in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}>
                                                                    <label class="form-check-label flex-grow-1 mb-0 ms-2" for="perm{{ $permission->id }}" style="cursor: pointer; line-height: 1.4;">
                                                                        <span class="text-dark">{{ ucfirst(str_replace(['.', '_'], ' ', $permission->name)) }}</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @error('permissions.*')
                                <div class="text-danger small mt-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        .accordion-button {
            background-color: #ffffff !important;
            border: none !important;
            box-shadow: none !important;
            padding: 1rem 1.25rem !important;
            transition: all 0.3s ease;
        }
        
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
            color: #0066cc !important;
            box-shadow: none !important;
        }
        
        .accordion-button:not(.collapsed) .bi-folder-fill {
            color: #0066cc !important;
        }
        
        .accordion-button:focus {
            border-color: transparent !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 102, 204, 0.15) !important;
        }
        
        .accordion-body {
            background-color: #f8f9fa !important;
        }
        
        .permission-checkbox {
            width: 20px !important;
            height: 20px !important;
            margin-top: 0 !important;
            margin-left: 0 !important;
            cursor: pointer;
            border: 2px solid #ced4da;
            border-radius: 4px;
            flex-shrink: 0;
        }
        
        .permission-checkbox:checked {
            background-color: #0066cc !important;
            border-color: #0066cc !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .permission-checkbox:focus {
            border-color: #0066cc !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 102, 204, 0.15) !important;
            outline: none;
        }
        
        .form-check {
            transition: all 0.2s ease;
        }
        
        .form-check:hover {
            background-color: #f0f7ff !important;
            border-color: #0066cc !important;
            transform: translateY(-1px);
        }
        
        .form-check-label {
            user-select: none;
        }
        
        .permissions-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .permissions-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .permissions-container::-webkit-scrollbar-thumb {
            background: #0066cc;
            border-radius: 10px;
        }
        
        .permissions-container::-webkit-scrollbar-thumb:hover {
            background: #0052a3;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
            const permissionsCount = document.getElementById('permissionsCount');
            const searchInput = document.getElementById('permissionSearch');

            function updateCount() {
                const checked = document.querySelectorAll('.permission-checkbox:checked').length;
                permissionsCount.textContent = checked;
            }

            function updateGroupCount(group) {
                const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                const checked = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
                const countElement = document.querySelector(`.group-selected-count[data-group="${group}"]`);
                if (countElement) {
                    countElement.textContent = checked + ' محدد';
                }
            }

            function updateAllCounts() {
                updateCount();
                document.querySelectorAll('.permission-checkbox').forEach(cb => {
                    updateGroupCount(cb.getAttribute('data-group'));
                });
            }

            permissionCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateAllCounts();
                });
            });

            document.querySelectorAll('.select-all-group-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const group = this.getAttribute('data-group');
                    const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                    checkboxes.forEach(cb => cb.checked = true);
                    updateAllCounts();
                });
            });

            document.querySelectorAll('.deselect-all-group-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const group = this.getAttribute('data-group');
                    const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                    checkboxes.forEach(cb => cb.checked = false);
                    updateAllCounts();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    document.querySelectorAll('.permission-item').forEach(item => {
                        const label = item.querySelector('label').textContent.toLowerCase();
                        if (label.includes(searchTerm)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            updateAllCounts();
        });
    </script>
    @endpush
@endsection
