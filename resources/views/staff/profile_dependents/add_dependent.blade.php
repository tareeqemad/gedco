<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استمارة البيانات الشخصية</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

    {{-- Bootstrap RTL فقط --}}
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

    <style>
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 400; src: url('{{ asset('assets/fonts/cairo/Cairo-Regular.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 500; src: url('{{ asset('assets/fonts/cairo/Cairo-Medium.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 600; src: url('{{ asset('assets/fonts/cairo/Cairo-SemiBold.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 700; src: url('{{ asset('assets/fonts/cairo/Cairo-Bold.ttf') }}') format('truetype'); font-display: swap; }

        :root {
            --surface: #fff7f2; --surface-alt: #fff1e6; --border: #f1b08d;
            --accent: #ef7c4c; --accent-dark: #c65a28; --text: #2f2b28; --muted: #8c6f61;
        }

        body { margin:0; font-family:"Cairo", Arial, sans-serif; background:#fff; color:var(--text); padding:2rem 0; }

        .form-shell { width:min(1080px, 100%); margin:0 auto; background:#fff; border-radius:24px;
            border:1px solid rgba(239,124,76,.2); box-shadow:0 20px 50px rgba(239,124,76,.15); overflow:hidden; }
        .form-header { padding:2rem 1rem; background:linear-gradient(135deg, rgba(239,124,76,.15), rgba(239,124,76,.05));
            border-bottom:1px solid rgba(239,124,76,.15); text-align:center; }
        .form-header h1 { margin:0; color:var(--accent-dark); font-weight:700; }

        .form-section { padding:2rem; border-bottom:1px solid rgba(239,124,76,.08); }
        .form-section:last-of-type { border-bottom:none; }

        .section-title { display:inline-flex; align-items:center; gap:.5rem; background:rgba(239,124,76,.1); color:var(--accent-dark);
            padding:.4rem 1.1rem; border-radius:999px; font-weight:700; margin-bottom:1.25rem; }

        label.field { display:flex; flex-direction:column; gap:.35rem; font-weight:600; color:var(--muted); }
        label.field span { font-size:.9rem; }

        /* نجمة الحقول الإلزامية */
        label.field.required > span::after { content:" *"; color:#e63946; font-weight:bold; }

        input, select, textarea {
            border:1px solid rgba(239,124,76,.2); border-radius:14px; padding:.8rem 1rem; font-size:.95rem; background:#fff;
            transition:border-color .2s ease, box-shadow .2s ease;
        }
        input:focus, select:focus, textarea:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(239,124,76,.18); }
        textarea { min-height:90px; resize:vertical; }

        .grid { display:grid; gap:1rem 1.5rem; }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }

        .family-table { width:100%; border-collapse:collapse; border:1px solid rgba(239,124,76,.18); border-radius:16px; overflow:hidden; }
        .family-table thead th { background:var(--surface-alt); padding:.8rem; font-weight:700; color:var(--accent-dark); }
        .family-table td { padding:.6rem; border-top:1px solid rgba(239,124,76,.12); }
        .family-table input, .family-table select { width:100%; }

        .add-member-btn, button { transition: all .2s ease; }
        .add-member-btn { margin-top:1rem; display:inline-flex; align-items:center; gap:.6rem; border:1px dashed rgba(239,124,76,.45);
            padding:.6rem 1.4rem; border-radius:999px; color:var(--accent-dark); background:rgba(239,124,76,.08); font-weight:700; cursor:pointer; }
        .add-member-btn:hover { background:rgba(239,124,76,.16); transform:translateY(-2px); box-shadow:0 4px 10px rgba(239,124,76,.2); }

        .remove-member-btn { border:none; background:rgba(239,124,76,.1); color:var(--accent-dark);
            padding:.45rem .8rem; border-radius:12px; cursor:pointer; font-size:.85rem; }
        .remove-member-btn:hover { background:rgba(239,124,76,.2); }

        .submit-row { padding:2rem; text-align:center; }
        .submit-row button {
            background:linear-gradient(135deg, #ef7c4c, #f49a6a); border:none; color:#fff; padding:.9rem 2.75rem;
            border-radius:18px; font-size:1rem; font-weight:700;
        }
        .submit-row button:hover { transform:translateY(-2px); box-shadow:0 12px 20px rgba(239,124,76,.25); }

        .alert { border-radius:14px; padding:1rem 1.1rem; margin:1rem 2rem 0; font-weight:600; }
        .alert-success { background:#e9f9ee; border:1px solid #b6e1c5; color:#165c2f; }
        .alert-danger  { background:#fdeeee; border:1px solid #f5b1b1; color:#8a1f1f; }
        .alert-warning { background:#fff6ed; border:1px solid #ffe0c2; color:#b15b00; }

        /* إظهار/إخفاء ناعم */
        .hidden { opacity:0; max-height:0; overflow:hidden; transition:all .3s ease; }
        .show   { opacity:1; max-height:400px; }

        @media (max-width: 640px) {
            .form-section { padding:1.5rem; }
            .grid-3, .grid-2 { grid-template-columns:1fr; }
            .family-table thead { display:none; }
            .family-table, .family-table tbody, .family-table tr, .family-table td { display:block; width:100%; }
            .family-table tr { margin-bottom:1rem; border:1px solid rgba(239,124,76,.18); border-radius:12px; overflow:hidden; }
            .family-table td { border-top:none; padding:.65rem .9rem; }
            .family-table td::before { content:attr(data-label); display:block; font-weight:700; color:var(--accent-dark); margin-bottom:.35rem; }
            .remove-member-btn { width:100%; }
        }
    </style>
</head>
<body>

@php
    // جلب القوائم من staff_enums (مع fallback بسيط لو الملف غير متوفر)
    $locations       = config('staff_enums.locations',      ['1'=>'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال','4'=>'مقر الوسطى','6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة']);
    $maritalStatus   = config('staff_enums.marital_status', ['single'=>'أعزب / عزباء','married'=>'متزوج / متزوجة','widowed'=>'أرمل / أرملة','divorced'=>'مطلق / مطلقة']);
    $houseStatus     = config('staff_enums.house_status',   ['intact'=>'سليم','partial'=>'هدم جزئي','demolished'=>'هدم كلي']);
    $residentStatus  = config('staff_enums.status',         ['resident'=>'مقيم','displaced'=>'نازح']);
    $housingTypes    = config('staff_enums.housing_type',   ['house'=>'منزل','apartment'=>'شقة','tent'=>'خيمة','other'=>'أخرى']);
    $readinessList   = config('staff_enums.readiness',      ['ready'=>'جاهز','not_ready'=>'غير جاهز مع توضيح الأسباب']);
    $relations       = config('staff_enums.relations',      ['spouse'=>'زوج / زوجة','son'=>'ابن','daughter'=>'ابنة','other'=>'أخرى']);
@endphp

<div class="form-shell">
    <div class="form-header">
        <h1>إقرار المعلومات الشخصية</h1>
    </div>

    {{-- تنبيه عام للنجمة --}}
    <div class="alert alert-warning" style="margin: 1rem 2rem 0;">
        يرجى تعبئة جميع الحقول الإلزامية المعلّمة بـ (<span style="color:#e63946">*</span>)
    </div>

    {{-- بانر منع التحديث لو مسجّل مسبقًا --}}
    @if (session('locked'))
        <div class="alert alert-danger">
            <div class="fw-bold">لا يمكن التحديث</div>
            <div>{{ session('locked_msg') ?? 'أنت مسجّل مسبقًا في النظام بهذه البيانات.' }}</div>
        </div>
    @endif

    {{-- رسائل الأخطاء والنجاح --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="lh-lg">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('staff.profile.store') }}" method="post" class="needs-validation" novalidate>
        @csrf

        {{-- نقفل كل المدخلات لو Locked --}}
        <fieldset @if(session('locked')) disabled @endif>

            {{-- البيانات الأساسية --}}
            <section class="form-section">
                <div class="section-title">البيانات الأساسية</div>
                <div class="grid grid-3">
                    <label class="field required">
                        <span>الاسم رباعي</span>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               class="@error('full_name') is-invalid @enderror">
                        @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>تاريخ الميلاد</span>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}">
                    </label>

                    <label class="field required">
                        <span>الرقم الوظيفي</span>
                        <input type="text" name="employee_number" value="{{ old('employee_number') }}" required
                               maxlength="4" pattern="\d{1,4}" class="@error('employee_number') is-invalid @enderror">
                        @error('employee_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field required">
                        <span>رقم الهوية</span>
                        <input type="text" name="national_id" value="{{ old('national_id') }}"
                               required maxlength="9" pattern="\d{9}" class="@error('national_id') is-invalid @enderror">
                        @error('national_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>الوظيفة الحالية</span>
                        <input type="text" name="job_title" value="{{ old('job_title') }}">
                    </label>

                    <label class="field required">
                        <span>المقر</span>
                        <select name="location" class="@error('location') is-invalid @enderror" required>
                            <option value="">_________</option>
                            @foreach($locations as $val => $label)
                                <option value="{{ $val }}" @selected(old('location') == (string)$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>الإدارة</span>
                        <input type="text" name="department" value="{{ old('department') }}">
                    </label>

                    <label class="field">
                        <span>الدائرة</span>
                        <input type="text" name="directorate" value="{{ old('directorate') }}">
                    </label>

                    <label class="field">
                        <span>القسم</span>
                        <input type="text" name="section" value="{{ old('section') }}">
                    </label>

                    <label class="field">
                        <span>الحالة الاجتماعية</span>
                        <select name="marital_status">
                            <option value="">_______</option>
                            @foreach($maritalStatus as $val => $label)
                                <option value="{{ $val }}" @selected(old('marital_status') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>عدد أفراد الأسرة حاليًا</span>
                        <input type="number" min="1" max="10" name="family_members_count" id="family-count-input"
                               value="{{ old('family_members_count', 1) }}">
                    </label>
                </div>
            </section>

            {{-- أفراد الأسرة --}}
            <section class="form-section">
                <div class="section-title">بيانات أفراد الأسرة</div>

                <div class="table-responsive">
                    <table class="family-table">
                        <thead>
                        <tr>
                            <th style="width: 60px;">م.</th>
                            <th>الاسم</th>
                            <th>صلة القرابة</th>
                            <th>تاريخ الميلاد</th>
                            <th>طالب جامعي</th>
                            <th style="width: 120px;">إزالة</th>
                        </tr>
                        </thead>
                        <tbody id="family-rows"></tbody>
                    </table>
                </div>

                <button type="button" class="add-member-btn" id="add-family-member">
                    + إضافة فرد جديد
                </button>

                <template id="family-row-template">
                    <tr>
                        <td data-label="م." class="family-index"></td>
                        <td data-label="الاسم">
                            <input type="text" data-field="name">
                        </td>
                        <td data-label="صلة القرابة">
                            <select data-field="relation">
                                <option value="">_______</option>
                                @foreach($relations as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td data-label="تاريخ الميلاد">
                            <input type="date" data-field="birth_date">
                        </td>
                        <td data-label="طالب جامعي">
                            <select data-field="is_student">
                                <option value="">_______</option>
                                <option value="yes">نعم</option>
                                <option value="no">لا</option>
                            </select>
                        </td>
                        <td data-label="إزالة" class="remove-cell">
                            <button type="button" class="remove-member-btn">حذف</button>
                        </td>
                    </tr>
                </template>

                <div class="grid grid-2" style="margin-top:1.25rem;">
                    <label class="field">
                        <span>هل يوجد إصابات أو معتقلين في أفراد الأسرة؟</span>
                        <select name="has_family_incidents" id="family-incidents-select">
                            <option value="">_______</option>
                            <option value="no"  @selected(old('has_family_incidents')==='no')>لا</option>
                            <option value="yes" @selected(old('has_family_incidents')==='yes')>نعم</option>
                        </select>
                    </label>
                </div>

                <label class="field {{ old('has_family_incidents')==='yes' ? 'show' : 'hidden' }}" id="family-incidents-notes" style="margin-top: 1rem;">
                    <span>تفاصيل الإصابات أو الاعتقالات</span>
                    <textarea name="family_notes">{{ old('family_notes') }}</textarea>
                </label>
            </section>

            {{-- السكن والوضع الاجتماعي --}}
            <section class="form-section">
                <div class="section-title">بيانات السكن والوضع الاجتماعي</div>
                <div class="grid grid-2">
                    <label class="field">
                        <span>عنوان السكن الأصلي كامل</span>
                        <input type="text" name="original_address" value="{{ old('original_address') }}">
                    </label>

                    <label class="field">
                        <span>وضع المنزل حاليًا</span>
                        <select name="house_status">
                            <option value="">_______</option>
                            @foreach($houseStatus as $val => $label)
                                <option value="{{ $val }}" @selected(old('house_status')===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="grid grid-3" style="margin-top: 1.25rem;">
                    <label class="field">
                        <span>الحالة</span>
                        <select name="status" id="status-select">
                            <option value="">_______</option>
                            @foreach($residentStatus as $val => $label)
                                <option value="{{ $val }}" @selected(old('status')===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field {{ old('status')==='displaced' ? 'show' : 'hidden' }}" id="current-address-field">
                        <span>العنوان الحالي بعد النزوح</span>
                        <input type="text" name="current_address" value="{{ old('current_address') }}">
                    </label>

                    <label class="field">
                        <span>حالة السكن</span>
                        <select name="housing_type">
                            <option value="">_______</option>
                            @foreach($housingTypes as $val => $label)
                                <option value="{{ $val }}" @selected(old('housing_type')===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </section>

            {{-- وسائل التواصل --}}
            <section class="form-section">
                <div class="section-title">وسائل التواصل</div>
                <div class="grid grid-3">
                    <label class="field required">
                        <span>رقم الجوال</span>
                        <input type="tel" name="mobile" value="{{ old('mobile') }}" required maxlength="10" pattern="\d{8,10}"
                               class="@error('mobile') is-invalid @enderror">
                        @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>رقم جوال بديل</span>
                        <input type="tel" name="mobile_alt" value="{{ old('mobile_alt') }}" maxlength="10" pattern="\d{8,10}">
                    </label>

                    <label class="field">
                        <span>واتس آب</span>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" maxlength="10" pattern="\d{8,10}">
                    </label>

                    <label class="field">
                        <span>تيليجرام</span>
                        <input type="text" name="telegram" value="{{ old('telegram') }}">
                    </label>

                    <label class="field">
                        <span>Gmail</span>
                        <input type="email" name="gmail" value="{{ old('gmail') }}">
                    </label>
                </div>
            </section>

            {{-- الجاهزية --}}
            <section class="form-section">
                <div class="section-title">الجاهزية للعودة للعمل</div>
                <div class="grid grid-2">
                    <label class="field">
                        <span>مستوى الجاهزية</span>
                        <select name="readiness" id="readiness-select">
                            <option value="">_______</option>
                            @foreach($readinessList as $val => $label)
                                <option value="{{ $val }}" @selected(old('readiness')===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field {{ old('readiness')==='not_ready' ? 'show' : 'hidden' }}" id="readiness-notes-field">
                        <span>أسباب عدم الجاهزية</span>
                        <textarea name="readiness_notes">{{ old('readiness_notes') }}</textarea>
                    </label>
                </div>
            </section>

            {{-- إنشاء كلمة مرور --}}
            <section class="form-section">
                <div class="section-title">إنشاء كلمة مرور</div>

                <div class="alert alert-info" style="margin-top:.25rem">
                    هذه الكلمة ستُستخدم للتحقّق منك قبل تعديل بياناتك لاحقًا. احتفظ بها جيدًا.
                </div>

                <div class="grid grid-2" style="margin-top:1rem;">
                    <label class="field required">
                        <span>كلمة المرور</span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            minlength="6"
                            required
                            class="@error('password') is-invalid @enderror"
                            autocomplete="new-password"
                        >
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        <small id="password-hint" class="text-muted">الحد الأدنى 6 حروف/أرقام.</small>
                        <div id="password-strength" style="height:6px;border-radius:6px;background:#eee;margin-top:.5rem;overflow:hidden;">
                            <div id="password-strength-bar" style="height:100%;width:0;"></div>
                        </div>
                        <div style="margin-top:.5rem;">
                            <label style="display:inline-flex;align-items:center;gap:.4rem;cursor:pointer;">
                                <input type="checkbox" id="toggle-password"> إظهار كلمة المرور
                            </label>
                        </div>
                    </label>

                    <label class="field required">
                        <span>تأكيد كلمة المرور</span>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            minlength="6"
                            required
                            autocomplete="new-password"
                        >
                        <small class="text-muted">أعد إدخال كلمة المرور للتأكيد.</small>
                    </label>
                </div>
            </section>

        </fieldset>

        {{-- إقرار الموظف أو العودة --}}
        @if(!session('locked'))
            <section class="submit-row">
                <div class="section-title" style="margin-bottom: 1.1rem;">إقرار الموظف</div>
                <p class="text-muted mb-3">أقرّ بأن جميع البيانات المذكورة أعلاه صحيحة ومطابقة للواقع، وأتعهد بإبلاغ الإدارة فور حدوث أي تغيير.</p>
                <button type="submit">حفظ البيانات</button>
            </section>
        @else
            <div class="submit-row">
                <a href="{{ url('/') }}" class="add-member-btn" style="text-decoration:none">العودة</a>
            </div>
        @endif
    </form>
</div>

<script>
    (() => {
        const template = document.getElementById('family-row-template');
        const container = document.getElementById('family-rows');
        const addButton = document.getElementById('add-family-member');
        const familyCountInput = document.getElementById('family-count-input');
        const MAX_FAMILY_MEMBERS = parseInt(familyCountInput?.getAttribute('max') ?? '10', 10);

        const readinessSelectEl = document.getElementById('readiness-select');
        const readinessNotesEl  = document.getElementById('readiness-notes-field');
        const familyIncidentsSelectEl = document.getElementById('family-incidents-select');
        const familyIncidentsNotesEl  = document.getElementById('family-incidents-notes');
        const statusSelectEl = document.getElementById('status-select');
        const currentAddressEl = document.getElementById('current-address-field');

        const oldFamily = @json(old('family', []));

        if (!template || !container) { return; }

        function updateIndices() {
            container.querySelectorAll('tr').forEach((row, idx) => {
                const index = idx + 1;
                row.querySelector('.family-index').textContent = index;
                row.querySelectorAll('[data-field]').forEach(input => {
                    const field = input.dataset.field;
                    input.name = `family[${index}][${field}]`;
                });
            });
        }

        function toggleRemoveState() {
            const rows = container.querySelectorAll('tr');
            rows.forEach(row => {
                const btn = row.querySelector('.remove-member-btn');
                if (!btn) return;
                const disabled = rows.length === 1;
                btn.disabled = disabled;
                btn.style.opacity = disabled ? 0.5 : 1;
            });
        }

        function updateAddButtonState() {
            const count = container.querySelectorAll('tr').length;
            const disabled = count >= MAX_FAMILY_MEMBERS;
            addButton.disabled = disabled;
            addButton.style.opacity = disabled ? 0.5 : 1;
            addButton.setAttribute('aria-disabled', disabled ? 'true' : 'false');
        }

        function createMemberRow(prefill = null) {
            const row = template.content.firstElementChild.cloneNode(true);
            const removeButton = row.querySelector('.remove-member-btn');

            if (prefill) {
                row.querySelector('[data-field="name"]').value = prefill.name ?? '';
                row.querySelector('[data-field="relation"]').value = prefill.relation ?? '';
                row.querySelector('[data-field="birth_date"]').value = prefill.birth_date ?? '';
                row.querySelector('[data-field="is_student"]').value = prefill.is_student ?? '';
            }

            removeButton.addEventListener('click', () => {
                row.remove();
                const remaining = container.querySelectorAll('tr').length;
                const nextCount = Math.max(remaining, 1);
                ensureRowCount(nextCount);
                if (familyCountInput) familyCountInput.value = nextCount;
            });

            return row;
        }

        function ensureRowCount(desired, prefillList = null) {
            let target = Number.isFinite(desired) ? desired : 1;
            target = Math.max(1, Math.min(MAX_FAMILY_MEMBERS, target));

            if (Array.isArray(prefillList)) {
                container.innerHTML = '';
                target = Math.max(1, Math.min(MAX_FAMILY_MEMBERS, prefillList.length || 1));
            }

            let current = container.querySelectorAll('tr').length;

            while (current < target) {
                const prefill = Array.isArray(prefillList) ? (prefillList[current] || null) : null;
                container.appendChild(createMemberRow(prefill));
                current++;
            }

            while (current > target) {
                container.removeChild(container.lastElementChild);
                current--;
            }

            updateIndices();
            toggleRemoveState();
            updateAddButtonState();
        }

        // زر إضافة فرد
        addButton?.addEventListener('click', () => {
            const nextCount = Math.min(container.querySelectorAll('tr').length + 1, MAX_FAMILY_MEMBERS);
            ensureRowCount(nextCount);
            if (familyCountInput) familyCountInput.value = nextCount;
        });

        // التحكم بعدد الصفوف من المدخل
        familyCountInput?.addEventListener('input', () => {
            let desired = parseInt(familyCountInput.value, 10);
            if (!Number.isFinite(desired)) desired = 1;
            desired = Math.max(1, Math.min(MAX_FAMILY_MEMBERS, desired));
            familyCountInput.value = desired;
            ensureRowCount(desired);
        });

        // بداية: لو فيه old data رجّعها، غير هيك صف واحد أو حسب العدد
        if (Array.isArray(oldFamily) && oldFamily.filter(Boolean).length) {
            const normalized = oldFamily
                .filter(Boolean)
                .map(item => ({
                    name: item?.name ?? '',
                    relation: item?.relation ?? '',
                    birth_date: item?.birth_date ?? '',
                    is_student: item?.is_student ?? '',
                }));
            ensureRowCount(normalized.length, normalized);
            if (familyCountInput) familyCountInput.value = normalized.length;
        } else {
            const startCount = parseInt(familyCountInput?.value ?? '1', 10) || 1;
            ensureRowCount(startCount);
            if (familyCountInput) familyCountInput.value = container.querySelectorAll('tr').length;
        }

        // الحقول الشرطية (مع إظهار/إخفاء ناعم)
        const toggleShow = (selectEl, targetEl, value) => {
            const show = selectEl?.value === value;
            if (!targetEl) return;
            if (show) {
                targetEl.classList.add('show');
                targetEl.classList.remove('hidden');
            } else {
                targetEl.classList.add('hidden');
                targetEl.classList.remove('show');
            }
        };

        readinessSelectEl?.addEventListener('change', () => toggleShow(readinessSelectEl, readinessNotesEl, 'not_ready'));
        toggleShow(readinessSelectEl, readinessNotesEl, 'not_ready');

        familyIncidentsSelectEl?.addEventListener('change', () => toggleShow(familyIncidentsSelectEl, familyIncidentsNotesEl, 'yes'));
        toggleShow(familyIncidentsSelectEl, familyIncidentsNotesEl, 'yes');

        statusSelectEl?.addEventListener('change', () => toggleShow(statusSelectEl, currentAddressEl, 'displaced'));
        toggleShow(statusSelectEl, currentAddressEl, 'displaced');

        // Bootstrap client-side validation
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });

        // ====== كلمة المرور: إظهار/إخفاء + مؤشر قوة بسيط ======
        const passInput = document.getElementById('password');
        const passConf  = document.getElementById('password_confirmation');
        const togglePw  = document.getElementById('toggle-password');
        const bar       = document.getElementById('password-strength-bar');

        function scorePassword(pwd) {
            let score = 0; if (!pwd) return 0;
            const letters = {};
            for (let i = 0; i < pwd.length; i++) {
                letters[pwd[i]] = (letters[pwd[i]] || 0) + 1;
                score += 5.0 / letters[pwd[i]];
            }
            const variations = {
                digits: /\d/.test(pwd),
                lower: /[a-z]/.test(pwd),
                upper: /[A-Z]/.test(pwd),
                nonWords: /[^a-zA-Z0-9]/.test(pwd),
            };
            let variationCount = 0;
            for (let k in variations) { variationCount += variations[k] ? 1 : 0; }
            score += (variationCount - 1) * 10;
            return parseInt(score);
        }

        function updateStrength(pwd) {
            const s = scorePassword(pwd);
            let width = 0, color = '#ddd';
            if (s > 0)  { width = 25; color = '#f66'; }
            if (s > 40) { width = 50; color = '#f8a22f'; }
            if (s > 70) { width = 75; color = '#8bc34a'; }
            if (s > 90) { width = 100; color = '#4caf50'; }
            if (bar) { bar.style.width = width + '%'; bar.style.background = color; }
        }

        togglePw?.addEventListener('change', () => {
            const type = togglePw.checked ? 'text' : 'password';
            if (passInput) passInput.type = type;
            if (passConf)  passConf.type  = type;
        });

        passInput?.addEventListener('input', (e) => updateStrength(e.target.value));
        updateStrength(passInput?.value || '');
    })();
</script>
</body>
</html>
