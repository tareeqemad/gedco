<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض بيانات الموظف</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

    <style>
        :root { --surface:#fff7f2; --surface-alt:#fff1e6; --border:#f1b08d; --accent:#ef7c4c; --accent-dark:#c65a28; --text:#2f2b28; --muted:#8c6f61; }
        body { margin:0; font-family:"Cairo", Arial, sans-serif; background:#ffffff; color:var(--text); padding:2rem 0; }
        .shell { width:min(1080px,100%); margin:0 auto; background:#fff; border-radius:24px; border:1px solid rgba(239,124,76,.2); box-shadow:0 20px 50px rgba(239,124,76,.15); overflow:hidden; }
        .header { padding:2rem 1rem; background:linear-gradient(135deg, rgba(239,124,76,.15), rgba(239,124,76,.05)); border-bottom:1px solid rgba(239,124,76,.15); text-align:center; }
        .header h1 { margin:0; color:var(--accent-dark); font-weight:700; }
        .section { padding:2rem; border-bottom:1px solid rgba(239,124,76,.08); }
        .section:last-of-type { border-bottom:none; }
        .title { display:inline-flex; align-items:center; gap:.5rem; background:rgba(239,124,76,.1); color:var(--accent-dark); padding:.4rem 1.1rem; border-radius:999px; font-weight:700; margin-bottom:1.25rem; }
        .grid { display:grid; gap:1rem 1.5rem; }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); }
        .card { border:1px solid rgba(239,124,76,.18); border-radius:16px; padding:1rem 1.25rem; background:#fff; }
        .label { color:var(--muted); font-weight:700; font-size:.9rem; }
        .value { font-weight:700; color:#2f2b28; }
        .family-table { width:100%; border-collapse:collapse; border:1px solid rgba(239,124,76,.18); border-radius:16px; overflow:hidden; }
        .family-table thead th { background:var(--surface-alt); padding:.8rem; font-weight:700; color:var(--accent-dark); }
        .family-table td { padding:.7rem; border-top:1px solid rgba(239,124,76,.12); }
        .actions { padding:2rem; text-align:center; }
        .btn-main { background:linear-gradient(135deg,#ef7c4c,#f49a6a); border:none; color:#fff; padding:.9rem 2.75rem; border-radius:18px; font-size:1rem; font-weight:700; text-decoration:none; display:inline-block; }
        .alert { border-radius:14px; padding:1rem 1.1rem; margin:1rem 2rem 0; font-weight:600; }
        .alert-info { background:#eef6ff; border:1px solid #cfe3ff; color:#0b4b8a; }
        .alert-success { background:#e9f9ee; border:1px solid #b6e1c5; color:#165c2f; }
        .alert-warning { background:#fff8e6; border:1px solid #ffe2a6; color:#7a4c00; }
    </style>
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
