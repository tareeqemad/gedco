{{-- resources/views/admin/staff-profiles/show.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'بيانات الموظف')

@push('styles')
    <style>
        :root {
            --primary: #e67e22;
            --primary-dark: #d35400;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
            --light: #fdf2e9;
            --border: #e2e8f0;
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .profile-card {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            border-color: var(--primary);
        }

        .profile-card:hover::before {
            opacity: 1;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--light);
            display: inline-block;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            padding: 1rem 0;
            border-bottom: 1px dashed #eee;
            transition: all 0.2s;
        }

        .info-item:hover {
            background: linear-gradient(90deg, transparent, var(--light), transparent);
            padding-right: 0.5rem;
        }

        .info-item:last-child { border-bottom: none; }

        .info-label {
            font-weight: 600;
            color: #5f6368;
            font-size: 0.95rem;
            margin-bottom: 0.4rem;
        }

        .info-value {
            font-size: 1.05rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .badge-custom {
            padding: 0.5rem 1.2rem;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .badge-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .family-table th {
            background: var(--light);
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .btn-floating {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-floating::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .btn-floating:hover {
            transform: translateY(-2px) scale(1.1);
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        .btn-floating:hover::before {
            width: 100%;
            height: 100%;
        }

        @media print {
            body { background: white !important; }
            .no-print, .app-sidebar, .app-header { display: none !important; }
            .profile-card { box-shadow: none !important; border: 1px solid #ddd !important; page-break-inside: avoid; }
            .container-fluid { padding: 1rem !important; }
        }
    </style>
@endpush

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
                        <div><strong>رقم الهوية:</strong> {{ $profile->national_id }}</div>
                        @if($profile->employee_number)
                            <div><strong>الرقم الوظيفي:</strong> #{{ $profile->employee_number }}</div>
                        @endif
                        <div><strong>المقر:</strong> {{ $loc[$profile->location] ?? 'غير محدد' }}</div>
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

            <!-- حالة الجاهزية والحالة الاجتماعية في الأعلى -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold small">الجاهزية:</span>
                        @switch($profile->readiness)
                            @case('working')
                                <span class="badge badge-custom bg-success text-white px-3 py-2 small">باشر العمل</span>
                                @break
                            @case('ready')
                                <span class="badge badge-custom bg-info text-white px-3 py-2 small">جاهز للعودة</span>
                                @break
                            @case('not_ready')
                                <span class="badge badge-custom bg-warning text-dark px-3 py-2 small">غير جاهز</span>
                                @break
                            @default
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 small">غير محدد</span>
                        @endswitch
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end gap-2">
                        <span class="fw-bold small">الحالة:</span>
                        @if($profile->status == 'resident')
                            <span class="badge badge-custom bg-success-subtle text-success px-3 py-2 small">مقيم</span>
                        @elseif($profile->status == 'displaced')
                            <span class="badge badge-custom bg-danger-subtle text-danger px-3 py-2 small">نازح</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 small">غير محدد</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- البيانات الأساسية -->
            <div class="col-lg-8">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-person-badge-fill me-2"></i>البيانات الأساسية</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">تاريخ الميلاد</div>
                            <div class="info-value">{{ $profile->birth_date?->format('d/m/Y') ?? 'غير محدد' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">الوظيفة الحالية</div>
                            <div class="info-value">{{ $profile->job_title ?? 'غير محدد' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">الإدارة / الدائرة / القسم</div>
                            <div class="info-value">
                                {{ $profile->department ?? '' }}
                                @if($profile->directorate) / {{ $profile->directorate }} @endif
                                @if($profile->section) / {{ $profile->section }} @endif
                                @if(!$profile->department && !$profile->directorate && !$profile->section) غير محدد @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">الحالة الاجتماعية</div>
                            <div class="info-value">{{ $marital[$profile->marital_status] ?? 'غير محدد' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">عدد أفراد الأسرة</div>
                            <div class="info-value">{{ $profile->family_members_count ?? 'غير محدد' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">تاريخ آخر تعديل</div>
                            <div class="info-value">
                                {{ $profile->updated_at?->format('d/m/Y H:i') ?? 'لم يُحدث' }}
                                @if($profile->updated_at && $profile->updated_at->diffInDays($profile->created_at) > 0)
                                    <small class="text-success d-block">({{ $profile->updated_at->diffForHumans() }})</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أفراد الأسرة -->
            <div class="col-lg-4">
                <div class="profile-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>أفراد الأسرة</h4>
                        @if($profile->dependents?->count())
                            <span class="badge bg-primary fs-6">{{ $profile->dependents->count() }} فرد</span>
                        @endif
                    </div>
                    @if($profile->dependents?->count())
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-sm family-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>القرابة</th>
                                    <th>تاريخ الميلاد</th>
                                    <th>طالب جامعي</th>
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
                                                <span class="badge bg-success-subtle text-success small">نعم</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary small">لا</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center my-5">لا توجد بيانات أفراد أسرة</p>
                    @endif
                </div>
            </div>

            <!-- السكن والتواصل -->
            <div class="col-lg-6">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-house-door-fill me-2"></i>بيانات السكن</h4>
                    <div class="info-item">
                        <div class="info-label">العنوان الأصلي</div>
                        <div class="info-value">{{ $profile->original_address ?: 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">وضع المنزل حاليًا</div>
                        <div class="info-value">{{ $house[$profile->house_status] ?? 'غير محدد' }}</div>
                    </div>
                    @if($profile->status == 'displaced')
                        <div class="info-item">
                            <div class="info-label text-danger">العنوان الحالي (بعد النزوح)</div>
                            <div class="info-value text-danger fw-bold">{{ $profile->current_address }}</div>
                        </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">نوع السكن الحالي</div>
                        <div class="info-value">{{ $housing[$profile->housing_type] ?? 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-telephone-fill me-2"></i>وسائل التواصل</h4>
                    <div class="info-item">
                        <div class="info-label">رقم الجوال</div>
                        <div class="info-value">{{ $profile->mobile ?: 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">جوال بديل</div>
                        <div class="info-value">{{ $profile->mobile_alt ?: 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">واتس آب</div>
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
                                غير محدد
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">تيليجرام</div>
                        <div class="info-value">{{ $profile->telegram ?: 'غير محدد' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">البريد الإلكتروني</div>
                        <div class="info-value">{{ $profile->gmail ?: 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <!-- الحوادث العائلية -->
            @if($profile->has_family_incidents == 'yes' || $profile->family_notes)
            <div class="col-12">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>الحوادث العائلية</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-label">هل يوجد حوادث عائلية؟</div>
                            <div class="info-value">
                                @if($profile->has_family_incidents == 'yes')
                                    <span class="badge bg-danger text-white">نعم</span>
                                @else
                                    <span class="badge bg-success text-white">لا</span>
                                @endif
                            </div>
                        </div>
                        @if($profile->family_notes)
                            <div class="col-md-8">
                                <div class="info-label">ملاحظات الحوادث العائلية</div>
                                <div class="info-value p-3 bg-light rounded-3 border">
                                    {{ $profile->family_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- الجاهزية والملاحظات -->
            <div class="col-12">
                <div class="profile-card p-4">
                    <h4 class="card-title"><i class="bi bi-clipboard-check-fill me-2"></i>الجاهزية للعودة للعمل</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-label">مستوى الجاهزية</div>
                            <div class="info-value fs-5">
                                @php
                                    $r = $ready[$profile->readiness] ?? null;
                                @endphp
                                @if($r)
                                    <span class="badge badge-custom bg-{{ $r['color'] ?? 'secondary' }} text-white">
                                    {{ $r['label'] ?? $r }}
                                </span>
                                @else
                                    غير محدد
                                @endif
                            </div>
                        </div>
                        @if($profile->readiness_notes)
                            <div class="col-md-8">
                                <div class="info-label">ملاحظات الجاهزية</div>
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
