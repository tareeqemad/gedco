@extends('layouts.admin')

@section('title', __('admin.profile.title'))

@section('content')
    @php
        $breadcrumbTitle = __('admin.profile.title');
        $user = auth()->user();
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-person-circle"
                :title="__('admin.profile.title')" />
        </x-admin.card>

        <!-- Flash Messages -->

        <div class="row g-4">
            <!-- Profile Picture Card -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body text-center p-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="avatar-wrapper">
                                <img src="{{ $user->avatar_url }}" 
                                     id="avatarPreview"
                                     class="avatar-xl rounded-circle img-thumbnail border-4 border-white shadow-lg" 
                                     alt="{{ __('admin.profile.avatar_alt') }}"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="avatar-overlay">
                                    <label for="avatarInput" class="avatar-upload-btn">
                                        <i class="bi bi-camera-fill fs-4"></i>
                                        <span class="d-block small mt-1">{{ __('admin.profile.change_avatar') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    <i class="bi bi-shield-check me-1"></i>{{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ __('admin.profile.member_since') }}: {{ $user->created_at->format('Y/m/d') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Form -->
            <div class="col-xl-8">
                <x-admin.card class="mb-4">
                    <x-admin.card-header-form
                        icon="bi-person-lines-fill"
                        :title="__('admin.profile.personal_info')" />
                    <div class="card-body p-5">
                        <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="visually-hidden">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-person text-primary"></i>
                                        {{ __('admin.profile.name') }}
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="nameInput"
                                           value="{{ old('name', $user->name) }}"
                                           class="form-control rounded-3  @error('name') is-invalid @enderror" 
                                           placeholder="{{ __('admin.profile.name_placeholder') }}"
                                           required
                                           style="height: 45px; font-size: 1rem;">
                                    @error('name')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-envelope text-primary"></i>
                                        {{ __('admin.profile.email') }}
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="emailInput"
                                           value="{{ old('email', $user->email) }}"
                                           class="form-control rounded-3  @error('email') is-invalid @enderror" 
                                           placeholder="{{ __('admin.profile.email_placeholder') }}"
                                           required
                                           style="height: 45px; font-size: 1rem;">
                                    @error('email')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 shadow-sm" style="min-width: 150px; font-weight: 600;">
                                    <i class="bi bi-check-lg me-2"></i>{{ __('admin.profile.save_changes') }}
                                </button>
                                <button type="reset" class="btn btn-outline-secondary px-5 py-2 rounded-3" style="min-width: 150px; font-weight: 600;">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>{{ __('admin.profile.reset') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </x-admin.card>

                <!-- Change Password Card -->
                <x-admin.card>
                    <x-admin.card-header-form
                        icon="bi-shield-lock"
                        :title="__('admin.profile.change_password')" />
                    <div class="card-body p-5">
                        <form id="passwordForm" action="{{ route('admin.profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-key text-danger"></i>
                                        {{ __('admin.profile.current_password') }}
                                    </label>
                                    <input type="password" 
                                           name="current_password" 
                                           id="currentPasswordInput"
                                           class="form-control rounded-3  @error('current_password') is-invalid @enderror" 
                                           placeholder="{{ __('admin.profile.current_password_placeholder') }}"
                                           required
                                           style="height: 45px; font-size: 1rem;">
                                    @error('current_password')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-key-fill text-danger"></i>
                                        {{ __('admin.profile.new_password') }}
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           id="passwordInput"
                                           class="form-control rounded-3  @error('password') is-invalid @enderror" 
                                           placeholder="{{ __('admin.profile.new_password_placeholder') }}"
                                           required
                                           style="height: 45px; font-size: 1rem;">
                                    @error('password')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-key-fill text-danger"></i>
                                        {{ __('admin.profile.confirm_password') }}
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="passwordConfirmationInput"
                                           class="form-control rounded-3 " 
                                           placeholder="{{ __('admin.profile.confirm_password_placeholder') }}"
                                           required
                                           style="height: 45px; font-size: 1rem;">
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-danger px-5 py-2 rounded-3 shadow-sm" style="min-width: 200px; font-weight: 600;">
                                    <i class="bi bi-shield-lock me-2"></i>{{ __('admin.profile.update_password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-upload-btn {
            color: white;
            text-align: center;
            cursor: pointer;
            padding: 1rem;
        }

        .avatar-upload-btn:hover {
            color: #66b3ff;
        }

        #avatarPreview {
            transition: transform 0.3s ease;
        }

        .avatar-wrapper:hover #avatarPreview {
            transform: scale(1.05);
        }

        .form-control:focus,
        .form-select:focus {
            background: #ffffff !important;
            border-color: #0066cc !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.15) !important;
        }


        @media (max-width: 768px) {
            .avatar-xl {
                width: 120px !important;
                height: 120px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');
            const profileForm = document.getElementById('profileForm');

            // Handle avatar upload
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file size (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
                                confirmButtonText: 'حسناً'
                            });
                            return;
                        }

                        // Validate file type
                        if (!file.type.startsWith('image/')) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'الملف يجب أن يكون صورة',
                                confirmButtonText: 'حسناً'
                            });
                            return;
                        }

                        // Preview image
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            avatarPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Handle form reset
            const resetBtn = profileForm?.querySelector('button[type="reset"]');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    avatarPreview.src = '{{ $user->avatar_url }}';
                    if (avatarInput) {
                        avatarInput.value = '';
                    }
                });
            }

            // Handle form submission with loading state
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    const submitBtn = profileForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري الحفظ...';
                    }
                });
            }

            const passwordForm = document.getElementById('passwordForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    const submitBtn = passwordForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري التحديث...';
                    }
                });
            }
        });
    </script>
@endpush
