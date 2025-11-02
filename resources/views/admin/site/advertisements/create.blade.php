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

                                    <!-- المحتوى -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-text-line fs-6 text-success"></i>
                                            محتوى الإعلان
                                        </label>
                                        <div id="editor" class="border rounded-3 shadow-sm" style="min-height: 280px;"></div>
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
                                            <input type="file" name="PDF" id="pdfInput" class="d-none" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="ri-upload-cloud-2-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">اسحب الملف هنا أو <span class="text-primary cursor-pointer" id="browseLink">اختر ملف</span></p>
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
        <link href="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.0.0/build/ckeditor.css" rel="stylesheet">
        <style>
            :root {
                --primary: #4361ee;
                --info: #3f83f8;
                --success: #10b981;
                --danger: #ef4444;
            }
            .ck-editor__editable { min-height: 280px; }
            .form-control:focus { box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15); }
            #dropZone.dragover { background: #ebf2ff !important; border-color: var(--primary) !important; }
            .btn-primary { background: var(--primary); border: none; }
            .btn-primary:hover { background: #3b56d7; }
            .nav-tabs .nav-link { font-size: 0.875rem; border: none; color: #6c757d; }
            .nav-tabs .nav-link.active { color: var(--primary); font-weight: 600; border-bottom: 2px solid var(--primary); }
            .nav-tabs .nav-link:hover { color: var(--primary); }
            .focus-ring-primary:focus { --bs-focus-ring-color: rgba(67, 97, 238, 0.25); }
            .focus-ring-info:focus { --bs-focus-ring-color: rgba(63, 131, 248, 0.25); }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let editor;
                let draftTimeout;

                // CKEditor
                ClassicEditor
                    .create(document.querySelector('#editor'), {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                        language: 'ar',
                        placeholder: 'اكتب محتوى الإعلان هنا...'
                    })
                    .then(newEditor => {
                        editor = newEditor;
                        const savedDraft = localStorage.getItem('ad_draft');
                        if (savedDraft) {
                            const draft = JSON.parse(savedDraft);
                            if (draft.body) editor.setData(draft.body);
                        }
                        editor.model.document.on('change:data', updateAllPreviews);
                        updateWordCount();
                        updateAllPreviews();
                    })
                    .catch(err => console.error(err));

                const titleInput = document.getElementById('titleInput');
                const dateInput = document.getElementById('dateInput');
                const pdfInput = document.getElementById('pdfInput');
                const dropZone = document.getElementById('dropZone');
                const pdfPreview = document.getElementById('pdfPreview');
                const browseLink = document.getElementById('browseLink');

                // تحديث عدد الأحرف
                titleInput.addEventListener('input', () => {
                    document.getElementById('titleCount').textContent = titleInput.value.length;
                    updateAllPreviews();
                    autoSaveDraft();
                });

                dateInput.addEventListener('change', () => {
                    updateAllPreviews();
                    autoSaveDraft();
                });

                // رفع PDF: Drag & Drop أو اختيار
                browseLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    pdfInput.click();
                });

                ['dragover', 'dragenter'].forEach(e => dropZone.addEventListener(e, ev => {
                    ev.preventDefault();
                    dropZone.classList.add('dragover');
                }));

                ['dragleave', 'dragend', 'drop'].forEach(e => dropZone.addEventListener(e, ev => {
                    ev.preventDefault();
                    dropZone.classList.remove('dragover');
                }));

                dropZone.addEventListener('drop', e => {
                    e.preventDefault();
                    const file = e.dataTransfer.files[0];
                    if (file) {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        pdfInput.files = dt.files;
                        handlePDFFile(file);
                    }
                });

                pdfInput.addEventListener('change', () => {
                    const file = pdfInput.files[0];
                    if (file) handlePDFFile(file);
                });

                // التعامل مع الملف (رفع فقط، بدون معاينة)
                function handlePDFFile(file) {
                    if (file.type !== 'application/pdf') {
                        Swal.fire('خطأ', 'يرجى رفع ملف PDF فقط', 'error');
                        pdfInput.value = '';
                        pdfPreview.innerHTML = '';
                        return;
                    }
                    if (file.size > 10 * 1024 * 1024) {
                        Swal.fire('خطأ', 'حجم الملف لا يتجاوز 10 ميجابايت', 'error');
                        pdfInput.value = '';
                        pdfPreview.innerHTML = '';
                        return;
                    }

                    // أعد تعيين الملف للإرسال
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    pdfInput.files = dt.files;

                    // عرض اسم الملف فقط
                    pdfPreview.innerHTML = `
                        <div class="alert alert-success d-flex align-items-center gap-2 p-2 mb-0 rounded">
                            <i class="ri-file-pdf-line fs-5"></i>
                            <div>
                                <strong>${file.name}</strong><br>
                                <small>${(file.size / 1024 / 1024).toFixed(2)} ميجابايت</small>
                            </div>
                            <button type="button" class="btn-close btn-close-sm ms-auto" onclick="removePDF()"></button>
                        </div>`;

                    autoSaveDraft();
                }

                window.removePDF = function() {
                    pdfInput.value = '';
                    pdfPreview.innerHTML = '';
                    autoSaveDraft();
                };

                function updateWordCount() {
                    if (!editor) return;
                    const text = editor.getData().replace(/<[^>]*>/g, ' ').trim();
                    const words = text ? text.split(/\s+/).length : 0;
                    document.getElementById('wordCount').textContent = words;
                }

                // المعاينة الفورية (بدون PDF)
                function updateAllPreviews() {
                    const title = titleInput.value || 'عنوان الإعلان';
                    const date = dateInput.value ? new Date(dateInput.value).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' }) : 'تاريخ الخبر';
                    const content = editor ? editor.getData() : '';

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

                    ['livePreview', 'fullPreview'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.innerHTML = previewHTML;
                    });

                    updateWordCount();
                }

                function autoSaveDraft() {
                    clearTimeout(draftTimeout);
                    draftTimeout = setTimeout(() => {
                        const draft = {
                            title: titleInput.value,
                            date: dateInput.value,
                            body: editor?.getData() || '',
                            pdf: pdfInput.files[0]?.name || null
                        };
                        localStorage.setItem('ad_draft', JSON.stringify(draft));
                    }, 800);
                }

                // تحميل المسودة
                const draft = localStorage.getItem('ad_draft');
                if (draft) {
                    const d = JSON.parse(draft);
                    if (d.title) titleInput.value = d.title;
                    if (d.date) dateInput.value = d.date;
                    if (d.body && editor) editor.setData(d.body);
                    updateAllPreviews();
                }

                // حفظ مسودة
                document.getElementById('saveDraft').addEventListener('click', () => {
                    autoSaveDraft();
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed bottom-0 end-0 p-3';
                    toast.innerHTML = `<div class="toast show align-items-center text-bg-success border-0" role="alert">
                        <div class="d-flex"><div class="toast-body">تم حفظ المسودة</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
                    </div>`;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                });

                // عند الإرسال
                document.getElementById('adForm').addEventListener('submit', function () {
                    if (editor) document.getElementById('bodyInput').value = editor.getData();
                    localStorage.removeItem('ad_draft');
                });

                updateAllPreviews();
            });
        </script>
    @endpush
@endsection
