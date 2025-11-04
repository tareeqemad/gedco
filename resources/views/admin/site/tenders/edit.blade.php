@extends('layouts.admin')
@section('title', 'تعديل العطاء #' . $tender->id)

@section('content')
    @php
        $breadcrumbTitle     = 'تعديل العطاء #' . $tender->id;
        $breadcrumbParent    = 'سجل العطاءات';
        $breadcrumbParentUrl = route('admin.tenders.index');
    @endphp

    <div class="container-fluid p-0">
        <!-- هيدر + تبويبات -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <i class="ri-edit-box-line text-warning fs-6"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">تعديل العطاء #{{ $tender->id }}</h6>
                </div>
                <ul class="nav nav-tabs nav-tabs-sm border-0" id="tenderTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-1" id="form-tab" data-bs-toggle="tab" data-bs-target="#form-content" type="button">
                            <i class="ri-edit-line me-1"></i> تعديل
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-1" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-content" type="button">
                            <i class="ri-eye-line me-1"></i> معاينة
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="tenderTabContent">
            <!-- تبويب التعديل -->
            <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                <form id="tenderForm" action="{{ route('admin.tenders.update', $tender->id) }}" method="POST" novalidate>
                    @csrf @method('PUT')

                    <div class="row g-4">
                        <!-- الحقول الأساسية -->
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm rounded-3 bg-white">
                                <div class="card-body p-4 row g-3">

                                    <div class="col-md-4">
                                        <label class="form-label">MNEWS_ID</label>
                                        <input type="number" name="mnews_id"
                                               class="form-control @error('mnews_id') is-invalid @enderror"
                                               value="{{ old('mnews_id', $tender->mnews_id) }}" placeholder="مثال: 57">
                                        @error('mnews_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-8">
                                        <label class="form-label">COLUMN_NAME_1</label>
                                        <input type="text" name="column_name_1"
                                               class="form-control @error('column_name_1') is-invalid @enderror"
                                               value="{{ old('column_name_1', $tender->column_name_1) }}"
                                               placeholder="body / title / pdf ...">
                                        @error('column_name_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">THE_DATE_1 <span class="text-muted">(نصي)</span></label>
                                        <input type="text" name="the_date_1"
                                               class="form-control @error('the_date_1') is-invalid @enderror"
                                               value="{{ old('the_date_1', $tender->the_date_1) }}"
                                               placeholder="مثال: 6/20/2016 11:55 AM">
                                        @error('the_date_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">COULM_SERIAL</label>
                                        <input type="number" name="coulm_serial"
                                               class="form-control @error('coulm_serial') is-invalid @enderror"
                                               value="{{ old('coulm_serial', $tender->coulm_serial) }}" placeholder="مثال: 57">
                                        @error('coulm_serial')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">EVENT_1</label>
                                        <input type="text" name="event_1"
                                               class="form-control @error('event_1') is-invalid @enderror"
                                               value="{{ old('event_1', $tender->event_1) }}" placeholder="update / delete">
                                        @error('event_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">THE_USER_1</label>
                                        <input type="text" name="the_user_1"
                                               class="form-control @error('the_user_1') is-invalid @enderror"
                                               value="{{ old('the_user_1', $tender->the_user_1) }}" placeholder="admin@gedco">
                                        @error('the_user_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- المحرران (OLD / NEW) -->
                        <div class="col-lg-7">
                            {{-- OLD_VALUE_1 --}}
                            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                                <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ri-file-text-line text-secondary"></i>
                                        <span class="fw-semibold">النص القديم (OLD_VALUE_1)</span>
                                    </div>
                                    <small class="text-muted">HTML مدعوم</small>
                                </div>
                                <div class="card-body p-3">
                                    <div class="quill-shell border rounded-3 shadow-sm">
                                        <div id="toolbar-old" class="ql-toolbar ql-snow">
                                            <span class="ql-formats"><select class="ql-header"></select></span>
                                            <span class="ql-formats">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-link"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                                <select class="ql-align"></select>
                                                <button class="ql-clean"></button>
                                            </span>
                                        </div>
                                        <div id="editor-old" class="ql-container ql-snow"></div>
                                    </div>
                                    <textarea name="old_value_1" id="old_value_1" class="d-none">{{ old('old_value_1', $tender->old_value_1) }}</textarea>
                                    @error('old_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- NEW_VALUE_1 --}}
                            <div class="card border-0 shadow-sm rounded-3 bg-white">
                                <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ri-file-text-line text-success"></i>
                                        <span class="fw-semibold">النص الجديد (NEW_VALUE_1)</span>
                                    </div>
                                    <small class="text-muted">HTML مدعوم</small>
                                </div>
                                <div class="card-body p-3">
                                    <div class="quill-shell border rounded-3 shadow-sm">
                                        <div id="toolbar-new" class="ql-toolbar ql-snow">
                                            <span class="ql-formats"><select class="ql-header"></select></span>
                                            <span class="ql-formats">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-link"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                                <select class="ql-align"></select>
                                                <button class="ql-clean"></button>
                                            </span>
                                        </div>
                                        <div id="editor-new" class="ql-container ql-snow"></div>
                                    </div>
                                    <textarea name="new_value_1" id="new_value_1" class="d-none">{{ old('new_value_1', $tender->new_value_1) }}</textarea>
                                    @error('new_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="ri-check-line me-1"></i> تحديث
                        </button>
                        <a href="{{ route('admin.tenders.index') }}" class="btn btn-light px-4">رجوع</a>
                    </div>
                </form>
            </div>

            <!-- تبويب المعاينة الكاملة -->
            <div class="tab-pane fade" id="preview-content" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="ri-file-search-line fs-5 d-block mb-2"></i>
                            <small>ابدأ التعديل في تبويب "تعديل" لترى المعاينة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
        <style>
            :root{ --primary:#4361ee; --success:#10b981; }
            .quill-shell{ height: 420px; background:#fff; border-radius:.5rem; overflow:hidden; }
            .quill-shell .ql-toolbar{ border-top-left-radius:.5rem; border-top-right-radius:.5rem; }
            .quill-shell .ql-container{ height: calc(100% - 42px); border-bottom-left-radius:.5rem; border-bottom-right-radius:.5rem; }
            .quill-shell .ql-editor{ height:100%; overflow-y:auto; direction:rtl; text-align:right; }
            .form-control:focus{ box-shadow:0 0 0 .2rem rgba(67,97,238,.15); }
            .btn-success{ background:var(--success); border:none; }
            .btn-success:hover{ background:#0d9488; }
            .nav-tabs .nav-link{ font-size:.875rem; border:none; color:#6c757d; }
            .nav-tabs .nav-link.active{ color:var(--primary); font-weight:600; border-bottom:2px solid var(--primary); }
            .nav-tabs .nav-link:hover{ color:var(--primary); }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const commonModules = (toolbarId) => ({
                    toolbar: { container: toolbarId },
                    history: { delay: 400, maxStack: 100, userOnly: true }
                });

                // init editors
                const qOld = new Quill('#editor-old', {
                    theme: 'snow',
                    modules: commonModules('#toolbar-old'),
                    placeholder: 'الصق/اكتب النص القديم هنا...'
                });
                const qNew = new Quill('#editor-new', {
                    theme: 'snow',
                    modules: commonModules('#toolbar-new'),
                    placeholder: 'الصق/اكتب النص الجديد هنا...'
                });

                // preload from hidden (old())
                const oldHidden = document.getElementById('old_value_1');
                const newHidden = document.getElementById('new_value_1');
                if (oldHidden.value) qOld.root.innerHTML = oldHidden.value;
                if (newHidden.value) qNew.root.innerHTML = newHidden.value;

                // live preview (جهة التبويب الثاني)
                function renderPreview(){
                    const title = @json('عطاء #' . $tender->id);
                    const meta  = [
                        'MNEWS_ID: {{ $tender->mnews_id ?? "-" }}',
                        'USER: {{ $tender->the_user_1 ?? "-" }}',
                        'DATE: {{ $tender->the_date_1 ?? "-" }}',
                        'EVENT: {{ $tender->event_1 ?? "-" }}'
                    ].join(' • ');

                    const oldHtml = qOld.root.innerHTML?.trim();
                    const newHtml = qNew.root.innerHTML?.trim();

                    const html = `
                        <article class="p-2">
                            <h5 class="fw-bold text-primary mb-1">${title}</h5>
                            <div class="text-muted small mb-3">${meta}</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-2">
                                        <div class="text-secondary fw-semibold mb-2">OLD_VALUE_1</div>
                                        ${oldHtml || '<p class="text-muted small m-0">فارغ…</p>'}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-2">
                                        <div class="text-success fw-semibold mb-2">NEW_VALUE_1</div>
                                        ${newHtml || '<p class="text-muted small m-0">فارغ…</p>'}
                                    </div>
                                </div>
                            </div>
                        </article>`;
                    const box = document.getElementById('fullPreview');
                    if (box) box.innerHTML = html;
                }
                qOld.on('text-change', renderPreview);
                qNew.on('text-change', renderPreview);
                renderPreview();

                // on submit → dump html to hidden fields
                document.getElementById('tenderForm').addEventListener('submit', function () {
                    oldHidden.value = qOld.root.innerHTML.trim();
                    newHidden.value = qNew.root.innerHTML.trim();
                });
            });
        </script>
    @endpush
@endsection
