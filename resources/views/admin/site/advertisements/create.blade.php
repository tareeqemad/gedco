@extends('layouts.admin')
@section('title', 'إضافة إعلان جديد')

@section('content')
    @php
        $breadcrumbTitle     = 'إضافة إعلان جديد';
        $breadcrumbParent    = 'الإعلانات والوظائف';
        $breadcrumbParentUrl = route('admin.advertisements.index');
    @endphp

    <div class="container-fluid p-0">
        <!-- هيدر موحد + تبويبات -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <i class="ri-add-circle-line text-primary fs-6"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">إنشاء إعلان جديد</h6>
                </div>
                <ul class="nav nav-tabs nav-tabs-sm border-0" id="adTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-1" id="form-tab" data-bs-toggle="tab" data-bs-target="#form-content" type="button">
                            <i class="ri-edit-line me-1"></i> إدخال
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

        <!-- المحتوى -->
        <div class="tab-content" id="adTabContent">
            <!-- تبويب الإدخال -->
            <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-3 bg-white">
                            <div class="card-body p-4">
                                <form id="adForm" action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- العنوان -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-heading fs-6 text-primary"></i>
                                            عنوان الإعلان <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="TITLE" id="titleInput" class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-primary @error('TITLE') is-invalid @enderror"
                                               placeholder="أدخل عنوانًا جذابًا..." value="{{ old('TITLE') }}" required>
                                        @error('TITLE') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small"><span id="titleCount">{{ old('TITLE') ? strlen(old('TITLE')) : 0 }}</span>/255</div>
                                    </div>

                                    <!-- تاريخ الخبر -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-calendar-line fs-6 text-info"></i>
                                            تاريخ الخبر <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="DATE_NEWS" id="dateInput" class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-info @error('DATE_NEWS') is-invalid @enderror"
                                               value="{{ old('DATE_NEWS', now()->format('Y-m-d')) }}" required>
                                        @error('DATE_NEWS') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- المحتوى (Quill) -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-text-line fs-6 text-success"></i>
                                            محتوى الإعلان
                                        </label>

                                        <!-- غلاف ثابت يمنع تمدد المحرر ويخلي السكول داخلي -->
                                        <div class="quill-shell border rounded-3 shadow-sm">
                                            <div id="quill-toolbar">
                                                <span class="ql-formats">
                                                    <select class="ql-header"></select>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-bold"></button>
                                                    <button class="ql-italic"></button>
                                                    <button class="ql-underline"></button>
                                                    <button class="ql-link"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-list" value="ordered"></button>
                                                    <button class="ql-list" value="bullet"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-undo" type="button">↶</button>
                                                    <button class="ql-redo" type="button">↷</button>
                                                </span>
                                            </div>
                                            <div id="quill-editor"></div>
                                        </div>

                                        <textarea name="BODY" id="bodyInput" class="d-none">{{ old('BODY') }}</textarea>
                                        @error('BODY') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small mt-1"><span id="wordCount">0</span> كلمة</div>
                                    </div>

                                    <!-- رفع PDF -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-pdf-line fs-6 text-danger"></i>
                                            ملف PDF (اختياري)
                                        </label>
                                        <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle position-relative overflow-hidden" id="dropZone">
                                            <input type="file" name="PDF" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="ri-upload-cloud-2-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    اسحب الملف هنا أو
                                                    <label for="pdfInput" class="text-primary m-0" style="text-decoration: underline; cursor: pointer;">
                                                        اختر ملف
                                                    </label>
                                                </p>
                                                <small class="text-muted">PDF • حتى 10 ميجابايت</small>
                                            </div>
                                        </div>
                                        <div id="pdfPreview" class="mt-3"></div>
                                        @error('PDF') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- الأزرار -->
                                    <div class="d-flex gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm">
                                            <i class="ri-check-line"></i> نشر الإعلان
                                        </button>
                                        <button type="button" id="saveDraft" class="btn btn-outline-secondary px-4 d-flex align-items-center gap-2">
                                            <i class="ri-draft-line"></i> حفظ مسودة
                                        </button>
                                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-link text-muted text-decoration-none">
                                            إلغاء
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- المعاينة الجانبية -->
                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="card border-0 shadow-sm rounded-3 bg-white sticky-top" style="top: 1rem;">
                            <div class="card-body p-4" id="livePreview">
                                <div class="text-center text-muted py-5">
                                    <i class="ri-file-search-line fs-5 d-block mb-2"></i>
                                    <small>ابدأ الكتابة لرؤية المعاينة</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تبويب المعاينة الكاملة -->
            <div class="tab-pane fade" id="preview-content" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="ri-file-search-line fs-5 d-block mb-2"></i>
                            <small>ابدأ الكتابة في تبويب "إدخال" لترى المعاينة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        {{-- Quill CSS من مجلد الأصول لديك --}}
        <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.bubble.css') }}">
        <style>
            :root{
                --primary:#4361ee; --info:#3f83f8; --success:#10b981; --danger:#ef4444;
            }

            /* غلاف ثابت لمحرر Quill */
            .quill-shell{
                height: 420px;          /* عدّلها حسب رغبتك */
                background:#fff;
                border-radius:.5rem;
                overflow:hidden;        /* يمنع تمدد المحرر خارج الكارد */
            }
            /* اجعل محرر Quill يملأ الغلاف */
            .quill-shell .ql-toolbar{
                border-top-left-radius:.5rem;
                border-top-right-radius:.5rem;
            }
            .quill-shell .ql-container{
                height: calc(100% - 42px); /* 42 تقريبًا ارتفاع التولبار */
                border-bottom-left-radius:.5rem;
                border-bottom-right-radius:.5rem;
            }
            .quill-shell .ql-editor{
                height: 100%;
                overflow-y: auto;       /* السكول داخلي */
                direction: rtl;         /* RTL */
                text-align: right;
            }

            .form-control:focus{ box-shadow:0 0 0 0.2rem rgba(67,97,238,0.15);}
            #dropZone{ cursor:pointer;}
            #dropZone.dragover{ background:#ebf2ff!important; border-color:var(--primary)!important;}
            .btn-primary{ background:var(--primary); border:none;}
            .btn-primary:hover{ background:#3b56d7;}
            .nav-tabs .nav-link{ font-size:.875rem; border:none; color:#6c757d;}
            .nav-tabs .nav-link.active{ color:var(--primary); font-weight:600; border-bottom:2px solid var(--primary);}
            .nav-tabs .nav-link:hover{ color:var(--primary);}
            .focus-ring-primary:focus{ --bs-focus-ring-color: rgba(67,97,238,.25);}
            .focus-ring-info:focus{ --bs-focus-ring-color: rgba(63,131,248,.25);}

            /* أزرار undo/redo البسيطة */
            #quill-toolbar .ql-undo, #quill-toolbar .ql-redo{
                border: 1px solid #ced4da; border-radius:.375rem; padding:0 .5rem; line-height:26px;
                background:#fff; cursor:pointer;
            }
        </style>
    @endpush

    @push('scripts')
        {{-- Quill JS --}}
        <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>

        {{-- SweetAlert2 --}}
        <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let draftTimeout;

                // تهيئة Quill
                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: { container: '#quill-toolbar' },
                        history: { delay: 500, maxStack: 100, userOnly: true }
                    },
                    placeholder: 'اكتب محتوى الإعلان هنا...'
                });

                // أزرار Undo/Redo (إن وُجدت)
                const undoBtn = document.querySelector('#quill-toolbar .ql-undo');
                const redoBtn = document.querySelector('#quill-toolbar .ql-redo');
                if (undoBtn) undoBtn.addEventListener('click', () => quill.history.undo());
                if (redoBtn) redoBtn.addEventListener('click', () => quill.history.redo());

                // عناصر النموذج
                const titleInput = document.getElementById('titleInput');
                const dateInput  = document.getElementById('dateInput');
                const pdfInput   = document.getElementById('pdfInput');
                const dropZone   = document.getElementById('dropZone');
                const pdfPreview = document.getElementById('pdfPreview');

                // تحميل مسودة
                const savedDraft = localStorage.getItem('ad_draft');
                if (savedDraft) {
                    const d = JSON.parse(savedDraft);
                    if (d.title) titleInput.value = d.title;
                    if (d.date)  dateInput.value  = d.date;
                    if (d.body)  quill.root.innerHTML = d.body;
                }
                updateAllPreviews();
                updateWordCount();

                // تحديث العداد
                titleInput.addEventListener('input', () => {
                    document.getElementById('titleCount').textContent = titleInput.value.length;
                    updateAllPreviews(); autoSaveDraft();
                });
                dateInput.addEventListener('change', () => { updateAllPreviews(); autoSaveDraft(); });

                // تغييرات المحرر
                quill.on('text-change', () => { updateAllPreviews(); updateWordCount(); autoSaveDraft(); });

                function updateWordCount() {
                    const text = quill.getText().trim();
                    const words = text ? text.split(/\s+/).length : 0;
                    document.getElementById('wordCount').textContent = words;
                }

                function updateAllPreviews() {
                    const title   = titleInput.value || 'عنوان الإعلان';
                    const date    = dateInput.value ? new Date(dateInput.value).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' }) : 'تاريخ الخبر';
                    const content = quill.root.innerHTML;

                    const previewHTML = `
                <article class="p-3">
                    <h5 class="fw-bold text-primary mb-2">${title}</h5>
                    <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                        <i class="ri-calendar-line"></i> <span>${date}</span>
                    </div>
                    <div class="content-preview lh-lg text-dark" style="font-size: 0.95rem;">
                        ${content || '<p class="text-muted small">ابدأ الكتابة لرؤية المعاينة هنا...</p>'}
                    </div>
                </article>`;

                    ['livePreview','fullPreview'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.innerHTML = previewHTML;
                    });
                }

                function autoSaveDraft() {
                    clearTimeout(draftTimeout);
                    draftTimeout = setTimeout(() => {
                        const draft = {
                            title: titleInput.value,
                            date:  dateInput.value,
                            body:  quill.root.innerHTML,
                            pdf:   pdfInput.files[0]?.name || null
                        };
                        localStorage.setItem('ad_draft', JSON.stringify(draft));
                    }, 700);
                }

                // رفع PDF — سحب ونقر
                dropZone.addEventListener('click', (e) => {
                    if (!(e.target.closest('.btn-close'))) pdfInput.click();
                });
                ['dragover','dragenter'].forEach(type => dropZone.addEventListener(type, ev => {
                    ev.preventDefault(); dropZone.classList.add('dragover');
                }));
                ['dragleave','dragend','drop'].forEach(type => dropZone.addEventListener(type, ev => {
                    ev.preventDefault(); dropZone.classList.remove('dragover');
                }));
                dropZone.addEventListener('drop', e => {
                    e.preventDefault();
                    const file = e.dataTransfer.files?.[0];
                    if (file) handlePDFFile(file);
                });
                pdfInput.addEventListener('change', () => {
                    const file = pdfInput.files?.[0];
                    if (file) handlePDFFile(file);
                });

                function handlePDFFile(file) {
                    if (file.type !== 'application/pdf') {
                        Swal.fire('خطأ', 'يرجى رفع ملف PDF فقط', 'error');
                        pdfInput.value = ''; pdfPreview.innerHTML = '';
                        return;
                    }
                    if (file.size > 10 * 1024 * 1024) {
                        Swal.fire('خطأ', 'حجم الملف لا يتجاوز 10 ميجابايت', 'error');
                        pdfInput.value = ''; pdfPreview.innerHTML = '';
                        return;
                    }
                    const dt = new DataTransfer(); dt.items.add(file); pdfInput.files = dt.files;
                    pdfPreview.innerHTML = `
                <div class="alert alert-success d-flex align-items-center gap-2 p-2 mb-0 rounded">
                    <i class="ri-file-pdf-line fs-5"></i>
                    <div>
                        <strong>${file.name}</strong><br>
                        <small>${(file.size/1024/1024).toFixed(2)} ميجابايت</small>
                    </div>
                    <button type="button" class="btn-close btn-close-sm ms-auto" onclick="removePDF()"></button>
                </div>`;
                    autoSaveDraft();
                }
                window.removePDF = function(){
                    pdfInput.value = ''; pdfPreview.innerHTML = ''; autoSaveDraft();
                };

                // عند الإرسال — خزّن HTML داخل الحقل المخفي
                document.getElementById('adForm').addEventListener('submit', function () {
                    document.getElementById('bodyInput').value = quill.root.innerHTML;
                    localStorage.removeItem('ad_draft');
                });
            });
        </script>
    @endpush
@endsection
