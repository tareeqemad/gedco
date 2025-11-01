@extends('layouts.admin')
@section('title','إضافة مستخدم')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">إضافة مستخدم جديد</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">المستخدمون</a></li>
                        <li class="breadcrumb-item active">إضافة مستخدم</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">إضافة مستخدم جديد</h5>
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

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الدور</label>
                    <select name="role_id" class="form-select">
                        <option value="">— بدون دور —</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- === الصلاحيات الإضافية (محدثة) === -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label mb-0 fw-bold">صلاحيات إضافية</label>
                        <div>
                            <input type="text" id="permissionSearch" class="form-control form-control-sm d-inline-block"
                                   placeholder="ابحث..." style="width: 220px;">
                            <button type="button" id="selectAll" class="btn btn-sm btn-outline-success ms-2">تحديد الكل</button>
                            <button type="button" id="deselectAll" class="btn btn-sm btn-outline-danger">إلغاء الكل</button>
                        </div>
                    </div>

                    @php $selectedPermissions = old('permissions', []); @endphp

                    <div class="permissions-container border rounded p-4 bg-light" style="max-height: 450px; overflow-y: auto;">
                        @forelse($permissions as $guard => $perms)
                            <div class="permission-group mb-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2 d-flex justify-content-between">
                                    <span><i class="bi bi-shield-lock-fill me-1"></i> {{ $guard === 'web' ? 'لوحة التحكم (Web)' : ucfirst($guard) }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $perms->count() }}</span>
                                </h6>
                                <div class="row g-3">
                                    @foreach($perms as $perm)
                                        <div class="col-md-6 col-lg-4 permission-item">
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                                                       value="{{ $perm->id }}" id="perm{{ $perm->id }}"
                                                    {{ in_array($perm->id, $selectedPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label text-capitalize" for="perm{{ $perm->id }}">
                                                    <small class="fw-medium">{{ ucfirst(str_replace(['.', '_'], ' ', $perm->name)) }}</small>
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
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5">حفظ المستخدم</button>
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

            searchInput?.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                items.forEach(item => {
                    const label = item.textContent.toLowerCase();
                    item.style.display = term === '' || label.includes(term) ? '' : 'none';
                });
            });

            selectAll?.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = true);
            });

            deselectAll?.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
            });
        });
    </script>
@endpush
