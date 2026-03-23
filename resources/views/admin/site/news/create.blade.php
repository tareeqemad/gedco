@extends('layouts.admin')
@section('title', __('admin.news.create_title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.news.create_title');
        $breadcrumbParent    = __('admin.menu.news');
        $breadcrumbParentUrl = route('admin.news.index');

        // القيود
        $MAX_IMAGES = 8;
        $MAX_IMAGE_BYTES = 2 * 1024 * 1024; // 2MB
    @endphp

    <div class="container-fluid p-0" id="news-create-page">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header card-header-form border-0 py-2 py-md-3 px-3 px-md-4">
                <div class="d-flex justify-content-between align-items-center w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white" style="font-size: 1.25rem; line-height: 1.3;">{{ __('admin.news.create_news') }}</h5>
                        </div>
                    </div>
                    <ul class="nav nav-tabs nav-tabs-sm border-0" id="newsTabs" role="tablist" style="margin-inline-start: auto;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-3 py-2 rounded-3 text-white" data-bs-toggle="tab" data-bs-target="#form-content" type="button" role="tab" aria-selected="true" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <i class="bi bi-pencil me-1"></i> {{ __('admin.news.form_tab_input') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3 py-2 rounded-3 text-white" data-bs-toggle="tab" data-bs-target="#preview-content" type="button" role="tab" aria-selected="false" style="background: transparent; border: 1px solid rgba(255, 255, 255, 0.3);">
                                <i class="bi bi-eye me-1"></i> {{ __('admin.news.form_tab_preview') }}
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content" id="newsTabContent">
            <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-white">
                            <div class="card-body p-3 p-md-4">
                                <form id="newsForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.news.store') }}">
                                    @csrf

                                    <input type="file" id="quillImageInput" accept="image/*" multiple class="visually-hidden">

                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-type-h1 text-primary"></i> {{ __('admin.news.form_title') }}
                                        </label>
                                        <input type="text" name="title" id="titleInput"
                                               class="form-control rounded-3 border-0 bg-light focus-ring focus-ring-primary @error('title') is-invalid @enderror"
                                               placeholder="{{ __('admin.news.form_title_placeholder') }}" value="{{ old('title') }}">
                                        @error('title') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Date + Status + Featured -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                                <i class="bi bi-calendar text-info"></i> {{ __('admin.news.form_publish_date') }}
                                            </label>
                                            <input type="date" name="published_at" id="dateInput"
                                                   class="form-control rounded-3 border-0 bg-light focus-ring focus-ring-info @error('published_at') is-invalid @enderror"
                                                   value="{{ old('published_at', now()->format('Y-m-d')) }}">
                                            @error('published_at') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.news.form_status') }}</label>
                                            <select name="status" id="statusInput" class="form-select rounded-3 border-0 bg-light @error('status') is-invalid @enderror">
                                                <option value="published" @selected(old('status','published')==='published')>{{ __('admin.news.status_published') }}</option>
                                                <option value="draft" @selected(old('status')==='draft')>{{ __('admin.news.status_draft') }}</option>
                                            </select>
                                            @error('status') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="form-check form-switch form-switch-lg mb-2">
                                                <input class="form-check-input" type="checkbox" name="featured" id="featuredInput" value="1" @checked(old('featured')) style="width: 3rem; height: 1.5rem;">
                                                <label class="form-check-label fw-semibold text-dark ms-2" for="featuredInput">{{ __('admin.news.form_featured') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Language - Hidden, automatically set based on admin panel language -->
                                    @php
                                        $currentDirection = session('direction', 'rtl');
                                        $defaultLang = $currentDirection === 'rtl' ? 'ar' : 'en';
                                    @endphp
                                    <input type="hidden" name="language" value="{{ old('language', $defaultLang) }}">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="form-label mb-0" style="font-size: 0.82rem; color: #24364A; font-weight: 600;">اللغة:</label>
                                            <span class="stat-chip">
                                                <i class="bi bi-globe"></i>
                                                {{ $defaultLang === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
                                            </span>
                                        </div>
                                        @error('language') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Content (Quill) -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 flex-wrap mb-3">
                                            <span class="d-inline-flex align-items-center gap-2">
                                                <i class="bi bi-file-text text-success"></i> {{ __('admin.news.form_content') }}
                                            </span>
                                            <small class="text-muted">{{ __('admin.news.form_content_info') }} {{ $MAX_IMAGES }} {{ __('admin.news.form_content_info_end') }}</small>
                                            <!-- Image Counter -->
                                            <span id="imgCounter" class="badge img-counter bg-primary rounded-pill px-3 py-2">0 / {{ $MAX_IMAGES }}</span>
                                            <!-- Text Counter -->
                                            <span id="textCounter" class="badge text-counter bg-secondary rounded-pill px-3 py-2 ms-1">{{ __('admin.news.characters') }} 0 | {{ __('admin.news.words') }} 0</span>
                                        </label>

                                        <div class="quill-wrapper border rounded-4 shadow-sm overflow-hidden">
                                            <div id="quill-toolbar" class="px-2 py-1">
                                                <span class="ql-formats">
                                                    <select class="ql-font">
                                                        <option value="system" selected>System</option>
                                                        <option value="cairo">Cairo</option>
                                                        <option value="tajawal">Tajawal</option>
                                                    </select>
                                                </span>
                                                <span class="ql-formats">
                                                    <select class="ql-size">
                                                        <option value="12px">12</option>
                                                        <option value="14px">14</option>
                                                        <option value="16px" selected>16</option>
                                                        <option value="18px">18</option>
                                                        <option value="24px">24</option>
                                                        <option value="32px">32</option>
                                                    </select>
                                                </span>
                                                <span class="ql-formats">
                                                    <select class="ql-lineheight">
                                                        <option value="">LH</option>
                                                        <option value="1.4">1.4</option>
                                                        <option value="1.6" selected>1.6</option>
                                                        <option value="1.8">1.8</option>
                                                        <option value="2">2.0</option>
                                                    </select>
                                                </span>
                                                <span class="ql-formats">
                                                    <select class="ql-header">
                                                        <option value="1">{{ __('admin.news.quill_header_1') }}</option>
                                                        <option value="2">{{ __('admin.news.quill_header_2') }}</option>
                                                        <option value="3">{{ __('admin.news.quill_header_3') }}</option>
                                                        <option selected>{{ __('admin.news.quill_normal') }}</option>
                                                    </select>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-bold"></button>
                                                    <button class="ql-italic"></button>
                                                    <button class="ql-underline"></button>
                                                    <button class="ql-strike"></button>
                                                    <button class="ql-link"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-list" value="ordered"></button>
                                                    <button class="ql-list" value="bullet"></button>
                                                    <button class="ql-blockquote"></button>
                                                    <button class="ql-code-block"></button>
                                                    <button class="ql-clean"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-align" value="right"></button>
                                                    <button class="ql-align" value="center"></button>
                                                    <button class="ql-align" value="left"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-image" id="imageUploader" title="{{ __('admin.news.quill_add_images') }}"></button>
                                                </span>
                                                <span class="ql-formats">
                                                      <button type="button" class="ql-undo" title="Undo">↶</button>
                                                      <button type="button" class="ql-redo" title="Redo">↷</button>
                                                </span>
                                            </div>

                                            <div id="quill-editor" class="ql-container ql-snow"></div>
                                        </div>

                                        <textarea name="body" id="bodyInput" class="d-none">{{ old('body') }}</textarea>
                                        @error('body') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Cover Image -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-image text-primary"></i> {{ __('admin.news.form_cover_image') }}
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-4 p-4 p-md-5 text-center bg-light transition-all" id="coverDrop" style="cursor: pointer; min-height: 180px; display: flex; align-items: center; justify-content: center;">
                                            <input type="file" name="cover" id="coverInput" class="visually-hidden" accept="image/*">
                                            <div class="text-primary">
                                                <i class="bi bi-image fs-1 mb-3 d-block"></i>
                                                <p class="mb-2 fw-semibold" style="font-size: 1rem;">
                                                    {{ __('admin.news.form_cover_drag') }}
                                                    <label for="coverInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.news.form_cover_select') }}</label>
                                                </p>
                                                <small class="text-muted">{{ __('admin.news.form_cover_formats') }}</small>
                                            </div>
                                        </div>
                                        <div id="coverPreview" class="mt-3"></div>
                                        @error('cover') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- PDF -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-file-pdf text-danger"></i> {{ __('admin.news.form_pdf') }}
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-4 p-4 p-md-5 text-center bg-light transition-all" id="pdfDrop" style="cursor: pointer; min-height: 180px; display: flex; align-items: center; justify-content: center;">
                                            <input type="file" name="pdf" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="bi bi-cloud-upload fs-1 mb-3 d-block"></i>
                                                <p class="mb-2 fw-semibold" style="font-size: 1rem;">
                                                    {{ __('admin.news.form_pdf_drag') }}
                                                    <label for="pdfInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.news.form_pdf_select') }}</label>
                                                </p>
                                                <small class="text-muted">{{ __('admin.news.form_pdf_formats') }}</small>
                                            </div>
                                        </div>
                                        <div id="pdfPreview" class="mt-3"></div>
                                        @error('pdf') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex flex-wrap gap-3 mt-5 pt-3 border-top">
                                        <button type="button" id="submitBtn" class="btn btn-save d-flex align-items-center gap-2">
                                            <i class="bi bi-check-lg"></i>
                                            <span id="submitText">{{ __('admin.news.form_publish') }}</span>
                                            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <button type="button" id="saveDraft" class="btn btn-outline-secondary px-5 py-2 d-flex align-items-center gap-2 rounded-3" style="min-width: 150px; font-weight: 600;">
                                            <i class="bi bi-file-earmark"></i> {{ __('admin.news.form_save_draft') }}
                                        </button>
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-cancel d-flex align-items-center">
                                            <i class="bi bi-x-circle me-1"></i> {{ __('admin.news.form_cancel') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Tab -->
            <div class="tab-pane fade" id="preview-content" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-header card-header-form py-2 py-md-3 px-3 px-md-4">
                        <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                            <i class="bi bi-eye"></i> {{ __('admin.news.form_preview_title') }}
                        </h5>
                    </div>
                    <div class="card-body p-4 p-md-5" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-eye fs-1 d-block mb-3 text-primary opacity-50"></i>
                            <p class="mb-0" style="font-size: 1rem;">{{ __('admin.news.form_preview_start_writing') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/news.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const DRAFT_KEY = 'news_create_draft';
            const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const MAX_IMAGES = {{ $MAX_IMAGES }};
            const MAX_IMAGE_BYTES = {{ $MAX_IMAGE_BYTES }};
            let draftTimeout;
            let quill;
            let isSubmitting = false;
            let isPickingImages = false;
            let coverSrc = '';

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
                saveDraftBtn: document.getElementById('saveDraft'),
                quillImageInput: document.getElementById('quillImageInput'),
            };

            const warn = (title, text) => Swal.fire(title, text, 'warning');
            const err  = (title, text) => Swal.fire(title, text, 'error');

            // Helpers لعرض/مسح أخطاء الحقول
            function clearFieldError(inputEl) {
                if (!inputEl) return;
                inputEl.classList.remove('is-invalid');
                const next = inputEl.nextElementSibling;
                if (next?.classList?.contains('invalid-feedback')) next.remove();
            }
            function setFieldError(inputEl, message) {
                if (!inputEl) return;
                clearFieldError(inputEl);
                inputEl.classList.add('is-invalid');
                const fb = document.createElement('div');
                fb.className = 'invalid-feedback d-block';
                fb.textContent = message;
                inputEl.insertAdjacentElement('afterend', fb);
            }
            function setQuillError(quillContainer, message) {
                quillContainer.classList.add('border','border-danger');
                let exist = document.getElementById('quillErrorFb');
                if (!exist) {
                    exist = document.createElement('div');
                    exist.id = 'quillErrorFb';
                    exist.className = 'invalid-feedback d-block mt-2';
                    quillContainer.insertAdjacentElement('afterend', exist);
                }
                exist.textContent = message;
            }
            function clearQuillError(quillContainer) {
                quillContainer.classList.remove('border','border-danger');
                const exist = document.getElementById('quillErrorFb');
                if (exist) exist.remove();
            }


            function styleQuillImages() {
                const imgs = document.querySelectorAll('#quill-editor .ql-editor img');
                imgs.forEach(img => {
                    img.removeAttribute('width');
                    img.removeAttribute('height');
                    img.style.maxWidth  = '100%';
                    img.style.height    = 'auto';
                    img.style.maxHeight = '420px';
                    img.style.objectFit = 'contain';
                    img.style.display   = 'block';
                    img.style.margin    = '.5rem 0';
                });
            }

            // تهيئة Quill — خطوط/أحجام/line-height
            const Font = Quill.import('formats/font');
            Font.whitelist = ['cairo', 'tajawal', 'system'];
            Quill.register(Font, true);

            const Size = Quill.import('attributors/style/size');
            Size.whitelist = ['12px','14px','16px','18px','24px','32px'];
            Quill.register(Size, true);

            const Parchment = Quill.import('parchment');
            const LineHeight = new Parchment.Attributor.Style('lineheight','line-height', {
                scope: Parchment.Scope.BLOCK,
                whitelist: ['1.4','1.6','1.8','2']
            });
            Quill.register(LineHeight, true);

            quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: '{{ __('admin.news.form_content_placeholder') }}',
                modules: {
                    toolbar: {
                        container: '#quill-toolbar',
                        handlers: {
                            image: function () {
                                if (isPickingImages) return;
                                isPickingImages = true;
                                el.quillImageInput.value = '';
                                el.quillImageInput.click();
                            }
                        }
                    },
                    history: { delay: 1000, maxStack: 50 }
                },
                formats: [
                    'font','size','lineheight',
                    'header','bold','italic','underline','strike','blockquote','code','code-block',
                    'list','indent','align','direction',
                    'color','background','link','image'
                ]
            });
            document.querySelector('.ql-undo')?.addEventListener('click', () => quill.history.undo());
            document.querySelector('.ql-redo')?.addEventListener('click', () => quill.history.redo());

            // line-height picker
            const lhPicker = document.querySelector('.ql-lineheight');
            if (lhPicker) lhPicker.addEventListener('change', () => quill.format('lineheight', lhPicker.value || false));

            // اتجاه افتراضي
            quill.format('direction', 'rtl');
            quill.format('align', 'right');

            // عدّادات صور/نص
            const currentImageCount = () => (quill?.root?.querySelectorAll('img')?.length || 0);
            const remainingSlots = () => Math.max(0, {{ $MAX_IMAGES }} - currentImageCount());
            const imgBtn = document.querySelector('#quill-toolbar .ql-image');

            function countWords(text) {
                return text.trim().split(/\s+/).filter(Boolean).length;
            }
            function updateTextCounter() {
                const badge = document.getElementById('textCounter');
                if (!badge) return;
                let plain = quill.getText() || '';
                if (plain.endsWith('\n')) plain = plain.slice(0, -1);
                badge.textContent = `{{ __('admin.news.characters') }} ${plain.length} | {{ __('admin.news.words') }} ${countWords(plain)}`;
            }
            function updateImageCounter() {
                const badge = document.getElementById('imgCounter');
                if (!badge) return;
                const count = currentImageCount();
                badge.textContent = `${count} / {{ $MAX_IMAGES }}`;
                badge.classList.remove('bg-primary','bg-warning','bg-danger');
                badge.classList.add(count < {{ $MAX_IMAGES }} ? 'bg-primary' : (count === {{ $MAX_IMAGES }} ? 'bg-warning' : 'bg-danger'));
                if (imgBtn) {
                    imgBtn.disabled = count >= {{ $MAX_IMAGES }};
                    imgBtn.setAttribute('aria-disabled', imgBtn.disabled ? 'true' : 'false');
                    imgBtn.title = imgBtn.disabled ? `وصلت للحد ({{ $MAX_IMAGES }})` : 'إضافة صور متعددة (اختيار/سحب/لصق)';
                }
            }
            function refreshCounters(){ updateImageCounter(); updateTextCounter(); }
            refreshCounters();
            quill.on('text-change', () => { refreshCounters(); styleQuillImages(); });

            // اختيار صور متعددة من المتصفح (نفس edit — بدون تصغير)
            el.quillImageInput.addEventListener('change', async () => {
                try {
                    const files = Array.from(el.quillImageInput.files || []);
                    if (!files.length) return;

                    let slots = remainingSlots();
                    if (slots <= 0) return warn('{{ __('admin.news.errors_max_images_reached') }}', `{{ __('admin.news.errors_max_images_limit') }} {{ $MAX_IMAGES }} {{ __('admin.news.errors_images_for_news') }}`);

                    for (const file of files) {
                        if (slots <= 0) { warn('{{ __('admin.news.errors_limit_exceeded') }}', `{{ __('admin.news.errors_max_inserted') }} {{ $MAX_IMAGES }} {{ __('admin.news.errors_images_max_limit') }}`); break; }
                        if (!file.type.startsWith('image/')) continue;
                        if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('{{ __('admin.news.errors_image_size_large') }}', '{{ __('admin.news.errors_image_size_max') }}'); continue; }
                        try {
                            const url = await uploadQuillImage(file);
                            insertImageAtCursor(url);
                            slots--;
                        } catch (e) { err('{{ __('admin.news.delete_error') }}', e.message || '{{ __('admin.news.errors_upload_failed') }}'); }
                    }
                } finally {
                    isPickingImages = false;
                    refreshCounters();
                }
            });

            function insertImageAtCursor(url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url, 'user');
                quill.setSelection(range.index + 1, 0, 'user');
                styleQuillImages();
                refreshCounters();
            }

            async function uploadQuillImage(file) {
                if (!file.type.startsWith('image/')) throw new Error('{{ __('admin.news.errors_image_only') }}');
                if (file.size > {{ $MAX_IMAGE_BYTES }}) throw new Error('{{ __('admin.news.errors_image_max_size') }}');

                const fd = new FormData();
                fd.append('image', file);

                const res = await fetch("/admin/uploads/quill-image", {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    }
                });

                if (!res.ok) {
                    let msg = `HTTP ${res.status}`;
                    try { msg = (await res.json()).message || msg; } catch(_) {}
                    throw new Error(msg);
                }
                const data = await res.json();
                if (!data.ok || !data.url) throw new Error('استجابة غير متوقعة');
                return data.url;
            }

            // سحب/إفلات + لصق صور (نفس edit)
            const quillEditorArea = document.querySelector('#quill-editor .ql-editor');
            ['dragover','dragenter'].forEach(evt =>
                quillEditorArea.addEventListener(evt, e => { e.preventDefault(); e.dataTransfer.dropEffect = 'copy'; })
            );
            quillEditorArea.addEventListener('drop', async (e) => {
                e.preventDefault();
                const files = Array.from(e.dataTransfer.files || []).filter(f => f.type.startsWith('image/'));
                if (!files.length) return;

                let slots = remainingSlots();
                if (slots <= 0) return warn('{{ __('admin.news.errors_max_images_reached') }}', `{{ __('admin.news.errors_max_images_limit') }} {{ $MAX_IMAGES }} {{ __('admin.news.errors_images_for_news') }}`);

                for (const file of files) {
                    if (slots <= 0) { warn('{{ __('admin.news.errors_limit_exceeded') }}', `{{ __('admin.news.errors_max_inserted') }} {{ $MAX_IMAGES }} {{ __('admin.news.errors_images_max_limit') }}`); break; }
                    if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('{{ __('admin.news.errors_image_size_large') }}', '{{ __('admin.news.errors_image_size_max_short') }}'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('{{ __('admin.news.delete_error') }}', e.message || '{{ __('admin.news.errors_upload_drag_failed') }}'); }
                }
                refreshCounters();
            });
            quillEditorArea.addEventListener('paste', async (e) => {
                const items = Array.from(e.clipboardData?.items || []);
                const images = items.filter(it => it.type && it.type.startsWith('image/'));
                if (!images.length) return;
                e.preventDefault();
                let slots = remainingSlots();
                if (slots <= 0) return warn('{{ __('admin.news.errors_max_images_reached') }}', `{{ __('admin.news.errors_max_images_limit') }} {{ $MAX_IMAGES }} {{ __('admin.news.errors_images_for_news') }}`);
                for (const it of images) {
                    if (slots <= 0) { warn('{{ __('admin.news.errors_limit_exceeded') }}', `{{ __('admin.news.errors_max_inserted') }} {{ $MAX_IMAGES }} {{ __('admin.news.errors_images_max_limit') }}`); break; }
                    const file = it.getAsFile();
                    if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('{{ __('admin.news.errors_image_size_large') }}', '{{ __('admin.news.errors_image_size_max_short') }}'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('{{ __('admin.news.delete_error') }}', e.message || '{{ __('admin.news.errors_upload_paste_failed') }}'); }
                }
                refreshCounters();
            });

            // حفظ/استعادة مسودة بسيطة
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

            const update = () => { updatePreview(); autoSave(); refreshCounters(); };
            el.title.addEventListener('input', update);
            el.date.addEventListener('change', update);
            el.status.addEventListener('change', update);
            el.featured.addEventListener('change', update);
            quill.on('text-change', update);

            function updatePreview() {
                const title = el.title.value || '{{ __('admin.news.news_title') }}';
                const date = el.date.value ? new Date(el.date.value).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' }) : '';
                const content = quill.root.innerHTML || '<p class="text-muted">{{ __('admin.news.preview_start_writing') }}</p>';
                const featured = el.featured.checked ? '<span class="badge bg-warning text-dark px-2 py-1 rounded-pill small ms-2">{{ __('admin.news.featured_badge') }}</span>' : '';
                const status = el.status.value === 'draft' ? '<span class="badge bg-secondary text-white px-2 py-1 rounded-pill small ms-2">{{ __('admin.news.draft_badge') }}</span>' : '';
                const cover = el.coverPreview.innerHTML;

                const previewHTML = `
                    <article class="p-3">
                        ${cover ? `<div class="mb-3"><img src="${cover.match(/src="([^"]+)"/)?.[1]}" class="w-100 rounded" style="max-height:200px; object-fit:cover;"></div>` : ''}
                        <h5 class="fw-bold text-primary mb-2">${title} ${featured} ${status}</h5>
                        <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                            <i class="bi bi-calendar"></i> <span>${date || '{{ __('admin.news.publish_date') }}'}</span>
                        </div>
                        <div class="content-preview lh-lg" style="font-size:.95rem;">${content}</div>
                        ${el.pdfPreview.innerHTML ? `<div class="mt-3"><a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-pdf"></i> عرض المرفق</a></div>` : ''}
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

            // Dropzones (غلاف + PDF)
            setupDropzone(el.coverDrop, el.coverInput, handleCover, {{ $MAX_IMAGE_BYTES }}, 'image/*');
            setupDropzone(el.pdfDrop, el.pdfInput, handlePDF, 10 * 1024 * 1024, 'application/pdf');

            function setupDropzone(dropzone, input, handler, maxSize, accept) {
                dropzone.addEventListener('click', () => input.click());
                ['dragover', 'dragenter'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('dragover'); }));
                ['dragleave', 'dragend', 'drop'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('dragover'); }));
                dropzone.addEventListener('drop', e => { const f = e.dataTransfer.files[0]; if (f) handler(f); });
                input.addEventListener('change', () => { const f = input.files[0]; if (f) handler(f); });
            }
            function handleCover(file) {
                if (!file.type.startsWith('image/')) return err('{{ __('admin.news.delete_error') }}', '{{ __('admin.news.errors_image_only') }}');
                if (file.size > {{ $MAX_IMAGE_BYTES }}) return err('{{ __('admin.news.delete_error') }}', '{{ __('admin.news.errors_image_max_size_short') }}');
                const reader = new FileReader();
                reader.onload = () => {
                    el.coverPreview.innerHTML = `<img src="${reader.result}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">`;
                    updatePreview(); autoSave(); refreshCounters();
                };
                reader.readAsDataURL(file);
            }
            function handlePDF(file) {
                if (file.type !== 'application/pdf') return err('{{ __('admin.news.delete_error') }}', '{{ __('admin.news.errors_pdf_only') }}');
                if (file.size > 10 * 1024 * 1024) return err('{{ __('admin.news.delete_error') }}', '{{ __('admin.news.errors_pdf_max_size') }}');
                el.pdfPreview.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center justify-content-between p-2 rounded shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-pdf fs-5"></i>
                            <div><strong>${file.name}</strong><br><small>${(file.size/1024/1024).toFixed(2)} MB</small></div>
                        </div>
                        <button type="button" class="btn-close btn-close-sm" onclick="removePDF()"></button>
                    </div>`;
                updatePreview(); autoSave();
            }
            window.removePDF = () => { el.pdfInput.value = ''; el.pdfPreview.innerHTML = ''; updatePreview(); autoSave(); };

            // ⭐ مثل edit: تحويل أي صور Base64 في Quill إلى روابط (رفعها) واحترام الحد + تأكيد قبل حذف الزائد
            async function replaceBase64ImagesInEditor() {
                const container = document.createElement('div');
                container.innerHTML = quill.root.innerHTML;

                const imgs = Array.from(container.querySelectorAll('img[src^="data:"]'));
                for (const img of imgs) {
                    try {
                        const file = dataURLtoFile(img.src, 'inline.png');
                        if (file.size > {{ $MAX_IMAGE_BYTES }}) {
                            await warn('حجم صورة ملصوقة كبير', 'تجاوزت 2MB وتم تجاهلها.');
                            img.remove();
                            continue;
                        }
                        const url = await uploadQuillImage(file);
                        img.src = url;
                    } catch (e) {
                        console.warn('تعذر استبدال صورة base64:', e);
                        img.remove();
                    }
                }

                const finalImgs = Array.from(container.querySelectorAll('img'));
                if (finalImgs.length > {{ $MAX_IMAGES }}) {
                    const extraCount = finalImgs.length - {{ $MAX_IMAGES }};
                    const { isConfirmed } = await Swal.fire({
                        title: 'عدد الصور زائد',
                        html: `لديك <b>${finalImgs.length}</b> صورة، والحد الأقصى <b>{{ $MAX_IMAGES }}</b>.<br>هل تريد حذف <b>${extraCount}</b> صورة زائدة والاحتفاظ بالأوائل؟`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، احذف الزائد',
                        cancelButtonText: 'إلغاء',
                    });
                    if (!isConfirmed) return null;
                    finalImgs.slice({{ $MAX_IMAGES }}).forEach(img => img.remove());
                }
                return container.innerHTML;
            }
            function dataURLtoFile(dataUrl, filename) {
                const arr = dataUrl.split(',');
                const mime = arr[0].match(/:(.*?);/)[1];
                const bstr = atob(arr[1]);
                let n = bstr.length;
                const u8arr = new Uint8Array(n);
                while (n--) u8arr[n] = bstr.charCodeAt(n);
                return new File([u8arr], filename, { type: mime });
            }

            // إرسال + قفل الزر + عرض أخطاء 422
            el.submitBtn.addEventListener('click', onSubmitClick, { once: true });

            async function onSubmitClick() {
                if (isSubmitting) return;
                isSubmitting = true;

                el.submitBtn.disabled = true;
                el.submitText.classList.add('d-none');
                el.submitSpinner.classList.remove('d-none');


                const cleanedHtml = await replaceBase64ImagesInEditor();
                if (cleanedHtml === null) {
                    isSubmitting = false;
                    el.submitBtn.disabled = false;
                    el.submitText.classList.remove('d-none');
                    el.submitSpinner.classList.add('d-none');
                    el.submitBtn.addEventListener('click', onSubmitClick, { once: true });
                    return;
                }
                el.bodyInput.value = cleanedHtml;

                sessionStorage.removeItem(DRAFT_KEY);

                const formData = new FormData(el.form);

                // امسح أخطاء قديمة قبل المحاولة
                ['titleInput','dateInput','statusInput','coverDrop','pdfDrop'].forEach(id => clearFieldError(el[id] || document.getElementById(id)));
                clearQuillError(document.querySelector('.quill-wrapper'));

                try {
                    const res = await fetch(el.form.action, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        }
                    });

                    if (res.status === 422) {
                        const data = await res.json();
                        const errs = data?.errors || {};
                        let firstErrorElement = null;

                        if (errs.title?.[0]) {
                            setFieldError(el.title, errs.title[0]);
                            firstErrorElement = firstErrorElement || el.title;
                        }
                        if (errs.published_at?.[0]) {
                            setFieldError(el.date, errs.published_at[0]);
                            firstErrorElement = firstErrorElement || el.date;
                        }
                        if (errs.status?.[0]) {
                            setFieldError(el.status, errs.status[0]);
                            firstErrorElement = firstErrorElement || el.status;
                        }
                        if (errs.body?.[0]) {
                            setQuillError(document.querySelector('.quill-wrapper'), errs.body[0]);
                            firstErrorElement = firstErrorElement || document.querySelector('.quill-wrapper');
                        }
                        if (errs.cover?.[0]) {
                            setFieldError(el.coverDrop, errs.cover[0]);
                            firstErrorElement = firstErrorElement || el.coverDrop;
                        }
                        if (errs.pdf?.[0]) {
                            setFieldError(el.pdfDrop, errs.pdf[0]);
                            firstErrorElement = firstErrorElement || el.pdfDrop;
                        }

                        if (firstErrorElement?.scrollIntoView) {
                            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }

                        // رجّع الزر
                        isSubmitting = false;
                        el.submitBtn.disabled = false;
                        el.submitText.classList.remove('d-none');
                        el.submitSpinner.classList.add('d-none');
                        el.submitBtn.addEventListener('click', onSubmitClick, { once: true });
                        return;
                    }

                    if (!res.ok) {
                        let msg = `HTTP ${res.status}`;
                        try { msg = (await res.json()).message || msg; } catch (_) {}
                        throw new Error(msg);
                    }

                    const data = await res.json();
                    window.location.href = data.redirect || "{{ route('admin.news.index') }}";
                } catch (error) {
                    console.error('فشل الإرسال:', error);
                    Swal.fire('خطأ', 'تعذّر النشر. تأكد من الاتصال وحجم الملفات.', 'error');

                    isSubmitting = false;
                    el.submitBtn.disabled = false;
                    el.submitText.classList.remove('d-none');
                    el.submitSpinner.classList.add('d-none');
                    el.submitBtn.addEventListener('click', onSubmitClick, { once: true });
                }
            }

            // حفظ مسودة
            el.saveDraftBtn.addEventListener('click', () => {
                autoSave();
                Swal.fire({ title: 'تم!', text: 'تم حفظ المسودة', icon: 'success', timer: 1500, showConfirmButton: false });
            });

            // معاينة أولية
            updatePreview();

            // تحسين التبويبات
            const tabs = document.querySelectorAll('#newsTabs .nav-link');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (e) {
                    // تحديث التصميم عند تغيير التبويب
                    tabs.forEach(t => {
                        if (t === e.target) {
                            t.style.background = 'rgba(255, 255, 255, 0.25)';
                            t.style.borderColor = 'rgba(255, 255, 255, 0.5)';
                        } else {
                            t.style.background = 'transparent';
                            t.style.borderColor = 'rgba(255, 255, 255, 0.3)';
                        }
                    });
                });
            });
        });
    </script>
@endpush
