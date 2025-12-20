@extends('layouts.admin')
@section('title', __('admin.users.title'))

@section('content')
    <!-- Breadcrumbs -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.users.title') }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('admin.menu.users') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ __('admin.users.users_list') }}</h5>
                            <small class="text-muted">إجمالي المستخدمين: {{ $users->total() }}</small>
                        </div>
                    </div>
                    @can('users.create')
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            {{ __('admin.users.new_user') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success border-0 m-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger border-0 m-3 d-flex align-items-center">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Search and Filter -->
                    <div class="p-3 bg-light border-bottom">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">{{ __('admin.users.search_placeholder') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="{{ __('admin.users.search_placeholder') }}"
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">{{ __('admin.users.roles') }}</label>
                                <select name="role" class="form-select">
                                    <option value="">{{ __('admin.users.all_roles') }}</option>
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel me-2"></i>{{ __('admin.users.filter') }}
                                </button>
                            </div>
                        </form>
                        @if(request()->hasAny(['search', 'role']))
                            <div class="mt-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>{{ __('admin.users.cancel') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 60px">#</th>
                                    <th>{{ __('admin.users.user') }}</th>
                                    <th>{{ __('admin.users.email') }}</th>
                                    <th>{{ __('admin.users.roles') }}</th>
                                    <th>{{ __('admin.users.created_at') }}</th>
                                    <th class="text-center" style="width: 180px">{{ __('admin.users.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td class="text-center text-muted small">
                                            {{ $users->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                                         class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $user->name }}</div>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                <i class="bi bi-envelope me-1 text-muted"></i>{{ $user->email }}
                                            </a>
                                        </td>
                                        <td>
                                            @forelse($user->roles as $role)
                                                <span class="badge rounded-pill
                                                    @if($role->name === 'super-admin') bg-danger
                                                    @elseif($role->name === 'admin') bg-primary
                                                    @elseif($role->name === 'editor') bg-warning text-dark
                                                    @elseif($role->name === 'viewer') bg-secondary
                                                    @else bg-info text-dark
                                                    @endif
                                                    me-1">
                                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                                </span>
                                            @empty
                                                <span class="badge bg-light text-dark border">{{ __('admin.users.no_role_badge') }}</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div class="text-dark">{{ $user->created_at->format('d/m/Y') }}</div>
                                                <div class="text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                @can('users.edit')
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="{{ __('admin.users.edit') }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                @endcan

                                                @if(auth()->user()->hasRole('super-admin'))
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-success"
                                                            onclick="showTemporaryPassword({{ $user->id }})"
                                                            title="عرض كلمة المرور المؤقتة"
                                                            id="showPasswordBtn-{{ $user->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#changePasswordModal-{{ $user->id }}"
                                                            title="تغيير كلمة المرور">
                                                        <i class="bi bi-key"></i>
                                                    </button>
                                                @endif

                                                @can('users.delete')
                                                    @if(auth()->id() !== $user->id)
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteUserModal-{{ $user->id }}"
                                                                title="{{ __('admin.users.delete') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-secondary" disabled 
                                                                title="{{ __('admin.users.cannot_delete_self') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-people fs-1 d-block mb-3 opacity-50"></i>
                                                <p class="mb-0">{{ __('admin.users.no_users') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من {{ $users->total() }} مستخدم
                                </div>
                                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete User Modals -->
    @foreach($users as $user)
        @can('users.delete')
            @if(auth()->id() !== $user->id)
                <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title text-danger" id="deleteUserModalLabel-{{ $user->id }}">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>تأكيد الحذف
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                                    <p class="mt-3 mb-2">{{ __('admin.users.delete_confirm') }}</p>
                                    <p class="fw-bold text-dark">{{ $user->name }} ({{ $user->email }})</p>
                                    <small class="text-danger">هذا الإجراء لا يمكن التراجع عنه</small>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i>حذف المستخدم
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endcan

        <!-- Change Password Modal (Super Admin Only) -->
        @if(auth()->user()->hasRole('super-admin'))
            <div class="modal fade" id="changePasswordModal-{{ $user->id }}" tabindex="-1" aria-labelledby="changePasswordModalLabel-{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 bg-primary text-white">
                            <h5 class="modal-title" id="changePasswordModalLabel-{{ $user->id }}">
                                <i class="bi bi-key-fill me-2"></i>تغيير كلمة المرور
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="changePasswordForm-{{ $user->id }}" method="POST" action="{{ route('admin.users.update-password', $user) }}">
                            @csrf
                            <div class="modal-body py-4">
                                <div class="mb-3 text-center">
                                    <div class="avatar avatar-lg mx-auto mb-2">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                             class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                                    </div>
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>

                                <div class="alert alert-info d-flex align-items-center mb-3">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <small>سيتم تغيير كلمة المرور لهذا المستخدم. تأكد من إبلاغه بكلمة المرور الجديدة.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="password-{{ $user->id }}" class="form-label">كلمة المرور الجديدة</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password-{{ $user->id }}" 
                                               name="password" 
                                               required 
                                               minlength="8"
                                               placeholder="أدخل كلمة المرور (8 أحرف على الأقل)">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password-{{ $user->id }}', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation-{{ $user->id }}" class="form-label">تأكيد كلمة المرور</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation-{{ $user->id }}" 
                                               name="password_confirmation" 
                                               required 
                                               minlength="8"
                                               placeholder="أعد إدخال كلمة المرور">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation-{{ $user->id }}', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password-match-{{ $user->id }}" class="invalid-feedback d-none">
                                        كلمة المرور غير متطابقة
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn-{{ $user->id }}">
                                    <i class="bi bi-check-circle me-1"></i>تغيير كلمة المرور
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@push('scripts')
<script>
    // Show temporary password
    function showTemporaryPassword(userId) {
        const btn = document.getElementById('showPasswordBtn-' + userId);
        const originalHTML = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        fetch(`/admin/users/${userId}/show-temporary-password`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            
            if (data.success) {
                // Show password in modal
                const modalHTML = `
                    <div class="modal fade" id="showPasswordModal-${userId}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-key-fill me-2"></i>كلمة المرور المؤقتة
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">كلمة المرور:</label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control form-control-lg text-center fw-bold" 
                                                   id="passwordDisplay-${userId}" 
                                                   value="${data.password}" 
                                                   readonly>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="copyPassword(${userId})">
                                                <i class="bi bi-clipboard"></i> نسخ
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        تنتهي صلاحيتها: ${new Date(data.expires_at).toLocaleString('ar-SA')}
                                    </small>
                                    <div class="alert alert-warning mt-3 mb-0">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <small>كلمة المرور المؤقتة تنتهي بعد 24 ساعة من الإنشاء/التعديل</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove existing modal if any
                const existingModal = document.getElementById('showPasswordModal-' + userId);
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('showPasswordModal-' + userId));
                modal.show();
                
                // Remove modal from DOM after hiding
                document.getElementById('showPasswordModal-' + userId).addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            } else {
                alert('خطأ: ' + (data.message || 'لا توجد كلمة مرور مؤقتة متاحة'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            alert('حدث خطأ أثناء جلب كلمة المرور');
        });
    }

    // Copy password to clipboard
    function copyPassword(userId) {
        const passwordInput = document.getElementById('passwordDisplay-' + userId);
        passwordInput.select();
        document.execCommand('copy');
        
        // Show feedback
        const btn = passwordInput.nextElementSibling;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> تم';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }

    // Toggle password visibility
    function togglePassword(inputId, btn) {
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
    }

    // Validate password confirmation
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($users as $user)
            @if(auth()->user()->hasRole('super-admin'))
                const form{{ $user->id }} = document.getElementById('changePasswordForm-{{ $user->id }}');
                const password{{ $user->id }} = document.getElementById('password-{{ $user->id }}');
                const passwordConf{{ $user->id }} = document.getElementById('password_confirmation-{{ $user->id }}');
                const matchMsg{{ $user->id }} = document.getElementById('password-match-{{ $user->id }}');
                const submitBtn{{ $user->id }} = document.getElementById('submitBtn-{{ $user->id }}');

                function validatePassword{{ $user->id }}() {
                    if (passwordConf{{ $user->id }}.value && password{{ $user->id }}.value !== passwordConf{{ $user->id }}.value) {
                        passwordConf{{ $user->id }}.classList.add('is-invalid');
                        matchMsg{{ $user->id }}.classList.remove('d-none');
                        submitBtn{{ $user->id }}.disabled = true;
                    } else {
                        passwordConf{{ $user->id }}.classList.remove('is-invalid');
                        matchMsg{{ $user->id }}.classList.add('d-none');
                        submitBtn{{ $user->id }}.disabled = false;
                    }
                }

                password{{ $user->id }}.addEventListener('input', validatePassword{{ $user->id }});
                passwordConf{{ $user->id }}.addEventListener('input', validatePassword{{ $user->id }});

                form{{ $user->id }}.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (password{{ $user->id }}.value !== passwordConf{{ $user->id }}.value) {
                        return false;
                    }

                    submitBtn{{ $user->id }}.disabled = true;
                    submitBtn{{ $user->id }}.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري التغيير...';

                    fetch('{{ route('admin.users.update-password', $user) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            password: password{{ $user->id }}.value,
                            password_confirmation: passwordConf{{ $user->id }}.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const alert = document.createElement('div');
                            alert.className = 'alert alert-success alert-dismissible fade show m-3';
                            alert.innerHTML = `
                                <i class="bi bi-check-circle-fill me-2"></i>
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                            document.querySelector('.card-body').insertBefore(alert, document.querySelector('.card-body').firstChild);
                            
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal-{{ $user->id }}'));
                            modal.hide();
                            
                            // Reset form
                            form{{ $user->id }}.reset();
                        } else {
                            alert('خطأ: ' + (data.message || 'حدث خطأ أثناء تغيير كلمة المرور'));
                            submitBtn{{ $user->id }}.disabled = false;
                            submitBtn{{ $user->id }}.innerHTML = '<i class="bi bi-check-circle me-1"></i>تغيير كلمة المرور';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء تغيير كلمة المرور');
                        submitBtn{{ $user->id }}.disabled = false;
                        submitBtn{{ $user->id }}.innerHTML = '<i class="bi bi-check-circle me-1"></i>تغيير كلمة المرور';
                    });
                });
            @endif
        @endforeach
    });
</script>
@endpush
