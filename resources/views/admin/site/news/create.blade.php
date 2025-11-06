@extends('layouts.admin')
@section('title', 'إضافة خبر جديد')

@section('content')
    @php
        $breadcrumbTitle     = 'إضافة خبر جديد';
        $breadcrumbParent    = 'الأخبار';
        $breadcrumbParentUrl = route('admin.news.index');
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Tabs -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-add-circle-line text-primary fs-5"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">إنشاء خبر جديد</h6>
                </div>
                <ul class="nav nav-tabs nav-tabs-sm border-0" id="newsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-1 rounded-3" data-bs-toggle="tab" data-bs-target="#form-content">
                            <i class="ri-edit-line me-1"></i> إدخال
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-1 rounded-3" data-bs-toggle="tab" data-bs-target="#preview-content">
                            <i class="ri-eye-line me-1"></i> معاينة
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="newsTabContent">
            <!-- نموذج الإدخال -->
            <div class="tab-pane fade show active" id="form-content">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3 bg-white">
                            <div class="card-body p-4">
                                <!-- النموذج بدون action -->
                                <form id="newsForm" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- العنوان -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-heading fs-6 text-primary"></i>
                                            عنوان الخبر
                                        </label>
                                        <input type="text" name="title" id="titleInput"
                                               class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-primary @error('title') is-invalid @enderror"
                                               placeholder="أدخل عنوانًا..." value="{{ old('title') }}">
                                        @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- تاريخ + حالة + مميز -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                                <i class="ri-calendar-line fs-6 text-info"></i>
                                                تاريخ النشر
                                            </label>
                                            <input type="date" name="published_at" id="dateInput"
                                                   class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-info"
                                                   value="{{ old('published_at', now()->format('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium text-secondary">الحالة</label>
                                            <select name="status" id="statusInput" class="form-select rounded-3 shadow-sm">
                                                <option value="published" @selected(old('status','published')==='published')>منشور</option>
                                                <option value="draft" @selected(old('status')==='draft')>مسودة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="featured" id="featuredInput" value="1" @checked(old('featured'))>
                                                <label class="form-check-label fw-medium" for="featuredInput">مميّز</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- المحتوى -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-text-line fs-6 text-success"></i>
                                            المحتوى
                                        </label>
                                        <div class="quill-wrapper border rounded-3 shadow-sm overflow-hidden">
                                            <div id="quill-toolbar">
                                                <span class="ql-formats">
                                                    <select class="ql-header">
                                                        <option value="1">عنوان 1</option>
                                                        <option value="2">عنوان 2</option>
                                                        <option value="3">عنوان 3</option>
                                                        <option selected>نص عادي</option>
                                                    </select>
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
                                                    <button class="ql-blockquote"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-image" id="imageUploader" title="إضافة صور متعددة"></button>
                                                    <button class="ql-code-block"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-undo">Undo</button>
                                                    <button class="ql-redo">Redo</button>
                                                </span>
                                            </div>
                                            <div id="quill-editor" class="ql-container ql-snow"></div>
                                        </div>
                                        <textarea name="body" id="bodyInput" class="d-none">{{ old('body') }}</textarea>
                                        @error('body') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- صورة الغلاف -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-image-add-line fs-6 text-primary"></i>
                                            صورة الغلاف (اختياري)
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="coverDrop">
                                            <input type="file" name="cover" id="coverInput" class="visually-hidden" accept="image/*">
                                            <div class="text-primary">
                                                <i class="ri-image-add-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    اسحب صورة أو
                                                    <label for="coverInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">اختر ملف</label>
                                                </p>
                                                <small class="text-muted">PNG/JPG • حتى 2MB</small>
                                            </div>
                                        </div>
                                        <div id="coverPreview" class="mt-3"></div>
                                        @error('cover') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- PDF -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-pdf-line fs-6 text-danger"></i>
                                            ملف PDF (اختياري)
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="pdfDrop">
                                            <input type="file" name="pdf" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="ri-upload-cloud-2-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    اسحب ملف أو
                                                    <label for="pdfInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">اختر ملف</label>
                                                </p>
                                                <small class="text-muted">PDF • حتى 10 ميجابايت</small>
                                            </div>
                                        </div>
                                        <div id="pdfPreview" class="mt-3"></div>
                                        @error('pdf') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- أزرار -->
                                    <div class="d-flex flex-wrap gap-2 mt-5">
                                        <button type="button" id="submitBtn" class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm">
                                            <i class="ri-check-line"></i>
                                            <span id="submitText">نشر الخبر</span>
                                            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <button type="button" id="saveDraft" class="btn btn-outline-secondary px-4 d-flex align-items-center gap-2">
                                            <i class="ri-draft-line"></i> حفظ مسودة
                                        </button>
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-link text-muted">إلغاء</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معاينة كاملة -->
            <div class="tab-pane fade" id="preview-content">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-header bg-light py-2 px-3">
                        <h6 class="mb-0 fw-semibold text-primary"><i class="ri-file-search-line me-1"></i> معاينة كاملة</h6>
                    </div>
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="ri-file-search-line fs-4 d-block mb-2"></i>
                            <small>ابدأ الكتابة في تبويب "إدخال"</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        :root { --primary: #4361ee; --success: #10b981; --danger: #ef4444; }
        .quill-wrapper { height: 500px; background: #fff; }
        .ql-container { height: calc(100% - 42px); font-size: 1.1rem; }
        .ql-editor { direction: rtl; text-align: right; min-height: 100%; padding: 1rem; }
        .ql-toolbar { border-bottom: 1px solid #dee2e6; background: #f8f9fa; }
        .dropzone { cursor: pointer; transition: all 0.2s ease; }
        .dropzone.dragover { background: #ebf2ff !important; border-color: var(--primary) !important; }
        .focus-ring:focus { box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15); }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const DRAFT_KEY = 'news_create_draft';
            let draftTimeout;
            let quill;
            let isSubmitting = false;

            const el = {
                title: document.getElementById('titleInput'),
                date: document.getElementById('dateInput'),
                status: document.getElementById('statusInput'),
                featured: document.getElementById('featuredInput'),
                coverInput: document.getElementById('coverInput'),
                coverDrop: document.getElementById('coverDrop'),
                coverPreview: document.getElementById('coverPreview'),
                pdfInput: document.getElementById('pdfInput'),
                pdfDrop: document.getElementById('pdfDrop'),
                pdfPreview: document.getElementById('pdfPreview'),
                bodyInput: document.getElementById('bodyInput'),
                fullPreview: document.getElementById('fullPreview'),
                form: document.getElementById('newsForm'),
                submitBtn: document.getElementById('submitBtn'),
                submitText: document.getElementById('submitText'),
                submitSpinner: document.getElementById('submitSpinner'),
                saveDraftBtn: document.getElementById('saveDraft')
            };

            // تهيئة Quill
            quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'اكتب محتوى الخبر هنا... (يمكنك إضافة صور متعددة)',
                modules: {
                    toolbar: { container: '#quill-toolbar' },
                    history: { delay: 1000, maxStack: 50 }
                }
            });

            // إضافة صور متعددة
            const imageUploader = document.getElementById('imageUploader');
            imageUploader.addEventListener('click', (e) => {
                e.preventDefault();
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.multiple = true;
                input.onchange = () => {
                    Array.from(input.files).forEach(file => {
                        if (file.size > 5 * 1024 * 1024) {
                            Swal.fire('خطأ', `الصورة ${file.name} أكبر من 5MB`, 'error');
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const range = quill.getSelection(true);
                            quill.insertEmbed(range.index, 'image', e.target.result);
                            quill.setSelection(range.index + 1);
                        };
                        reader.readAsDataURL(file);
                    });
                };
                input.click();
            });

            // استعادة مسودة
            const saved = sessionStorage.getItem(DRAFT_KEY);
            if (saved) {
                const d = JSON.parse(saved);
                if (d.title) el.title.value = d.title;
                if (d.date) el.date.value = d.date;
                if (d.status) el.status.value = d.status;
                if (d.featured) el.featured.checked = true;
                if (d.cover) el.coverPreview.innerHTML = d.cover;
                if (d.pdf) el.pdfPreview.innerHTML = d.pdf;
            }

            const update = () => {
                updatePreview();
                autoSave();
            };

            el.title.addEventListener('input', update);
            el.date.addEventListener('change', update);
            el.status.addEventListener('change', update);
            el.featured.addEventListener('change', update);
            quill.on('text-change', update);

            function updatePreview() {
                const title = el.title.value || 'عنوان الخبر';
                const date = el.date.value ? new Date(el.date.value).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' }) : '';
                const content = quill.root.innerHTML || '<p class="text-muted">ابدأ الكتابة...</p>';
                const featured = el.featured.checked ? '<span class="badge bg-warning text-dark px-2 py-1 rounded-pill small ms-2">مميز</span>' : '';
                const status = el.status.value === 'draft' ? '<span class="badge bg-secondary text-white px-2 py-1 rounded-pill small ms-2">مسودة</span>' : '';

                const cover = el.coverPreview.innerHTML;

                const previewHTML = `
                    <article class="p-3">
                        ${cover ? `<div class="mb-3"><img src="${cover.match(/src="([^"]+)"/)?.[1]}" class="w-100 rounded" style="max-height:200px; object-fit:cover;"></div>` : ''}
                        <h5 class="fw-bold text-primary mb-2">${title} ${featured} ${status}</h5>
                        <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                            <i class="ri-calendar-line"></i> <span>${date || 'تاريخ النشر'}</span>
                        </div>
                        <div class="content-preview lh-lg" style="font-size:.95rem;">${content}</div>
                        ${el.pdfPreview.innerHTML ? `<div class="mt-3"><a href="#" class="btn btn-sm btn-outline-danger"><i class="ri-file-pdf-line"></i> عرض المرفق</a></div>` : ''}
                    </article>`;

                el.fullPreview.innerHTML = previewHTML;
            }

            function autoSave() {
                clearTimeout(draftTimeout);
                draftTimeout = setTimeout(() => {
                    const draft = {
                        title: el.title.value,
                        date: el.date.value,
                        status: el.status.value,
                        featured: el.featured.checked,
                        cover: el.coverPreview.innerHTML,
                        pdf: el.pdfPreview.innerHTML
                    };
                    sessionStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                }, 800);
            }

            // رفع صورة الغلاف + PDF
            setupDropzone(el.coverDrop, el.coverInput, handleCover, 2 * 1024 * 1024, 'image/*');
            setupDropzone(el.pdfDrop, el.pdfInput, handlePDF, 10 * 1024 * 1024, 'application/pdf');

            function setupDropzone(dropzone, input, handler, maxSize, accept) {
                dropzone.addEventListener('click', () => input.click());
                ['dragover', 'dragenter'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('dragover'); }));
                ['dragleave', 'dragend', 'drop'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('dragover'); }));
                dropzone.addEventListener('drop', e => { const f = e.dataTransfer.files[0]; if (f) handler(f); });
                input.addEventListener('change', () => { const f = input.files[0]; if (f) handler(f); });
            }

            function handleCover(file) {
                if (!file.type.startsWith('image/')) return Swal.fire('خطأ', 'صورة فقط', 'error');
                if (file.size > 2 * 1024 * 1024) return Swal.fire('خطأ', 'الحد 2MB', 'error');

                const reader = new FileReader();
                reader.onload = () => {
                    el.coverPreview.innerHTML = `<img src="${reader.result}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">`;
                    updatePreview(); autoSave();
                };
                reader.readAsDataURL(file);
            }

            function handlePDF(file) {
                if (file.type !== 'application/pdf') return Swal.fire('خطأ', 'PDF فقط', 'error');
                if (file.size > 10 * 1024 * 1024) return Swal.fire('خطأ', 'الحد 10MB', 'error');

                el.pdfPreview.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center justify-content-between p-2 rounded shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-pdf-line fs-5"></i>
                            <div><strong>${file.name}</strong><br><small>${(file.size/1024/1024).toFixed(2)} MB</small></div>
                        </div>
                        <button type="button" class="btn-close btn-close-sm" onclick="removePDF()"></button>
                    </div>`;
                updatePreview(); autoSave();
            }

            window.removePDF = () => { el.pdfInput.value = ''; el.pdfPreview.innerHTML = ''; updatePreview(); autoSave(); };

            // إرسال النموذج مع إجبار HTTPS ورفع الصور
            el.submitBtn.addEventListener('click', function () {
                if (isSubmitting) return;

                isSubmitting = true;
                el.submitBtn.disabled = true;
                el.submitText.classList.add('d-none');
                el.submitSpinner.classList.remove('d-none');

                el.bodyInput.value = quill.root.innerHTML;
                sessionStorage.removeItem(DRAFT_KEY);

                const formData = new FormData(el.form);

                // الحل النهائي: إنشاء الرابط من route() وإجباره على HTTPS
                let submitUrl = '{{ route('admin.news.store') }}';
                submitUrl = submitUrl.replace(/^http:\/\//i, 'https://');

                fetch(submitUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        // لا تُضيف Content-Type هنا!
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text || `HTTP ${response.status}`); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        window.location.href = data.redirect || '{{ route('admin.news.index') }}';
                    })
                    .catch(error => {
                        console.error('فشل الإرسال:', error);
                        Swal.fire('خطأ', 'تعذر النشر. تحقق من حجم الصور أو الاتصال.', 'error');
                        isSubmitting = false;
                        el.submitBtn.disabled = false;
                        el.submitText.classList.remove('d-none');
                        el.submitSpinner.classList.add('d-none');
                    });
            });

            el.saveDraftBtn.addEventListener('click', () => {
                autoSave();
                Swal.fire({ title: 'تم!', text: 'تم حفظ المسودة', icon: 'success', timer: 1500, showConfirmButton: false });
            });

            updatePreview();
        });
    </script>
@endpush
