@extends('layouts.admin')
@section('title', __('admin.roles.edit_title'))


@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.roles.edit_title') }}</h5>
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
                        <li class="breadcrumb-item active">{{ __('admin.roles.edit_title') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>
                        {{ __('admin.roles.edit_title') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('admin.roles.form_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="{{ __('admin.roles.form_name_placeholder') }}"
                                   value="{{ old('name', $role->name) }}"
                                   {{ $role->name === 'super-admin' ? 'readonly' : '' }}
                                   required>
                            @if($role->name === 'super-admin')
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i>
                                    لا يمكن تعديل اسم دور Super Admin
                                </small>
                            @endif
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                {{ __('admin.roles.form_permissions') }}
                            </label>

                            @foreach($permissions as $group => $groupPermissions)
                                <div class="permission-group">
                                    <div class="permission-group-header d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="bi bi-folder me-2"></i>
                                            {{ ucfirst($group) }}
                                        </span>
                                        <button type="button" 
                                                class="btn btn-sm btn-link p-0 select-all-group-btn"
                                                data-group="{{ $group }}">
                                            تحديد الكل
                                        </button>
                                    </div>
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-md-4 mb-2">
                                                <div class="permission-item">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}"
                                                           id="perm_{{ $permission->id }}"
                                                           class="permission-checkbox"
                                                           data-group="{{ $group }}"
                                                           {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}>
                                                    <label for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @error('permissions.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ __('admin.common.form_save') }}
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                                {{ __('admin.actions.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تحديد/إلغاء تحديد جميع الصلاحيات في مجموعة
        document.querySelectorAll('.select-all-group-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.getAttribute('data-group');
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });

                this.textContent = allChecked ? 'تحديد الكل' : 'إلغاء تحديد الكل';
            });

            // تحديث نص الزر بناءً على حالة الصلاحيات
            const group = btn.getAttribute('data-group');
            const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const someChecked = Array.from(checkboxes).some(cb => cb.checked);

            if (allChecked && checkboxes.length > 0) {
                btn.textContent = 'إلغاء تحديد الكل';
            } else if (someChecked) {
                btn.textContent = 'تحديد الكل';
            }
        });
    });
</script>
@endpush