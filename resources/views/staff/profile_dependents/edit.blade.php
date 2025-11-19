<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البيانات الشخصية</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

    {{-- Bootstrap RTL فقط --}}
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

    <style>
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 400; src: url('{{ asset('assets/fonts/cairo/Cairo-Regular.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 500; src: url('{{ asset('assets/fonts/cairo/Cairo-Medium.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 600; src: url('{{ asset('assets/fonts/cairo/Cairo-SemiBold.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 700; src: url('{{ asset('assets/fonts/cairo/Cairo-Bold.ttf') }}') format('truetype'); font-display: swap; }

        :root{
            --surface:#fff7f2; --surface-alt:#fff1e6; --border:#f1b08d; --accent:#ef7c4c; --accent-dark:#c65a28; --text:#2f2b28; --muted:#8c6f61;
        }
        body{ margin:0; font-family:"Cairo",Arial,sans-serif; background:#ffffff; color:var(--text); padding:2rem 0; }
        .form-shell{ width:min(1080px,100%); margin:0 auto; background:#fff; border-radius:24px; border:1px solid rgba(239,124,76,.2); box-shadow:0 20px 50px rgba(239,124,76,.15); overflow:hidden; }
        .form-header{ padding:2rem 1rem; background:linear-gradient(135deg, rgba(239,124,76,.15), rgba(239,124,76,.05)); border-bottom:1px solid rgba(239,124,76,.15); text-align:center; }
        .form-header h1{ margin:0; color:var(--accent-dark); font-weight:700; }

        .form-section{ padding:2rem; border-bottom:1px solid rgba(239,124,76,.08); }
        .form-section:last-of-type{ border-bottom:none; }
        .section-title{ display:inline-flex; align-items:center; gap:.5rem; background:rgba(239,124,76,.1); color:var(--accent-dark); padding:.4rem 1.1rem; border-radius:999px; font-weight:700; margin-bottom:1.25rem; }

        label.field{ display:flex; flex-direction:column; gap:.35rem; font-weight:600; color:var(--muted); }
        label.field span{ font-size:.9rem; }

        input,select,textarea{
            border:1px solid rgba(239,124,76,.2); border-radius:14px; padding:.8rem 1rem;
            font-size:.95rem; background:#fff; transition:border-color .2s, box-shadow .2s;
        }
        input:focus,select:focus,textarea:focus{ outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(239,124,76,.18); }
        textarea{ min-height:90px; resize:vertical; }

        .grid{ display:grid; gap:1rem 1.5rem; }
        .grid-3{ grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); }
        .grid-2{ grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); }

        .family-table{ width:100%; border-collapse:collapse; border:1px solid rgba(239,124,76,.18); border-radius:16px; overflow:hidden; }
        .family-table thead th{ background:var(--surface-alt); padding:.8rem; font-weight:700; color:var(--accent-dark); }
        .family-table td{ padding:.6rem; border-top:1px solid rgba(239,124,76,.12); }
        .family-table input, .family-table select{ width:100%; }

        .add-member-btn{
            margin-top:1rem; display:inline-flex; align-items:center; gap:.6rem;
            border:1px dashed rgba(239,124,76,.45); padding:.6rem 1.4rem; border-radius:999px;
            color:var(--accent-dark); background:rgba(239,124,76,.08); font-weight:700; cursor:pointer;
        }
        .add-member-btn:hover{ background:rgba(239,124,76,.16); }
        .remove-member-btn{ border:none; background:rgba(239,124,76,.1); color:var(--accent-dark); padding:.45rem .8rem; border-radius:12px; cursor:pointer; font-size:.85rem; }

        .submit-row{ padding:2rem; text-align:center; }
        .submit-row button{
            background:linear-gradient(135deg, #ef7c4c, #f49a6a); border:none; color:#fff;
            padding:.9rem 2.75rem; border-radius:18px; font-size:1rem; font-weight:700;
        }
        .submit-row button:hover{ transform:translateY(-2px); box-shadow:0 12px 20px rgba(239,124,76,.25); }

        .alert{ border-radius:14px; padding:1rem 1.1rem; margin:1rem 2rem 0; font-weight:600; }
        .alert-success{ background:#e9f9ee; border:1px solid #b6e1c5; color:#165c2f; }
        .alert-danger{ background:#fdeeee; border:1px solid #f5b1b1; color:#8a1f1f; }
        .alert-info{ background:#eef6ff; border:1px solid #b6d5ff; color:#1b4d91; }
        .alert-secondary{ background:#f6f6f6; border:1px solid #ddd; color:#333; }

        .hidden{ display:none!important; }
        .req-star{ color:#d9534f; margin-inline-start:.2rem; }

        @media (max-width:640px){
            .form-section{ padding:1.5rem; }
            .grid-3,.grid-2{ grid-template-columns:1fr; }
            .family-table thead{ display:none; }
            .family-table, .family-table tbody, .family-table tr, .family-table td{ display:block; width:100%; }
            .family-table tr{ margin-bottom:1rem; border:1px solid rgba(239,124,76,.18); border-radius:12px; overflow:hidden; }
            .family-table td{ border-top:none; padding:.65rem .9rem; }
            .family-table td::before{ content:attr(data-label); display:block; font-weight:700; color:var(--accent-dark); margin-bottom:.35rem; }
            .remove-member-btn{ width:100%; }
        }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;

    // من config/staff_enums.php لو موجود، وإلا fallback
    $LOC     = config('staff_enums.locations', ['1'=>'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال','4'=>'مقر الوسطى','6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة']);
    $HOUSE   = config('staff_enums.house_status', ['intact'=>'سليم','partial'=>'هدم جزئي','demolished'=>'هدم كلي']);
    $HOUSING = config('staff_enums.housing_type', ['house'=>'منزل','apartment'=>'شقة','tent'=>'خيمة','other'=>'أخرى']);
    $MARITAL = config('staff_enums.marital_status', ['single'=>'أعزب/عزباء','married'=>'متزوج/متزوجة','widowed'=>'أرمل/أرملة','divorced'=>'مطلق/مطلقة']);

    // صف الموظف نفسه (نضيفه أول واحد)
    $selfRow = [
        'name'       => $profile->full_name,
        'relation'   => 'self',
        'birth_date' => $profile->birth_date
            ? Carbon::parse($profile->birth_date)->toDateString()
            : null,
        'is_student' => '',
    ];

    // أفراد الأسرة من الجدول
    $dependentsData = ($profile->dependents ?? collect())->map(function($d){
        $birth = $d->birth_date;
        if ($birth instanceof Carbon) {
            $birth = $birth->toDateString();
        } elseif (is_string($birth)) {
            try { $birth = Carbon::parse($birth)->toDateString(); } catch (\Throwable $e) { $birth = null; }
        }

        return [
            'name'       => $d->name,
            'relation'   => $d->relation, // spouse/son/daughter/other
            'birth_date' => $birth,
            'is_student' => $d->is_student ? 'yes' : 'no',
        ];
    })->values();

    // نجهز مصفوفة initial family (الموظف نفسه + التوابع)
    $serverFamily = collect([$selfRow])->merge($dependentsData)->values();
@endphp

<div class="form-shell">
    <div class="form-header">
        <h1>تعديل البيانات الشخصية</h1>
    </div>

    @if(session('info'))    <div class="alert alert-info">{{ session('info') }}</div> @endif
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="lh-lg">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- تنبيه المحاولات المتبقية --}}
    <div class="alert alert-secondary" style="margin:1rem 2rem 0;">
        لديك <b>{{ $profile->edits_remaining }}</b> محاولة تعديل متبقية.
        @if($profile->edits_remaining == 1) <span class="text-danger">⚠️ هذه آخر محاولة!</span> @endif
    </div>

    <form action="{{ route('staff.profile.update', ['profile' => $profile->getKey()]) }}" method="post" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        {{-- البيانات الأساسية --}}
        <section class="form-section">
            <div class="section-title">البيانات الأساسية</div>
            <div class="grid grid-3">
                <label class="field">
                    <span>الاسم رباعي <span class="req-star">*</span></span>
                    <input type="text" name="full_name" value="{{ old('full_name',$profile->full_name) }}" required class="@error('full_name') is-invalid @enderror">
                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>تاريخ الميلاد</span>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $profile->birth_date ? \Carbon\Carbon::parse($profile->birth_date)->format('Y-m-d') : '') }}">
                </label>

                <label class="field">
                    <span>الرقم الوظيفي <span class="req-star">*</span></span>
                    <input type="text" name="employee_number" value="{{ old('employee_number',$profile->employee_number) }}" required class="@error('employee_number') is-invalid @enderror">
                    @error('employee_number') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>رقم الهوية <span class="req-star">*</span></span>
                    <input type="text" name="national_id" value="{{ old('national_id',$profile->national_id) }}" required class="@error('national_id') is-invalid @enderror">
                    @error('national_id') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>الوظيفة الحالية</span>
                    <input type="text" name="job_title" value="{{ old('job_title',$profile->job_title) }}">
                </label>

                <label class="field">
                    <span>المقر <span class="req-star">*</span></span>
                    <select name="location" class="@error('location') is-invalid @enderror" required>
                        <option value="">_________</option>
                        @foreach($LOC as $key=>$label)
                            <option value="{{ $key }}" @selected(old('location', (string)$profile->location)===(string)$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>الإدارة</span>
                    <input type="text" name="department" value="{{ old('department',$profile->department) }}">
                </label>

                <label class="field">
                    <span>الدائرة</span>
                    <input type="text" name="directorate" value="{{ old('directorate',$profile->directorate) }}">
                </label>

                <label class="field">
                    <span>القسم</span>
                    <input type="text" name="section" value="{{ old('section',$profile->section) }}">
                </label>

                <label class="field">
                    <span>الحالة الاجتماعية</span>
                    <select name="marital_status">
                        <option value="">_______</option>
                        @foreach($MARITAL as $k=>$lbl)
                            <option value="{{ $k }}" @selected(old('marital_status',$profile->marital_status)===$k)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    <span>عدد أفراد الأسرة حاليًا</span>
                    <input type="number" min="1" max="10" name="family_members_count" id="family-count-input" value="{{ old('family_members_count', $profile->family_members_count) }}">
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

            <button type="button" class="add-member-btn" id="add-family-member">+ إضافة فرد جديد</button>

            <template id="family-row-template">
                <tr>
                    <td data-label="م." class="family-index"></td>
                    <td data-label="الاسم">
                        <input type="text" data-field="name">
                    </td>
                    <td data-label="صلة القرابة">
                        <select data-field="relation">
                            <option value="">_______</option>
                            <option value="spouse">زوج / زوجة</option>
                            <option value="son">ابن</option>
                            <option value="daughter">ابنة</option>
                            <option value="other">أخرى</option>
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
        </section>

        {{-- السكن والوضع الاجتماعي --}}
        <section class="form-section">
            <div class="section-title">بيانات السكن والوضع الاجتماعي</div>
            <div class="grid grid-2">
                <label class="field">
                    <span>عنوان السكن الأصلي كامل</span>
                    <input type="text" name="original_address" value="{{ old('original_address',$profile->original_address) }}">
                </label>

                <label class="field">
                    <span>وضع المنزل حاليًا</span>
                    <select name="house_status">
                        <option value="">_______</option>
                        @foreach($HOUSE as $k=>$lbl)
                            <option value="{{ $k }}" @selected(old('house_status',$profile->house_status)===$k)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="grid grid-3" style="margin-top:1.25rem;">
                <label class="field">
                    <span>الحالة</span>
                    <select name="status" id="status-select">
                        <option value="">_______</option>
                        <option value="resident"  @selected(old('status',$profile->status)==='resident')>مقيم</option>
                        <option value="displaced" @selected(old('status',$profile->status)==='displaced')>نازح</option>
                    </select>
                </label>

                <label class="field {{ old('status',$profile->status)==='displaced' ? '' : 'hidden' }}" id="current-address-field">
                    <span>العنوان الحالي بعد النزوح</span>
                    <input type="text" name="current_address" value="{{ old('current_address',$profile->current_address) }}">
                </label>

                <label class="field">
                    <span>حالة السكن</span>
                    <select name="housing_type">
                        <option value="">_______</option>
                        @foreach($HOUSING as $k=>$lbl)
                            <option value="{{ $k }}" @selected(old('housing_type',$profile->housing_type)===$k)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </section>

        {{-- وسائل التواصل --}}
        <section class="form-section">
            <div class="section-title">وسائل التواصل</div>
            <div class="grid grid-3">
                <label class="field">
                    <span>رقم الجوال <span class="req-star">*</span></span>
                    <input type="tel" name="mobile" value="{{ old('mobile',$profile->mobile) }}" required class="@error('mobile') is-invalid @enderror">
                    @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>رقم جوال بديل</span>
                    <input type="tel" name="mobile_alt" value="{{ old('mobile_alt',$profile->mobile_alt) }}">
                </label>

                <label class="field">
                    <span>واتس آب</span>
                    <input type="tel" name="whatsapp" value="{{ old('whatsapp',$profile->whatsapp) }}">
                </label>

                <label class="field">
                    <span>تيليجرام</span>
                    <input type="text" name="telegram" value="{{ old('telegram',$profile->telegram) }}">
                </label>

                <label class="field">
                    <span>Gmail</span>
                    <input type="email" name="gmail" value="{{ old('gmail',$profile->gmail) }}">
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
                        <option value="ready"     @selected(old('readiness',$profile->readiness)==='ready')>جاهز</option>
                        <option value="not_ready" @selected(old('readiness',$profile->readiness)==='not_ready')>غير جاهز مع توضيح الأسباب</option>
                    </select>
                </label>

                <label class="field {{ old('readiness',$profile->readiness)==='not_ready' ? '' : 'hidden' }}" id="readiness-notes-field">
                    <span>أسباب عدم الجاهزية</span>
                    <textarea name="readiness_notes">{{ old('readiness_notes',$profile->readiness_notes) }}</textarea>
                </label>
            </div>
        </section>

        <section class="submit-row">
            <button type="submit">حفظ التعديلات</button>
        </section>
    </form>
</div>

<script>
    (() => {
        const template          = document.getElementById('family-row-template');
        const container         = document.getElementById('family-rows');
        const addButton         = document.getElementById('add-family-member');
        const familyCountInput  = document.getElementById('family-count-input');
        const readinessSelectEl = document.getElementById('readiness-select');
        const readinessNotesEl  = document.getElementById('readiness-notes-field');
        const statusSelectEl    = document.getElementById('status-select');
        const currentAddressEl  = document.getElementById('current-address-field');
        const MAX_FAMILY_MEMBERS = parseInt(familyCountInput?.getAttribute('max') ?? '10', 10);

        const serverFamily = @json($serverFamily);
        const oldFamily    = @json(old('family', []));
        const initialFamily = (Array.isArray(oldFamily) && oldFamily.filter(Boolean).length)
            ? oldFamily
            : serverFamily;

        if (!template || !container) return;

        function updateIndices() {
            container.querySelectorAll('tr').forEach((row, idx) => {
                const index = idx + 1;
                row.querySelector('.family-index').textContent = index;

                row.querySelectorAll('[data-field]').forEach(input => {
                    const field = input.dataset.field;
                    input.name = `family[${index}][${field}]`;
                });

                const relationSelect = row.querySelector('[data-field="relation"]');

                if (relationSelect) {
                    if (index === 1) {
                        // الصف الأول = الموظف نفسه دائماً
                        relationSelect.innerHTML = `<option value="self">الموظف نفسه</option>`;
                        relationSelect.value = 'self';
                        relationSelect.disabled = true;
                        relationSelect.classList.add('bg-light');
                        relationSelect.style.cursor = 'not-allowed';

                        // hidden input لأن disabled لا يُرسل
                        let hidden = row.querySelector('input[type="hidden"][name*="[relation]"]');
                        if (!hidden) {
                            hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = relationSelect.name;
                            hidden.value = 'self';
                            relationSelect.insertAdjacentElement('afterend', hidden);
                        } else {
                            hidden.value = 'self';
                        }
                    } else {
                        // باقي الصفوف: صلة القرابة عادية
                        if (relationSelect.disabled) {
                            relationSelect.disabled = false;
                            relationSelect.classList.remove('bg-light');
                            relationSelect.style.cursor = '';
                        }

                        if (!relationSelect.options.length || relationSelect.options[0].value === 'self') {
                            relationSelect.innerHTML = `
                                <option value="">_______</option>
                                <option value="spouse">زوج / زوجة</option>
                                <option value="son">ابن</option>
                                <option value="daughter">ابنة</option>
                                <option value="other">أخرى</option>
                            `;
                        }
                    }
                }
            });
        }

        function toggleRemoveState() {
            const rows = container.querySelectorAll('tr');
            rows.forEach((row, idx) => {
                const btn = row.querySelector('.remove-member-btn');
                if (!btn) return;

                if (idx === 0) {
                    // لا يمكن حذف الموظف نفسه
                    btn.disabled = true;
                    btn.style.opacity = 0.5;
                    btn.style.cursor = 'not-allowed';
                } else {
                    const disabled = rows.length === 1;
                    btn.disabled = disabled;
                    btn.style.opacity = disabled ? 0.5 : 1;
                    btn.style.cursor = disabled ? 'not-allowed' : '';
                }
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
                row.querySelector('[data-field="name"]').value       = prefill.name ?? '';
                row.querySelector('[data-field="relation"]').value   = prefill.relation ?? '';
                row.querySelector('[data-field="birth_date"]').value = prefill.birth_date ?? '';
                row.querySelector('[data-field="is_student"]').value = prefill.is_student ?? '';
            }

            removeButton.addEventListener('click', () => {
                const rows = container.querySelectorAll('tr');
                if (row === rows[0]) return; // لا تحذف الموظف نفسه
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
                const lastRow = container.lastElementChild;
                if (!lastRow) break;
                const rows = container.querySelectorAll('tr');
                if (lastRow === rows[0] && rows.length === 1) break;
                container.removeChild(lastRow);
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

        // تهيئة الصفوف أول مرة
        if (Array.isArray(initialFamily) && initialFamily.filter(Boolean).length) {
            const normalized = initialFamily.filter(Boolean).map(item => ({
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

        // الحقول الشرطية
        const toggleShow = (selectEl, targetEl, value) => {
            const show = selectEl?.value === value;
            targetEl?.classList.toggle('hidden', !show);
        };
        readinessSelectEl?.addEventListener('change', () => toggleShow(readinessSelectEl, readinessNotesEl, 'not_ready'));
        toggleShow(readinessSelectEl, readinessNotesEl, 'not_ready');

        statusSelectEl?.addEventListener('change', () => toggleShow(statusSelectEl, currentAddressEl, 'displaced'));
        toggleShow(statusSelectEl, currentAddressEl, 'displaced');

        // Bootstrap client-side validation
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
</body>
</html>
