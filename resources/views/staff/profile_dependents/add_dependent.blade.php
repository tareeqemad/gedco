<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استمارة البيانات الشخصية</title>
    <style>
        @font-face {
            font-family: 'Cairo';
            font-style: normal;
            font-weight: 400;
            src: url('{{ asset('assets/fonts/cairo/Cairo-Regular.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Cairo';
            font-style: normal;
            font-weight: 500;
            src: url('{{ asset('assets/fonts/cairo/Cairo-Medium.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Cairo';
            font-style: normal;
            font-weight: 600;
            src: url('{{ asset('assets/fonts/cairo/Cairo-SemiBold.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Cairo';
            font-style: normal;
            font-weight: 700;
            src: url('{{ asset('assets/fonts/cairo/Cairo-Bold.ttf') }}') format('truetype');
        }

        :root {
            --surface: #fff7f2;
            --surface-alt: #fff1e6;
            --border: #f1b08d;
            --accent: #ef7c4c;
            --accent-dark: #c65a28;
            --text: #2f2b28;
            --muted: #8c6f61;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Cairo", Helvetica, Arial, sans-serif;
            background: #ffffff;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 3rem 1rem;
        }
        .form-shell {
            width: min(1080px, 100%);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            border: 1px solid rgba(239, 124, 76, 0.2);
            box-shadow: 0 20px 50px rgba(239, 124, 76, 0.15);
            overflow: hidden;
        }
        .form-header {
            padding: 2.5rem 3rem 1.5rem;
            background: linear-gradient(135deg, rgba(239, 124, 76, 0.15), rgba(239, 124, 76, 0.05));
            border-bottom: 1px solid rgba(239, 124, 76, 0.15);
            text-align: center;
        }
        .form-header h1 {
            margin: 0;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            color: var(--accent-dark);
            letter-spacing: 1px;
        }
        .form-section {
            padding: 2.25rem 3rem;
            border-bottom: 1px solid rgba(239, 124, 76, 0.08);
        }
        .form-section:last-of-type { border-bottom: none; }
        .section-title {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            background: rgba(239, 124, 76, 0.1);
            color: var(--accent-dark);
            padding: .45rem 1.1rem;
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: .5px;
            margin-bottom: 1.75rem;
            font-size: 1rem;
        }
        .grid { display: grid; gap: 1rem 1.5rem; }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }

        label.field { display: flex; flex-direction: column; gap: .45rem; font-weight: 600; color: var(--muted); }
        label.field span { font-size: .85rem; }

        input, select, textarea {
            border: 1px solid rgba(239, 124, 76, 0.2);
            border-radius: 14px;
            padding: .85rem 1rem;
            font-size: .95rem;
            background: rgba(255, 255, 255, 0.9);
            transition: border-color .2s ease, box-shadow .2s ease;
            font-family: inherit; color: inherit;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(239, 124, 76, 0.18);
        }
        textarea { min-height: 90px; resize: vertical; }

        .family-table {
            width: 100%; border-collapse: collapse;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 18px; overflow: hidden;
            border: 1px solid rgba(239, 124, 76, 0.18);
        }
        .family-table thead th {
            background: var(--surface-alt);
            padding: .85rem; font-weight: 700; font-size: .9rem; color: var(--accent-dark);
        }
        .family-table td { padding: .7rem; border-bottom: 1px solid rgba(239, 124, 76, 0.12); }
        .family-table tr:last-child td { border-bottom: none; }
        .family-table input, .family-table select { width: 100%; padding: .7rem .75rem; }

        .inline-inputs {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem;
        }
        .add-member-btn {
            margin-top: 1.5rem; display: inline-flex; align-items: center; gap: .6rem;
            border: 1px dashed rgba(239, 124, 76, 0.45); padding: .6rem 1.4rem; border-radius: 999px;
            color: var(--accent-dark); background: rgba(239, 124, 76, 0.08); cursor: pointer; font-weight: 700;
            transition: all .2s ease;
        }
        .add-member-btn:hover { background: rgba(239, 124, 76, 0.16); border-style: solid; }
        .remove-member-btn {
            border: none; background: rgba(239, 124, 76, 0.1); color: var(--accent-dark);
            padding: .45rem .8rem; border-radius: 12px; cursor: pointer; font-size: .85rem; transition: all .2s ease; white-space: nowrap;
        }
        .remove-member-btn:hover { background: rgba(239, 124, 76, 0.2); }
        .hidden { display: none !important; }

        .submit-row { padding: 2.25rem 3rem 3rem; }
        .submit-row button {
            background: linear-gradient(135deg, #ef7c4c, #f49a6a);
            border: none; color: #fff; padding: .9rem 2.75rem; border-radius: 18px;
            font-size: 1rem; font-weight: 700; cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .submit-row button:hover { transform: translateY(-2px); box-shadow: 0 12px 20px rgba(239, 124, 76, 0.25); }

        /* Alerts & status banners */
        .alert { border-radius: 14px; padding: .9rem 1.1rem; margin: 1rem 3rem; font-weight: 600; }
        .alert-success { background: #e9f9ee; border: 1px solid #b6e1c5; color: #165c2f; }
        .alert-danger  { background: #fdeeee; border: 1px solid #f5b1b1; color: #8a1f1f; }
        .status-card { margin: 1rem 3rem 0; border-radius: 16px; padding: 1rem 1.25rem; border: 1px solid transparent; }
        .status-danger { background:#fff5f5; border-color:#ffc7c7; color:#912f2f; }
        .status-card b{ font-size:1rem }

        @media (max-width: 640px) {
            .form-section, .form-header, .submit-row { padding: 1.75rem 1.5rem; }
            .family-table thead { display: none; }
            .inline-inputs { grid-template-columns: 1fr; }
            .family-table, .family-table tbody, .family-table tr, .family-table td { display: block; width: 100%; }
            .family-table tr {
                margin-bottom: 1rem; border: 1px solid rgba(239, 124, 76, 0.18);
                border-radius: 12px; overflow: hidden; background: rgba(255, 255, 255, 0.9);
            }
            .family-table td { border-bottom: none; padding: .65rem .9rem; }
            .family-table td::before {
                content: attr(data-label); display: block; font-weight: 700; margin-bottom: .35rem; color: var(--accent-dark);
            }
            .remove-member-btn { width: 100%; text-align: center; }
            .submit-row { text-align: center; }
            .submit-row .section-title { justify-content: center; }
        }
    </style>
</head>
<body>
<div class="form-shell">
    <div class="form-header">
        <h1 style="margin-bottom: 0;">إقرار المعلومات الشخصية</h1>
    </div>

    {{-- بانر منع التحديث لو مسجّل مسبقًا --}}
    @if (session('locked'))
        <div class="status-card status-danger">
            <b>لا يمكن التحديث</b><br>
            {{ session('locked_msg') ?? 'أنت مسجّل مسبقًا في النظام بهذه البيانات.' }}
        </div>
    @endif

    {{-- رسائل الأخطاء والنجاح --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:1.25rem">
                @foreach ($errors->all() as $error)
                    <li style="line-height:1.6">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('staff.profile.dependents.store') }}" method="post">
        @csrf

        {{-- نقفل كل المدخلات لو Locked --}}
        <fieldset @if(session('locked')) disabled @endif>

            <section class="form-section">
                <div class="section-title">البيانات الأساسية</div>
                <div class="grid grid-3">
                    <label class="field">
                        <span>الاسم رباعي</span>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name') <small style="color:#c0392b">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>تاريخ الميلاد</span>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}">
                    </label>

                    <label class="field">
                        <span>الرقم الوظيفي</span>
                        <input type="text" name="employee_number" value="{{ old('employee_number') }}" required>
                        @error('employee_number') <small style="color:#c0392b">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>رقم الهوية</span>
                        <input type="text" name="national_id" value="{{ old('national_id') }}">
                    </label>

                    <label class="field">
                        <span>الوظيفة الحالية</span>
                        <input type="text" name="job_title" value="{{ old('job_title') }}">
                    </label>

                    <label class="field">
                        <span>المقر</span>
                        <input type="text" name="location" value="{{ old('location') }}">
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
                            <option value="single"   @selected(old('marital_status')==='single')>أعزب / عزباء</option>
                            <option value="married"  @selected(old('marital_status')==='married')>متزوج / متزوجة</option>
                            <option value="widowed"  @selected(old('marital_status')==='widowed')>أرمل / أرملة</option>
                            <option value="divorced" @selected(old('marital_status')==='divorced')>مطلق / مطلقة</option>
                        </select>
                    </label>

                    <label class="field">
                        <span>عدد أفراد الأسرة حاليًا</span>
                        <input type="number" min="1" max="10" name="family_members_count" id="family-count-input" value="{{ old('family_members_count', 1) }}">
                    </label>
                </div>
            </section>

            <section class="form-section">
                <div class="section-title">بيانات أفراد الأسرة</div>

                <div class="family-table-wrapper">
                    <table class="family-table">
                        <thead>
                        <tr>
                            <th style="width: 50px;">م.</th>
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

                <div class="inline-inputs" style="margin-top: 1.5rem;">
                    <label class="field">
                        <span>هل يوجد إصابات أو معتقلين في أفراد الأسرة؟</span>
                        <select name="has_family_incidents" id="family-incidents-select">
                            <option value="">_______</option>
                            <option value="no"  @selected(old('has_family_incidents')==='no')>لا</option>
                            <option value="yes" @selected(old('has_family_incidents')==='yes')>نعم</option>
                        </select>
                    </label>
                </div>

                <label class="field {{ old('has_family_incidents')==='yes' ? '' : 'hidden' }}" id="family-incidents-notes" style="margin-top: 1rem;">
                    <span>تفاصيل الإصابات أو الاعتقالات</span>
                    <textarea name="family_notes">{{ old('family_notes') }}</textarea>
                </label>
            </section>

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
                            <option value="intact"     @selected(old('house_status')==='intact')>سليم</option>
                            <option value="partial"    @selected(old('house_status')==='partial')>هدم جزئي</option>
                            <option value="demolished" @selected(old('house_status')==='demolished')>هدم كلي</option>
                        </select>
                    </label>
                </div>

                <div class="inline-inputs" style="margin-top: 1.5rem;">
                    <label class="field">
                        <span>الحالة</span>
                        <select name="status" id="status-select">
                            <option value="">_______</option>
                            <option value="resident"  @selected(old('status')==='resident')>مقيم</option>
                            <option value="displaced" @selected(old('status')==='displaced')>نازح</option>
                        </select>
                    </label>

                    <label class="field {{ old('status')==='displaced' ? '' : 'hidden' }}" id="current-address-field">
                        <span>العنوان الحالي بعد النزوح</span>
                        <input type="text" name="current_address" value="{{ old('current_address') }}">
                    </label>

                    <label class="field">
                        <span>حالة السكن</span>
                        <select name="housing_type">
                            <option value="">_______</option>
                            <option value="house"     @selected(old('housing_type')==='house')>منزل</option>
                            <option value="apartment" @selected(old('housing_type')==='apartment')>شقة</option>
                            <option value="tent"      @selected(old('housing_type')==='tent')>خيمة</option>
                            <option value="other"     @selected(old('housing_type')==='other')>أخرى</option>
                        </select>
                    </label>
                </div>
            </section>

            <section class="form-section">
                <div class="section-title">وسائل التواصل</div>
                <div class="inline-inputs">
                    <label class="field">
                        <span>رقم الجوال</span>
                        <input type="tel" name="mobile" value="{{ old('mobile') }}" required>
                        @error('mobile') <small style="color:#c0392b">{{ $message }}</small> @enderror
                    </label>

                    <label class="field">
                        <span>رقم جوال بديل</span>
                        <input type="tel" name="mobile_alt" value="{{ old('mobile_alt') }}">
                    </label>

                    <label class="field">
                        <span>واتس آب</span>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}">
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

            <section class="form-section">
                <div class="section-title">الجاهزية للعودة للعمل</div>
                <div class="inline-inputs">
                    <label class="field">
                        <span>مستوى الجاهزية</span>
                        <select name="readiness" id="readiness-select">
                            <option value="">_______</option>
                            <option value="ready"     @selected(old('readiness')==='ready')>جاهز</option>
                            <option value="not_ready" @selected(old('readiness')==='not_ready')>غير جاهز مع توضيح الأسباب</option>
                        </select>
                    </label>

                    <label class="field {{ old('readiness')==='not_ready' ? '' : 'hidden' }}" id="readiness-notes-field">
                        <span>أسباب عدم الجاهزية</span>
                        <textarea name="readiness_notes">{{ old('readiness_notes') }}</textarea>
                    </label>
                </div>
            </section>

        </fieldset>

        @if(!session('locked'))
            <section class="submit-row">
                <div class="section-title" style="margin-bottom: 1.25rem;">إقرار الموظف</div>
                <p style="color: var(--muted); font-size: .95rem; margin-bottom: 1.75rem;">
                    أقرّ بأن جميع البيانات المذكورة أعلاه صحيحة ومطابقة للواقع، وأتعهد بإبلاغ الإدارة فور حدوث أي تغيير.
                </p>
                <button type="submit">حفظ البيانات</button>
            </section>
        @else
            <div class="submit-row" style="text-align:center">
                <a href="{{ url('/') }}" class="add-member-btn" style="text-decoration:none">العودة</a>
            </div>
        @endif
    </form>
</div>

<script>
    (function () {
        const template = document.getElementById('family-row-template');
        const container = document.getElementById('family-rows');
        const addButton = document.getElementById('add-family-member');
        const familyCountInput = document.getElementById('family-count-input');
        const readinessSelect = document.getElementById('readiness-select');
        const readinessNotesField = document.getElementById('readiness-notes-field');
        const familyIncidentsSelect = document.getElementById('family-incidents-select');
        const familyIncidentsNotes = document.getElementById('family-incidents-notes');
        const statusSelect = document.getElementById('status-select');
        const currentAddressField = document.getElementById('current-address-field');
        const MAX_FAMILY_MEMBERS = parseInt(familyCountInput?.getAttribute('max') ?? '10', 10);

        // بيانات قديمة (لو الصفحة رجعت بسبب تنبيه)
        const oldFamily = @json(old('family', []));
        const oldHasIncidents = @json(old('has_family_incidents'));
        const oldStatus = @json(old('status'));
        const oldReadiness = @json(old('readiness'));

        if (!template || !container) return;

        function updateIndices() {
            const rows = container.querySelectorAll('tr');
            rows.forEach((row, idx) => {
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
                if (btn) {
                    const disabled = rows.length === 1;
                    btn.disabled = disabled;
                    btn.style.opacity = disabled ? 0.5 : 1;
                }
            });
        }

        function updateAddButtonState() {
            if (!addButton) return;
            const count = container.querySelectorAll('tr').length;
            const disabled = count >= MAX_FAMILY_MEMBERS;
            addButton.disabled = disabled;
            addButton.style.opacity = disabled ? 0.5 : 1;
            addButton.setAttribute('aria-disabled', disabled ? 'true' : 'false');
        }

        function createMemberRow(prefill = null) {
            const fragment = template.content.cloneNode(true);
            const row = fragment.querySelector('tr');
            const removeButton = row.querySelector('.remove-member-btn');

            // Prefill values if provided
            if (prefill) {
                const name = row.querySelector('[data-field="name"]');
                const relation = row.querySelector('[data-field="relation"]');
                const birth_date = row.querySelector('[data-field="birth_date"]');
                const is_student = row.querySelector('[data-field="is_student"]');

                if (name) name.value = prefill.name ?? '';
                if (relation) relation.value = prefill.relation ?? '';
                if (birth_date) birth_date.value = prefill.birth_date ?? '';
                if (is_student) is_student.value = prefill.is_student ?? '';
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

            // Clear then rebuild if we are pre-filling
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
                container.removeChild(container.lastElementChild);
                current--;
            }

            updateIndices();
            toggleRemoveState();
            updateAddButtonState();
        }

        if (addButton) {
            addButton.addEventListener('click', () => {
                const nextCount = Math.min(container.querySelectorAll('tr').length + 1, MAX_FAMILY_MEMBERS);
                ensureRowCount(nextCount);
                if (familyCountInput) familyCountInput.value = nextCount;
            });
        }

        if (familyCountInput) {
            familyCountInput.addEventListener('input', () => {
                let desired = parseInt(familyCountInput.value, 10);
                if (!Number.isFinite(desired)) desired = 1;
                desired = Math.max(1, Math.min(MAX_FAMILY_MEMBERS, desired));
                familyCountInput.value = desired;
                ensureRowCount(desired);
            });
        }

        // Initial rows: if old family exists, prefill; else from count input
        if (Array.isArray(oldFamily) && oldFamily.length > 0) {
            // Normalize to zero-based array
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

        // Toggle readiness notes
        if (readinessSelect && readinessNotesField) {
            const textarea = readinessNotesField.querySelector('textarea');
            const toggleReadinessNotes = () => {
                const show = readinessSelect.value === 'not_ready';
                readinessNotesField.classList.toggle('hidden', !show);
            };
            readinessSelect.addEventListener('change', toggleReadinessNotes);
            toggleReadinessNotes();
        }

        // Toggle family incidents notes
        if (familyIncidentsSelect && familyIncidentsNotes) {
            const textarea = familyIncidentsNotes.querySelector('textarea');
            const toggleFamilyNotes = () => {
                const show = familyIncidentsSelect.value === 'yes';
                familyIncidentsNotes.classList.toggle('hidden', !show);
            };
            familyIncidentsSelect.addEventListener('change', toggleFamilyNotes);
            toggleFamilyNotes();
        }

        // Toggle current address if displaced
        if (statusSelect && currentAddressField) {
            const toggleCurrentAddress = () => {
                const show = statusSelect.value === 'displaced';
                currentAddressField.classList.toggle('hidden', !show);
            };
            statusSelect.addEventListener('change', toggleCurrentAddress);
            toggleCurrentAddress();
        }
    })();
</script>
</body>
</html>
