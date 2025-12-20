{{-- resources/views/admin/staff-profiles/show.blade.php --}}
@extends('layouts.admin')

@section('page-title', __('admin.staff_profiles.show_title'))


@section('content')
    @php
        $loc = config('staff_enums.locations');
        $status = config('staff_enums.status');
        $ready = config('staff_enums.readiness');
        $marital = config('staff_enums.marital_status');
        $house = config('staff_enums.house_status');
        $housing = config('staff_enums.housing_type');
        $relation = config('staff_enums.relation');
    @endphp

    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h5 class="mb-1 text-white"><i class="bi bi-person-vcard-fill me-2"></i>{{ $profile->full_name }}</h5>
                    <div class="d-flex flex-wrap gap-3 text-white-50 mt-2 small">
                        <div><strong>{{ __('admin.staff_profiles.show_national_id') }}</strong> {{ $profile->national_id }}</div>
                        @if($profile->employee_number)
                            <div><strong>{{ __('admin.staff_profiles.show_employee_number') }}</strong> #{{ $profile->employee_number }}</div>
                        @endif
                        <div><strong>{{ __('admin.staff_profiles.show_location') }}</strong> {{ $loc[$profile->location] ?? __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 no-print">
                    <div class="d-flex justify-content-lg-end gap-2">
                        <button onclick="window.print()" class="btn btn-light btn-sm" style="width:40px;height:40px;padding:0;">
                            <i class="bi bi-printer-fill"></i>
                        </button>
                        <a href="{{ route('admin.staff-profiles.edit', $profile) }}" class="btn btn-warning btn-sm" style="width:40px;height:40px;padding:0;">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-outline-light btn-sm" style="width:40px;height:40px;padding:0;">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Readiness and Status -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold small">{{ __('admin.staff_profiles.show_readiness') }}</span>
                        @switch($profile->readiness)
                            @case('working')
                                <span class="badge badge-custom bg-success text-white px-3 py-2 small">{{ __('admin.staff_profiles.readiness_working') }}</span>
                                @break
                            @case('ready')
                                <span class="badge badge-custom bg-info text-white px-3 py-2 small">{{ __('admin.staff_profiles.readiness_ready') }}</span>
                                @break
                            @case('not_ready')
                                <span class="badge badge-custom bg-warning text-dark px-3 py-2 small">{{ __('admin.staff_profiles.readiness_not_ready') }}</span>
                                @break
                            @default
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 small">{{ __('admin.staff_profiles.show_not_specified') }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end gap-2">
                        <span class="fw-bold small">{{ __('admin.staff_profiles.show_status') }}</span>
                        @if($profile->status == 'resident')
                            <span class="badge badge-custom bg-success-subtle text-success px-3 py-2 small">{{ __('admin.staff_profiles.status_resident') }}</span>
                        @elseif($profile->status == 'displaced')
                            <span class="badge badge-custom bg-danger-subtle text-danger px-3 py-2 small">{{ __('admin.staff_profiles.status_displaced') }}</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 small">{{ __('admin.staff_profiles.show_not_specified') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- Basic Data -->
            <div class="col-lg-8">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-person-badge-fill me-2"></i>{{ __('admin.staff_profiles.show_basic_data') }}</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">{{ __('admin.staff_profiles.show_birth_date') }}</div>
                            <div class="info-value">{{ $profile->birth_date?->format('d/m/Y') ?? __('admin.staff_profiles.show_not_specified') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">{{ __('admin.staff_profiles.show_job_title') }}</div>
                            <div class="info-value">{{ $profile->job_title ?? __('admin.staff_profiles.show_not_specified') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">{{ __('admin.staff_profiles.show_department') }}</div>
                            <div class="info-value">
                                {{ $profile->department ?? '' }}
                                @if($profile->directorate) / {{ $profile->directorate }} @endif
                                @if($profile->section) / {{ $profile->section }} @endif
                                @if(!$profile->department && !$profile->directorate && !$profile->section) {{ __('admin.staff_profiles.show_not_specified') }} @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">{{ __('admin.staff_profiles.show_marital_status') }}</div>
                            <div class="info-value">{{ $marital[$profile->marital_status] ?? __('admin.staff_profiles.show_not_specified') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">{{ __('admin.staff_profiles.show_family_members') }}</div>
                            <div class="info-value">{{ $profile->family_members_count ?? __('admin.staff_profiles.show_not_specified') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">{{ __('admin.staff_profiles.show_last_update') }}</div>
                            <div class="info-value">
                                {{ $profile->updated_at?->format('d/m/Y H:i') ?? __('admin.staff_profiles.show_not_updated') }}
                                @if($profile->updated_at && $profile->updated_at->diffInDays($profile->created_at) > 0)
                                    <small class="text-success d-block">({{ $profile->updated_at->diffForHumans() }})</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Members -->
            <div class="col-lg-4">
                <div class="profile-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>{{ __('admin.staff_profiles.show_family_members_title') }}</h4>
                        @if($profile->dependents?->count())
                            <span class="badge bg-primary fs-6">{{ $profile->dependents->count() }} {{ __('admin.staff_profiles.show_family_member') }}</span>
                        @endif
                    </div>
                    @if($profile->dependents?->count())
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-sm family-table">
                                <thead>
                                <tr>
                                    <th>{{ __('admin.staff_profiles.show_family_table_id') }}</th>
                                    <th>{{ __('admin.staff_profiles.show_family_table_name') }}</th>
                                    <th>{{ __('admin.staff_profiles.show_family_table_relation') }}</th>
                                    <th>{{ __('admin.staff_profiles.show_family_table_birth_date') }}</th>
                                    <th>{{ __('admin.staff_profiles.show_family_table_student') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($profile->dependents as $i => $d)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td class="fw-bold">{{ $d->name }}</td>
                                        <td><small>{{ $relation[$d->relation] ?? $d->relation }}</small></td>
                                        <td><small>{{ $d->birth_date ? \Carbon\Carbon::parse($d->birth_date)->format('d/m/Y') : '—' }}</small></td>
                                        <td>
                                            @if($d->is_student)
                                                <span class="badge bg-success-subtle text-success small">{{ __('admin.staff_profiles.show_yes') }}</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary small">{{ __('admin.staff_profiles.show_no') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center my-5">{{ __('admin.staff_profiles.show_no_family_data') }}</p>
                    @endif
                </div>
            </div>

            <!-- Housing and Contact -->
            <div class="col-lg-6">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-house-door-fill me-2"></i>{{ __('admin.staff_profiles.show_housing_data') }}</h4>
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_original_address') }}</div>
                        <div class="info-value">{{ $profile->original_address ?: __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_house_status') }}</div>
                        <div class="info-value">{{ $house[$profile->house_status] ?? __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                    @if($profile->status == 'displaced')
                        <div class="info-item">
                            <div class="info-label text-danger">{{ __('admin.staff_profiles.show_current_address') }}</div>
                            <div class="info-value text-danger fw-bold">{{ $profile->current_address }}</div>
                        </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_housing_type') }}</div>
                        <div class="info-value">{{ $housing[$profile->housing_type] ?? __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-telephone-fill me-2"></i>{{ __('admin.staff_profiles.show_contact_info') }}</h4>
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_mobile') }}</div>
                        <div class="info-value">{{ $profile->mobile ?: __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_mobile_alt') }}</div>
                        <div class="info-value">{{ $profile->mobile_alt ?: __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_whatsapp') }}</div>
                        <div class="info-value">
                            @if($profile->whatsapp)
                                @php
                                    $whats = $profile->whatsapp;
                                    if (str_starts_with($whats, '970')) {
                                        echo '+970 ' . substr($whats, 3);
                                    } elseif (str_starts_with($whats, '972')) {
                                        echo '+972 ' . substr($whats, 3);
                                    } else {
                                        echo $whats;
                                    }
                                @endphp
                            @else
                                {{ __('admin.staff_profiles.show_not_specified') }}
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_telegram') }}</div>
                        <div class="info-value">{{ $profile->telegram ?: __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">{{ __('admin.staff_profiles.show_email') }}</div>
                        <div class="info-value">{{ $profile->gmail ?: __('admin.staff_profiles.show_not_specified') }}</div>
                    </div>
                </div>
            </div>

            <!-- Family Incidents -->
            @if($profile->has_family_incidents == 'yes' || $profile->family_notes)
            <div class="col-12">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('admin.staff_profiles.show_family_incidents') }}</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-label">{{ __('admin.staff_profiles.show_has_incidents') }}</div>
                            <div class="info-value">
                                @if($profile->has_family_incidents == 'yes')
                                    <span class="badge bg-danger text-white">{{ __('admin.staff_profiles.show_yes') }}</span>
                                @else
                                    <span class="badge bg-success text-white">{{ __('admin.staff_profiles.show_no') }}</span>
                                @endif
                            </div>
                        </div>
                        @if($profile->family_notes)
                            <div class="col-md-8">
                                <div class="info-label">{{ __('admin.staff_profiles.show_incidents_notes') }}</div>
                                <div class="info-value p-3 bg-light rounded-3 border">
                                    {{ $profile->family_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Readiness and Notes -->
            <div class="col-12">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-clipboard-check-fill me-2"></i>{{ __('admin.staff_profiles.show_readiness_title') }}</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-label">{{ __('admin.staff_profiles.show_readiness_level') }}</div>
                            <div class="info-value fs-5">
                                @php
                                    $r = $ready[$profile->readiness] ?? null;
                                @endphp
                                @if($r)
                                    <span class="badge badge-custom bg-{{ $r['color'] ?? 'secondary' }} text-white">
                                    {{ $r['label'] ?? $r }}
                                </span>
                                @else
                                    {{ __('admin.staff_profiles.show_not_specified') }}
                                @endif
                            </div>
                        </div>
                        @if($profile->readiness_notes)
                            <div class="col-md-8">
                                <div class="info-label">{{ __('admin.staff_profiles.show_readiness_notes') }}</div>
                                <div class="info-value p-3 bg-light rounded-3 border">
                                    {{ $profile->readiness_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
