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

        $currentDirection = session('direction', 'rtl');
        $defaultLang = $currentDirection === 'rtl' ? 'ar' : 'en';
    @endphp

    <div class="container-fluid p-0" id="news-create-page">
        <x-admin.card>
            {{-- Header with Tabs --}}
            <x-admin.card-header-form
                icon="bi-newspaper"
                :title="__('admin.news.create_news')"
                :back-route="route('admin.news.index')"
                :back-label="__('admin.menu.news')">
                <x-slot:actions>
                    <div class="news-tabs" id="newsTabs" role="tablist">
                        <button class="news-tab active" data-bs-toggle="tab" data-bs-target="#form-content" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-pencil-square"></i> {{ __('admin.news.form_tab_input') }}
                        </button>
                        <button class="news-tab" data-bs-toggle="tab" data-bs-target="#preview-content" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-eye"></i> {{ __('admin.news.form_tab_preview') }}
                        </button>
                    </div>
                </x-slot:actions>
            </x-admin.card-header-form>

            <div class="tab-content" id="newsTabContent">
                {{-- ============ Form Tab ============ --}}
                <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                    <div class="card-body p-3 p-md-4">
                        <form id="newsForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.news.store') }}">
                            @csrf
                            <input type="file" id="quillImageInput" accept="image/*" multiple class="visually-hidden">
                            <input type="hidden" name="language" value="{{ old('language', $defaultLang) }}">

                            {{-- ── Section 1: Basic Info ── --}}
                            <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                <i class="bi bi-info-circle"></i> {{ __('admin.common.basic_info') }}
                            </h6>

                            {{-- Title --}}
                            <div class="mb-3">
                                <label for="titleInput" class="form-label fw-semibold">
                                    <i class="bi bi-type-h1 me-1"></i> {{ __('admin.news.form_title') }}
                                </label>
                                <input type="text" name="title" id="titleInput"
                                       class="form-control rounded-3 @error('title') is-invalid @enderror"
                                       placeholder="{{ __('admin.news.form_title_placeholder') }}" value="{{ old('title') }}">
                                @error('title') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Date + Status + Featured --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-5">
                                    <label for="dateInput" class="form-label fw-semibold">
                                        <i class="bi bi-calendar me-1"></i> {{ __('admin.news.form_publish_date') }}
                                    </label>
                                    <input type="date" name="published_at" id="dateInput"
                                           class="form-control rounded-3 @error('published_at') is-invalid @enderror"
                                           value="{{ old('published_at', now()->format('Y-m-d')) }}">
                                    @error('published_at') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="statusInput" class="form-label fw-semibold">{{ __('admin.news.form_status') }}</label>
                                    <select name="status" id="statusInput" class="form-select rounded-3 @error('status') is-invalid @enderror">
                                        <option value="published" @selected(old('status','published')==='published')>{{ __('admin.news.status_published') }}</option>
                                        <option value="draft" @selected(old('status')==='draft')>{{ __('admin.news.status_draft') }}</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="featured" id="featuredInput" value="1"
                                               @checked(old('featured')) style="width: 2.5rem; height: 1.25rem; cursor: pointer;">
                                        <label class="form-check-label fw-semibold ms-2" for="featuredInput">{{ __('admin.news.form_featured') }}</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Language Badge --}}
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <label class="form-label mb-0 fw-semibold">{{ __('admin.labels.language') }}:</label>
                                <span class="stat-chip">
                                    <i class="bi bi-globe"></i>
                                    {{ $defaultLang === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
                                </span>
                                @error('language') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- ── Section 2: Content ── --}}
                            <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                <i class="bi bi-file-text"></i> {{ __('admin.news.form_content') }}
                            </h6>

                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <small class="text-muted">{{ __('admin.news.form_content_info') }} {{ $MAX_IMAGES }} {{ __('admin.news.form_content_info_end') }}</small>
                                <div class="d-flex gap-2">
                                    <span id="imgCounter" class="stat-chip stat-chip-primary" style="font-size: 0.65rem;">0 / {{ $MAX_IMAGES }}</span>
                                    <span id="textCounter" class="stat-chip" style="font-size: 0.65rem;">{{ __('admin.common.characters') }} 0 | {{ __('admin.common.words') }} 0</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="quill-wrapper border rounded-3 overflow-hidden">
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
                                                <option value="1">{{ __('admin.common.quill_header_1') }}</option>
                                                <option value="2">{{ __('admin.common.quill_header_2') }}</option>
                                                <option value="3">{{ __('admin.common.quill_header_3') }}</option>
                                                <option selected>{{ __('admin.common.quill_normal') }}</option>
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
                                            <button class="ql-image" id="imageUploader" title="{{ __('admin.common.quill_add_images') }}"></button>
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

                            {{-- ── Section 3: Attachments ── --}}
                            <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                <i class="bi bi-paperclip"></i> {{ __('admin.common.attachments') }}
                            </h6>

                            {{-- Cover Image --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-image me-1"></i> {{ __('admin.news.form_cover_image') }}
                                </label>
                                <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center" id="coverDrop" style="cursor: pointer;">
                                    <input type="file" name="cover" id="coverInput" class="visually-hidden" accept="image/*">
                                    <div class="text-muted">
                                        <i class="bi bi-image fs-2 mb-2 d-block"></i>
                                        <p class="mb-1 fw-semibold">
                                            {{ __('admin.news.form_cover_drag') }}
                                            <label for="coverInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.news.form_cover_select') }}</label>
                                        </p>
                                        <small class="text-muted">{{ __('admin.news.form_cover_formats') }}</small>
                                    </div>
                                </div>
                                <div id="coverPreview" class="mt-3"></div>
                                @error('cover') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                            </div>

                            {{-- PDF --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-file-pdf me-1"></i> {{ __('admin.news.form_pdf') }}
                                </label>
                                <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center" id="pdfDrop" style="cursor: pointer;">
                                    <input type="file" name="pdf" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                    <div class="text-muted">
                                        <i class="bi bi-cloud-upload fs-2 mb-2 d-block"></i>
                                        <p class="mb-1 fw-semibold">
                                            {{ __('admin.news.form_pdf_drag') }}
                                            <label for="pdfInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.news.form_pdf_select') }}</label>
                                        </p>
                                        <small class="text-muted">{{ __('admin.news.form_pdf_formats') }}</small>
                                    </div>
                                </div>
                                <div id="pdfPreview" class="mt-3"></div>
                                @error('pdf') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                            </div>

                            {{-- ── Buttons ── --}}
                            <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                                <button type="button" id="submitBtn" class="btn btn-save d-flex align-items-center gap-2">
                                    <i class="bi bi-check-lg"></i>
                                    <span id="submitText">{{ __('admin.news.form_publish') }}</span>
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                                <button type="button" id="saveDraft" class="btn btn-cancel d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark"></i> {{ __('admin.news.form_save_draft') }}
                                </button>
                                <a href="{{ route('admin.news.index') }}" class="btn btn-cancel d-flex align-items-center">
                                    <i class="bi bi-x-circle me-1"></i> {{ __('admin.news.form_cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ============ Preview Tab ============ --}}
                <div class="tab-pane fade" id="preview-content" role="tabpanel">
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-eye fs-3 d-block mb-2 opacity-50"></i>
                            <p class="mb-0">{{ __('admin.news.form_preview_start_writing') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card>
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

            // اتجاه افتراضي حسب لغة لوحة التحكم
            const isRtl = '{{ session('direction', 'rtl') }}' === 'rtl';
            const editorEl = document.querySelector('#quill-editor .ql-editor');
            if (editorEl) {
                editorEl.setAttribute('dir', isRtl ? 'rtl' : 'ltr');
                editorEl.style.textAlign = isRtl ? 'right' : 'left';
            }
            // Set default format for new content
            if (isRtl) {
                quill.format('direction', 'rtl');
                quill.format('align', 'right');
            } else {
                // Replace default paragraph to be LTR
                quill.root.innerHTML = '<p dir="ltr" style="text-align: left;"><br></p>';
                quill.setSelection(0, 0);
            }

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
                badge.textContent = `{{ __('admin.common.characters') }} ${plain.length} | {{ __('admin.common.words') }} ${countWords(plain)}`;
            }
            function updateImageCounter() {
                const badge = document.getElementById('imgCounter');
                if (!badge) return;
                const count = currentImageCount();
                badge.textContent = `${count} / {{ $MAX_IMAGES }}`;
                badge.classList.remove('stat-chip-primary','stat-chip-warning','stat-chip-danger');
                badge.classList.add(count < {{ $MAX_IMAGES }} ? 'stat-chip-primary' : (count === {{ $MAX_IMAGES }} ? 'stat-chip-warning' : 'stat-chip-danger'));
                if (imgBtn) {
                    imgBtn.disabled = count >= {{ $MAX_IMAGES }};
                    imgBtn.setAttribute('aria-disabled', imgBtn.disabled ? 'true' : 'false');
                    imgBtn.title = imgBtn.disabled ? `{{ __('admin.common.err_reached_limit') }} ({{ $MAX_IMAGES }})` : '{{ __('admin.common.quill_add_images') }}';
                }
            }
            function refreshCounters(){ updateImageCounter(); updateTextCounter(); }
            refreshCounters();
            quill.on('text-change', () => { refreshCounters(); styleQuillImages(); });

            // اختيار صور متعددة من المتصفح
            el.quillImageInput.addEventListener('change', async () => {
                try {
                    const files = Array.from(el.quillImageInput.files || []);
                    if (!files.length) return;

                    let slots = remainingSlots();
                    if (slots <= 0) return warn('{{ __('admin.common.err_max_images_reached') }}', `{{ __('admin.common.err_max_images_limit', ['max' => $MAX_IMAGES]) }}`);

                    for (const file of files) {
                        if (slots <= 0) { warn('{{ __('admin.common.err_limit_exceeded') }}', `{{ __('admin.common.err_max_images_inserted', ['max' => $MAX_IMAGES]) }}`); break; }
                        if (!file.type.startsWith('image/')) continue;
                        if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('{{ __('admin.common.err_image_too_large') }}', '{{ __('admin.common.err_image_max_2mb') }}'); continue; }
                        try {
                            const url = await uploadQuillImage(file);
                            insertImageAtCursor(url);
                            slots--;
                        } catch (e) { err('{{ __('admin.ui.error') }}', e.message || '{{ __('admin.common.err_upload_failed') }}'); }
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
                if (!file.type.startsWith('image/')) throw new Error('{{ __('admin.common.err_images_only') }}');
                if (file.size > {{ $MAX_IMAGE_BYTES }}) throw new Error('{{ __('admin.common.err_image_max_size') }}');

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
                if (!data.ok || !data.url) throw new Error('{{ __('admin.common.err_unexpected_response') }}');
                return data.url;
            }

            // سحب/إفلات + لصق صور
            const quillEditorArea = document.querySelector('#quill-editor .ql-editor');
            ['dragover','dragenter'].forEach(evt =>
                quillEditorArea.addEventListener(evt, e => { e.preventDefault(); e.dataTransfer.dropEffect = 'copy'; })
            );
            quillEditorArea.addEventListener('drop', async (e) => {
                e.preventDefault();
                const files = Array.from(e.dataTransfer.files || []).filter(f => f.type.startsWith('image/'));
                if (!files.length) return;

                let slots = remainingSlots();
                if (slots <= 0) return warn('{{ __('admin.common.err_max_images_reached') }}', `{{ __('admin.common.err_max_images_limit', ['max' => $MAX_IMAGES]) }}`);

                for (const file of files) {
                    if (slots <= 0) { warn('{{ __('admin.common.err_limit_exceeded') }}', `{{ __('admin.common.err_max_images_inserted', ['max' => $MAX_IMAGES]) }}`); break; }
                    if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('{{ __('admin.common.err_image_too_large') }}', '{{ __('admin.common.err_image_max_2mb_short') }}'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('{{ __('admin.ui.error') }}', e.message || '{{ __('admin.common.err_drag_upload_failed') }}'); }
                }
                refreshCounters();
            });
            quillEditorArea.addEventListener('paste', async (e) => {
                const items = Array.from(e.clipboardData?.items || []);
                const images = items.filter(it => it.type && it.type.startsWith('image/'));
                if (!images.length) return;
                e.preventDefault();
                let slots = remainingSlots();
                if (slots <= 0) return warn('{{ __('admin.common.err_max_images_reached') }}', `{{ __('admin.common.err_max_images_limit', ['max' => $MAX_IMAGES]) }}`);
                for (const it of images) {
                    if (slots <= 0) { warn('{{ __('admin.common.err_limit_exceeded') }}', `{{ __('admin.common.err_max_images_inserted', ['max' => $MAX_IMAGES]) }}`); break; }
                    const file = it.getAsFile();
                    if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('{{ __('admin.common.err_image_too_large') }}', '{{ __('admin.common.err_image_max_2mb_short') }}'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('{{ __('admin.ui.error') }}', e.message || '{{ __('admin.common.err_paste_upload_failed') }}'); }
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
                        ${el.pdfPreview.innerHTML ? `<div class="mt-3"><a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-pdf"></i> {{ __('admin.ui.view_attachment') }}</a></div>` : ''}
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
                if (!file.type.startsWith('image/')) return err('{{ __('admin.ui.error') }}', '{{ __('admin.common.err_images_only') }}');
                if (file.size > {{ $MAX_IMAGE_BYTES }}) return err('{{ __('admin.ui.error') }}', '{{ __('admin.common.err_image_max_2mb_short') }}');
                const reader = new FileReader();
                reader.onload = () => {
                    el.coverPreview.innerHTML = `<img src="${reader.result}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">`;
                    updatePreview(); autoSave(); refreshCounters();
                };
                reader.readAsDataURL(file);
            }
            function handlePDF(file) {
                if (file.type !== 'application/pdf') return err('{{ __('admin.ui.error') }}', '{{ __('admin.common.err_pdf_only') }}');
                if (file.size > 10 * 1024 * 1024) return err('{{ __('admin.ui.error') }}', '{{ __('admin.common.err_pdf_max_10mb') }}');
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

            // تحويل أي صور Base64 في Quill إلى روابط
            async function replaceBase64ImagesInEditor() {
                const container = document.createElement('div');
                container.innerHTML = quill.root.innerHTML;

                const imgs = Array.from(container.querySelectorAll('img[src^="data:"]'));
                for (const img of imgs) {
                    try {
                        const file = dataURLtoFile(img.src, 'inline.png');
                        if (file.size > {{ $MAX_IMAGE_BYTES }}) {
                            await warn('{{ __('admin.common.err_pasted_image_large') }}', '{{ __('admin.common.err_pasted_image_ignored') }}');
                            img.remove();
                            continue;
                        }
                        const url = await uploadQuillImage(file);
                        img.src = url;
                    } catch (e) {
                        console.warn('Failed to replace base64 image:', e);
                        img.remove();
                    }
                }

                const finalImgs = Array.from(container.querySelectorAll('img'));
                if (finalImgs.length > {{ $MAX_IMAGES }}) {
                    const extraCount = finalImgs.length - {{ $MAX_IMAGES }};
                    const { isConfirmed } = await Swal.fire({
                        title: '{{ __('admin.common.err_excess_images') }}',
                        html: `{{ __('admin.common.err_excess_images_html', ['count' => '${finalImgs.length}', 'max' => $MAX_IMAGES, 'extra' => '${extraCount}']) }}`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '{{ __('admin.common.err_yes_delete_excess') }}',
                        cancelButtonText: '{{ __('admin.actions.cancel') }}',
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
                    console.error('Submit failed:', error);
                    Swal.fire('{{ __('admin.ui.error') }}', '{{ __('admin.ui.publish_failed') }}', 'error');

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
                Swal.fire({ title: '{{ __('admin.ui.done') }}', text: '{{ __('admin.ui.draft_saved') }}', icon: 'success', timer: 1500, showConfirmButton: false });
            });

            // معاينة أولية
            updatePreview();
        });
    </script>
@endpush
