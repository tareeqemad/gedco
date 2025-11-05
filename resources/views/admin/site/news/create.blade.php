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
                    <!-- النموذج -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                            <div class="card-body p-4">
                                <form id="newsForm" action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                                    @csrf

                                    <!-- العنوان -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-heading fs-6 text-primary"></i>
                                            عنوان الخبر <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" id="titleInput" maxlength="255"
                                               class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-primary @error('title') is-invalid @enderror"
                                               placeholder="أدخل عنوانًا جذابًا وواضحًا..." value="{{ old('title') }}" required>
                                        @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small d-flex justify-content-between">
                                            <span id="titleCount">{{ strlen(old('title') ?? '') }}</span>
                                            <span>/ 255 حرف</span>
                                        </div>
                                    </div>

                                    <!-- تاريخ النشر + الحالة + مميز -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                                <i class="ri-calendar-line fs-6 text-info"></i>
                                                تاريخ النشر <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="published_at" id="dateInput"
                                                   class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-info @error('published_at') is-invalid @enderror"
                                                   value="{{ old('published_at', now()->format('Y-m-d')) }}" required>
                                            @error('published_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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

                                    <!-- الوسوم -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary">
                                            <i class="ri-price-tag-3-line text-success"></i> وسوم (افصل بفاصلة)
                                        </label>
                                        <input type="text" id="tagsInput" class="form-control rounded-3 border-0 shadow-sm"
                                               placeholder="سياسة, اقتصاد, تكنولوجيا..." value="{{ old('tags_string') }}">
                                        <input type="hidden" name="tags" id="tagsHidden">
                                        <div class="form-text small text-muted">مثال: سياسة, طاقة, شركات</div>
                                    </div>

                                    <!-- المحتوى -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-text-line fs-6 text-success"></i>
                                            المحتوى <span class="text-danger">*</span>
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
                                                    <button class="ql-image"></button>
                                                    <button class="ql-code-block"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-undo" title="تراجع">↶</button>
                                                    <button class="ql-redo" title="إعادة">↷</button>
                                                </span>
                                            </div>
                                            <div id="quill-editor" class="ql-container ql-snow"></div>
                                        </div>
                                        <textarea name="body" id="bodyInput" class="d-none">{{ old('body') }}</textarea>
                                        @error('body') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small mt-1">
                                            <span id="wordCount">0</span> كلمة
                                        </div>
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
                                                <small class="text-muted">PNG/JPG • حتى 2MB • نسبة 16:9 مفضّلة</small>
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

                                    <!-- أزرار الإرسال -->
                                    <div class="d-flex flex-wrap gap-2 mt-5">
                                        <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm">
                                            <i class="ri-check-line"></i> نشر الخبر
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

                    <!-- المعاينة الحية (جانبية) -->
                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="card border-0 shadow-sm rounded-3 bg-white sticky-top" style="top: 1rem;">
                            <div class="card-header bg-light py-2 px-3">
                                <h6 class="mb-0 fw-semibold text-primary"><i class="ri-eye-line me-1"></i> معاينة حية</h6>
                            </div>
                            <div class="card-body p-0" id="livePreview">
                                <div class="text-center text-muted py-5">
                                    <i class="ri-file-search-line fs-4 d-block mb-2"></i>
                                    <small>ابدأ الكتابة لرؤية المعاينة</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تبويب المعاينة الكاملة -->
            <div class="tab-pane fade" id="preview-content">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-header bg-light py-2 px-3">
                        <h6 class="mb-0 fw-semibold text-primary"><i class="ri-file-search-line me-1"></i> معاينة كاملة</h6>
                    </div>
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="ri-file-search-line fs-4 d-block mb-2"></i>
                            <small>انتقل إلى تبويب "إدخال" لتحرير الخبر</small>
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
        :root {
            --primary: #4361ee;
            --info: #3f83f8;
            --success: #10b981;
            --danger: #ef4444;
        }

        .quill-wrapper {
            height: 460px;
            background: #fff;
        }
        .ql-container {
            height: calc(100% - 42px);
            font-size: 1rem;
        }
        .ql-editor {
            direction: rtl;
            text-align: right;
            min-height: 100%;
            padding: 1rem;
        }
        .ql-toolbar {
            border-bottom: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        .dropzone {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .dropzone.dragover {
            background: #ebf2ff !important;
            border-color: var(--primary) !important;
            transform: scale(1.01);
        }
        .focus-ring:focus {
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }
        .transition { transition: all 0.2s ease; }
        .badge-featured { background: linear-gradient(135deg, #f59e0b, #f97316); color: white; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const DRAFT_KEY = 'news_create_draft_v2';
            let draftTimeout;
            let quill;

            // عناصر DOM
            const elements = {
                title: document.getElementById('titleInput'),
                date: document.getElementById('dateInput'),
                status: document.getElementById('statusInput'),
                featured: document.getElementById('featuredInput'),
                tags: document.getElementById('tagsInput'),
                tagsHidden: document.getElementById('tagsHidden'),
                coverInput: document.getElementById('coverInput'),
                coverDrop: document.getElementById('coverDrop'),
                coverPreview: document.getElementById('coverPreview'),
                pdfInput: document.getElementById('pdfInput'),
                pdfDrop: document.getElementById('pdfDrop'),
                pdfPreview: document.getElementById('pdfPreview'),
                bodyInput: document.getElementById('bodyInput'),
                wordCount: document.getElementById('wordCount'),
                titleCount: document.getElementById('titleCount'),
                livePreview: document.getElementById('livePreview'),
                fullPreview: document.getElementById('fullPreview'),
                form: document.getElementById('newsForm'),
                saveDraftBtn: document.getElementById('saveDraft')
            };

            // تهيئة Quill
            quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'اكتب محتوى الخبر هنا... (يمكنك إضافة صور، روابط، قوائم...)',
                modules: {
                    toolbar: { container: '#quill-toolbar' },
                    history: { delay: 1000, maxStack: 50 }
                }
            });

            // أحداث التراجع/الإعادة
            document.querySelectorAll('.ql-undo, .ql-redo').forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.classList.contains('ql-undo') ? quill.history.undo() : quill.history.redo();
                });
            });

            // استعادة المسودة
            const saved = localStorage.getItem(DRAFT_KEY);
            if (saved) {
                const d = JSON.parse(saved);
                if (d.title) elements.title.value = d.title;
                if (d.date) elements.date.value = d.date;
                if (d.status) elements.status.value = d.status;
                if (d.featured) elements.featured.checked = true;
                if (d.body) quill.root.innerHTML = d.body;
                if (d.tags) elements.tags.value = d.tags;
                if (d.cover) elements.coverPreview.innerHTML = d.cover;
                if (d.pdf) elements.pdfPreview.innerHTML = d.pdf;
            }

            // تحديثات فورية
            const update = () => {
                updateCounters();
                updatePreviews();
                syncTags();
                autoSave();
            };

            elements.title.addEventListener('input', update);
            elements.date.addEventListener('change', update);
            elements.status.addEventListener('change', update);
            elements.featured.addEventListener('change', update);
            elements.tags.addEventListener('input', update);
            quill.on('text-change', update);

            function updateCounters() {
                elements.titleCount.textContent = elements.title.value.length;
                const words = quill.getText().trim().split(/\s+/).filter(Boolean).length;
                elements.wordCount.textContent = words;
            }

            function syncTags() {
                const tags = elements.tags.value.split(',').map(t => t.trim()).filter(Boolean);
                elements.tagsHidden.value = tags.length ? JSON.stringify(tags) : '';
            }

            function updatePreviews() {
                const title = elements.title.value || 'عنوان الخبر';
                const date = elements.date.value ? new Date(elements.date.value).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' }) : '';
                const content = quill.root.innerHTML || '<p class="text-muted">ابدأ الكتابة...</p>';
                const featured = elements.featured.checked ? '<span class="badge badge-featured px-2 py-1 rounded-pill small ms-2">مميز</span>' : '';
                const status = elements.status.value === 'draft' ? '<span class="badge bg-warning text-dark small ms-2">مسودة</span>' : '';

                const tags = elements.tags.value.split(',').map(t => t.trim()).filter(Boolean);
                const tagsHTML = tags.length ? `<div class="mt-3"><small class="text-muted">الوسوم:</small> ${tags.map(t => `<span class="badge bg-light text-dark small mx-1">${t}</span>`).join('')}</div>` : '';

                const cover = elements.coverPreview.innerHTML;

                const previewHTML = `
                    <article class="news-preview p-3">
                        ${cover ? `<div class="mb-3"><img src="${cover.match(/src="([^"]+)"/)?.[1]}" class="w-100 rounded" style="max-height:200px; object-fit:cover;"></div>` : ''}
                        <h5 class="fw-bold text-primary mb-2">${title} ${featured} ${status}</h5>
                        <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                            <i class="ri-calendar-line"></i> <span>${date || 'تاريخ النشر'}</span>
                        </div>
                        <div class="content-preview lh-lg" style="font-size:.95rem;">${content}</div>
                        ${tagsHTML}
                        ${elements.pdfPreview.innerHTML ? `<div class="mt-3"><a href="#" class="btn btn-sm btn-outline-danger"><i class="ri-file-pdf-line"></i> عرض المرفق</a></div>` : ''}
                    </article>`;

                [elements.livePreview, elements.fullPreview].forEach(el => el.innerHTML = previewHTML);
            }

            function autoSave() {
                clearTimeout(draftTimeout);
                draftTimeout = setTimeout(() => {
                    const draft = {
                        title: elements.title.value,
                        date: elements.date.value,
                        status: elements.status.value,
                        featured: elements.featured.checked,
                        body: quill.root.innerHTML,
                        tags: elements.tags.value,
                        cover: elements.coverPreview.innerHTML,
                        pdf: elements.pdfPreview.innerHTML
                    };
                    localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                }, 800);
            }

            // معالجة الصور
            setupDropzone(elements.coverDrop, elements.coverInput, handleCover, 2 * 1024 * 1024, 'image/*');
            // معالجة PDF
            setupDropzone(elements.pdfDrop, elements.pdfInput, handlePDF, 10 * 1024 * 1024, 'application/pdf');

            function setupDropzone(dropzone, input, handler, maxSize, accept) {
                dropzone.addEventListener('click', () => input.click());
                ['dragover', 'dragenter'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('dragover'); }));
                ['dragleave', 'dragend', 'drop'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('dragover'); }));
                dropzone.addEventListener('drop', e => { const f = e.dataTransfer.files[0]; if (f) handler(f); });
                input.addEventListener('change', () => { const f = input.files[0]; if (f) handler(f); });
            }

            function handleCover(file) {
                if (!file.type.startsWith('image/')) return Swal.fire('خطأ', 'يرجى رفع صورة فقط', 'error');
                if (file.size > 2 * 1024 * 1024) return Swal.fire('خطأ', 'الحد الأقصى 2 ميجابايت', 'error');

                const reader = new FileReader();
                reader.onload = () => {
                    elements.coverPreview.innerHTML = `<img src="${reader.result}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">`;
                    updatePreviews(); autoSave();
                };
                reader.readAsDataURL(file);
            }

            function handlePDF(file) {
                if (file.type !== 'application/pdf') return Swal.fire('خطأ', 'PDF فقط', 'error');
                if (file.size > 10 * 1024 * 1024) return Swal.fire('خطأ', 'الحد الأقصى 10 ميجابايت', 'error');

                elements.pdfPreview.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center justify-content-between p-2 rounded shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-pdf-line fs-5"></i>
                            <div><strong>${file.name}</strong><br><small>${(file.size/1024/1024).toFixed(2)} MB</small></div>
                        </div>
                        <button type="button" class="btn-close btn-close-sm" onclick="removePDF()"></button>
                    </div>`;
                updatePreviews(); autoSave();
            }

            window.removePDF = () => { elements.pdfInput.value = ''; elements.pdfPreview.innerHTML = ''; updatePreviews(); autoSave(); };

            // إرسال النموذج
            elements.form.addEventListener('submit', () => {
                elements.bodyInput.value = quill.root.innerHTML;
                localStorage.removeItem(DRAFT_KEY);
            });

            elements.saveDraftBtn.addEventListener('click', () => {
                autoSave();
                Swal.fire({ title: 'تم!', text: 'تم حفظ المسودة محليًا', icon: 'success', timer: 1500, showConfirmButton: false });
            });

            // تحديث أولي
            update();
        });
    </script>
@endpush
