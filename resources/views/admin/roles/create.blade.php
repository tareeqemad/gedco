{{-- resources/views/admin/roles/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'إضافة دور جديد')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إضافة دور جديد</h5>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">رجوع</a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">اسم الدور <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                           placeholder="مثال: editor, manager">
                    <small class="text-muted">استخدم حروف صغيرة وشرطة (-) مثل: content-editor</small>
                </div>

                <!-- === الصلاحيات === -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label mb-0">الصلاحيات</label>
                        <div>
                            <input type="text" id="permissionSearch" class="form-control form-control-sm d-inline-block"
                                   placeholder="ابحث..." style="width: 200px;">
                            <button type="button" id="selectAll" class="btn btn-sm btn-outline-primary">تحديد الكل</button>
                            <button type="button" id="deselectAll" class="btn btn-sm btn-outline-secondary">إلغاء الكل</button>
                        </div>
                    </div>

                    @php $selectedPermissions = old('permissions', []); @endphp

                    <div class="permissions-container border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                        @forelse($permissions as $guard => $perms)
                            <div class="permission-group mb-4">
                                <h6 class="text-primary fw-bold mb-2 border-bottom pb-2">
                                    {{ $guard === 'web' ? 'لوحة التحكم (Web)' : ucfirst($guard) }}
                                    <span class="badge bg-light text-dark float-end">{{ $perms->count() }}</span>
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
                                                <label class="form-check-label" for="perm{{ $perm->id }}">
                                                    <small>{{ str_replace(['.', '_'], ' ', $perm->name) }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">لا توجد صلاحيات متاحة.</p>
                        @endforelse
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5">إضافة الدور</button>
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

            // البحث
            searchInput?.addEventListener('input', function () {
                const term = this.value.toLowerCase();
                items.forEach(item => {
                    const label = item.textContent.toLowerCase();
                    item.style.display = label.includes(term) ? '' : 'none';
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
        });
    </script>
@endpush
