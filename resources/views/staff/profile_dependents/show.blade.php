<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض بيانات الموظف</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/site/css/staff-common.css') }}">

</head>
<body>

<div class="shell">
    <div class="header">
        <h1>عرض بيانات الموظف</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info text-center">{{ session('info') }}</div>
    @endif

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
                <div class="value">{{ optional($profile->birth_date)->format('Y-m-d') ?: '—' }}</div>
            </div>
            <div class="card">
                <div class="label">المسمّى الوظيفي</div>
                <div class="value">{{ $profile->job_title ?: '—' }}</div>
            </div>
            <div class="card">
                <div class="label">المقر</div>
                @php
                    $locations = [
                        '1' => 'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال','4'=>'مقر الوسطى',
                        '6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة'
                    ];
                @endphp
                <div class="value">{{ $locations[$profile->location] ?? $profile->location }}</div>
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
                @php
                    $mar = ['single'=>'أعزب/عزباء','married'=>'متزوج/متزوجة','widowed'=>'أرمل/أرملة','divorced'=>'مطلق/مطلقة'];
                @endphp
                <div class="value">{{ $mar[$profile->marital_status] ?? '—' }}</div>
            </div>
            <div class="card">
                <div class="label">عدد أفراد الأسرة</div>
                <div class="value">{{ $profile->family_members_count }}</div>
            </div>
        </div>
    </section>

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
                @forelse ($profile->dependents as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->name }}</td>
                        <td>
                            @php
                                $rel = ['spouse'=>'زوج/زوجة','son'=>'ابن','daughter'=>'ابنة','other'=>'أخرى'];
                            @endphp
                            {{ $rel[$d->relation] ?? $d->relation }}
                        </td>
                        <td>{{ optional($d->birth_date)->format('Y-m-d') ?: '—' }}</td>
                        <td>{{ $d->is_student ? 'نعم' : 'لا' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">لا يوجد بيانات أفراد أسرة.</td></tr>
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

    <section class="section">
        <div class="title">بيانات السكن والوضع الاجتماعي</div>
        <div class="grid grid-3">
            <div class="card">
                <div class="label">عنوان السكن الأصلي</div>
                <div class="value">{{ $profile->original_address ?: '—' }}</div>
            </div>
            <div class="card">
                <div class="label">وضع المنزل</div>
                @php $hs = ['intact'=>'سليم','partial'=>'هدم جزئي','demolished'=>'هدم كلي']; @endphp
                <div class="value">{{ $hs[$profile->house_status] ?? '—' }}</div>
            </div>
            <div class="card">
                <div class="label">الحالة</div>
                @php $st = ['resident'=>'مقيم','displaced'=>'نازح']; @endphp
                <div class="value">{{ $st[$profile->status] ?? '—' }}</div>
            </div>
            <div class="card">
                <div class="label">العنوان الحالي بعد النزوح</div>
                <div class="value">{{ $profile->current_address ?: '—' }}</div>
            </div>
            <div class="card">
                <div class="label">حالة السكن</div>
                @php $ht = ['house'=>'منزل','apartment'=>'شقة','tent'=>'خيمة','other'=>'أخرى']; @endphp
                <div class="value">{{ $ht[$profile->housing_type] ?? '—' }}</div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="title">وسائل التواصل</div>
        <div class="grid grid-3">
            <div class="card"><div class="label">الجوال</div><div class="value">{{ $profile->mobile }}</div></div>
            <div class="card"><div class="label">جوال بديل</div><div class="value">{{ $profile->mobile_alt ?: '—' }}</div></div>
            <div class="card"><div class="label">واتس آب</div><div class="value">{{ $profile->whatsapp ?: '—' }}</div></div>
            <div class="card"><div class="label">تيليجرام</div><div class="value">{{ $profile->telegram ?: '—' }}</div></div>
            <div class="card"><div class="label">Gmail</div><div class="value">{{ $profile->gmail ?: '—' }}</div></div>
        </div>
    </section>

    <section class="section">
        <div class="title">الجاهزية للعودة للعمل</div>
        <div class="grid grid-2">
            <div class="card">
                <div class="label">الجاهزية</div>
                @php $rd = ['ready'=>'جاهز','not_ready'=>'غير جاهز']; @endphp
                <div class="value">{{ $rd[$profile->readiness] ?? '—' }}</div>
            </div>
            <div class="card">
                <div class="label">ملاحظات الجاهزية</div>
                <div class="value">{{ $profile->readiness_notes ?: '—' }}</div>
            </div>
        </div>
    </section>

    <div class="actions">
        <a class="btn-main" href="{{ route('staff.profile.verify.form') }}">تعديل البيانات</a>
    </div>
</div>

</body>
</html>
