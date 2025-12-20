@extends('layouts.admin')
@section('title', __('admin.permissions.edit_title'))

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.permissions.edit_title') }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.permissions.index') }}">{{ __('admin.menu.permissions') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('admin.permissions.edit_title') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>
                        {{ __('admin.permissions.edit_title') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('admin.permissions.form_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="{{ __('admin.permissions.form_name_placeholder') }}"
                                   value="{{ old('name', $permission->name) }}"
                                   required>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ __('admin.permissions.form_name_help') }}
                            </small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.permissions.form_guard') }}</label>
                            <select name="guard_name" class="form-select @error('guard_name') is-invalid @enderror">
                                <option value="web" {{ old('guard_name', $permission->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ old('guard_name', $permission->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                            @error('guard_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(count($roles) > 0)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">الأدوار المستخدمة</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($roles as $role)
                                    <span class="badge bg-info">{{ $role }}</span>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                هذه الأدوار تستخدم هذه الصلاحية
                            </small>
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ __('admin.common.form_save') }}
                            </button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-light">
                                {{ __('admin.actions.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection