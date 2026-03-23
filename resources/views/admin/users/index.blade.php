@php
    $breadcrumbTitle     = __('admin.users.users_list');
    $breadcrumbParent    = __('admin.menu.users');
    $breadcrumbParentUrl = route('admin.users.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.users.title'))

@section('content')
    <div class="container-fluid">
        <!-- Main Card -->
        <x-admin.card class="users-card">
            <!-- Header Section -->
            <x-admin.card-header-index
                icon="bi-people-fill"
                :title="__('admin.users.users_list')"
                :create-route="route('admin.users.create')"
                :create-label="__('admin.users.new_user')"
                create-permission="users.create">
                <x-slot:badge>
                    <div class="d-flex align-items-center gap-2 text-white-50" style="font-size: 0.9rem;">
                        <i class="bi bi-people-fill"></i>
                        <span>{{ __('admin.users.total_users_text') }}:</span>
                        <strong class="text-white" id="totalUsers">{{ $users->total() }}</strong>
                    </div>
                </x-slot:badge>
            </x-admin.card-header-index>
            <!-- Filter Section -->
            <div class="card-body p-3">
                <div class="users-filters">
                <form id="usersFilterForm" method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-7">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-search me-1"></i>{{ __('admin.users.search_placeholder') }}
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   id="searchInput"
                                   class="form-control" 
                                   placeholder="{{ __('admin.users.search_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-funnel me-1"></i>{{ __('admin.users.roles') }}
                        </label>
                        <select name="role" id="roleSelect" class="form-select">
                            <option value="">{{ __('admin.users.all_roles') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" id="searchBtn">
                            <i class="bi bi-funnel me-2"></i>{{ __('admin.users.filter') }}
                        </button>
                    </div>
                </form>
                @if(request()->hasAny(['search', 'role']))
                    <div class="users-filter-badge">
                        <span class="text-muted">فلاتر نشطة:</span>
                        @if(request('search'))
                            <span class="badge bg-primary">{{ __('admin.users.search_placeholder') }}: {{ request('search') }}</span>
                        @endif
                        @if(request('role'))
                            <span class="badge bg-info">{{ __('admin.users.roles') }}: {{ ucfirst(str_replace('-', ' ', request('role'))) }}</span>
                        @endif
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>{{ __('admin.users.cancel') }}
                        </a>
                    </div>
                @endif
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="users-loading" id="usersLoading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <p class="mt-2 text-muted">جاري تحميل البيانات...</p>
            </div>

            <!-- Table Container -->
            <div class="p-0">
                <div id="usersTableContainer">
                    @include('admin.users.partials.table', ['users' => $users])
                </div>
            </div>

            <!-- Pagination Container -->
            @if($users->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div id="usersPaginationContainer">
                        @include('admin.users.partials.pagination', ['users' => $users])
                    </div>
                </div>
            @endif
        </x-admin.card>
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
                        <div class="modal-header border-0 bg-gradient-primary text-white">
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

                                <div class="alert alert-info d-flex align-items-center mb-3 password-change-alert" id="passwordAlert-{{ $user->id }}" style="display: none !important; visibility: hidden !important; opacity: 0 !important; height: 0 !important; padding: 0 !important; margin: 0 !important; overflow: hidden !important;">
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

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/users.css') }}">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hide all password alerts on page load
        document.querySelectorAll('[id^="passwordAlert-"]').forEach(function(alert) {
            alert.style.display = 'none';
            alert.style.visibility = 'hidden';
            alert.style.opacity = '0';
            alert.style.height = '0';
            alert.style.padding = '0';
            alert.style.margin = '0';
            alert.style.overflow = 'hidden';
        });
        
        const filterForm = document.getElementById('usersFilterForm');
        const searchInput = document.getElementById('searchInput');
        const roleSelect = document.getElementById('roleSelect');
        const searchBtn = document.getElementById('searchBtn');
        
        // Update role select styling when value changes
        function updateRoleSelectStyle() {
            if (roleSelect) {
                if (roleSelect.value) {
                    roleSelect.classList.add('role-selected');
                } else {
                    roleSelect.classList.remove('role-selected');
                }
            }
        }
        
        // Initial check
        if (roleSelect) {
            updateRoleSelectStyle();
            // Listen for changes
            roleSelect.addEventListener('change', updateRoleSelectStyle);
        }
        const usersTableContainer = document.getElementById('usersTableContainer');
        const usersPaginationContainer = document.getElementById('usersPaginationContainer');
        const usersLoading = document.getElementById('usersLoading');
        const totalUsersSpan = document.getElementById('totalUsers');

        // AJAX Search Function
        function performSearch() {
            if (!filterForm || !usersLoading || !usersTableContainer) return;
            
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            
            // Show loading
            usersLoading.classList.add('active');
            if (usersTableContainer) usersTableContainer.style.opacity = '0.5';
            const paginationContainer = document.getElementById('usersPaginationContainer');
            if (paginationContainer) paginationContainer.style.opacity = '0.5';
            
            // Disable button
            if (searchBtn) {
                searchBtn.disabled = true;
                searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري البحث...';
            }

            fetch(`{{ route('admin.users.index') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update table
                    if (usersTableContainer) usersTableContainer.innerHTML = data.html;
                    
                    // Update pagination
                    if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                    
                    // Update total users count
                    if (totalUsersSpan && data.total !== undefined) {
                        totalUsersSpan.textContent = data.total;
                    }
                    
                    // Update role select style
                    updateRoleSelectStyle();
                    
                    // Update URL without reload
                    window.history.pushState({}, '', `{{ route('admin.users.index') }}?${params.toString()}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.adminNotifications) {
                    window.adminNotifications.error('حدث خطأ أثناء البحث. سيتم إعادة تحميل الصفحة.');
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    alert('حدث خطأ أثناء البحث. سيتم إعادة تحميل الصفحة.');
                    window.location.reload();
                }
            })
            .finally(() => {
                // Hide loading
                if (usersLoading) usersLoading.classList.remove('active');
                if (usersTableContainer) usersTableContainer.style.opacity = '1';
                if (paginationContainer) paginationContainer.style.opacity = '1';
                
                // Re-enable button
                if (searchBtn) {
                    searchBtn.disabled = false;
                    searchBtn.innerHTML = '<i class="bi bi-funnel me-2"></i>{{ __('admin.users.filter') }}';
                }
            });
        }

        // Handle form submit
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
            });
        }

        // Handle pagination links (delegate event)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('a').href;
                const urlObj = new URL(url);
                
                // Update form values from URL
                if (searchInput && urlObj.searchParams.get('search')) {
                    searchInput.value = urlObj.searchParams.get('search');
                }
                if (roleSelect && urlObj.searchParams.get('role')) {
                    roleSelect.value = urlObj.searchParams.get('role');
                    updateRoleSelectStyle();
                }
                
                // Perform search with new page
                if (!filterForm) return;
                
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                params.set('page', urlObj.searchParams.get('page') || '1');
                
                if (usersLoading) usersLoading.classList.add('active');
                if (usersTableContainer) usersTableContainer.style.opacity = '0.5';
                const paginationContainer = document.getElementById('usersPaginationContainer');
                if (paginationContainer) paginationContainer.style.opacity = '0.5';
                
                fetch(`${urlObj.pathname}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (usersTableContainer) usersTableContainer.innerHTML = data.html;
                        if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                        if (totalUsersSpan && data.total !== undefined) {
                            totalUsersSpan.textContent = data.total;
                        }
                        window.history.pushState({}, '', url);
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.href = url;
                })
                .finally(() => {
                    if (usersLoading) usersLoading.classList.remove('active');
                    if (usersTableContainer) usersTableContainer.style.opacity = '1';
                    if (paginationContainer) paginationContainer.style.opacity = '1';
                });
            }
        });

        // Show temporary password
        window.showTemporaryPassword = function(userId) {
            const btn = document.getElementById('showPasswordBtn-' + userId) || document.getElementById('showPasswordBtn-mobile-' + userId);
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
                    
                    const existingModal = document.getElementById('showPasswordModal-' + userId);
                    if (existingModal) {
                        existingModal.remove();
                    }
                    
                    document.body.insertAdjacentHTML('beforeend', modalHTML);
                    const modal = new bootstrap.Modal(document.getElementById('showPasswordModal-' + userId));
                    modal.show();
                    
                    document.getElementById('showPasswordModal-' + userId).addEventListener('hidden.bs.modal', function() {
                        this.remove();
                    });
                } else {
                    if (window.adminNotifications) {
                        window.adminNotifications.error(data.message || 'لا توجد كلمة مرور مؤقتة متاحة');
                    } else {
                        alert('خطأ: ' + (data.message || 'لا توجد كلمة مرور مؤقتة متاحة'));
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = originalHTML;
                if (window.adminNotifications) {
                    window.adminNotifications.error('حدث خطأ أثناء جلب كلمة المرور');
                } else {
                    alert('حدث خطأ أثناء جلب كلمة المرور');
                }
            });
        };

        // Copy password to clipboard
        window.copyPassword = function(userId) {
            const passwordInput = document.getElementById('passwordDisplay-' + userId);
            passwordInput.select();
            document.execCommand('copy');
            
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
        };

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

        // Validate password confirmation
        @foreach($users as $user)
            @if(auth()->user()->hasRole('super-admin'))
                // Show alert only when modal is opened
                const changePasswordModal{{ $user->id }} = document.getElementById('changePasswordModal-{{ $user->id }}');
                const passwordAlert{{ $user->id }} = document.getElementById('passwordAlert-{{ $user->id }}');
                
                if (changePasswordModal{{ $user->id }} && passwordAlert{{ $user->id }}) {
                    // Ensure alert is hidden on page load
                    passwordAlert{{ $user->id }}.style.display = 'none';
                    passwordAlert{{ $user->id }}.style.visibility = 'hidden';
                    passwordAlert{{ $user->id }}.style.opacity = '0';
                    passwordAlert{{ $user->id }}.style.height = '0';
                    passwordAlert{{ $user->id }}.style.padding = '0';
                    passwordAlert{{ $user->id }}.style.margin = '0';
                    passwordAlert{{ $user->id }}.style.overflow = 'hidden';
                    
                    changePasswordModal{{ $user->id }}.addEventListener('show.bs.modal', function() {
                        passwordAlert{{ $user->id }}.style.display = 'flex';
                        passwordAlert{{ $user->id }}.style.visibility = 'visible';
                        passwordAlert{{ $user->id }}.style.opacity = '1';
                        passwordAlert{{ $user->id }}.style.height = 'auto';
                        passwordAlert{{ $user->id }}.style.padding = '0.75rem 1rem';
                        passwordAlert{{ $user->id }}.style.marginBottom = '1rem';
                        passwordAlert{{ $user->id }}.style.overflow = 'visible';
                    });
                    
                    changePasswordModal{{ $user->id }}.addEventListener('hidden.bs.modal', function() {
                        passwordAlert{{ $user->id }}.style.display = 'none';
                        passwordAlert{{ $user->id }}.style.visibility = 'hidden';
                        passwordAlert{{ $user->id }}.style.opacity = '0';
                        passwordAlert{{ $user->id }}.style.height = '0';
                        passwordAlert{{ $user->id }}.style.padding = '0';
                        passwordAlert{{ $user->id }}.style.margin = '0';
                        passwordAlert{{ $user->id }}.style.overflow = 'hidden';
                    });
                }
                
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

                if (password{{ $user->id }}) {
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
                                // Show toast notification
                                if (window.adminNotifications) {
                                    window.adminNotifications.showToast(data.message, 'success');
                                }
                                
                                const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal-{{ $user->id }}'));
                                modal.hide();
                                
                                form{{ $user->id }}.reset();
                            } else {
                                if (window.adminNotifications) {
                                    window.adminNotifications.error(data.message || 'حدث خطأ أثناء تغيير كلمة المرور');
                                } else {
                                    alert('خطأ: ' + (data.message || 'حدث خطأ أثناء تغيير كلمة المرور'));
                                }
                                submitBtn{{ $user->id }}.disabled = false;
                                submitBtn{{ $user->id }}.innerHTML = '<i class="bi bi-check-circle me-1"></i>تغيير كلمة المرور';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (window.adminNotifications) {
                                window.adminNotifications.error('حدث خطأ أثناء تغيير كلمة المرور');
                            } else {
                                alert('حدث خطأ أثناء تغيير كلمة المرور');
                            }
                            submitBtn{{ $user->id }}.disabled = false;
                            submitBtn{{ $user->id }}.innerHTML = '<i class="bi bi-check-circle me-1"></i>تغيير كلمة المرور';
                        });
                    });
                }
            @endif
        @endforeach
    });
</script>
@endpush
