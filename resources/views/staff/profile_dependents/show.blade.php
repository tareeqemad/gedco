<!DOCTYPE html>
<html lang="ar" dir="{{ $direction ?? 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض بيانات الموظف</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icons/icon.ico') }}">

    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap' . (($direction ?? 'rtl') === 'rtl' ? '.rtl' : '') . '.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site/css/staff-common.css') }}">
</head>
<body>

@php
    use Carbon\Carbon;

    // القراءة من config مثل add / edit
    $LOC       = config('staff_enums.locations', []);
    $MARITAL   = config('staff_enums.marital_status', []);
    $HOUSE     = config('staff_enums.house_status', []);
    $STATUS    = config('staff_enums.status', []);
    $HOUSING   = config('staff_enums.housing_type', []);
    $RELATIONS = config('staff_enums.relation', []);     // spouse / son / daughter / other
    $READINESS = config('staff_enums.readiness', []);

    // ترتيب المعالين من الأكبر للأصغر حسب تاريخ الميلاد
    $dependents = ($profile->dependents ?? collect())->sortBy('birth_date');

    // تفكيك رقم الواتس: مقدّمة + رقم، نفس منطق شاشة edit
    $whats   = $profile->whatsapp ?? '';
    $waPref  = '';
    $waNum   = '';

    if ($whats) {
        if (str_starts_with($whats, '970')) {
            $waPref = '970';
            $waNum  = substr($whats, 3);
        } elseif (str_starts_with($whats, '972')) {
            $waPref = '972';
            $waNum  = substr($whats, 3);
        } else {
            $waNum = $whats;
        }
    }
@endphp

<div class="shell">
    <div class="header">
        <img src="{{ asset('assets/site/images/logos/logo-dark.webp') }}" alt="GEDCO" style="height:52px;margin-bottom:.6rem;">
        <h1>عرض بيانات الموظف</h1>
        <p style="color:#7a6b63;font-size:.88rem;margin:.3rem 0 0;">شركة توزيع كهرباء محافظات غزة</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info text-center">{{ session('info') }}</div>
    @endif

    {{-- البيانات الأساسية --}}
    <section class="section">
        <div class="title">البيانات الأساسية</div>
        <div class="grid grid-3">
            <div class="card">
                <div class="label">الاسم الرباعي</div>
                <div class="value">{{ $profile->full_name }}</div>
            </div>

            <div class="card">
                <div class="label">الرقم الوظيفي</div>
                <div class="value">{{ $profile->employee_number }}</div>
            </div>

            <div class="card">
                <div class="label">رقم الهوية</div>
                <div class="value">{{ $profile->national_id }}</div>
            </div>

            <div class="card">
                <div class="label">تاريخ الميلاد</div>
                <div class="value">
                    {{ optional($profile->birth_date)->format('Y-m-d') ?: '—' }}
                </div>
            </div>

            <div class="card">
                <div class="label">المسمّى الوظيفي</div>
                <div class="value">{{ $profile->job_title ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">المقر</div>
                <div class="value">
                    {{ $LOC[$profile->location] ?? $profile->location ?? '—' }}
                </div>
            </div>

            <div class="card">
                <div class="label">الإدارة</div>
                <div class="value">{{ $profile->department ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">الدائرة</div>
                <div class="value">{{ $profile->directorate ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">القسم</div>
                <div class="value">{{ $profile->section ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">الحالة الاجتماعية</div>
                <div class="value">
                    {{ $MARITAL[$profile->marital_status] ?? '—' }}
                </div>
            </div>

            <div class="card">
                <div class="label">عدد أفراد الأسرة</div>
                <div class="value">{{ $profile->family_members_count ?: '—' }}</div>
            </div>
        </div>
    </section>

    {{-- أفراد الأسرة (المعالين) --}}
    <section class="section">
        <div class="title">أفراد الأسرة</div>

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
                @forelse ($dependents as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->name }}</td>
                        <td>{{ $RELATIONS[$d->relation] ?? $d->relation }}</td>
                        <td>{{ optional($d->birth_date)->format('Y-m-d') ?: '—' }}</td>
                        <td>{{ $d->is_student ? 'نعم' : 'لا' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            لا يوجد بيانات أفراد أسرة.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($profile->has_family_incidents === 'yes')
            <div class="alert alert-warning mt-3">
                <strong>ملاحظات الأسرة:</strong>
                <div>{{ $profile->family_notes }}</div>
            </div>
        @endif
    </section>

    {{-- بيانات السكن والوضع الاجتماعي --}}
    <section class="section">
        <div class="title">بيانات السكن والوضع الاجتماعي</div>
        <div class="grid grid-3">
            <div class="card">
                <div class="label">عنوان السكن الأصلي</div>
                <div class="value">{{ $profile->original_address ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">وضع المنزل</div>
                <div class="value">
                    {{ $HOUSE[$profile->house_status] ?? '—' }}
                </div>
            </div>

            <div class="card">
                <div class="label">الحالة</div>
                <div class="value">
                    {{ $STATUS[$profile->status] ?? '—' }}
                </div>
            </div>

            <div class="card">
                <div class="label">العنوان الحالي بعد النزوح</div>
                <div class="value">{{ $profile->current_address ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">حالة السكن</div>
                <div class="value">
                    {{ $HOUSING[$profile->housing_type] ?? '—' }}
                </div>
            </div>
        </div>
    </section>

    {{-- وسائل التواصل --}}
    <section class="section">
        <div class="title">وسائل التواصل</div>
        <div class="grid grid-3">
            <div class="card">
                <div class="label">الجوال</div>
                <div class="value">{{ $profile->mobile ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">جوال بديل</div>
                <div class="value">{{ $profile->mobile_alt ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">واتس آب</div>
                <div class="value">
                    @if($waNum)
                        +{{ $waPref }} {{ $waNum }}
                    @else
                        —
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="label">تيليجرام</div>
                <div class="value">{{ $profile->telegram ?: '—' }}</div>
            </div>

            <div class="card">
                <div class="label">Gmail</div>
                <div class="value">{{ $profile->gmail ?: '—' }}</div>
            </div>
        </div>
    </section>

    {{-- الجاهزية --}}
    <section class="section">
        <div class="title">الجاهزية للعودة للعمل</div>
        <div class="grid grid-2">
            <div class="card">
                <div class="label">مستوى الجاهزية</div>
                <div class="value">
                    {{ $READINESS[$profile->readiness] ?? '—' }}
                </div>
            </div>

            <div class="card">
                <div class="label">ملاحظات الجاهزية</div>
                <div class="value">{{ $profile->readiness_notes ?: '—' }}</div>
            </div>
        </div>
    </section>

    <div class="actions">
        <a class="btn-main" href="{{ route('staff.profile.verify.form') }}">
            تعديل البيانات
        </a>
    </div>
</div>

</body>
</html>
