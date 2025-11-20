@extends('layouts.admin')

@section('page-title', 'عرض بيانات الموظف')

@push('styles')
    <style>
        :root {
            --surface:      #fff7f2;
            --surface-alt:  #fff1e6;
            --border:       #f1b08d;
            --accent:       #ef7c4c;
            --accent-dark:  #c65a28;
            --text:         #2f2b28;
            --muted:        #8c6f61;
        }

        .profile-shell {
            background: #fff;
            border-radius: 18px;
            border: 1px solid rgba(239,124,76,.18);
            box-shadow: 0 14px 35px rgba(15,23,42,.08);
            padding: 1.75rem;
        }

        .profile-header-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(239,124,76,.08);
            color: var(--accent-dark);
            padding: .3rem .9rem;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
        }

        .profile-section {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px dashed rgba(148,163,184,.5);
        }

        .profile-section-title {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(239,124,76,.1);
            color: var(--accent-dark);
            padding: .35rem 1.1rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: .9rem;
            margin-bottom: 1.1rem;
        }

        .profile-grid-3 {
            display: grid;
            gap: 1rem 1.25rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .profile-grid-2 {
            display: grid;
            gap: 1rem 1.25rem;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .profile-card {
            border-radius: 14px;
            border: 1px solid rgba(226,232,240,.9);
            background: linear-gradient(135deg,#ffffff,#fffaf7);
            padding: .9rem 1rem;
        }

        .profile-card-label {
            color: var(--muted);
            font-size: .8rem;
            font-weight: 700;
            margin-bottom: .25rem;
        }

        .profile-card-value {
            font-weight: 700;
            color: #111827;
            font-size: .95rem;
            word-break: break-word;
        }

        .profile-card-muted {
            color: #9ca3af;
            font-weight: 500;
        }

        .profile-meta {
            font-size: .8rem;
            color: #6b7280;
        }

        /* جدول الأسرة */
        .family-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(226,232,240,.9);
        }

        .family-table thead th {
            background: var(--surface-alt);
            padding: .7rem .75rem;
            font-size: .8rem;
            font-weight: 700;
            color: var(--accent-dark);
            border-bottom: 1px solid rgba(226,232,240,1);
            white-space: nowrap;
        }

        .family-table tbody td {
            padding: .55rem .75rem;
            border-top: 1px solid rgba(226,232,240,.9);
            font-size: .86rem;
        }

        .family-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .badge-soft {
            border-radius: 999px;
            padding: .15rem .55rem;
            font-size: .75rem;
            font-weight: 600;
        }

        .badge-soft-success {
            background: #e7f7ec;
            color: #166534;
        }

        .badge-soft-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-soft-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-soft-info {
            background: #e0f2fe;
            color: #075985;
        }

        /* زر الطباعة */
        .btn-print {
            background: linear-gradient(135deg,#ef7c4c,#f59e0b);
            border: none;
            color: #fff;
        }

        .btn-print:hover {
            opacity: .95;
            box-shadow: 0 10px 24px rgba(239,124,76,.4);
        }

        /* تنسيق التنبيهات داخل الـshell */
        .profile-shell .alert {
            border-radius: 12px;
            padding: .75rem .9rem;
            margin-bottom: 1rem;
        }

        /* ريسبونسيف للموبايل */
        @media (max-width: 768px) {
            .profile-shell {
                padding: 1.25rem;
            }
        }

        /* طباعة */
        @media print {
            body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .app-header,
            .main-header,
            .app-sidebar,
            .main-sidebar,
            .footer,
            .page-header,
            .breadcrumb,
            .d-print-none {
                display: none !important;
            }

            .main-content,
            .content,
            .container-fluid {
                margin: 0 !important;
                padding: .5rem 1rem !important;
                max-width: 100% !important;
            }

            .profile-shell {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
                padding: 1rem !important;
            }

            .profile-section {
                page-break-inside: avoid;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $locations = config('staff_enums.locations', [
            '1'=>'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال',
            '4'=>'مقر الوسطى','6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة'
        ]);

        $maritalStatus = config('staff_enums.marital_status', [
            'single'=>'أعزب/عزباء','married'=>'متزوج/متزوجة',
            'widowed'=>'أرمل/أرملة','divorced'=>'مطلق/مطلقة'
        ]);

        $houseStatus = config('staff_enums.house_status', [
            'intact'=>'سليم','partial'=>'هدم جزئي','demolished'=>'هدم كلي'
        ]);

        $residentStatus = config('staff_enums.status', [
            'resident'=>'مقيم','displaced'=>'نازح'
        ]);

        $housingTypes = config('staff_enums.housing_type', [
            'house'=>'منزل','apartment'=>'شقة','tent'=>'خيمة','other'=>'أخرى'
        ]);

        $readinessList = config('staff_enums.readiness', [
            'working'=>'باشر العمل فعلياًً','ready'=>'جاهز للعودة','not_ready'=>'مش جاهز بعد'
        ]);

        $relations = config('staff_enums.relation', [
            'self'    => 'الموظف نفسه',
            'husband' => 'زوج',
            'wife'    => 'زوجة',
            'spouse'  => 'زوج / زوجة',
            'son'     => 'ابن',
            'daughter'=> 'ابنة',
            'other'   => 'أخرى',
        ]);
    @endphp

    <div class="container-fluid">

        {{-- هيدر أعلى الصفحة + أزرار (لا تُطبع) --}}
        <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
            <div>
                <h4 class="mb-1">بيانات الموظف</h4>
                <div class="profile-meta">
                    {{ $profile->full_name }}
                    <span class="mx-2">•</span>
                    رقم الهوية: <strong>{{ $profile->national_id }}</strong>
                    @if($profile->employee_number)
                        <span class="mx-2">•</span>
                        رقم وظيفي: <strong>{{ $profile->employee_number }}</strong>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="button" onclick="window.print()" class="btn btn-sm btn-print">
                    <i class="bi bi-printer"></i> طباعة
                </button>

                <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-right"></i> رجوع للقائمة
                </a>
            </div>
        </div>

        <div class="profile-shell">

            {{-- شريحة معلومات سريعة أعلى --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div class="profile-header-chip">
                    <i class="bi bi-person-vcard"></i>
                    <span>الموظف: {{ $profile->full_name }}</span>
                </div>

                <div class="d-flex gap-2 flex-wrap profile-meta">
                    <span>أنشئ: {{ optional($profile->created_at)->format('Y-m-d H:i') }}</span>
                    @if($profile->updated_at && $profile->updated_at != $profile->created_at)
                        <span class="text-muted">| آخر تحديث: {{ $profile->updated_at->format('Y-m-d H:i') }}</span>
                    @endif
                </div>
            </div>

            {{-- Alerts داخل صفحة العرض للأدمن لو حاب تستخدمها من الكنترولر --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            {{-- القسم 1: البيانات الأساسية --}}
            <div class="profile-section">
                <div class="profile-section-title">
                    <i class="bi bi-person-badge"></i>
                    <span>البيانات الأساسية</span>
                </div>

                <div class="profile-grid-3">
                    <div class="profile-card">
                        <div class="profile-card-label">الاسم الرباعي</div>
                        <div class="profile-card-value">{{ $profile->full_name }}</div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">رقم الهوية</div>
                        <div class="profile-card-value">{{ $profile->national_id }}</div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">الرقم الوظيفي</div>
                        <div class="profile-card-value">
                            {{ $profile->employee_number ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">تاريخ الميلاد</div>
                        <div class="profile-card-value">
                            {{ optional($profile->birth_date)->format('Y-m-d') ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">المسمّى الوظيفي</div>
                        <div class="profile-card-value">
                            {{ $profile->job_title ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">المقر</div>
                        <div class="profile-card-value">
                            {{ $locations[$profile->location] ?? $profile->location ?? '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">الإدارة</div>
                        <div class="profile-card-value">
                            {{ $profile->department ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">الدائرة</div>
                        <div class="profile-card-value">
                            {{ $profile->directorate ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">القسم</div>
                        <div class="profile-card-value">
                            {{ $profile->section ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">الحالة الاجتماعية</div>
                        <div class="profile-card-value">
                            {{ $maritalStatus[$profile->marital_status] ?? '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">عدد أفراد الأسرة</div>
                        <div class="profile-card-value">
                            {{ $profile->family_members_count ?: '—' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- القسم 2: أفراد الأسرة --}}
            <div class="profile-section">
                <div class="profile-section-title">
                    <i class="bi bi-people"></i>
                    <span>أفراد الأسرة</span>
                </div>

                <div class="table-responsive">
                    <table class="family-table">
                        <thead>
                        <tr>
                            <th style="width:60px;">م.</th>
                            <th>الاسم</th>
                            <th>صلة القرابة</th>
                            <th>تاريخ الميلاد</th>
                            <th>طالب جامعي</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $dependents = $profile->dependents ?? collect();
                        @endphp

                        @forelse($dependents as $i => $d)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $d->name }}</td>
                                <td>
                                    {{ $relations[$d->relation] ?? $d->relation }}
                                </td>
                                <td>{{ optional($d->birth_date)->format('Y-m-d') ?: '—' }}</td>
                                <td>
                                    @if($d->is_student)
                                        <span class="badge-soft badge-soft-success">نعم</span>
                                    @else
                                        <span class="badge-soft badge-soft-danger">لا</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    لا يوجد بيانات أفراد أسرة مسجلة.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($profile->has_family_incidents === 'yes')
                    <div class="alert alert-warning mt-3 mb-0">
                        <strong>ملاحظات حول الإصابات / الاعتقالات:</strong>
                        <div class="mt-1">{{ $profile->family_notes }}</div>
                    </div>
                @endif
            </div>

            {{-- القسم 3: السكن والوضع الاجتماعي --}}
            <div class="profile-section">
                <div class="profile-section-title">
                    <i class="bi bi-house-door"></i>
                    <span>بيانات السكن والوضع الاجتماعي</span>
                </div>

                <div class="profile-grid-3">
                    <div class="profile-card">
                        <div class="profile-card-label">عنوان السكن الأصلي</div>
                        <div class="profile-card-value">
                            {{ $profile->original_address ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">وضع المنزل حاليًا</div>
                        <div class="profile-card-value">
                            {{ $houseStatus[$profile->house_status] ?? '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">الحالة (مقيم / نازح)</div>
                        <div class="profile-card-value">
                            {{ $residentStatus[$profile->status] ?? '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">العنوان الحالي بعد النزوح</div>
                        <div class="profile-card-value">
                            {{ $profile->current_address ?: '—' }}
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">نوع السكن</div>
                        <div class="profile-card-value">
                            {{ $housingTypes[$profile->housing_type] ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- القسم 4: وسائل التواصل --}}
            <div class="profile-section">
                <div class="profile-section-title">
                    <i class="bi bi-telephone"></i>
                    <span>وسائل التواصل</span>
                </div>

                <div class="profile-grid-3">
                    <div class="profile-card">
                        <div class="profile-card-label">رقم الجوال</div>
                        <div class="profile-card-value">{{ $profile->mobile ?: '—' }}</div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">جوال بديل</div>
                        <div class="profile-card-value">{{ $profile->mobile_alt ?: '—' }}</div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">واتس آب</div>
                        <div class="profile-card-value">{{ $profile->whatsapp ?: '—' }}</div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">تيليجرام</div>
                        <div class="profile-card-value">{{ $profile->telegram ?: '—' }}</div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">Gmail</div>
                        <div class="profile-card-value">{{ $profile->gmail ?: '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- القسم 5: الجاهزية --}}
            <div class="profile-section">
                <div class="profile-section-title">
                    <i class="bi bi-clipboard-check"></i>
                    <span>الجاهزية للعودة للعمل</span>
                </div>

                <div class="profile-grid-2">
                    <div class="profile-card">
                        <div class="profile-card-label">مستوى الجاهزية</div>
                        <div class="profile-card-value">
                            @php
                                $readText = $readinessList[$profile->readiness] ?? null;
                            @endphp

                            @if($profile->readiness === 'working')
                                <span class="badge-soft badge-soft-success">{{ $readText }}</span>
                            @elseif($profile->readiness === 'ready')
                                <span class="badge-soft badge-soft-info">{{ $readText }}</span>
                            @elseif($profile->readiness === 'not_ready')
                                <span class="badge-soft badge-soft-warning">{{ $readText ?? 'غير جاهز بعد' }}</span>
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="profile-card-label">ملاحظات الجاهزية</div>
                        <div class="profile-card-value">
                            {{ $profile->readiness_notes ?: '—' }}
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- /profile-shell --}}
    </div>
@endsection
