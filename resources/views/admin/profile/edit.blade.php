@php
    $breadcrumbTitle = __('admin.profile.title');
    $breadcrumbParent = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
    $user = auth()->user();
@endphp
@extends('layouts.admin')
@section('title', __('admin.profile.title'))

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-4">
            {{-- بطاقة البروفايل --}}
            <div class="col-xl-4">
                <x-admin.card>
                    <div class="card-body text-center p-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="avatar-wrapper">
                                <img src="{{ $user->avatar_url }}" id="avatarPreview"
                                     class="rounded-circle img-thumbnail shadow-sm"
                                     alt="{{ __('admin.profile.avatar_alt') }}"
                                     style="width: 130px; height: 130px; object-fit: cover;">
                                <div class="avatar-overlay">
                                    <label for="avatarInput" class="avatar-upload-btn">
                                        <i class="bi bi-camera-fill fs-5"></i>
                                        <span class="d-block small mt-1">{{ __('admin.profile.change_avatar') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #24364A;">{{ $user->display_name }}</h5>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">{{ $user->email }}</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                                    {{ $role->name }}
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
                </x-admin.card>
            </div>

            {{-- المعلومات والأمان --}}
            <div class="col-xl-8">
                {{-- المعلومات الشخصية --}}
                <x-admin.card class="mb-4">
                    <x-admin.card-header-form
                        icon="bi-person-lines-fill"
                        :title="__('admin.profile.personal_info')" />
                    <div class="card-body p-4">
                        <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="visually-hidden">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">{{ __('admin.profile.name') }}</label>
                                    <input type="text" name="name" id="nameInput"
                                           value="{{ old('name', $user->name) }}"
                                           class="form-control rounded-3 @error('name') is-invalid @enderror"
                                           placeholder="{{ __('admin.profile.name_placeholder') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">{{ __('admin.users.name_en') }}</label>
                                    <input type="text" name="name_en"
                                           value="{{ old('name_en', $user->name_en) }}"
                                           class="form-control rounded-3 @error('name_en') is-invalid @enderror"
                                           placeholder="{{ __('admin.users.name_en_placeholder') }}" dir="ltr">
                                    @error('name_en')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">{{ __('admin.profile.email') }}</label>
                                    <input type="email" name="email" id="emailInput"
                                           value="{{ old('email', $user->email) }}"
                                           class="form-control rounded-3 @error('email') is-invalid @enderror"
                                           placeholder="{{ __('admin.profile.email_placeholder') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-save">
                                    <i class="bi bi-check-lg me-1"></i>{{ __('admin.profile.save_changes') }}
                                </button>
                                <button type="reset" class="btn btn-cancel">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>{{ __('admin.profile.reset') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </x-admin.card>

                {{-- تغيير كلمة المرور --}}
                <x-admin.card>
                    <x-admin.card-header-form
                        icon="bi-shield-lock"
                        :title="__('admin.profile.change_password')" />
                    <div class="card-body p-4">
                        <form id="passwordForm" action="{{ route('admin.profile.password') }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">{{ __('admin.profile.current_password') }}</label>
                                    <input type="password" name="current_password" id="currentPasswordInput"
                                           class="form-control rounded-3 @error('current_password') is-invalid @enderror"
                                           placeholder="{{ __('admin.profile.current_password_placeholder') }}" required>
                                    @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">{{ __('admin.profile.new_password') }}</label>
                                    <input type="password" name="password" id="passwordInput"
                                           class="form-control rounded-3 @error('password') is-invalid @enderror"
                                           placeholder="{{ __('admin.profile.new_password_placeholder') }}" required>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">{{ __('admin.profile.confirm_password') }}</label>
                                    <input type="password" name="password_confirmation" id="passwordConfirmationInput"
                                           class="form-control rounded-3"
                                           placeholder="{{ __('admin.profile.confirm_password_placeholder') }}" required>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-save">
                                    <i class="bi bi-shield-lock me-1"></i>{{ __('admin.profile.update_password') }}
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
        .avatar-wrapper { position: relative; display: inline-block; }
        .avatar-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease; cursor: pointer;
        }
        .avatar-wrapper:hover .avatar-overlay { opacity: 1; }
        .avatar-upload-btn { color: #fff; text-align: center; cursor: pointer; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');

            avatarInput?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({ icon: 'error', title: '{{ __('admin.ui.error') }}', text: '{{ __('admin.common.err_avatar_size') }}', confirmButtonText: '{{ __('admin.ui.ok') }}' });
                    return;
                }
                if (!file.type.startsWith('image/')) {
                    Swal.fire({ icon: 'error', title: '{{ __('admin.ui.error') }}', text: '{{ __('admin.common.err_file_must_be_image') }}', confirmButtonText: '{{ __('admin.ui.ok') }}' });
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => avatarPreview.src = e.target.result;
                reader.readAsDataURL(file);
            });

            document.getElementById('profileForm')?.querySelector('[type="reset"]')?.addEventListener('click', () => {
                avatarPreview.src = '{{ $user->avatar_url }}';
                if (avatarInput) avatarInput.value = '';
            });

            ['profileForm', 'passwordForm'].forEach(id => {
                document.getElementById(id)?.addEventListener('submit', function() {
                    const btn = this.querySelector('[type="submit"]');
                    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin.ui.saving') }}'; }
                });
            });
        });
    </script>
@endpush
