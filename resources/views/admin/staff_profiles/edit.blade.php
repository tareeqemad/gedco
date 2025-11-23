{{-- resources/views/admin/staff-profiles/edit.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'تعديل بيانات الموظف')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #e67e22;
            --primary-dark: #d35400;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light: #fdf2e9;
            --border: #e2e8f0;
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .card-admin {
            border: none;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: #fff;
            transition: all 0.3s;
        }

        .card-admin:hover {
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .card-admin .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 1rem 1.5rem;
            border: none;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 2.5rem 0 1.5rem;
            padding-bottom: 0.75rem;
            position: relative;
            transition: all 0.3s;
        }

        .section-title:hover {
            color: var(--primary);
            padding-right: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
            transition: width 0.3s;
        }

        .section-title:hover::after {
            width: 100px;
        }

        .form-control-lg,
        .form-select-lg {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1.5px solid #ced4da;
            transition: all 0.3s;
        }

        .form-control-lg:hover,
        .form-select-lg:hover {
            border-color: var(--primary);
        }

        .form-control-lg:focus,
        .form-select-lg:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(230, 126, 34, 0.2);
            transform: translateY(-1px);
        }

        .btn-save {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-save::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-save:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 25px rgba(39, 174, 96, 0.4);
        }

        .btn-save:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-save:active {
            transform: translateY(0) scale(0.98);
        }

        .family-table th {
            background: var(--light);
            font-weight: 600;
            color: var(--primary-dark);
        }

        .remove-member-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hidden { display: none !important; }
        .show { display: block !important; }

        .wa-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            direction: ltr;
            background: #fff;
            border: 1.5px solid #ced4da;
            border-radius: 0.75rem;
            padding: 0 12px;
            height: 48px;
            transition: all 0.3s;
        }

        .wa-wrapper:hover {
            border-color: var(--primary);
        }

        .wa-wrapper:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(230, 126, 34, 0.2);
        }

        .wa-plus {
            font-size: 16px;
            font-weight: bold;
            color: #6c757d;
        }

        .wa-prefix {
            width: 80px;
            border: none;
            background: transparent;
            font-size: 15px;
            outline: none;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .wa-number {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 15px;
            outline: none;
        }

        .wa-number::placeholder {
            color: #adb5bd;
        }
    </style>
@endpush

@section('content')
    @php
        use Carbon\Carbon;

        $locations      = config('staff_enums.locations');
        $maritalStatus  = config('staff_enums.marital_status');
        $houseStatus    = config('staff_enums.house_status');
        $residentStatus = config('staff_enums.status');
        $housingTypes   = config('staff_enums.housing_type');
        $readinessList  = config('staff_enums.readiness');
        $relations      = config('staff_enums.relation');

        $serverFamily = ($profile->dependents ?? collect())->map(function($d) {
            $birth = $d->birth_date instanceof Carbon ? $d->birth_date->format('Y-m-d') : $d->birth_date;
            return [
                'name'       => $d->name,
                'relation'   => $d->relation,
                'birth_date' => $birth,
                'is_student' => $d->is_student ? 'yes' : 'no',
            ];
        })->values();
    @endphp

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-xxl-11">
                <div class="card card-admin">
                    <div class="card-header text-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="mb-0 text-white"><i class="bi bi-person-gear me-2"></i> تعديل بيانات الموظف</h5>
                            </div>
                            <a href="{{ route('admin.staff-profiles.show', $profile) }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-right"></i> رجوع للعرض
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4 p-xl-5">
                        <!-- بطاقة معلومات سريعة -->
                        <div class="text-center text-lg-start p-3 bg-light rounded-3 mb-4 border">
                            <h5 class="fw-bold text-primary mb-2">{{ $profile->full_name }}</h5>
                            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start text-muted small mt-1">
                                <div><strong>رقم الهوية:</strong> {{ $profile->national_id }}</div>
                                <div><strong>الرقم الوظيفي:</strong> {{ $profile->employee_number ?? 'غير محدد' }}</div>
                                <div><strong>المتبقي من التعديلات:</strong>
                                    <span class="badge bg-warning text-dark">{{ $profile->edits_remaining }} / {{ $profile->edits_allowed }}</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.staff-profiles.update', $profile) }}" method="POST" class="needs-validation" novalidate>
                            @csrf @method('PUT')

                            <!-- البيانات الأساسية -->
                            <h5 class="section-title"><i class="bi bi-person-badge"></i> البيانات الأساسية</h5>
                            <div class="row g-4">
                                <div class="col-md-6 col-lg-4"><label class="formLABEL">رقم الهوية <span class="text-danger">*</span></label><input type="text" name="national_id" class="form-control form-control-lg" value="{{ old('national_id', $profile->national_id) }}" required maxlength="9" pattern="\d{9}"></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">الاسم الرباعي <span class="text-danger">*</span></label><input type="text" name="full_name" class="form-control form-control-lg" value="{{ old('full_name', $profile->full_name) }}" required></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">تاريخ الميلاد</label><input type="date" name="birth_date" class="form-control form-control-lg" value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}"></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">الرقم الوظيفي <span class="text-danger">*</span></label><input type="text" name="employee_number" class="form-control form-control-lg" value="{{ old('employee_number', $profile->employee_number) }}" required></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">الوظيفة</label><input type="text" name="job_title" class="form-control form-control-lg" value="{{ old('job_title', $profile->job_title) }}"></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">المقر <span class="text-danger">*</span></label>
                                    <select name="location" class="form-select form-select-lg" required>
                                        <option value="">اختر المقر</option>
                                        @foreach($locations as $k => $v)
                                            <option value="{{ $k }}" {{ old('location', $profile->location) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">الإدارة</label><input type="text" name="department" class="form-control form-control-lg" value="{{ old('department', $profile->department) }}"></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">الدائرة</label><input type="text" name="directorate" class="form-control form-control-lg" value="{{ old('directorate', $profile->directorate) }}"></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">القسم</label><input type="text" name="section" class="form-control form-control-lg" value="{{ old('section', $profile->section) }}"></div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">الحالة الاجتماعية</label>
                                    <select name="marital_status" class="form-select form-select-lg">
                                        <option value="">غير محدد</option>
                                        @foreach($maritalStatus as $k => $v)
                                            <option value="{{ $k }}" {{ old('marital_status', $profile->marital_status) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4"><label class="form-label">عدد أفراد الأسرة</label>
                                    <input type="number" min="0" max="20" id="family-count-input" name="family_members_count" class="form-control form-control-lg" value="{{ old('family_members_count', $profile->family_members_count ?? 1) }}">
                                </div>
                            </div>

                            <!-- أفراد الأسرة -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="section-title mb-0"><i class="bi bi-people"></i> أفراد الأسرة</h5>
                                <span class="badge bg-primary fs-6" id="family-count-badge">
                                    {{ $profile->dependents?->count() ?? 0 }} فرد
                                </span>
                            </div>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered family-table align-middle">
                                    <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>الاسم</th>
                                        <th>صلة القرابة</th>
                                        <th>تاريخ الميلاد</th>
                                        <th>طالب جامعي؟</th>
                                        <th width="60"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="family-rows"></tbody>
                                </table>
                                <button type="button" id="add-family-member" class="btn btn-outline-primary btn-sm"><i class="bi bi-plus-lg"></i> إضافة فرد</button>
                            </div>

                            <!-- السكن والوضع الاجتماعي -->
                            <h5 class="section-title"><i class="bi bi-house-door"></i> السكن والوضع الاجتماعي</h5>
                            <div class="row g-4">
                                <div class="col-12"><label class="form-label">عنوان السكن الأصلي</label><input type="text" name="original_address" class="form-control form-control-lg" value="{{ old('original_address', $profile->original_address) }}"></div>
                                <div class="col-md-6"><label class="form-label">وضع المنزل حاليًا</label>
                                    <select name="house_status" class="form-select form-select-lg">
                                        <option value="">غير محدد</option>
                                        @foreach($houseStatus as $k => $v)
                                            <option value="{{ $k }}" {{ old('house_status', $profile->house_status) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6"><label class="form-label">الحالة (مقيم / نازح)</label>
                                    <select name="status" id="status-select" class="form-select form-select-lg">
                                        <option value="">غير محدد</option>
                                        @foreach($residentStatus as $k => $v)
                                            <option value="{{ $k }}" {{ old('status', $profile->status) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 {{ old('status', $profile->status) == 'displaced' ? 'show' : 'hidden' }}" id="current-address-field">
                                    <label class="form-label">العنوان الحالي بعد النزوح</label>
                                    <input type="text" name="current_address" class="form-control form-control-lg" value="{{ old('current_address', $profile->current_address) }}">
                                </div>
                                <div class="col-md-6"><label class="form-label">نوع السكن الحالي</label>
                                    <select name="housing_type" class="form-select form-select-lg">
                                        <option value="">غير محدد</option>
                                        @foreach($housingTypes as $k => $v)
                                            <option value="{{ $k }}" {{ old('housing_type', $profile->housing_type) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- وسائل التواصل -->
                            <h5 class="section-title"><i class="bi bi-telephone"></i> وسائل التواصل</h5>
                            <div class="row g-4">
                                <div class="col-md-6"><label class="form-label">رقم الجوال</label><input type="text" name="mobile" class="form-control form-control-lg" value="{{ old('mobile', $profile->mobile) }}"></div>
                                <div class="col-md-6"><label class="form-label">جوال بديل</label><input type="text" name="mobile_alt" class="form-control form-control-lg" value="{{ old('mobile_alt', $profile->mobile_alt) }}"></div>
                                
                                @php
                                    $whats = old('whatsapp', $profile->whatsapp ?? '');
                                    $waPref = '970';
                                    $waNum = '';
                                    
                                    if ($whats) {
                                        if (str_starts_with($whats, '970')) {
                                            $waPref = '970';
                                            $waNum = substr($whats, 3);
                                        } elseif (str_starts_with($whats, '972')) {
                                            $waPref = '972';
                                            $waNum = substr($whats, 3);
                                        } else {
                                            $waNum = $whats;
                                        }
                                    }
                                @endphp
                                
                                <div class="col-md-6">
                                    <label class="form-label">واتس آب</label>
                                    <div class="wa-wrapper">
                                        <span class="wa-plus">+</span>
                                        <select name="whatsapp_prefix" class="wa-prefix">
                                            <option value="970" @selected(old('whatsapp_prefix', $waPref)=='970')>970</option>
                                            <option value="972" @selected(old('whatsapp_prefix', $waPref)=='972')>972</option>
                                        </select>
                                        <input type="tel" name="whatsapp" placeholder="59xxxxxxx" value="{{ old('whatsapp', $waNum) }}" maxlength="10" class="wa-number">
                                    </div>
                                </div>
                                
                                <div class="col-md-6"><label class="form-label">تيليجرام</label><input type="text" name="telegram" class="form-control form-control-lg" value="{{ old('telegram', $profile->telegram) }}"></div>
                                <div class="col-md-6"><label class="form-label">البريد الإلكتروني (Gmail)</label><input type="email" name="gmail" class="form-control form-control-lg" value="{{ old('gmail', $profile->gmail) }}"></div>
                            </div>

                            <!-- الحوادث العائلية -->
                            <h5 class="section-title"><i class="bi bi-exclamation-triangle"></i> الحوادث العائلية</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">هل يوجد حوادث عائلية؟</label>
                                    <select name="has_family_incidents" class="form-select form-select-lg">
                                        <option value="no" {{ old('has_family_incidents', $profile->has_family_incidents ?? 'no') == 'no' ? 'selected' : '' }}>لا</option>
                                        <option value="yes" {{ old('has_family_incidents', $profile->has_family_incidents ?? 'no') == 'yes' ? 'selected' : '' }}>نعم</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ملاحظات الحوادث العائلية</label>
                                    <textarea name="family_notes" rows="3" class="form-control form-control-lg" placeholder="أدخل أي ملاحظات حول الحوادث العائلية إن وجدت">{{ old('family_notes', $profile->family_notes) }}</textarea>
                                </div>
                            </div>

                            <!-- الجاهزية -->
                            <h5 class="section-title"><i class="bi bi-clipboard-check"></i> الجاهزية للعودة للعمل</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">مستوى الجاهزية</label>
                                    <select name="readiness" id="readiness-select" class="form-select form-select-lg">
                                        <option value="">غير محدد</option>
                                        @foreach($readinessList as $k => $v)
                                            <option value="{{ $k }}" {{ old('readiness', $profile->readiness) == $k ? 'selected' : '' }}>{{ $v['label'] ?? $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 {{ old('readiness', $profile->readiness) == 'not_ready' ? 'show' : 'hidden' }}" id="readiness-notes-field">
                                    <label class="form-label">أسباب عدم الجاهزية</label>
                                    <textarea name="readiness_notes" rows="4" class="form-control form-control-lg">{{ old('readiness_notes', $profile->readiness_notes) }}</textarea>
                                </div>
                            </div>

                            <!-- إعدادات التعديل (خاص بالأدمن) -->
                            <h5 class="section-title"><i class="bi bi-shield-lock"></i> إعدادات التعديل (لوحة التحكم فقط)</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">إجمالي مرات التعديل المسموح بها</label>
                                    <input type="number" min="0" name="edits_allowed" class="form-control form-control-lg" value="{{ old('edits_allowed', $profile->edits_allowed) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المتبقي للموظف حاليًا</label>
                                    <input type="number" min="0" name="edits_remaining" class="form-control form-control-lg" value="{{ old('edits_remaining', $profile->edits_remaining) }}">
                                </div>
                            </div>
                            <div class="alert alert-info mt-3 small">
                                هذه الحقول لا تظهر للموظف أبدًا، وتُستخدم فقط من لوحة التحكم لإعادة منح محاولات التعديل.
                            </div>

                            <!-- زر الحفظ -->
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-save text-white shadow-lg">
                                    <i class="bi bi-check2-all"></i> حفظ جميع التعديلات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- قالب صف واحد للأسرة --}}
    <template id="family-row-template">
        <tr>
            <td class="text-center family-index">1</td>
            <td><input type="text" data-field="name" class="form-control" required></td>
            <td>
                <select data-field="relation" class="form-select" required>
                    <option value="">اختر</option>
                    @foreach($relations as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="date" data-field="birth_date" class="form-control"></td>
            <td>
                <select data-field="is_student" class="form-select">
                    <option value="no">لا</option>
                    <option value="yes">نعم</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm remove-member-btn"><i class="bi bi-trash"></i></button></td>
        </tr>
    </template>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const template = document.getElementById('family-row-template');
            const container = document.getElementById('family-rows');
            const addBtn = document.getElementById('add-family-member');
            const countInput = document.getElementById('family-count-input');
            const countBadge = document.getElementById('family-count-badge');
            const readinessSelect = document.getElementById('readiness-select');
            const readinessNotes = document.getElementById('readiness-notes-field');
            const statusSelect = document.getElementById('status-select');
            const currentAddress = document.getElementById('current-address-field');

            const serverFamily = @json($serverFamily);
            const oldFamily = @json(old('family', []));
            const initialData = oldFamily.length ? oldFamily : serverFamily;

            function updateIndices() {
                container.querySelectorAll('tr').forEach((row, i) => {
                    row.querySelector('.family-index').textContent = i + 1;
                    row.querySelectorAll('[data-field]').forEach(el => {
                        el.name = `family[${i}][${el.dataset.field}]`;
                    });
                });
                updateFamilyCount();
            }

            function updateFamilyCount() {
                const count = container.children.length;
                countInput.value = count;
                if (countBadge) {
                    countBadge.textContent = count + ' فرد';
                }
            }

            function createRow(data = null) {
                const row = template.content.firstElementChild.cloneNode(true);
                if (data) {
                    row.querySelector('[data-field="name"]').value = data.name || '';
                    row.querySelector('[data-field="relation"]').value = data.relation || '';
                    row.querySelector('[data-field="birth_date"]').value = data.birth_date || '';
                    row.querySelector('[data-field="is_student"]').value = data.is_student || 'no';
                }
                row.querySelector('.remove-member-btn').addEventListener('click', () => {
                    if (container.children.length === 1) return;
                    row.remove();
                    updateIndices();
                });
                return row;
            }

            function renderRows(count, dataList = null) {
                container.innerHTML = '';
                const target = Math.max(1, Math.min(20, count));
                for (let i = 0; i < target; i++) {
                    const data = dataList && dataList[i] ? dataList[i] : null;
                    container.appendChild(createRow(data));
                }
                updateIndices();
            }

            addBtn.addEventListener('click', () => {
                if (container.children.length >= 20) return;
                container.appendChild(createRow());
                updateIndices();
            });

            countInput.addEventListener('input', () => {
                let val = parseInt(countInput.value) || 1;
                val = Math.max(1, Math.min(20, val));
                countInput.value = val;
                renderRows(val);
            });

            // تهيئة البداية
            if (initialData.length) {
                renderRows(initialData.length, initialData);
            } else {
                renderRows(countInput.value || 1);
            }
            updateFamilyCount();

            // إظهار/إخفاء الحقول
            function toggle(field, target, value) {
                const show = field.value === value;
                target.classList.toggle('show', show);
                target.classList.toggle('hidden', !show);
            }

            readinessSelect?.addEventListener('change', () => toggle(readinessSelect, readinessNotes, 'not_ready'));
            statusSelect?.addEventListener('change', () => toggle(statusSelect, currentAddress, 'displaced'));

            // التهيئة الأولية
            toggle(readinessSelect, readinessNotes, 'not_ready');
            toggle(statusSelect, currentAddress, 'displaced');
        });
    </script>
@endpush
