@php
    $breadcrumbTitle     = __('admin.staff_profiles.show_title') . ' - ' . $profile->full_name;
    $breadcrumbParent    = __('admin.staff_profiles.title');
    $breadcrumbParentUrl = route('admin.staff-profiles.index');

    $loc     = config('staff_enums.locations');
    $status  = config('staff_enums.status');
    $ready   = config('staff_enums.readiness');
    $marital = config('staff_enums.marital_status');
    $house   = config('staff_enums.house_status');
    $housing = config('staff_enums.housing_type');
    $relation = config('staff_enums.relation');
    $na      = __('admin.staff_profiles.show_not_specified');
@endphp
@extends('layouts.admin')
@section('title', __('admin.staff_profiles.show_title'))

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-3">

            {{-- ============ LEFT: Main Info ============ --}}
            <div class="col-12 col-lg-8">

                {{-- Profile Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="sp-hero">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="sp-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="flex-fill">
                                <h4 class="mb-1 fw-bold text-white" style="font-size: 1.2rem;">{{ $profile->full_name }}</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="sp-chip"><i class="bi bi-card-text"></i> {{ $profile->national_id }}</span>
                                    <span class="sp-chip"><i class="bi bi-hash"></i> {{ $profile->employee_number }}</span>
                                    <span class="sp-chip"><i class="bi bi-geo-alt"></i> {{ $loc[$profile->location] ?? $na }}</span>
                                    @if($profile->job_title)
                                        <span class="sp-chip"><i class="bi bi-briefcase"></i> {{ $profile->job_title }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1 align-items-end">
                                @if($profile->status == 'resident')
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-house-check-fill me-1"></i>{{ $status['resident'] }}</span>
                                @elseif($profile->status == 'displaced')
                                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $status['displaced'] }}</span>
                                @endif
                                @switch($profile->readiness)
                                    @case('working')
                                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i>{{ $ready['working'] }}</span>
                                        @break
                                    @case('ready')
                                        <span class="badge bg-primary rounded-pill px-3 py-2"><i class="bi bi-clock-fill me-1"></i>{{ $ready['ready'] }}</span>
                                        @break
                                    @case('not_ready')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-dash-circle-fill me-1"></i>{{ $ready['not_ready'] }}</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card-body py-2 px-3 d-flex gap-2 flex-wrap" style="background: #F8FBFD; border-bottom: 1px solid #E6ECF2;">
                        <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-cancel btn-sm">
                            <i class="bi bi-arrow-right me-1"></i>{{ __('admin.common.back') }}
                        </a>
                        <a href="{{ route('admin.staff-profiles.edit', $profile) }}" class="btn btn-save btn-sm">
                            <i class="bi bi-pencil-square me-1"></i>{{ __('admin.common.edit') }}
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-3 no-print">
                            <i class="bi bi-printer me-1"></i>{{ __('admin.common.print') }}
                        </button>
                    </div>
                </div>

                {{-- Basic Data --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="sp-card-header">
                        <i class="bi bi-person-badge"></i>
                        {{ __('admin.staff_profiles.show_basic_data') }}
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-sm-6 col-md-4">
                                <div class="sp-field">
                                    <div class="sp-field-label">{{ __('admin.staff_profiles.show_birth_date') }}</div>
                                    <div class="sp-field-value">{{ $profile->birth_date?->format('d/m/Y') ?? $na }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="sp-field">
                                    <div class="sp-field-label">{{ __('admin.staff_profiles.show_marital_status') }}</div>
                                    <div class="sp-field-value">{{ $marital[$profile->marital_status] ?? $na }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="sp-field">
                                    <div class="sp-field-label">{{ __('admin.staff_profiles.show_family_members') }}</div>
                                    <div class="sp-field-value">{{ $profile->family_members_count ?? $na }}</div>
                                </div>
                            </div>
                            @if($profile->department || $profile->directorate || $profile->section)
                                <div class="col-12">
                                    <div class="sp-field">
                                        <div class="sp-field-label">{{ __('admin.staff_profiles.show_department') }}</div>
                                        <div class="sp-field-value">
                                            {{ collect([$profile->department, $profile->directorate, $profile->section])->filter()->implode(' / ') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Housing Data --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="sp-card-header">
                        <i class="bi bi-house-door"></i>
                        {{ __('admin.staff_profiles.show_housing_data') }}
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="sp-field">
                                    <div class="sp-field-label">{{ __('admin.staff_profiles.show_original_address') }}</div>
                                    <div class="sp-field-value">{{ $profile->original_address ?: $na }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="sp-field">
                                    <div class="sp-field-label">{{ __('admin.staff_profiles.show_house_status') }}</div>
                                    <div class="sp-field-value">
                                        @if($profile->house_status == 'demolished')
                                            <span class="text-danger fw-bold">{{ $house['demolished'] }}</span>
                                        @elseif($profile->house_status == 'partial')
                                            <span class="text-warning fw-bold">{{ $house['partial'] }}</span>
                                        @else
                                            {{ $house[$profile->house_status] ?? $na }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="sp-field">
                                    <div class="sp-field-label">{{ __('admin.staff_profiles.show_housing_type') }}</div>
                                    <div class="sp-field-value">{{ $housing[$profile->housing_type] ?? $na }}</div>
                                </div>
                            </div>
                            @if($profile->status == 'displaced' && $profile->current_address)
                                <div class="col-sm-6">
                                    <div class="sp-field" style="border-color: #f8d7da; background: #FFF5F5;">
                                        <div class="sp-field-label text-danger">{{ __('admin.staff_profiles.show_current_address') }}</div>
                                        <div class="sp-field-value text-danger fw-bold">{{ $profile->current_address }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Family Incidents + Readiness Notes --}}
                @if($profile->has_family_incidents == 'yes' || $profile->family_notes || $profile->readiness_notes)
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="sp-card-header">
                            <i class="bi bi-clipboard-check"></i>
                            {{ __('admin.staff_profiles.show_readiness_title') }} / {{ __('admin.staff_profiles.show_family_incidents') }}
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                @if($profile->has_family_incidents == 'yes')
                                    <div class="col-12">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ __('admin.staff_profiles.show_has_incidents') }}: {{ __('admin.staff_profiles.show_yes') }}
                                            </span>
                                        </div>
                                        @if($profile->family_notes)
                                            <div class="sp-notes">{{ $profile->family_notes }}</div>
                                        @endif
                                    </div>
                                @endif
                                @if($profile->readiness_notes)
                                    <div class="col-12">
                                        <div class="sp-field-label mb-1">{{ __('admin.staff_profiles.show_readiness_notes') }}</div>
                                        <div class="sp-notes">{{ $profile->readiness_notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ============ RIGHT: Sidebar ============ --}}
            <div class="col-12 col-lg-4">

                {{-- Contact Info --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="sp-card-header">
                        <i class="bi bi-telephone"></i>
                        {{ __('admin.staff_profiles.show_contact_info') }}
                    </div>
                    <div class="card-body p-0">
                        <div class="sp-contact-list">
                            {{-- Mobile --}}
                            <div class="sp-contact-row">
                                <div class="sp-contact-icon bg-success-subtle"><i class="bi bi-phone-fill text-success"></i></div>
                                <div>
                                    <div class="sp-field-label">{{ __('admin.staff_profiles.show_mobile') }}</div>
                                    @if($profile->mobile)
                                        <a href="tel:{{ $profile->mobile }}" class="sp-contact-link">{{ $profile->mobile }}</a>
                                    @else
                                        <span class="text-muted">{{ $na }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- Alt Mobile --}}
                            @if($profile->mobile_alt)
                                <div class="sp-contact-row">
                                    <div class="sp-contact-icon bg-success-subtle"><i class="bi bi-phone text-success"></i></div>
                                    <div>
                                        <div class="sp-field-label">{{ __('admin.staff_profiles.show_mobile_alt') }}</div>
                                        <a href="tel:{{ $profile->mobile_alt }}" class="sp-contact-link">{{ $profile->mobile_alt }}</a>
                                    </div>
                                </div>
                            @endif
                            {{-- WhatsApp --}}
                            @if($profile->whatsapp)
                                <div class="sp-contact-row">
                                    <div class="sp-contact-icon bg-success-subtle"><i class="bi bi-whatsapp text-success"></i></div>
                                    <div>
                                        <div class="sp-field-label">{{ __('admin.staff_profiles.show_whatsapp') }}</div>
                                        @php
                                            $wa = $profile->whatsapp;
                                            $waFormatted = str_starts_with($wa, '970') ? ('+970 ' . substr($wa, 3)) : (str_starts_with($wa, '972') ? ('+972 ' . substr($wa, 3)) : $wa);
                                        @endphp
                                        <a href="https://wa.me/{{ $wa }}" target="_blank" class="sp-contact-link">{{ $waFormatted }}</a>
                                    </div>
                                </div>
                            @endif
                            {{-- Telegram --}}
                            @if($profile->telegram)
                                <div class="sp-contact-row">
                                    <div class="sp-contact-icon bg-info-subtle"><i class="bi bi-telegram text-info"></i></div>
                                    <div>
                                        <div class="sp-field-label">{{ __('admin.staff_profiles.show_telegram') }}</div>
                                        <a href="https://t.me/{{ $profile->telegram }}" target="_blank" class="sp-contact-link">{{ $profile->telegram }}</a>
                                    </div>
                                </div>
                            @endif
                            {{-- Email --}}
                            @if($profile->gmail)
                                <div class="sp-contact-row">
                                    <div class="sp-contact-icon bg-danger-subtle"><i class="bi bi-envelope-fill text-danger"></i></div>
                                    <div>
                                        <div class="sp-field-label">{{ __('admin.staff_profiles.show_email') }}</div>
                                        <a href="mailto:{{ $profile->gmail }}" class="sp-contact-link">{{ $profile->gmail }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Family Members --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="sp-card-header">
                        <i class="bi bi-people"></i>
                        {{ __('admin.staff_profiles.show_family_members_title') }}
                        @if($profile->dependents?->count())
                            <span class="sp-header-badge">{{ $profile->dependents->count() }}</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if($profile->dependents?->count())
                            <div class="sp-family-scroll">
                                @foreach($profile->dependents as $i => $d)
                                    <div class="sp-family-row">
                                        <div class="sp-family-num">{{ $i + 1 }}</div>
                                        <div class="flex-fill">
                                            <div class="fw-bold" style="font-size: 0.88rem; color: #24364A;">{{ $d->name }}</div>
                                            <div class="d-flex flex-wrap gap-2 mt-1" style="font-size: 0.75rem; color: #6B7C93;">
                                                <span><i class="bi bi-person-badge me-1"></i>{{ $relation[$d->relation] ?? $d->relation }}</span>
                                                <span><i class="bi bi-calendar me-1"></i>{{ $d->birth_date ? \Carbon\Carbon::parse($d->birth_date)->format('d/m/Y') : '—' }}</span>
                                                @if($d->is_student)
                                                    <span class="text-success"><i class="bi bi-mortarboard-fill me-1"></i>{{ __('admin.staff_profiles.show_yes') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-people d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                <small>{{ __('admin.staff_profiles.show_no_family_data') }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Last Update --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-clock-history me-1"></i>
                            {{ __('admin.staff_profiles.show_last_update') }}:
                            {{ $profile->updated_at?->format('d/m/Y H:i') ?? __('admin.staff_profiles.show_not_updated') }}
                            @if($profile->updated_at)
                                <span class="d-block text-success mt-1">({{ $profile->updated_at->diffForHumans() }})</span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/staff-profiles.css') }}">
    @endpush
@endsection
