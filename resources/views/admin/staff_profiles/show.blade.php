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
    $na      = '—';
@endphp
@extends('layouts.admin')
@section('title', __('admin.staff_profiles.show_title'))

@section('content')
<div class="container-fluid p-0">
    <div class="card border-0 shadow-sm rounded-3">

        {{-- ── Header: Name + Actions ── --}}
        <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge fs-5"></i>
                    <div>
                        <h5 class="mb-0 fw-bold text-white" style="font-size: 1.05rem;">{{ $profile->full_name }}</h5>
                        <small class="opacity-75" style="font-size: 0.78rem;">
                            {{ $profile->national_id }} &bull; #{{ $profile->employee_number }} &bull; {{ $loc[$profile->location] ?? $na }}
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @if($profile->status == 'displaced')
                        <span class="badge bg-danger rounded-pill">{{ $status['displaced'] }}</span>
                    @elseif($profile->status == 'resident')
                        <span class="badge bg-success rounded-pill">{{ $status['resident'] }}</span>
                    @endif
                    @if($profile->readiness)
                        <span class="badge {{ $profile->readiness == 'working' ? 'bg-success' : ($profile->readiness == 'ready' ? 'bg-info' : 'bg-warning text-dark') }} rounded-pill">
                            {{ $ready[$profile->readiness] ?? '' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="px-3 py-2 d-flex gap-2 flex-wrap" style="background: #F8FBFD; border-bottom: 1px solid #E6ECF2;">
            <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-right me-1"></i>{{ __('admin.common.back') }}
            </a>
            <a href="{{ route('admin.staff-profiles.edit', $profile) }}" class="btn btn-sm btn-primary rounded-3">
                <i class="bi bi-pencil-square me-1"></i>{{ __('admin.common.edit') }}
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary rounded-3 no-print">
                <i class="bi bi-printer me-1"></i>{{ __('admin.common.print') }}
            </button>
        </div>

        <div class="card-body p-3">
            <div class="row g-4">

                {{-- ════════ RIGHT COL: Data Tables ════════ --}}
                <div class="col-lg-8">

                    {{-- Basic Data --}}
                    <h6 class="sp-section-title"><i class="bi bi-person-vcard"></i> {{ __('admin.staff_profiles.show_basic_data') }}</h6>
                    <table class="table table-sm sp-table mb-4">
                        <tbody>
                            <tr>
                                <th>{{ __('admin.staff_profiles.show_job_title') }}</th>
                                <td>{{ $profile->job_title ?? $na }}</td>
                                <th>{{ __('admin.staff_profiles.show_birth_date') }}</th>
                                <td>{{ $profile->birth_date?->format('d/m/Y') ?? $na }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.staff_profiles.show_marital_status') }}</th>
                                <td>{{ $marital[$profile->marital_status] ?? $na }}</td>
                                <th>{{ __('admin.staff_profiles.show_family_members') }}</th>
                                <td>{{ $profile->family_members_count ?? $na }}</td>
                            </tr>
                            @if($profile->department || $profile->directorate || $profile->section)
                            <tr>
                                <th>{{ __('admin.staff_profiles.show_department') }}</th>
                                <td colspan="3">{{ collect([$profile->department, $profile->directorate, $profile->section])->filter()->implode(' / ') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- Housing Data --}}
                    <h6 class="sp-section-title"><i class="bi bi-house-door"></i> {{ __('admin.staff_profiles.show_housing_data') }}</h6>
                    <table class="table table-sm sp-table mb-4">
                        <tbody>
                            <tr>
                                <th>{{ __('admin.staff_profiles.show_original_address') }}</th>
                                <td>{{ $profile->original_address ?: $na }}</td>
                                <th>{{ __('admin.staff_profiles.show_house_status') }}</th>
                                <td>
                                    @if($profile->house_status == 'demolished')
                                        <span class="text-danger fw-bold">{{ $house['demolished'] }}</span>
                                    @elseif($profile->house_status == 'partial')
                                        <span class="text-warning fw-bold">{{ $house['partial'] }}</span>
                                    @else
                                        {{ $house[$profile->house_status] ?? $na }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.staff_profiles.show_housing_type') }}</th>
                                <td>{{ $housing[$profile->housing_type] ?? $na }}</td>
                                @if($profile->status == 'displaced' && $profile->current_address)
                                    <th class="text-danger">{{ __('admin.staff_profiles.show_current_address') }}</th>
                                    <td class="text-danger fw-bold">{{ $profile->current_address }}</td>
                                @else
                                    <th></th><td></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>

                    {{-- Family Incidents + Readiness Notes --}}
                    @if($profile->has_family_incidents == 'yes' || $profile->family_notes || $profile->readiness_notes)
                        <h6 class="sp-section-title"><i class="bi bi-exclamation-circle"></i> {{ __('admin.staff_profiles.show_readiness_title') }}</h6>
                        @if($profile->has_family_incidents == 'yes')
                            <div class="alert alert-danger py-2 px-3 d-flex align-items-center gap-2 mb-2" style="font-size: 0.88rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>{{ __('admin.staff_profiles.show_has_incidents') }}: <strong>{{ __('admin.staff_profiles.show_yes') }}</strong></span>
                            </div>
                        @endif
                        @if($profile->family_notes)
                            <div class="sp-note mb-2">
                                <strong class="text-muted" style="font-size: 0.78rem;">{{ __('admin.staff_profiles.show_incidents_notes') }}:</strong>
                                <div>{{ $profile->family_notes }}</div>
                            </div>
                        @endif
                        @if($profile->readiness_notes)
                            <div class="sp-note mb-4">
                                <strong class="text-muted" style="font-size: 0.78rem;">{{ __('admin.staff_profiles.show_readiness_notes') }}:</strong>
                                <div>{{ $profile->readiness_notes }}</div>
                            </div>
                        @endif
                    @endif

                    {{-- Last Update --}}
                    <div class="text-muted text-end" style="font-size: 0.78rem;">
                        <i class="bi bi-clock-history me-1"></i>
                        {{ __('admin.staff_profiles.show_last_update') }}: {{ $profile->updated_at?->format('d/m/Y H:i') ?? $na }}
                        @if($profile->updated_at)
                            <span class="text-success">({{ $profile->updated_at->diffForHumans() }})</span>
                        @endif
                    </div>
                </div>

                {{-- ════════ LEFT COL: Contact + Family ════════ --}}
                <div class="col-lg-4">

                    {{-- Contact --}}
                    <h6 class="sp-section-title"><i class="bi bi-telephone"></i> {{ __('admin.staff_profiles.show_contact_info') }}</h6>
                    <table class="table table-sm sp-table mb-4">
                        <tbody>
                            @if($profile->mobile)
                            <tr>
                                <th><i class="bi bi-phone-fill text-success me-1"></i>{{ __('admin.staff_profiles.show_mobile') }}</th>
                                <td><a href="tel:{{ $profile->mobile }}">{{ $profile->mobile }}</a></td>
                            </tr>
                            @endif
                            @if($profile->mobile_alt)
                            <tr>
                                <th><i class="bi bi-phone text-success me-1"></i>{{ __('admin.staff_profiles.show_mobile_alt') }}</th>
                                <td><a href="tel:{{ $profile->mobile_alt }}">{{ $profile->mobile_alt }}</a></td>
                            </tr>
                            @endif
                            @if($profile->whatsapp)
                            <tr>
                                <th><i class="bi bi-whatsapp text-success me-1"></i>{{ __('admin.staff_profiles.show_whatsapp') }}</th>
                                @php
                                    $wa = $profile->whatsapp;
                                    $waF = str_starts_with($wa,'970') ? '+970 '.substr($wa,3) : (str_starts_with($wa,'972') ? '+972 '.substr($wa,3) : $wa);
                                @endphp
                                <td><a href="https://wa.me/{{ $wa }}" target="_blank">{{ $waF }}</a></td>
                            </tr>
                            @endif
                            @if($profile->telegram)
                            <tr>
                                <th><i class="bi bi-telegram text-info me-1"></i>{{ __('admin.staff_profiles.show_telegram') }}</th>
                                <td><a href="https://t.me/{{ $profile->telegram }}" target="_blank">{{ $profile->telegram }}</a></td>
                            </tr>
                            @endif
                            @if($profile->gmail)
                            <tr>
                                <th><i class="bi bi-envelope-fill text-danger me-1"></i>{{ __('admin.staff_profiles.show_email') }}</th>
                                <td><a href="mailto:{{ $profile->gmail }}">{{ $profile->gmail }}</a></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- Family Members --}}
                    <h6 class="sp-section-title">
                        <i class="bi bi-people"></i> {{ __('admin.staff_profiles.show_family_members_title') }}
                        @if($profile->dependents?->count())
                            <span class="badge bg-primary rounded-pill ms-1" style="font-size: 0.7rem;">{{ $profile->dependents->count() }}</span>
                        @endif
                    </h6>
                    @if($profile->dependents?->count())
                        <div class="sp-sidebar-card sp-family-scroll">
                            @foreach($profile->dependents as $i => $d)
                                <div class="sp-family-item">
                                    <span class="sp-family-num">{{ $i + 1 }}</span>
                                    <div class="flex-fill" style="min-width: 0;">
                                        <div class="fw-bold text-truncate" style="font-size: 0.85rem; color: #24364A;">{{ $d->name }}</div>
                                        <div style="font-size: 0.73rem; color: #6B7C93;">
                                            {{ $relation[$d->relation] ?? $d->relation }}
                                            &bull; {{ $d->birth_date ? \Carbon\Carbon::parse($d->birth_date)->format('d/m/Y') : '—' }}
                                            @if($d->is_student) &bull; <span class="text-success">{{ __('admin.staff_profiles.show_yes') }}</span> @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3" style="font-size: 0.85rem;">{{ __('admin.staff_profiles.show_no_family_data') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/staff-profiles.css') }}?v=5">
@endpush
