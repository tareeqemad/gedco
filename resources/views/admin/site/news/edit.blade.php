@extends('layouts.admin')
@section('title', 'تعديل الخبر #' . $item->id)

@section('content')
    @php
        $breadcrumbTitle     = 'تعديل الخبر #' . $item->id;
        $breadcrumbParent    = 'الأخبار';
        $breadcrumbParentUrl = route('admin.news.index');

        $tagsArr = is_array($item->tags ?? null) ? $item->tags : (is_string($item->tags ?? null) ? json_decode($item->tags, true) : []);
        if (!is_array($tagsArr)) $tagsArr = [];
        $tagsStr = old('tags_string', implode(', ', $tagsArr));
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Tabs -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-edit-box-line text-warning fs-5"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">تعديل الخبر #{{ $item->id }}</h6>
                </div>
                <ul class="nav nav-tabs nav-tabs-sm border-0" id="newsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-1 rounded-3" data-bs-toggle="tab" data-bs-target="#form-content">
                            <i class="ri-edit-line me-1"></i> تعديل
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
            <!-- نموذج التعديل -->
            <div class="tab-pane fade show active" id="form-content">
                <div class="row g-4">
                    <!-- النموذج -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                            <div class="card-body p-4">
                                <form id="newsForm" action="{{ route('admin.news.update', $item) }}" method="POST" enctype="multipart/form-data" novalidate>
                                    @csrf @method('PUT')

                                    <!-- العنوان -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-heading fs-6 text-primary"></i>
                                            عنوان الخبر <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" id="titleInput" maxlength="255"
                                               class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-primary @error('title') is-invalid @enderror"
                                               placeholder="أدخل عنوانًا واضحًا..." value="{{ old('title', $item->title) }}" required>
                                        @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small d-flex justify-content-between">
                                            <span id="titleCount">{{ strlen(old('title', $item->title ?? '')) }}</span>
                                            <span>/ 255 حرف</span>
                                        </div>
                                    </div>

                                    <!-- تاريخ + حالة + مميز -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                                <i class="ri-calendar-line fs-6 text-info"></i>
                                                تاريخ النشر <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="published_at" id="dateInput"
                                                   class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-info @error('published_at') is-invalid @enderror"
                                                   value="{{ old('published_at', optional($item->published_at)->format('Y-m-d')) }}" required>
                                            @error('published_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium text-secondary">الحالة</label>
                                            <select name="status" id="statusInput" class="form-select rounded-3 shadow-sm">
                                                <option value="published" @selected(old('status', $item->status) === 'published')>منشور</option>
                                                <option value="draft" @selected(old('status', $item->status) === 'draft')>مسودة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="featured" id="featuredInput" value="1" @checked(old('featured', $item->featured))>
                                                <label class="form-check-label fw-medium" for="featuredInput">مميّز</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- الوسوم -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary">
                                            <i class="ri-price-tag-3-line text-success"></i> وسوم (افصل بفاصلة)
                                        </label>
                                        <input type="text" name="tags_string" id="tagsInput" class="form-control rounded-3 border-0 shadow-sm"
                                               placeholder="سياسة, اقتصاد, تكنولوجيا..." value="{{ $tagsStr }}">
                                        <input type="hidden" name="tags" id="tagsHidden" value='@json($tagsArr)'>
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
                                                    <button class="ql-undo" title="تراجع">Undo</button>
                                                    <button class="ql-redo" title="إعادة">Redo</button>
                                                </span>
                                            </div>
                                            <div id="quill-editor" class="ql-container ql-snow"></div>
                                        </div>
                                        <textarea name="body" id="bodyInput" class="d-none">{{ old('body', $item->body) }}</textarea>
                                        @error('body') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small mt-1">
                                            <span id="wordCount">0</span> كلمة
                                        </div>
                                    </div>

                                    <!-- صورة الغلاف الحالية -->
                                    @if($item->cover_path ?? $item->cover_url ?? false)
                                        <div id="currentCoverBox" class="mb-3 p-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between border">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $item->cover_url ?? Storage::url($item->cover_path) }}" alt="cover" class="rounded" style="width:90px;height:64px;object-fit:cover;">
                                                <div><small class="text-muted d-block">صورة الغلاف الحالية</small></div>
                                            </div>
                                            <div>
                                                <a href="{{ $item->cover_url ?? Storage::url($item->cover_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnRemoveCover">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="remove_current_cover" id="removeCurrentCover" value="0">
                                    @endif

                                    <!-- استبدال صورة الغلاف -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-image-add-line fs-6 text-primary"></i>
                                            استبدال صورة الغلاف (اختياري)
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="coverDrop">
                                            <input type="file" name="cover" id="coverInput" class="visually-hidden" accept="image/*">
                                            <div class="text-primary">
                                                <i class="ri-image-add-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    اسحب صورة أو
                                                    <label for="coverInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">اختر ملف</label>
                                                </p>
                                                <small class="text-muted">PNG/JPG • حتى 2MB • نسبة 16:9</small>
                                            </div>
                                        </div>
                                        <div id="coverPreview" class="mt-3"></div>
                                        @error('cover') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- PDF الحالي -->
                                    @if($item->pdf_path ?? $item->pdf_url ?? false)
                                        <div id="currentPdfBox" class="mb-3 p-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between border">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-file-pdf-line text-danger fs-5"></i>
                                                <div>
                                                    <a href="{{ $item->pdf_url ?? Storage::url($item->pdf_path) }}" target="_blank" class="fw-medium text-decoration-none">
                                                        {{ basename($item->pdf_path ?? parse_url($item->pdf_url, PHP_URL_PATH)) }}
                                                    </a>
                                                    <small class="text-muted d-block">ملف PDF الحالي</small>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ $item->pdf_url ?? Storage::url($item->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnRemoveCurrentPdf">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="remove_current_pdf" id="removeCurrentPdf" value="0">
                                    @endif

                                    <!-- استبدال PDF -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-pdf-line fs-6 text-danger"></i>
                                            استبدال ملف PDF (اختياري)
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
                                        <button type="submit" class="btn btn-success px-4 d-flex align-items-center gap-2 shadow-sm">
                                            <i class="ri-check-line"></i> تحديث الخبر
                                        </button>
                                        <button type="button" id="saveDraft" class="btn btn-outline-secondary px-4 d-flex align-items-center gap-2">
                                            <i class="ri-draft-line"></i> حفظ مسودة
                                        </button>
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-link text-muted">رجوع</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- المعاينة الحية -->
                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="card border-0 shadow-sm rounded-3 bg-white sticky-top" style="top: 1rem;">
                            <div class="card-header bg-light py-2 px-3">
                                <h6 class="mb-0 fw-semibold text-primary"><i class="ri-eye-line me-1"></i> معاينة حية</h6>
                            </div>
                            <div class="card-body p-0" id="livePreview">
                                <div class="text-center text-muted py-5">
                                    <i class="ri-file-search-line fs-4 d-block mb-2"></i>
                                    <small>ابدأ التعديل لرؤية المعاينة</small>
                                </div>
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
                            <small>انتقل إلى تبويب "تعديل" لتحرير الخبر</small>
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
            --warning: #f59e0b;
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
        .badge-featured { background: linear-gradient(135deg, #f59e0b, #f97316); color: white; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const DRAFT_KEY = `news_edit_draft_{{ $item->id }}`;
            let draftTimeout;
            let quill;

            // عناصر DOM
            const el = {
                title: document.getElementById('titleInput'),
                date: document.getElementById('dateInput'),
                status: document.getElementById('statusInput'),
                featured: document.getElementById('featuredInput'),
                tags: document.getElementById('tagsInput'),
                tagsHidden: document.getElementById('tagsHidden'),
                coverInput: document.getElementById('coverInput'),
                coverDrop: document.getElementById('coverDrop'),
                coverPreview: document.getElementById('coverPreview'),
                currentCoverBox: document.getElementById('currentCoverBox'),
                removeCurrentCover: document.getElementById('removeCurrentCover'),
                pdfInput: document.getElementById('pdfInput'),
                pdfDrop: document.getElementById('pdfDrop'),
                pdfPreview: document.getElementById('pdfPreview'),
                currentPdfBox: document.getElementById('currentPdfBox'),
                removeCurrentPdf: document.getElementById('removeCurrentPdf'),
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
                placeholder: 'اكتب محتوى الخبر هنا...',
                modules: {
                    toolbar: { container: '#quill-toolbar' },
                    history: { delay: 1000, maxStack: 50 }
                }
            });

            // تحميل المحتوى الأصلي
            const initialBody = @json(old('body', $item->body ?? ''));
            if (initialBody) quill.root.innerHTML = initialBody;

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
                if (d.title) el.title.value = d.title;
                if (d.date) el.date.value = d.date;
                if (d.status) el.status.value = d.status;
                if ('featured' in d) el.featured.checked = d.featured;
                if (d.body) quill.root.innerHTML = d.body;
                if (d.tags) el.tags.value = d.tags;
                if (d.cover) el.coverPreview.innerHTML = d.cover;
                if (d.pdf) el.pdfPreview.innerHTML = d.pdf;
                if (d.removeCurrentCover && el.removeCurrentCover) { el.removeCurrentCover.value = '1'; el.currentCoverBox?.remove(); }
                if (d.removeCurrentPdf && el.removeCurrentPdf) { el.removeCurrentPdf.value = '1'; el.currentPdfBox?.remove(); }
            }

            // تحديثات فورية
            const update = () => {
                updateCounters();
                updatePreviews();
                syncTags();
                autoSave();
            };

            el.title.addEventListener('input', update);
            el.date.addEventListener('change', update);
            el.status.addEventListener('change', update);
            el.featured.addEventListener('change', update);
            el.tags.addEventListener('input', update);
            quill.on('text-change', update);

            function updateCounters() {
                el.titleCount.textContent = el.title.value.length;
                const words = quill.getText().trim().split(/\s+/).filter(Boolean).length;
                el.wordCount.textContent = words;
            }

            function syncTags() {
                const tags = el.tags.value.split(',').map(t => t.trim()).filter(Boolean);
                el.tagsHidden.value = tags.length ? JSON.stringify(tags) : '';
            }

            function updatePreviews() {
                const title = el.title.value || 'عنوان الخبر';
                const date = el.date.value ? new Date(el.date.value).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' }) : '';
                const content = quill.root.innerHTML || '<p class="text-muted">لا يوجد محتوى...</p>';
                const featured = el.featured.checked ? '<span class="badge badge-featured px-2 py-1 rounded-pill small ms-2">مميز</span>' : '';
                const status = el.status.value === 'draft' ? '<span class="badge bg-warning text-dark small ms-2">مسودة</span>' : '';

                const tags = el.tags.value.split(',').map(t => t.trim()).filter(Boolean);
                const tagsHTML = tags.length ? `<div class="mt-3"><small class="text-muted">الوسوم:</small> ${tags.map(t => `<span class="badge bg-light text-dark small mx-1">${t}</span>`).join('')}</div>` : '';

                const coverSrc = el.coverPreview.innerHTML.match(/src="([^"]+)"/)?.[1] ||
                    (el.currentCoverBox ? el.currentCoverBox.querySelector('img')?.src : '');

                const previewHTML = `
                    <article class="news-preview p-3">
                        ${coverSrc ? `<div class="mb-3"><img src="${coverSrc}" class="w-100 rounded" style="max-height:200px; object-fit:cover;"></div>` : ''}
                        <h5 class="fw-bold text-primary mb-2">${title} ${featured} ${status}</h5>
                        <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                            <i class="ri-calendar-line"></i> <span>${date || 'تاريخ النشر'}</span>
                        </div>
                        <div class="content-preview lh-lg" style="font-size:.95rem;">${content}</div>
                        ${tagsHTML}
                        ${el.pdfPreview.innerHTML || (el.currentPdfBox ? `<div class="mt-3"><a href="#" class="btn btn-sm btn-outline-danger"><i class="ri-file-pdf-line"></i> عرض المرفق</a></div>` : '')}
                    </article>`;

                [el.livePreview, el.fullPreview].forEach(e => e.innerHTML = previewHTML);
            }

            function autoSave() {
                clearTimeout(draftTimeout);
                draftTimeout = setTimeout(() => {
                    const draft = {
                        title: el.title.value,
                        date: el.date.value,
                        status: el.status.value,
                        featured: el.featured.checked,
                        body: quill.root.innerHTML,
                        tags: el.tags.value,
                        cover: el.coverPreview.innerHTML,
                        pdf: el.pdfPreview.innerHTML,
                        removeCurrentCover: el.removeCurrentCover?.value === '1',
                        removeCurrentPdf: el.removeCurrentPdf?.value === '1'
                    };
                    localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                }, 800);
            }

            // Drag & Drop
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
                if (!file.type.startsWith('image/')) return Swal.fire('خطأ', 'يرجى رفع صورة فقط', 'error');
                if (file.size > 2 * 1024 * 1024) return Swal.fire('خطأ', 'الحد الأقصى 2 ميجابايت', 'error');

                const reader = new FileReader();
                reader.onload = () => {
                    el.coverPreview.innerHTML = `<img src="${reader.result}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">`;
                    updatePreviews(); autoSave();
                };
                reader.readAsDataURL(file);
            }

            function handlePDF(file) {
                if (file.type !== 'application/pdf') return Swal.fire('خطأ', 'PDF فقط', 'error');
                if (file.size > 10 * 1024 * 1024) return Swal.fire('خطأ', 'الحد الأقصى 10 ميجابايت', 'error');

                el.pdfPreview.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center justify-content-between p-2 rounded shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-pdf-line fs-5"></i>
                            <div><strong>${file.name}</strong><br><small>${(file.size/1024/1024).toFixed(2)} MB</small></div>
                        </div>
                        <button type="button" class="btn-close btn-close-sm" onclick="removeNewPDF()"></button>
                    </div>`;
                updatePreviews(); autoSave();
            }

            window.removeNewPDF = () => { el.pdfInput.value = ''; el.pdfPreview.innerHTML = ''; updatePreviews(); autoSave(); };

            // حذف الحالي
            document.getElementById('btnRemoveCover')?.addEventListener('click', () => {
                Swal.fire({ title: 'تأكيد', text: 'حذف صورة الغلاف الحالية؟', icon: 'warning', showCancelButton: true }).then(res => {
                    if (res.isConfirmed) { el.removeCurrentCover.value = '1'; el.currentCoverBox.remove(); updatePreviews(); autoSave(); }
                });
            });

            document.getElementById('btnRemoveCurrentPdf')?.addEventListener('click', () => {
                Swal.fire({ title: 'تأكيد', text: 'حذف ملف PDF الحالي؟', icon: 'warning', showCancelButton: true }).then(res => {
                    if (res.isConfirmed) { el.removeCurrentPdf.value = '1'; el.currentPdfBox.remove(); updatePreviews(); autoSave(); }
                });
            });

            // إرسال النموذج
            el.form.addEventListener('submit', () => {
                el.bodyInput.value = quill.root.innerHTML;
                localStorage.removeItem(DRAFT_KEY);
            });

            el.saveDraftBtn.addEventListener('click', () => {
                autoSave();
                Swal.fire({ title: 'تم!', text: 'تم حفظ المسودة محليًا', icon: 'success', timer: 1500, showConfirmButton: false });
            });

            // تحديث أولي
            update();
        });
    </script>
@endpush
