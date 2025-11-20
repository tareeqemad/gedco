@extends('layouts.admin')

@section('title', 'استمارات بيانات الموظفين')

@section('content')
    <div class="container-fluid">

        @php
            $locations = [
                '1' => 'المقر الرئيسي',
                '2' => 'مقر غزة',
                '3' => 'مقر الشمال',
                '4' => 'مقر الوسطى',
                '6' => 'مقر خانيونس',
                '7' => 'مقر رفح',
                '8' => 'مقر الصيانة - غزة',
            ];

            $statusMap = [
                'resident'  => ['label' => 'مقيم',  'class' => 'bg-success-subtle text-success'],
                'displaced' => ['label' => 'نازح',  'class' => 'bg-danger-subtle text-danger'],
            ];

            $readinessMap = [
                'working'   => ['label' => 'باشر العمل',   'class' => 'bg-success text-white'],
                'ready'     => ['label' => 'جاهز للعودة',  'class' => 'bg-primary text-white'],
                'not_ready' => ['label' => 'غير جاهز',    'class' => 'bg-warning text-dark'],
            ];
        @endphp

        {{-- العنوان + لمحة سريعة --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">استمارات بيانات الموظفين</h4>
                <p class="text-muted mb-0">
                    إجمالي السجلات:
                    <span class="fw-semibold">{{ $profiles->total() }}</span>
                    @if(request('q'))
                        <span class="ms-2 badge bg-light text-muted">
                            بحث عن: "{{ request('q') }}"
                        </span>
                    @endif
                </p>
            </div>

            {{-- ممكن لاحقاً تضيف زر تصدير Excel هنا --}}
            {{-- <a href="#" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1"></i> تصدير Excel
            </a> --}}
        </div>

        {{-- فورم البحث --}}
        <div class="card mb-3">
            <div class="card-body">
                <form method="get" class="row g-2 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label mb-1">بحث عام</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="form-control"
                            placeholder="الاسم / رقم الهوية / الرقم الوظيفي">
                    </div>

                    {{-- لو حبيت تضيف فلاتر أخرى مستقبلاً (المقر / الحالة / الجاهزية) --}}
                    {{--
                    <div class="col-md-3">
                        <label class="form-label mb-1">المقر</label>
                        <select name="location" class="form-select">
                            <option value="">الكل</option>
                            @foreach($locations as $val => $label)
                                <option value="{{ $val }}" @selected(request('location') == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    --}}

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> بحث
                        </button>
                    </div>

                    @if(request()->has('q') && request('q') !== null && request('q') !== '')
                        <div class="col-md-2">
                            <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-outline-secondary w-100">
                                مسح البحث
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- جدول السجلات --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>الموظف</th>
                            <th>رقم الهوية</th>
                            <th>المقر</th>
                            <th>الحالة</th>
                            <th>الجاهزية</th>
                            <th>تاريخ التسجيل</th>
                            <th style="width:120px;">إجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($profiles as $profile)
                            <tr>
                                {{-- ترقيم مع مراعاة الصفحة الحالية --}}
                                <td>
                                    {{ $loop->iteration + ($profiles->currentPage() - 1) * $profiles->perPage() }}
                                </td>

                                {{-- الاسم + الرقم الوظيفي تحتها بشكل خفيف --}}
                                <td>
                                    <div class="fw-semibold">{{ $profile->full_name ?: '—' }}</div>
                                    <div class="text-muted small">
                                        <i class="bi bi-hash"></i>
                                        {{ $profile->employee_number ?: 'بدون رقم وظيفي' }}
                                    </div>
                                </td>

                                {{-- رقم الهوية --}}
                                <td>
                                    <span class="fw-semibold">{{ $profile->national_id }}</span>
                                </td>

                                {{-- المقر --}}
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ $locations[$profile->location] ?? $profile->location ?? '—' }}
                                    </span>
                                </td>

                                {{-- الحالة (مقيم / نازح) --}}
                                <td>
                                    @php
                                        $statusKey = $profile->status;
                                        $statusConf = $statusMap[$statusKey] ?? null;
                                    @endphp

                                    @if($statusConf)
                                        <span class="badge rounded-pill {{ $statusConf['class'] }}">
                                            {{ $statusConf['label'] }}
                                        </span>
                                    @else
                                        <span class="text-muted small">غير محدد</span>
                                    @endif
                                </td>

                                {{-- الجاهزية --}}
                                <td>
                                    @php
                                        $readyKey = $profile->readiness;
                                        $readyConf = $readinessMap[$readyKey] ?? null;
                                    @endphp

                                    @if($readyConf)
                                        <span class="badge {{ $readyConf['class'] }}">
                                            {{ $readyConf['label'] }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            غير محدد
                                        </span>
                                    @endif
                                </td>

                                {{-- تاريخ التسجيل --}}
                                <td>
                                    <div class="small">
                                        <i class="bi bi-calendar3 text-muted me-1"></i>
                                        {{ optional($profile->created_at)->format('Y-m-d H:i') }}
                                    </div>
                                    @if($profile->updated_at && $profile->updated_at->ne($profile->created_at))
                                        <div class="text-muted small">
                                            آخر تحديث:
                                            {{ $profile->updated_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>

                                {{-- الإجراءات --}}
                                <td>
                                    <a href="{{ route('admin.staff-profiles.show', $profile) }}"
                                       class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-eye"></i>
                                        عرض
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                        لا يوجد أي استمارات حتى الآن.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($profiles->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        عرض
                        <span class="fw-semibold">{{ $profiles->firstItem() }}</span>
                        إلى
                        <span class="fw-semibold">{{ $profiles->lastItem() }}</span>
                        من
                        <span class="fw-semibold">{{ $profiles->total() }}</span>
                        سجل
                    </div>
                    <div>
                        {{ $profiles->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
