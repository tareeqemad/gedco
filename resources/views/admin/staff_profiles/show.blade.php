@php
    $breadcrumbTitle     = __('admin.staff_profiles.show_title') . ' - ' . $profile->full_name;
    $breadcrumbParent    = __('admin.staff_profiles.title');
    $breadcrumbParentUrl = route('admin.staff-profiles.index');
    
    $loc = config('staff_enums.locations');
    $status = config('staff_enums.status');
    $ready = config('staff_enums.readiness');
    $marital = config('staff_enums.marital_status');
    $house = config('staff_enums.house_status');
    $housing = config('staff_enums.housing_type');
    $relation = config('staff_enums.relation');
@endphp
@extends('layouts.admin')
@section('title', __('admin.staff_profiles.show_title'))

@section('content')
    <div class="container-fluid staff-profile-show-page">
        <!-- Hero Header Section -->
        <div class="staff-profile-hero mb-4">
            <div class="staff-profile-hero-bg"></div>
            <div class="staff-profile-hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="staff-profile-identity">
                            <div class="staff-profile-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="staff-profile-info">
                                <h1 class="staff-profile-name">{{ $profile->full_name }}</h1>
                                <div class="staff-profile-meta">
                                    <div class="staff-meta-item">
                                        <i class="bi bi-card-text"></i>
                                        <span><strong>{{ __('admin.staff_profiles.show_national_id') }}:</strong> {{ $profile->national_id }}</span>
                                    </div>
                                    @if($profile->employee_number)
                                        <div class="staff-meta-item">
                                            <i class="bi bi-hash"></i>
                                            <span><strong>{{ __('admin.staff_profiles.show_employee_number') }}:</strong> #{{ $profile->employee_number }}</span>
                                        </div>
                                    @endif
                                    <div class="staff-meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span><strong>{{ __('admin.staff_profiles.show_location') }}:</strong> {{ $loc[$profile->location] ?? __('admin.staff_profiles.show_not_specified') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <div class="staff-profile-status-badges">
                            <div class="status-badge-group">
                                <span class="status-label">{{ __('admin.staff_profiles.show_readiness') }}</span>
                                @switch($profile->readiness)
                                    @case('working')
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('admin.staff_profiles.readiness_working') }}
                                        </span>
                                        @break
                                    @case('ready')
                                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('admin.staff_profiles.readiness_ready') }}
                                        </span>
                                        @break
                                    @case('not_ready')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i>{{ __('admin.staff_profiles.readiness_not_ready') }}
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                            {{ __('admin.staff_profiles.show_not_specified') }}
                                        </span>
                                @endswitch
                            </div>
                            <div class="status-badge-group mt-2">
                                <span class="status-label">{{ __('admin.staff_profiles.show_status') }}</span>
                                @if($profile->status == 'resident')
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-house-check-fill me-1"></i>{{ __('admin.staff_profiles.status_resident') }}
                                    </span>
                                @elseif($profile->status == 'displaced')
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ __('admin.staff_profiles.status_displaced') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                        {{ __('admin.staff_profiles.show_not_specified') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="staff-profile-actions mt-3 pt-3 border-top border-white border-opacity-25">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-light btn-sm rounded-pill shadow-sm">
                            <i class="bi bi-arrow-right me-1"></i>{{ __('admin.common.back') }}
                        </a>
                        <a href="{{ route('admin.staff-profiles.edit', $profile) }}" class="btn btn-warning btn-sm rounded-pill shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i>{{ __('admin.common.edit') }}
                        </a>
                        <button onclick="window.print()" class="btn btn-light btn-sm rounded-pill shadow-sm no-print">
                            <i class="bi bi-printer-fill me-1"></i>{{ __('admin.common.print') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Basic Data -->
            <div class="col-lg-8">
                <div class="staff-info-card">
                    <div class="staff-info-card-header">
                        <i class="bi bi-person-badge-fill"></i>
                        <h4>{{ __('admin.staff_profiles.show_basic_data') }}</h4>
                    </div>
                    <div class="staff-info-card-body">
                        <div class="staff-info-grid">
                            <div class="staff-info-box">
                                <div class="staff-info-icon">
                                    <i class="bi bi-calendar3"></i>
                                </div>
                                <div class="staff-info-content">
                                    <div class="staff-info-label">{{ __('admin.staff_profiles.show_birth_date') }}</div>
                                    <div class="staff-info-value">{{ $profile->birth_date?->format('d/m/Y') ?? __('admin.staff_profiles.show_not_specified') }}</div>
                                </div>
                            </div>
                            <div class="staff-info-box">
                                <div class="staff-info-icon">
                                    <i class="bi bi-briefcase-fill"></i>
                                </div>
                                <div class="staff-info-content">
                                    <div class="staff-info-label">{{ __('admin.staff_profiles.show_job_title') }}</div>
                                    <div class="staff-info-value">{{ $profile->job_title ?? __('admin.staff_profiles.show_not_specified') }}</div>
                                </div>
                            </div>
                            <div class="staff-info-box">
                                <div class="staff-info-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="staff-info-content">
                                    <div class="staff-info-label">{{ __('admin.staff_profiles.show_department') }}</div>
                                    <div class="staff-info-value">
                                        {{ $profile->department ?? '' }}
                                        @if($profile->directorate) / {{ $profile->directorate }} @endif
                                        @if($profile->section) / {{ $profile->section }} @endif
                                        @if(!$profile->department && !$profile->directorate && !$profile->section) {{ __('admin.staff_profiles.show_not_specified') }} @endif
                                    </div>
                                </div>
                            </div>
                            <div class="staff-info-box">
                                <div class="staff-info-icon">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                                <div class="staff-info-content">
                                    <div class="staff-info-label">{{ __('admin.staff_profiles.show_marital_status') }}</div>
                                    <div class="staff-info-value">{{ $marital[$profile->marital_status] ?? __('admin.staff_profiles.show_not_specified') }}</div>
                                </div>
                            </div>
                            <div class="staff-info-box">
                                <div class="staff-info-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="staff-info-content">
                                    <div class="staff-info-label">{{ __('admin.staff_profiles.show_family_members') }}</div>
                                    <div class="staff-info-value">{{ $profile->family_members_count ?? __('admin.staff_profiles.show_not_specified') }}</div>
                                </div>
                            </div>
                            <div class="staff-info-box">
                                <div class="staff-info-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="staff-info-content">
                                    <div class="staff-info-label">{{ __('admin.staff_profiles.show_last_update') }}</div>
                                    <div class="staff-info-value">
                                        {{ $profile->updated_at?->format('d/m/Y H:i') ?? __('admin.staff_profiles.show_not_updated') }}
                                        @if($profile->updated_at && $profile->updated_at->diffInDays($profile->created_at) > 0)
                                            <small class="text-success d-block mt-1">({{ $profile->updated_at->diffForHumans() }})</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Members -->
            <div class="col-lg-4">
                <div class="staff-info-card h-100">
                    <div class="staff-info-card-header">
                        <i class="bi bi-people-fill"></i>
                        <h4>{{ __('admin.staff_profiles.show_family_members_title') }}</h4>
                        @if($profile->dependents?->count())
                            <span class="staff-badge-count">{{ $profile->dependents->count() }}</span>
                        @endif
                    </div>
                    <div class="staff-info-card-body">
                        @if($profile->dependents?->count())
                            <div class="staff-family-list">
                                @foreach($profile->dependents as $i => $d)
                                    <div class="staff-family-item">
                                        <div class="staff-family-number">{{ $i+1 }}</div>
                                        <div class="staff-family-content">
                                            <div class="staff-family-name">{{ $d->name }}</div>
                                            <div class="staff-family-details">
                                                <span class="staff-family-relation">
                                                    <i class="bi bi-person-badge"></i>
                                                    {{ $relation[$d->relation] ?? $d->relation }}
                                                </span>
                                                <span class="staff-family-birth">
                                                    <i class="bi bi-calendar"></i>
                                                    {{ $d->birth_date ? \Carbon\Carbon::parse($d->birth_date)->format('d/m/Y') : '—' }}
                                                </span>
                                                @if($d->is_student)
                                                    <span class="staff-family-student">
                                                        <i class="bi bi-mortarboard-fill"></i>
                                                        {{ __('admin.staff_profiles.show_yes') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="staff-empty-state">
                                <i class="bi bi-people"></i>
                                <p>{{ __('admin.staff_profiles.show_no_family_data') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Housing and Contact -->
            <div class="col-lg-6">
                <div class="staff-info-card">
                    <div class="staff-info-card-header">
                        <i class="bi bi-house-door-fill"></i>
                        <h4>{{ __('admin.staff_profiles.show_housing_data') }}</h4>
                    </div>
                    <div class="staff-info-card-body">
                        <div class="staff-contact-list">
                            <div class="staff-contact-item">
                                <div class="staff-contact-icon bg-primary-subtle">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                </div>
                                <div class="staff-contact-content">
                                    <div class="staff-contact-label">{{ __('admin.staff_profiles.show_original_address') }}</div>
                                    <div class="staff-contact-value">{{ $profile->original_address ?: __('admin.staff_profiles.show_not_specified') }}</div>
                                </div>
                            </div>
                            <div class="staff-contact-item">
                                <div class="staff-contact-icon bg-info-subtle">
                                    <i class="bi bi-house-check-fill text-info"></i>
                                </div>
                                <div class="staff-contact-content">
                                    <div class="staff-contact-label">{{ __('admin.staff_profiles.show_house_status') }}</div>
                                    <div class="staff-contact-value">{{ $house[$profile->house_status] ?? __('admin.staff_profiles.show_not_specified') }}</div>
                                </div>
                            </div>
                            @if($profile->status == 'displaced')
                                <div class="staff-contact-item border-danger">
                                    <div class="staff-contact-icon bg-danger-subtle">
                                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                    </div>
                                    <div class="staff-contact-content">
                                        <div class="staff-contact-label text-danger">{{ __('admin.staff_profiles.show_current_address') }}</div>
                                        <div class="staff-contact-value text-danger fw-bold">{{ $profile->current_address }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="staff-contact-item">
                                <div class="staff-contact-icon bg-warning-subtle">
                                    <i class="bi bi-building text-warning"></i>
                                </div>
                                <div class="staff-contact-content">
                                    <div class="staff-contact-label">{{ __('admin.staff_profiles.show_housing_type') }}</div>
                                    <div class="staff-contact-value">{{ $housing[$profile->housing_type] ?? __('admin.staff_profiles.show_not_specified') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="staff-info-card">
                    <div class="staff-info-card-header">
                        <i class="bi bi-telephone-fill"></i>
                        <h4>{{ __('admin.staff_profiles.show_contact_info') }}</h4>
                    </div>
                    <div class="staff-info-card-body">
                        <div class="staff-contact-list">
                            <div class="staff-contact-item">
                                <div class="staff-contact-icon bg-success-subtle">
                                    <i class="bi bi-phone-fill text-success"></i>
                                </div>
                                <div class="staff-contact-content">
                                    <div class="staff-contact-label">{{ __('admin.staff_profiles.show_mobile') }}</div>
                                    <div class="staff-contact-value">
                                        @if($profile->mobile)
                                            <a href="tel:{{ $profile->mobile }}" class="text-decoration-none">{{ $profile->mobile }}</a>
                                        @else
                                            {{ __('admin.staff_profiles.show_not_specified') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($profile->mobile_alt)
                                <div class="staff-contact-item">
                                    <div class="staff-contact-icon bg-success-subtle">
                                        <i class="bi bi-phone text-success"></i>
                                    </div>
                                    <div class="staff-contact-content">
                                        <div class="staff-contact-label">{{ __('admin.staff_profiles.show_mobile_alt') }}</div>
                                        <div class="staff-contact-value">
                                            <a href="tel:{{ $profile->mobile_alt }}" class="text-decoration-none">{{ $profile->mobile_alt }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($profile->whatsapp)
                                <div class="staff-contact-item">
                                    <div class="staff-contact-icon bg-success-subtle">
                                        <i class="bi bi-whatsapp text-success"></i>
                                    </div>
                                    <div class="staff-contact-content">
                                        <div class="staff-contact-label">{{ __('admin.staff_profiles.show_whatsapp') }}</div>
                                        <div class="staff-contact-value">
                                            @php
                                                $whats = $profile->whatsapp;
                                                if (str_starts_with($whats, '970')) {
                                                    $formatted = '+970 ' . substr($whats, 3);
                                                } elseif (str_starts_with($whats, '972')) {
                                                    $formatted = '+972 ' . substr($whats, 3);
                                                } else {
                                                    $formatted = $whats;
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $whats }}" target="_blank" class="text-decoration-none">{{ $formatted }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($profile->telegram)
                                <div class="staff-contact-item">
                                    <div class="staff-contact-icon bg-info-subtle">
                                        <i class="bi bi-telegram text-info"></i>
                                    </div>
                                    <div class="staff-contact-content">
                                        <div class="staff-contact-label">{{ __('admin.staff_profiles.show_telegram') }}</div>
                                        <div class="staff-contact-value">
                                            <a href="https://t.me/{{ $profile->telegram }}" target="_blank" class="text-decoration-none">{{ $profile->telegram }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($profile->gmail)
                                <div class="staff-contact-item">
                                    <div class="staff-contact-icon bg-danger-subtle">
                                        <i class="bi bi-envelope-fill text-danger"></i>
                                    </div>
                                    <div class="staff-contact-content">
                                        <div class="staff-contact-label">{{ __('admin.staff_profiles.show_email') }}</div>
                                        <div class="staff-contact-value">
                                            <a href="mailto:{{ $profile->gmail }}" class="text-decoration-none">{{ $profile->gmail }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Incidents -->
            @if($profile->has_family_incidents == 'yes' || $profile->family_notes)
            <div class="col-12">
                <div class="staff-info-card {{ $profile->has_family_incidents == 'yes' ? 'border-danger' : '' }}">
                    <div class="staff-info-card-header {{ $profile->has_family_incidents == 'yes' ? 'bg-danger-subtle' : '' }}">
                        <i class="bi bi-exclamation-triangle-fill {{ $profile->has_family_incidents == 'yes' ? 'text-danger' : '' }}"></i>
                        <h4>{{ __('admin.staff_profiles.show_family_incidents') }}</h4>
                    </div>
                    <div class="staff-info-card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="staff-incident-status">
                                    <div class="staff-info-label mb-2">{{ __('admin.staff_profiles.show_has_incidents') }}</div>
                                    @if($profile->has_family_incidents == 'yes')
                                        <span class="badge bg-danger text-white px-4 py-2 rounded-pill fs-6">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ __('admin.staff_profiles.show_yes') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success text-white px-4 py-2 rounded-pill fs-6">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('admin.staff_profiles.show_no') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($profile->family_notes)
                                <div class="col-md-8">
                                    <div class="staff-info-label mb-2">{{ __('admin.staff_profiles.show_incidents_notes') }}</div>
                                    <div class="staff-notes-box">
                                        {{ $profile->family_notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Readiness and Notes -->
            <div class="col-12">
                <div class="staff-info-card">
                    <div class="staff-info-card-header">
                        <i class="bi bi-clipboard-check-fill"></i>
                        <h4>{{ __('admin.staff_profiles.show_readiness_title') }}</h4>
                    </div>
                    <div class="staff-info-card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="staff-readiness-level">
                                    <div class="staff-info-label mb-2">{{ __('admin.staff_profiles.show_readiness_level') }}</div>
                                    @php
                                        $r = $ready[$profile->readiness] ?? null;
                                    @endphp
                                    @if($r)
                                        <span class="badge bg-{{ $r['color'] ?? 'secondary' }} text-white px-4 py-2 rounded-pill fs-6">
                                            <i class="bi bi-check-circle-fill me-1"></i>{{ $r['label'] ?? $r }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white px-4 py-2 rounded-pill fs-6">
                                            {{ __('admin.staff_profiles.show_not_specified') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($profile->readiness_notes)
                                <div class="col-md-8">
                                    <div class="staff-info-label mb-2">{{ __('admin.staff_profiles.show_readiness_notes') }}</div>
                                    <div class="staff-notes-box">
                                        {{ $profile->readiness_notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/staff-profiles.css') }}">
    @endpush
@endsection
