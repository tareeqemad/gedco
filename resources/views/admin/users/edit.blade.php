{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'تعديل مستخدم: ' . $user->name)

@section('content')
    <!-- Breadcrumbs -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">تعديل المستخدم</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="feather icon-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">المستخدمون</a>
                        </li>
                        <li class="breadcrumb-item active">تعديل: {{ Str::limit($user->name, 20) }}</li>
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
                        <i class="bi bi-arrow-left me-1"></i> رجوع
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

                        <!-- الاسم والبريد -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">الاسم <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <!-- كلمة المرور -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                كلمة المرور
                                <small class="text-muted">(اتركها فارغة لعدم التغيير)</small>
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>

                        <!-- الدور الرئيسي -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">الدور الرئيسي</label>
                            <select name="role_id" class="form-select">
                                <option value="">— بدون دور —</option>
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
                                    الدور الحالي:
                                    <span class="badge bg-{{ $userRole->name === 'super-admin' ? 'danger' : 'primary' }}">
                                    {{ ucfirst(str_replace('-', ' ', $userRole->name)) }}
                                </span>
                                </small>
                            @endif
                        </div>

                        <!-- الصلاحيات الإضافية -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0 fw-bold">
                                    صلاحيات إضافية
                                    <span class="badge bg-info ms-2">{{ count(old('permissions', $userPermissionIds ?? [])) }}</span>
                                </label>
                                <div>
                                    <input type="text" id="permissionSearch" class="form-control form-control-sm d-inline-block"
                                           placeholder="ابحث..." style="width: 220px;">
                                    <button type="button" id="selectAll" class="btn btn-sm btn-outline-success ms-2">تحديد الكل</button>
                                    <button type="button" id="deselectAll" class="btn btn-sm btn-outline-danger">إلغاء الكل</button>
                                </div>
                            </div>

                            @php
                                $selectedPermissions = old('permissions', $userPermissionIds);
                            @endphp

                            <div class="permissions-container border rounded p-4 bg-light" style="max-height: 450px; overflow-y: auto;">
                                @forelse($permissions as $guard => $perms)
                                    <div class="permission-group mb-4">
                                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2 d-flex justify-content-between">
                                        <span>
                                            <i class="bi bi-shield-lock-fill me-1"></i>
                                            {{ $guard === 'web' ? 'لوحة التحكم (Web)' : ucfirst($guard) }}
                                        </span>
                                            <span class="badge bg-primary rounded-pill">{{ $perms->count() }}</span>
                                        </h6>
                                        <div class="row g-3">
                                            @foreach($perms as $perm)
                                                <div class="col-md-6 col-lg-4 permission-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox"
                                                               type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $perm->id }}"
                                                               id="perm{{ $perm->id }}"
                                                            {{ in_array($perm->id, $selectedPermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label text-capitalize fw-medium" for="perm{{ $perm->id }}">
                                                            <small>{{ ucfirst(str_replace(['.', '_'], ' ', $perm->name)) }}</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-shield-lock fs-1"></i>
                                        <p class="mt-2">لا توجد صلاحيات متاحة.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- إحصائية -->
                            <div class="mt-3 p-3 bg-white rounded border">
                                <small class="text-muted">
                                    <strong>{{ $user->getAllPermissions()->count() }}</strong> صلاحية إجمالية (من الدور + مباشرة)
                                </small>
                            </div>
                        </div>

                        <!-- أزرار -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-circle me-1"></i>
                                حفظ التغييرات
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
            const selectAll = document.getElementById('selectAll');
            const deselectAll = document.getElementById('deselectAll');

            // البحث
            searchInput?.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                items.forEach(item => {
                    const label = item.textContent.toLowerCase();
                    item.style.display = term === '' || label.includes(term) ? '' : 'none';
                });
            });

            // تحديد الكل
            selectAll?.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = true);
            });

            // إلغاء الكل
            deselectAll?.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
            });

            // تحديث العدد
            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    const count = document.querySelectorAll('.permission-checkbox:checked').length;
                    document.querySelector('.badge.bg-info').textContent = count;
                });
            });
        });
    </script>
@endpush
