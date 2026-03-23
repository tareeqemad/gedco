@php
    $breadcrumbTitle     = __('admin.permissions.edit_title');
    $breadcrumbParent    = __('admin.permissions.title');
    $breadcrumbParentUrl = route('admin.permissions.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.permissions.edit_title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-shield-lock"
                :title="__('admin.permissions.edit_title')" />
        </x-admin.card>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1 mb-2">
                                    {{ __('admin.permissions.form_name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control rounded-3 @error('name') is-invalid @enderror" 
                                       style="height: 45px;"
                                       placeholder="{{ __('admin.permissions.form_name_placeholder') }}"
                                       value="{{ old('name', $permission->name) }}"
                                       required>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ __('admin.permissions.form_name_help') }}
                                </small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1 mb-2">
                                    <i class="bi bi-shield-check text-primary"></i>
                                    {{ __('admin.permissions.form_guard') }}
                                </label>
                                <select name="guard_name" class="form-select rounded-3 @error('guard_name') is-invalid @enderror" style="height: 45px;">
                                    <option value="web" {{ old('guard_name', $permission->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="api" {{ old('guard_name', $permission->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                                </select>
                                @error('guard_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(count($roles) > 0)
                            <div class="mb-4 p-3 bg-light rounded-4 border border-2 border-info-subtle">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1 mb-2">
                                    <i class="bi bi-people text-primary"></i>
                                    الأدوار المستخدمة
                                </label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($roles as $role)
                                        <span class="badge bg-info text-white rounded-pill px-3 py-1">{{ $role }}</span>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    هذه الأدوار تستخدم هذه الصلاحية
                                </small>
                            </div>
                            @endif

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary rounded-3 px-4 shadow-sm">
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle"></i>
                                    {{ __('admin.common.form_save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
