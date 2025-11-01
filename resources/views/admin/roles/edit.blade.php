blade{{-- resources/views/admin/roles/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'تعديل الدور: ' . ucfirst(str_replace('-', ' ', $role->name)))

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0 text-primary">
                تعديل الدور:
                <span class="badge bg-{{ $role->name === 'super-admin' ? 'danger' : 'primary' }} fs-6">
                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
            </span>
            </h5>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary">
                رجوع
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

            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf @method('PUT')

                <!-- اسم الدور -->
                <div class="mb-4">
                    <label class="form-label fw-bold">اسم الدور <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $role->name) }}" required
                           placeholder="مثال: content-manager">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">استخدم حروف صغيرة وشرطة (-) مثل: site-editor</small>
                </div>

                <!-- الصلاحيات -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label mb-0 fw-bold">الصلاحيات ({{ $role->permissions->count() }} مختارة)</label>
                        <div>
                            <input type="text" id="permissionSearch" class="form-control form-control-sm d-inline-block"
                                   placeholder="ابحث..." style="width: 220px;">
                            <button type="button" id="selectAll" class="btn btn-sm btn-outline-success">تحديد الكل</button>
                            <button type="button" id="deselectAll" class="btn btn-sm btn-outline-danger">إلغاء الكل</button>
                        </div>
                    </div>

                    @php
                        $selectedPermissions = old('permissions', $rolePermissionIds);
                    @endphp

                    <div class="permissions-container border rounded p-4 bg-light" style="max-height: 450px; overflow-y: auto;">
                        @forelse($permissions as $guard => $perms)
                            <div class="permission-group mb-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2 d-flex justify-content-between">
                                <span>
                                    {{ $guard === 'web' ? 'لوحة التحكم (Web)' : ucfirst($guard) }}
                                </span>
                                    <span class="badge bg-primary rounded-pill">{{ $perms->count() }}</span>
                                </h6>

                                <div class="row g-3">
                                    @foreach($perms as $perm)
                                        <div class="col-md-6 col-lg-4 permission-item">
                                            <div class="form-check form-check-inline d-block">
                                                <input class="form-check-input permission-checkbox"
                                                       type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $perm->id }}"
                                                       id="perm{{ $perm->id }}"
                                                    {{ in_array($perm->id, $selectedPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label text-capitalize" for="perm{{ $perm->id }}">
                                                    <small class="fw-medium">
                                                        {{ ucfirst(str_replace(['.', '_'], ' ', $perm->name)) }}
                                                    </small>
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

                    <!-- إحصائية سريعة -->
                    <div class="mt-3 p-3 bg-white rounded border">
                        <small class="text-muted">
                            <strong>{{ $role->users_count }}</strong> مستخدم{{ $role->users_count == 1 ? '' : 'ين' }} لديه{{ $role->users_count == 1 ? '' : 'م' }} هذا الدور.
                            @if($role->users_count > 0)
                                <br>تغيير الصلاحيات سيؤثر عليهم فورًا.
                            @endif
                        </small>
                    </div>
                </div>

                <!-- أزرار الحفظ -->
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
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

            // البحث الفوري
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

            // تحديث العدد عند التغيير
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                    document.querySelector('.fw-bold').textContent = checkedCount;
                });
            });
        });
    </script>
@endpush
