<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البيانات الشخصية</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site/css/staff-common.css') }}">
    <style>
        .phone-input {
            display: flex;
            align-items: center;
            gap: 4px;
            direction: ltr;      /* عشان يطلع + 970 [input] بالترتيب الصحيح */
        }

        .phone-plus {
            font-weight: 600;
            padding: 0 2px;
        }

        .phone-prefix {
            max-width: 70px;
        }

        .phone-number {
            flex: 1;
        }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;

    $LOC     = config('staff_enums.locations');
    $HOUSE   = config('staff_enums.house_status');
    $HOUSING = config('staff_enums.housing_type');
    $MARITAL = config('staff_enums.marital_status');
    $relations = config('staff_enums.relation'); // spouse / son / daughter / other
    $readinessList = config('staff_enums.readiness');

    $serverFamily = ($profile->dependents ?? collect())->map(function($d){
        $birth = $d->birth_date;
        if ($birth instanceof Carbon) {
            $birth = $birth->toDateString();
        } elseif (is_string($birth)) {
            try { $birth = Carbon::parse($birth)->toDateString(); } catch (\Throwable $e) { $birth = null; }
        }

        return [
            'name'       => $d->name,
            'relation'   => $d->relation, // spouse / son / daughter / other
            'birth_date' => $birth,
            'is_student' => $d->is_student ? 'yes' : 'no',
        ];
    })->values();
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

    <div class="alert alert-secondary" style="margin:1rem 2rem 0;">
        لديك <b>{{ $profile->edits_remaining }}</b> محاولة تعديل متبقية.
        @if($profile->edits_remaining == 1)
            <span class="text-danger">⚠️ هذه آخر محاولة!</span>
        @endif
    </div>

    <form action="{{ route('staff.profile.update', ['profile' => $profile->getKey()]) }}"
          method="post" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        {{-- البيانات الأساسية --}}
        <section class="form-section">
            <div class="section-title">البيانات الأساسية</div>
            <div class="grid grid-3">
                <label class="field">
                    <span>الاسم رباعي <span class="req-star">*</span></span>
                    <input type="text" name="full_name"
                           value="{{ old('full_name',$profile->full_name) }}" required
                           class="@error('full_name') is-invalid @enderror">
                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>تاريخ الميلاد</span>
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date',
                               $profile->birth_date ? \Carbon\Carbon::parse($profile->birth_date)->format('Y-m-d') : ''
                           ) }}">
                </label>

                <label class="field">
                    <span>الرقم الوظيفي <span class="req-star">*</span></span>
                    <input type="text" name="employee_number"
                           value="{{ old('employee_number',$profile->employee_number) }}" required
                           class="@error('employee_number') is-invalid @enderror">
                    @error('employee_number') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>رقم الهوية <span class="req-star">*</span></span>
                    <input type="text" name="national_id"
                           value="{{ old('national_id',$profile->national_id) }}" required
                           class="@error('national_id') is-invalid @enderror">
                    @error('national_id') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>الوظيفة الحالية</span>
                    <input type="text" name="job_title"
                           value="{{ old('job_title',$profile->job_title) }}">
                </label>

                <label class="field">
                    <span>المقر <span class="req-star">*</span></span>
                    <select name="location" class="@error('location') is-invalid @enderror" required>
                        <option value="">_________</option>
                        @foreach($LOC as $key=>$label)
                            <option value="{{ $key }}"
                                @selected(old('location', (string)$profile->location)===(string)$key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>الإدارة</span>
                    <input type="text" name="department"
                           value="{{ old('department',$profile->department) }}">
                </label>

                <label class="field">
                    <span>الدائرة</span>
                    <input type="text" name="directorate"
                           value="{{ old('directorate',$profile->directorate) }}">
                </label>

                <label class="field">
                    <span>القسم</span>
                    <input type="text" name="section"
                           value="{{ old('section',$profile->section) }}">
                </label>

                <label class="field">
                    <span>الحالة الاجتماعية</span>
                    <select name="marital_status">
                        <option value="">_______</option>
                        @foreach($MARITAL as $k=>$lbl)
                            <option value="{{ $k }}"
                                @selected(old('marital_status',$profile->marital_status)===$k)>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    <span>عدد أفراد الأسرة حاليًا</span>
                    <input type="number" min="1" max="10" name="family_members_count" id="family-count-input"
                           value="{{ old('family_members_count', $profile->family_members_count) }}">
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
        </section>

        {{-- السكن والوضع الاجتماعي --}}
        <section class="form-section">
            <div class="section-title">بيانات السكن والوضع الاجتماعي</div>
            <div class="grid grid-2">
                <label class="field">
                    <span>عنوان السكن الأصلي كامل</span>
                    <input type="text" name="original_address"
                           value="{{ old('original_address',$profile->original_address) }}">
                </label>

                <label class="field">
                    <span>وضع المنزل حاليًا</span>
                    <select name="house_status">
                        <option value="">_______</option>
                        @foreach($HOUSE as $k=>$lbl)
                            <option value="{{ $k }}"
                                @selected(old('house_status',$profile->house_status)===$k)>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="grid grid-3" style="margin-top:1.25rem;">
                <label class="field">
                    <span>الحالة</span>
                    <select name="status" id="status-select">
                        <option value="">_______</option>
                        <option value="resident"
                            @selected(old('status',$profile->status)==='resident')>مقيم</option>
                        <option value="displaced"
                            @selected(old('status',$profile->status)==='displaced')>نازح</option>
                    </select>
                </label>

                <label class="field {{ old('status',$profile->status)==='displaced' ? '' : 'hidden' }}"
                       id="current-address-field">
                    <span>العنوان الحالي بعد النزوح</span>
                    <input type="text" name="current_address"
                           value="{{ old('current_address',$profile->current_address) }}">
                </label>

                <label class="field">
                    <span>حالة السكن</span>
                    <select name="housing_type">
                        <option value="">_______</option>
                        @foreach($HOUSING as $k=>$lbl)
                            <option value="{{ $k }}"
                                @selected(old('housing_type',$profile->housing_type)===$k)>
                                {{ $lbl }}
                            </option>
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
                    <input type="tel" name="mobile"
                           value="{{ old('mobile',$profile->mobile) }}" required
                           class="@error('mobile') is-invalid @enderror">
                    @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                </label>

                <label class="field">
                    <span>رقم جوال بديل</span>
                    <input type="tel" name="mobile_alt"
                           value="{{ old('mobile_alt',$profile->mobile_alt) }}">
                </label>

                @php
                    $whats   = $profile->whatsapp ?? '';
                    $waPref  = '970';
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
                <label class="field">
                    <span>واتس آب</span>

                    <div class="phone-input">
                        <span class="phone-plus">+</span>

                        <select name="whatsapp_prefix" class="phone-prefix">
                            <option value="970" @selected(old('whatsapp_prefix', $waPref)=='970')>970</option>
                            <option value="972" @selected(old('whatsapp_prefix', $waPref)=='972')>972</option>
                        </select>

                        <input type="tel"
                               name="whatsapp"
                               value="{{ old('whatsapp', $waNum) }}"
                               maxlength="10"
                               pattern="\d{8,10}"
                               class="phone-number"
                               placeholder="59xxxxxxx">
                    </div>
                </label>

                <label class="field">
                    <span>تيليجرام</span>
                    <input type="text" name="telegram"
                           value="{{ old('telegram',$profile->telegram) }}">
                </label>

                <label class="field">
                    <span>Gmail</span>
                    <input type="email" name="gmail"
                           value="{{ old('gmail',$profile->gmail) }}">
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
                            <option value="{{ $val }}"
                                @selected(old('readiness', $profile->readiness) === $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="field {{ old('readiness', $profile->readiness)==='not_ready' ? '' : 'hidden' }}"
                       id="readiness-notes-field">
                    <span>أسباب عدم الجاهزية</span>
                    <textarea name="readiness_notes">{{ old('readiness_notes', $profile->readiness_notes) }}</textarea>
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
                container.appendChild(createMemberRow(prefill));
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

        const toggleShow = (selectEl, targetEl, value) => {
            const show = selectEl?.value === value;
            targetEl?.classList.toggle('hidden', !show);
        };

        readinessSelectEl?.addEventListener('change', () => toggleShow(readinessSelectEl, readinessNotesEl, 'not_ready'));
        toggleShow(readinessSelectEl, readinessNotesEl, 'not_ready');

        statusSelectEl?.addEventListener('change', () => toggleShow(statusSelectEl, currentAddressEl, 'displaced'));
        toggleShow(statusSelectEl, currentAddressEl, 'displaced');

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
