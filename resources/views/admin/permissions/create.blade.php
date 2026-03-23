@php
    $breadcrumbTitle     = __('admin.permissions.add_new');
    $breadcrumbParent    = __('admin.permissions.title');
    $breadcrumbParentUrl = route('admin.permissions.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.permissions.add_new'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-shield-lock"
                :title="__('admin.permissions.add_new')" />
        </x-admin.card>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.permissions.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1 mb-2">
                                    {{ __('admin.permissions.form_name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control rounded-3 @error('name') is-invalid @enderror" 

                                       placeholder="{{ __('admin.permissions.form_name_placeholder') }}"
                                       value="{{ old('name') }}"
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
                                    <i class="bi bi-shield-check"></i>
                                    {{ __('admin.permissions.form_guard') }}
                                </label>
                                <select name="guard_name" class="form-select rounded-3 @error('guard_name') is-invalid @enderror">
                                    <option value="web" {{ old('guard_name', 'web') == 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                                </select>
                                @error('guard_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-cancel">
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-save d-flex align-items-center gap-2">
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
