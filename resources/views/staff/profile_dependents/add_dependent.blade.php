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
    $locations       = config('staff_enums.locations',      ['1'=>'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال','4'=>'مقر الوسطى','6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة']);
    $maritalStatus   = config('staff_enums.marital_status', ['single'=>'أعزب/عزباء','married'=>'متزوج/متزوجة','widowed'=>'أرمل/أرملة','divorced'=>'مطلق/مطلقة']);
    $houseStatus     = config('staff_enums.house_status',   ['intact'=>'سليم','partial'=>'هدم جزئي','demolished'=>'هدم كلي']);
    $residentStatus  = config('staff_enums.status',         ['resident'=>'مقيم','displaced'=>'نازح']);
    $housingTypes    = config('staff_enums.housing_type',   ['house'=>'منزل','apartment'=>'شقة','tent'=>'خيمة','other'=>'أخرى']);
    $readinessList   = config('staff_enums.readiness',      ['working'=>'باشر العمل فعلياًً','ready'=>'جاهز للعودة','not_ready'=>'مش جاهز بعد']);
    $relations       = config('staff_enums.relation', [
        'self'    => 'الموظف نفسه',
        'husband' => 'زوج',
        'wife'    => 'زوجة',
        'son'     => 'ابن',
        'daughter'=> 'ابنة',
        'other'   => 'أخرى',
    ]);
@endphp

<div class="form-shell">
    <div class="form-header">
        <h1>إقرار المعلومات الشخصية</h1>
    </div>

    <div class="alert alert-warning" style="margin: 1rem 2rem 0;">
        يرجى تعبئة جميع الحقول الإلزامية المعلّمة بـ (<span style="color:#e63946">*</span>)
    </div>

    @if (session('locked'))
        <div class="alert alert-danger">
            <div class="fw-bold">لا يمكن التحديث</div>
            <div>{{ session('locked_msg') ?? 'أنت مسجّل مسبقًا في النظام بهذه البيانات.' }}</div>
        </div>
    @endif

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

        <fieldset @if(session('locked')) disabled @endif>

            {{-- البيانات الأساسية --}}
            <section class="form-section">
                <div class="section-title">البيانات الأساسية</div>
                <div class="grid grid-3">
                    <label class="field required">
                        <span>رقم الهوية</span>
                        <input type="text" name="national_id" value="{{ old('national_id') }}"
                               required maxlength="9" pattern="\d{9}" class="@error('national_id') is-invalid @enderror">
                        @error('national_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field required">
                        <span>الاسم رباعي</span>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               class="@error('full_name') is-invalid @enderror">
                        @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>تاريخ الميلاد</span>
                        <input type="text" name="birth_date" value="{{ old('birth_date') }}" inputmode="numeric" pattern="\d{4}-\d{2}-\d{2}" autocomplete="off">
                    </label>

                    <label class="field required">
                        <span>الرقم الوظيفي</span>
                        <input type="text" name="employee_number" value="{{ old('employee_number') }}" required
                               maxlength="4" pattern="\d{1,4}" class="@error('employee_number') is-invalid @enderror">
                        @error('employee_number') <small class="text-danger">{{ $message }}</small> @enderror
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

                    <label class="field required">
                        <span>الحالة الاجتماعية</span>
                        <select name="marital_status" required class="@error('marital_status') is-invalid @enderror">
                            <option value="">_______</option>
                            @foreach($maritalStatus as $val => $label)
                                <option value="{{ $val }}" @selected(old('marital_status') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('marital_status') <small class="text-danger">{{ $message }}</small> @enderror
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

        </fieldset>

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
        const maritalSelect = document.querySelector('select[name="marital_status"]');

        const oldFamily = @json(old('family', []));
        const fullNameInput   = document.querySelector('input[name="full_name"]');
        const mainBirthInput  = document.querySelector('input[name="birth_date"]');

        if (!template || !container) { return; }

        function customizeRelationOptions(row, index) {
            const select = row.querySelector('[data-field="relation"]');
            if (!select) return;

            const currentVal = select.value;
            const marital = maritalSelect?.value || '';

            select.innerHTML = '';

            if (index === 1) {
                select.innerHTML = `<option value="self">الموظف نفسه</option>`;
                select.value = 'self';
                select.disabled = true;
                select.classList.add('bg-light');
                select.style.cursor = 'not-allowed';
            } else {
                select.disabled = false;
                select.classList.remove('bg-light');
                select.style.cursor = '';

                if (marital === 'married' && index === 2) {
                    select.innerHTML = `
                    <option value="">_______</option>
                    <option value="husband">زوج</option>
                    <option value="wife">زوجة</option>
                `;
                } else {
                    select.innerHTML = `
                    <option value="">_______</option>
                    <option value="son">ابن</option>
                    <option value="daughter">ابنة</option>
                    <option value="other">أخرى</option>
                `;
                }

                if (currentVal && Array.from(select.options).some(o => o.value === currentVal)) {
                    select.value = currentVal;
                }
            }
        }

        function updateIndices() {
            container.querySelectorAll('tr').forEach((row, idx) => {
                const index = idx + 1;
                row.querySelector('.family-index').textContent = index;
                row.querySelectorAll('[data-field]').forEach(input => {
                    const field = input.dataset.field;
                    input.name = `family[${index}][${field}]`;
                });
                customizeRelationOptions(row, index);
            });
        }

        function toggleRemoveState() {
            const rows = container.querySelectorAll('tr');
            rows.forEach((row, idx) => {
                const btn = row.querySelector('.remove-member-btn');
                if (!btn) return;
                if (idx === 0) {
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

        function bindHeadRow() {
            const headRow = container.querySelector('tr');
            if (!headRow) return;

            const nameInput  = headRow.querySelector('[data-field="name"]');
            const birthInput = headRow.querySelector('[data-field="birth_date"]');
            const removeBtn  = headRow.querySelector('.remove-member-btn');

            if (removeBtn) {
                removeBtn.disabled = true;
                removeBtn.style.opacity = 0.5;
                removeBtn.style.cursor = 'not-allowed';
            }

            if (fullNameInput && nameInput) {
                if (!nameInput.value) {
                    nameInput.value = fullNameInput.value || '';
                }
                fullNameInput.addEventListener('input', () => {
                    nameInput.value = fullNameInput.value || '';
                });
            }

            if (mainBirthInput && birthInput) {
                if (!birthInput.value) {
                    birthInput.value = mainBirthInput.value || '';
                }
                mainBirthInput.addEventListener('change', () => {
                    birthInput.value = mainBirthInput.value || '';
                });
            }
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
                if (row === rows[0]) return;
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
                const row = createMemberRow(prefill);
                container.appendChild(row);
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
            bindHeadRow();
        }

        addButton?.addEventListener('click', () => {
            const nextCount = Math.min(container.querySelectorAll('tr').length + 1, MAX_FAMILY_MEMBERS);
            ensureRowCount(nextCount);
            if (familyCountInput) familyCountInput.value = nextCount;
        });

        familyCountInput?.addEventListener('input', () => {
            let desired = parseInt(familyCountInput.value, 10);
            if (!Number.isFinite(desired)) desired = 1;
            desired = Math.max(1, Math.min(MAX_FAMILY_MEMBERS, desired));
            familyCountInput.value = desired;
            ensureRowCount(desired);
        });

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

        maritalSelect?.addEventListener('change', () => {
            if (maritalSelect.value === 'married') {
                const currentRows = container.querySelectorAll('tr').length;
                const desired = Math.max(currentRows, 2);
                ensureRowCount(desired);
                if (familyCountInput) familyCountInput.value = desired;
            } else {
                updateIndices();
            }
        });

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

        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });

        // (تم إلغاء عرض النص الإنجليزي أسفل الحقل وإرجاعه كما كان)

        // جلب تلقائي عبر رقم الهوية
        const nationalIdInput = document.querySelector('input[name="national_id"]');
        const fullNameEl      = document.querySelector('input[name="full_name"]');
        const birthDateEl     = document.querySelector('input[name="birth_date"]');
        const maritalEl       = document.querySelector('select[name="marital_status"]');
        const jobTitleEl      = document.querySelector('input[name="job_title"]');
        const locationEl      = document.querySelector('select[name="location"]');
        const employeeNoEl    = document.querySelector('input[name="employee_number"]');

        function setIfEmpty(input, value) {
            if (!input) return;
            if (!input.value) { input.value = value || ''; }
        }

        // تحويل أرقام عربية/فارسية إلى إنجليزية
        function toEnglishDigits(str) {
            if (!str) return '';
            return String(str)
                .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d))
                .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
        }

        // صيغ التاريخ:
        // - ISO: YYYY-MM-DD (لاستخدام input[type=date])
        // - DISPLAY: MM/DD/YYYY (لعرض النص)
        function toISODate(v) {
            const s = toEnglishDigits(v || '').trim();
            if (!s) return '';
            // نمط ISO
            let m = s.match(/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/);
            if (m) {
                const y = m[1];
                const mo = m[2].padStart(2, '0');
                const d  = m[3].padStart(2, '0');
                return `${y}-${mo}-${d}`;
            }
            // نمط MM/DD/YYYY
            m = s.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
            if (m) {
                const mo = m[1].padStart(2, '0');
                const d  = m[2].padStart(2, '0');
                const y  = m[3];
                return `${y}-${mo}-${d}`;
            }
            // اقتطاع مبسط إن لزم
            const cleaned = s.replace(/[^\d]/g, '');
            if (cleaned.length === 8) {
                // حاول تفسيره كـ YYYYMMDD
                const y = cleaned.slice(0,4);
                const mo = cleaned.slice(4,6);
                const d  = cleaned.slice(6,8);
                return `${y}-${mo}-${d}`;
            }
            return '';
        }
        function toDisplayDate(iso) {
            const s = toISODate(iso);
            if (!s) return '';
            const [y, mo, d] = s.split('-');
            return `${mo}/${d}/${y}`;
        }

        // قفل الحقول غير القابلة للتعديل من المستخدم
        function lockImmutableFields() {
            if (fullNameEl) {
                fullNameEl.readOnly = true;
                fullNameEl.setAttribute('aria-readonly', 'true');
            }
            if (birthDateEl) {
                birthDateEl.readOnly = true;
                birthDateEl.setAttribute('aria-readonly', 'true');
            }
            if (employeeNoEl) {
                employeeNoEl.readOnly = true;
                employeeNoEl.setAttribute('aria-readonly', 'true');
            }
            if (jobTitleEl) {
                jobTitleEl.readOnly = true;
                jobTitleEl.setAttribute('aria-readonly', 'true');
            }
            if (locationEl) {
                // mirror value to hidden input to ensure submission
                locationEl.disabled = true;
                let mirror = document.querySelector('input[type="hidden"][name="location"]');
                if (!mirror) {
                    mirror = document.createElement('input');
                    mirror.type = 'hidden';
                    mirror.name = 'location';
                    mirror.value = locationEl.value || '';
                    locationEl.insertAdjacentElement('afterend', mirror);
                }
            }
        }
        lockImmutableFields();

        if (mainBirthInput) {
            // عند التركيز: استخدم منتقي التاريخ
            mainBirthInput.addEventListener('focus', () => {
                if (mainBirthInput.readOnly) return;
                const val = toISODate(mainBirthInput.value);
                mainBirthInput.type = 'date';
                if (val) mainBirthInput.value = val;
            });
            // عند الخروج: اعرض كنص ثابت بأرقام إنجليزية
            mainBirthInput.addEventListener('blur', () => {
                const iso = toISODate(mainBirthInput.value);
                mainBirthInput.type = 'text';
                mainBirthInput.value = toDisplayDate(iso);
            });
            // تطبيع فوري للقيمة الحالية عند التحميل
            mainBirthInput.value = toDisplayDate(toISODate(mainBirthInput.value));
        }

        async function fetchEmployeeById(id) {
            // محاولة مباشرة (قد تفشل بسبب CORS)
            try {
                const direct = await fetch(`https://eservices.gedco.ps/employees/search/${id}`, { method: 'GET' });
                if (direct.ok) {
                    const json = await direct.json();
                    const row = (json?.data_rows || [])[0] || null;
                    if (row) {
                        return {
                            full_name: row.NAME || '',
                            birth_date: toEnglishDigits((row.BIRTH_DATE || '').slice(0,10)),
                            marital_status_text: row.STATUS_NAME || '',
                            job_title: row.W_NO_ADMIN_NAME || '',
                            branch: row.BRAN_NAME || '',
                            employee_number: row.NO || ''
                        };
                    }
                }
            } catch (_) {}

            // بروكسي عبر السيرفر
            const resp = await fetch(`{{ route('staff.profile.lookup') }}?id=${encodeURIComponent(id)}`, { method: 'GET', headers: { 'Accept': 'application/json' } });
            if (!resp.ok) throw new Error('lookup_failed');
            const payload = await resp.json();
            if (!payload?.ok) throw new Error(payload?.message || 'not_ok');
            return {
                full_name: payload.data?.full_name || '',
                birth_date: toEnglishDigits(payload.data?.birth_date || ''),
                marital_status: payload.data?.marital_status || '',
                job_title: payload.data?.job_title || '',
                location: payload.data?.location || '',
                employee_number: payload.data?.employee_number || ''
            };
        }

        function mapMaritalToValue(text) {
            if (!text) return '';
            if (text.includes('أعزب')) return 'single';
            if (text.includes('متزوج')) return 'married';
            if (text.includes('أرمل')) return 'widowed';
            if (text.includes('مطلق')) return 'divorced';
            return '';
        }

        function mapBranchToLocation(branch) {
            if (!branch) return '';
            const b = branch.toLowerCase();
            if (b.includes('الرئيس')) return '1';
            if (b.includes('غزة')) return '2';
            if (b.includes('الشمال')) return '3';
            if (b.includes('الوسطى')) return '4';
            if (b.includes('خانيونس')) return '6';
            if (b.includes('رفح')) return '7';
            if (b.includes('الصيانة')) return '8';
            return '';
        }

        let lookupInFlight = false;
        let lastFetchedId = '';
        let debounceTimer = null;

        function sanitizeId(value) {
            return (value || '').toString().replace(/\D/g, '');
        }

        // تفريغ القيم المجلوبة عند فشل/عدم العثور
        function clearFetchedFields() {
            if (fullNameEl)   fullNameEl.value = '';
            if (birthDateEl) {
                birthDateEl.type = 'text';
                birthDateEl.value = '';
            }
            if (employeeNoEl) employeeNoEl.value = '';
            if (jobTitleEl)   jobTitleEl.value = '';
            if (maritalEl)    maritalEl.value = '';
            if (locationEl)   locationEl.value = '';
            const mirror = document.querySelector('input[type="hidden"][name="location"]');
            if (mirror) mirror.value = '';
        }

        function fillFromData(data) {
            if (!data) return;
            if (birthDateEl) {
                const v = toDisplayDate(toISODate(data.birth_date || ''));
                const wasFocused = document.activeElement === birthDateEl;
                if (!wasFocused) birthDateEl.type = 'text';
                birthDateEl.value = v;
            }
            if (fullNameEl)    fullNameEl.value    = data.full_name || '';
            if (employeeNoEl)  employeeNoEl.value  = (data.employee_number || '').toString();
            if (jobTitleEl)    jobTitleEl.value    = data.job_title || '';
            if (maritalEl) {
                const val = data.marital_status || mapMaritalToValue(data.marital_status_text || '');
                if (val && Array.from(maritalEl.options).some(o => o.value === val)) {
                    maritalEl.value = val;
                }
            }
            if (locationEl) {
                const loc = data.location || mapBranchToLocation(data.branch || '');
                if (loc && Array.from(locationEl.options).some(o => o.value === String(loc))) {
                    locationEl.value = String(loc);
                    const mirror = document.querySelector('input[type="hidden"][name="location"]');
                    if (mirror) mirror.value = String(loc);
                }
            }
        }

        async function fetchAndFillByCurrentId() {
            const id = sanitizeId(nationalIdInput?.value || '');
            if (lookupInFlight) return;
            if (id.length !== 9) {
                lastFetchedId = '';
                clearFetchedFields();
                return;
            }
            if (id === lastFetchedId) return;
            lookupInFlight = true;
            nationalIdInput.disabled = true;
            nationalIdInput.style.opacity = 0.7;
            try {
                const data = await fetchEmployeeById(id);
                fillFromData(data);
                lastFetchedId = id;
            } catch (e) {
                // في حال الخطأ/عدم العثور امسح القيم المعروضة
                clearFetchedFields();
                lastFetchedId = '';
            } finally {
                nationalIdInput.disabled = false;
                nationalIdInput.style.opacity = 1;
                lookupInFlight = false;
            }
        }

        function debounceFetch() {
            if (!nationalIdInput) return;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchAndFillByCurrentId, 500);
        }

        nationalIdInput?.addEventListener('input', debounceFetch);
        nationalIdInput?.addEventListener('change', fetchAndFillByCurrentId);
        nationalIdInput?.addEventListener('blur', fetchAndFillByCurrentId);
    })();
</script>
</body>
</html>
