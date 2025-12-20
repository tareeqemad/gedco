@extends('layouts.admin')
@section('title', __('admin.menu.add_role'))


@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.menu.add_role') }}</h5>
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
                        <li class="breadcrumb-item active">{{ __('admin.menu.add_role') }}</li>
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
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        {{ __('admin.menu.add_role') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('admin.roles.form_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="{{ __('admin.roles.form_name_placeholder') }}"
                                   value="{{ old('name') }}"
                                   required>
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
                                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
        });
    });
</script>
@endpush