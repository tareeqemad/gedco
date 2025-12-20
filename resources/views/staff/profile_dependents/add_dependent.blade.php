<!DOCTYPE html>
<html lang="ar" dir="{{ $direction ?? 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استمارة البيانات الشخصية</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icons/icon.ico') }}">
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap' . (($direction ?? 'rtl') === 'rtl' ? '.rtl' : '') . '.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site/css/staff-common.css') }}">
    <style>
        .field .wa-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 6px;
            direction: ltr !important;
            background: #fff;
            border: 1px solid #f0b9a6;
            border-radius: 12px;
            padding: 0 10px;
            height: 48px;
            box-sizing: border-box;
        }

        .wa-plus {
            font-size: 16px;
            font-weight: bold;
        }

        .wa-prefix {
            width: 80px;
            border: none;
            background: transparent;
            font-size: 15px;
            outline: none;
        }

        .wa-number {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 15px;
            outline: none;
        }
    </style>
</head>
<body>

@php
    $locations       = config('staff_enums.locations');
    $maritalStatus   = config('staff_enums.marital_status');
    $houseStatus     = config('staff_enums.house_status');
    $residentStatus  = config('staff_enums.status');
    $housingTypes    = config('staff_enums.housing_type');
    $readinessList   = config('staff_enums.readiness');
    $relations       = config('staff_enums.relation');
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
                        <input type="text" name="national_id" id="national_id_input" value="{{ old('national_id') }}"
                               required maxlength="9" pattern="\d{9}" inputmode="numeric"
                               class="@error('national_id') is-invalid @enderror">
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
                        <input type="text" name="birth_date" value="{{ old('birth_date') }}"
                               inputmode="numeric" pattern="\d{4}-\d{2}-\d{2}" autocomplete="off">
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
                        <input type="number" min="1" max="15" name="family_members_count" id="family-count-input"
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

                <label class="field {{ old('has_family_incidents')==='yes' ? 'show' : 'hidden' }}"
                       id="family-incidents-notes" style="margin-top: 1rem;">
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

                    <label class="field {{ old('status')==='displaced' ? 'show' : 'hidden' }}"
                           id="current-address-field">
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
                        <input type="tel" name="mobile" value="{{ old('mobile') }}" required maxlength="10"
                               pattern="\d{8,10}" class="@error('mobile') is-invalid @enderror">
                        @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>رقم جوال بديل</span>
                        <input type="tel" name="mobile_alt" value="{{ old('mobile_alt') }}" maxlength="10"
                               pattern="\d{8,10}">
                    </label>

                    <label class="field">
                        <span>واتس آب</span>

                        <div class="wa-wrapper">
                            <span class="wa-plus">+</span>

                            <select name="whatsapp_prefix" class="wa-prefix">
                                <option value="970" @selected(old('whatsapp_prefix')=='970')>970</option>
                                <option value="972" @selected(old('whatsapp_prefix')=='972')>972</option>
                            </select>

                            <input type="tel"
                                   name="whatsapp"
                                   placeholder="59xxxxxxx"
                                   value="{{ old('whatsapp') }}"
                                   maxlength="10"
                                   class="wa-number">
                        </div>
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

                    <label class="field {{ old('readiness')==='not_ready' ? 'show' : 'hidden' }}"
                           id="readiness-notes-field">
                        <span>أسباب عدم الجاهزية</span>
                        <textarea name="readiness_notes">{{ old('readiness_notes') }}</textarea>
                    </label>
                </div>
            </section>

        </fieldset>

        @if(!session('locked'))
            <section class="submit-row">
                <div class="section-title" style="margin-bottom: 1.1rem;">إقرار الموظف</div>
                <p class="text-muted mb-3">
                    أقرّ بأن جميع البيانات المذكورة أعلاه صحيحة ومطابقة للواقع، وأتعهد بإبلاغ الإدارة فور حدوث أي تغيير.
                </p>
                <button type="submit">حفظ البيانات</button>
            </section>
        @else
            <div class="submit-row">
                <a href="{{ url('/') }}" class="add-member-btn" style="text-decoration:none">العودة</a>
            </div>
        @endif
    </form>
</div>

<link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    (() => {
        const template = document.getElementById('family-row-template');
        const container = document.getElementById('family-rows');
        const addButton = document.getElementById('add-family-member');
        const familyCountInput = document.getElementById('family-count-input');
        const MAX_FAMILY_MEMBERS = parseInt(familyCountInput?.getAttribute('max') ?? '10', 10);

        const readinessSelectEl        = document.getElementById('readiness-select');
        const readinessNotesEl         = document.getElementById('readiness-notes-field');
        const familyIncidentsSelectEl  = document.getElementById('family-incidents-select');
        const familyIncidentsNotesEl   = document.getElementById('family-incidents-notes');
        const statusSelectEl           = document.getElementById('status-select');
        const currentAddressEl         = document.getElementById('current-address-field');

        const oldFamily = @json(old('family', []));

        if (!template || !container) { return; }

        // ===================== family rows logic =====================
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
            rows.forEach((row) => {
                const btn = row.querySelector('.remove-member-btn');
                if (!btn) return;
                const disabled = rows.length === 1;
                btn.disabled = disabled;
                btn.style.opacity = disabled ? 0.5 : 1;
                btn.style.cursor = disabled ? 'not-allowed' : '';
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
                if (rows.length <= 1) return;
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
                if (current === 1) break;
                container.removeChild(lastRow);
                current--;
            }

            updateIndices();
            toggleRemoveState();
            updateAddButtonState();
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

        // ===================== جلب تلقائي عبر رقم الهوية =====================

        const nationalIdInput = document.querySelector('input[name="national_id"]');
        const fullNameEl      = document.querySelector('input[name="full_name"]');
        const birthDateEl     = document.querySelector('input[name="birth_date"]');
        const maritalEl       = document.querySelector('select[name="marital_status"]');
        const jobTitleEl      = document.querySelector('input[name="job_title"]');
        const locationEl      = document.querySelector('select[name="location"]');
        const departmentEl    = document.querySelector('input[name="department"]');
        const employeeNoEl    = document.querySelector('input[name="employee_number"]');

        function toEnglishDigits(str) {
            if (!str) return '';
            return String(str)
                .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d))
                .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
        }

        function toISODate(v) {
            const s = toEnglishDigits(v || '').trim();
            if (!s) return '';

            let m = s.match(/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/);
            if (m) return `${m[1]}-${m[2].padStart(2,'0')}-${m[3].padStart(2,'0')}`;

            m = s.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
            if (m) return `${m[3]}-${m[1].padStart(2,'0')}-${m[2].padStart(2,'0')}`;

            const cleaned = s.replace(/[^\d]/g, '');
            if (cleaned.length === 8) {
                return `${cleaned.slice(0,4)}-${cleaned.slice(4,6)}-${cleaned.slice(6,8)}`;
            }

            return '';
        }

        function toDisplayDate(iso) {
            const s = toISODate(iso);
            if (!s) return '';
            const [y, mo, d] = s.split('-');
            return `${mo}/${d}/${y}`;
        }

        // تعامل أفضل مع حقل تاريخ الميلاد (لما يكتب يدوي)
        const mainBirthInput  = document.querySelector('input[name="birth_date"]');
        if (mainBirthInput) {
            mainBirthInput.addEventListener('focus', () => {
                const val = toISODate(mainBirthInput.value);
                mainBirthInput.type = 'date';
                if (val) mainBirthInput.value = val;
            });
            mainBirthInput.addEventListener('blur', () => {
                const iso = toISODate(mainBirthInput.value);
                mainBirthInput.type = 'text';
                mainBirthInput.value = toDisplayDate(iso);
            });
            mainBirthInput.value = toDisplayDate(toISODate(mainBirthInput.value));
        }

        function lockImmutableFields() {
            const fields = [fullNameEl, birthDateEl, employeeNoEl, jobTitleEl, departmentEl];
            fields.forEach(el => {
                if (el) {
                    el.readOnly = true;
                    el.setAttribute('aria-readonly', 'true');
                }
            });

            if (locationEl) {
                locationEl.disabled = true;
                let mirror = document.querySelector('input[type="hidden"][name="location"]');
                if (!mirror) {
                    mirror = document.createElement('input');
                    mirror.type = 'hidden';
                    mirror.name = 'location';
                    locationEl.insertAdjacentElement('afterend', mirror);
                }
                mirror.value = locationEl.value || '';
            }
        }

        function clearFetchedFields() {
            if (fullNameEl)    fullNameEl.value = '';
            if (birthDateEl)   birthDateEl.value = '';
            if (employeeNoEl)  employeeNoEl.value = '';
            if (jobTitleEl)    jobTitleEl.value = '';
            if (maritalEl)     maritalEl.value = '';
            if (departmentEl)  departmentEl.value = '';
            if (locationEl)    locationEl.value = '';

            const mirror = document.querySelector('input[type="hidden"][name="location"]');
            if (mirror) mirror.value = '';
        }

        function fillFromData(data) {
            if (!data) return;

            if (birthDateEl) {
                const v = toDisplayDate(toISODate(data.birth_date));
                birthDateEl.type = 'text';
                birthDateEl.value = v;
            }

            if (fullNameEl)    fullNameEl.value    = data.full_name || '';
            if (employeeNoEl)  employeeNoEl.value  = data.employee_number || '';
            if (jobTitleEl)    jobTitleEl.value    = data.job_title || '';
            if (departmentEl)  departmentEl.value  = data.department || '';

            if (maritalEl && data.marital_status) {
                if (Array.from(maritalEl.options).some(o => o.value === data.marital_status)) {
                    maritalEl.value = data.marital_status;
                }
            }

            if (locationEl && data.location) {
                if (Array.from(locationEl.options).some(o => o.value === String(data.location))) {
                    locationEl.value = String(data.location);
                    const mirror = document.querySelector('input[type="hidden"][name="location"]');
                    if (mirror) mirror.value = String(data.location);
                }
            }

            lockImmutableFields();
        }

        async function fetchEmployeeById(id) {
            const baseUrl = @json($lookupUrl); // مثال: "http://127.0.0.1:8000/staff/profile/lookup"
            if (!baseUrl) {
                throw new Error('lookup_url_missing');
            }

            const url = baseUrl + '?id=' + encodeURIComponent(id);

            const resp = await fetch(url, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            if (!resp.ok) {
                throw new Error('lookup_failed_' + resp.status);
            }

            const payload = await resp.json();
            if (!payload?.ok || !payload.data) {
                throw new Error(payload?.message || 'not_ok');
            }

            return payload.data;
        }

        let lookupInFlight = false;
        let lastFetchedId  = '';
        let debounceTimer  = null;

        function sanitizeId(value) {
            return (value || '').replace(/\D/g, '');
        }

        async function fetchAndFillByCurrentId() {
            const id = sanitizeId(nationalIdInput?.value || '');

            if (lookupInFlight) return;

            if (id.length !== 9) {
                clearFetchedFields();
                lastFetchedId = '';
                return;
            }

            if (id === lastFetchedId) return;

            lookupInFlight = true;
            nationalIdInput.disabled = true;
            nationalIdInput.style.opacity = 0.7;

            try {
                const data = await fetchEmployeeById(id);
                if (data && (data.full_name || data.employee_number)) {
                    fillFromData(data);
                    lastFetchedId = id;
                } else {
                    clearFetchedFields();
                    lastFetchedId = '';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ في رقم الهوية',
                            text: 'لا توجد بيانات مطابقة لهذا الرقم',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#ef7c4c'
                        });
                    } else {
                        alert('لا توجد بيانات مطابقة لهذا الرقم.');
                    }
                }
            } catch (e) {
                clearFetchedFields();
                lastFetchedId = '';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ في رقم الهوية',
                        text: 'تعذّر جلب بيانات الموظف، يرجى المحاولة لاحقًا',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#ef7c4c'
                    });
                } else {
                    alert('تعذّر جلب بيانات الموظف، يرجى المحاولة لاحقًا.');
                }
            } finally {
                nationalIdInput.disabled = false;
                nationalIdInput.style.opacity = 1;
                lookupInFlight = false;
            }
        }

        function debounceFetch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchAndFillByCurrentId, 500);
        }

        if (nationalIdInput) {
            nationalIdInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 9);
                debounceFetch();
            });
            nationalIdInput.addEventListener('change', fetchAndFillByCurrentId);
            nationalIdInput.addEventListener('blur', fetchAndFillByCurrentId);
        }

        if (nationalIdInput && nationalIdInput.value && nationalIdInput.value.length === 9) {
            fetchAndFillByCurrentId();
        }
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
