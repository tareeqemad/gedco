@extends('layouts.admin')
@section('title', 'إضافة خبر جديد')

@section('content')
    @php
        $breadcrumbTitle     = 'إضافة خبر جديد';
        $breadcrumbParent    = 'الأخبار';
        $breadcrumbParentUrl = route('admin.news.index');

        // القيود
        $MAX_IMAGES = 4;                    // الحد الأقصى لعدد الصور داخل المحتوى
        $MAX_IMAGE_BYTES = 2 * 1024 * 1024; // 2MB
    @endphp

    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-newspaper"
                title="إنشاء خبر جديد"
                :back-route="route('admin.news.index')"
                back-label="الأخبار">
                <x-slot:actions>
                    <div class="news-tabs" id="newsTabs" role="tablist">
                        <button class="news-tab active" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#form-content" aria-selected="true">
                            <i class="bi bi-pencil-square"></i> إدخال
                        </button>
                        <button class="news-tab" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#preview-content" aria-selected="false">
                            <i class="bi bi-eye"></i> معاينة
                        </button>
                    </div>
                </x-slot:actions>
            </x-admin.card-header-form>

            <div class="tab-content" id="newsTabContent">
                <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                    <div class="card-body p-3 p-md-4">
                        <form id="newsForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.news.store') }}">
                            @csrf
                            <input type="file" id="quillImageInput" accept="image/*" multiple class="visually-hidden">

                            {{-- Section 1: المعلومات الأساسية --}}
                            <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                <i class="bi bi-info-circle"></i> {{ __('admin.news.basic_info') }}
                            </h6>

                            <div class="mb-3">
                                <label class="form-label">عنوان الخبر <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="titleInput"
                                       class="form-control @error('title') is-invalid @enderror"
                                       placeholder="{{ __('admin.news.title_placeholder') }}" value="{{ old('title') }}">
                                @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('admin.news.publish_date') }}</label>
                                    <input type="date" name="published_at" id="dateInput"
                                           class="form-control @error('published_at') is-invalid @enderror"
                                           value="{{ old('published_at', now()->format('Y-m-d')) }}">
                                    @error('published_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('admin.news.status') }}</label>
                                    <select name="status" id="statusInput" class="form-select @error('status') is-invalid @enderror">
                                        <option value="published" @selected(old('status','published')==='published')>{{ __('admin.labels.published') }}</option>
                                        <option value="draft" @selected(old('status')==='draft')>{{ __('admin.labels.draft') }}</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <label class="form-label d-flex align-items-center gap-2 mb-0 p-2 rounded" style="background: #F8FBFD; border: 1px solid #E6ECF2; cursor: pointer;">
                                        <input class="form-check-input m-0" type="checkbox" name="featured" id="featuredInput" value="1" @checked(old('featured'))>
                                        <span class="fw-medium" style="font-size: 0.85rem;">خبر مميّز</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Section 2: المحتوى --}}
                            <h6 class="fw-bold d-flex align-items-center gap-2 section-title mt-4">
                                <i class="bi bi-file-text"></i> المحتوى
                            </h6>

                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <small class="text-muted" style="font-size: 0.75rem;">حتى {{ $MAX_IMAGES }} صور × 2MB</small>
                                <div class="d-flex gap-2">
                                    <span id="imgCounter" class="stat-chip" style="font-size: 0.7rem;">0 / {{ $MAX_IMAGES }} صور</span>
                                    <span id="textCounter" class="stat-chip" style="font-size: 0.7rem;">الحروف: 0 | الكلمات: 0</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-none">المحتوى</label>

                                        <div class="quill-wrapper border rounded-3 shadow-sm overflow-hidden">
                                            <div id="quill-toolbar" class="px-2 py-1">
                                                <!-- اختيار الخط -->
                                                <span class="ql-formats">
                                                    <select class="ql-font">
                                                        <option value="default" selected>System</option>
                                                        <option value="cairo">Cairo</option>
                                                        <option value="tajawal">Tajawal</option>
                                                    </select>
                                                </span>

                                                <!-- حجم الخط -->
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

                                                <!-- line-height -->
                                                <span class="ql-formats">
                                                    <select class="ql-lineheight">
                                                        <option value="">LH</option>
                                                        <option value="1.4">1.4</option>
                                                        <option value="1.6" selected>1.6</option>
                                                        <option value="1.8">1.8</option>
                                                        <option value="2">2.0</option>
                                                    </select>
                                                </span>

                                                <!-- باقي الأدوات -->
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
                                                    <button class="ql-image" id="imageUploader" title="إضافة صور متعددة (اختيار/سحب/لصق)"></button>
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
                                            <i class="ri-image-add-line fs-6 text-primary"></i> {{ __('admin.news.cover_image') }}
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="coverDrop">
                                            <input type="file" name="cover" id="coverInput" class="visually-hidden" accept="image/*">
                                            <div class="text-primary">
                                                <i class="ri-image-add-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    {{ __('admin.common.drag_image_or') }}
                                                    <label for="coverInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.common.select_file') }}</label>
                                                </p>
                                                <small class="text-muted">PNG/JPG/WEBP • حتى 2MB</small>
                                            </div>
                                        </div>
                                        <div id="coverPreview" class="mt-3"></div>
                                        @error('cover') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- PDF -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-pdf-line fs-6 text-danger"></i> ملف PDF (اختياري)
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="pdfDrop">
                                            <input type="file" name="pdf" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="ri-upload-cloud-2-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    اسحب ملف أو
                                                    <label for="pdfInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">اختر ملف</label>
                                                </p>
                                                <small class="text-muted">PDF • حتى 10MB</small>
                                            </div>
                                        </div>
                                        <div id="pdfPreview" class="mt-3"></div>
                                        @error('pdf') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                            {{-- Section 3: الإجراءات --}}
                            <div class="d-flex flex-wrap gap-2 mt-4 pt-3" style="border-top: 1px solid #E6ECF2;">
                                <button type="button" id="submitBtn" class="btn btn-save d-flex align-items-center gap-2">
                                    <i class="bi bi-check-lg"></i>
                                    <span id="submitText">نشر الخبر</span>
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                                <button type="button" id="saveDraft" class="btn btn-outline-secondary px-4 d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark"></i> {{ __('admin.actions.save_draft') }}
                                </button>
                                <a href="{{ route('admin.news.index') }}" class="btn btn-cancel">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- تبويب المعاينة --}}
                <div class="tab-pane fade" id="preview-content" role="tabpanel">
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-file-search fs-4 d-block mb-2"></i>
                            <small>ابدأ الكتابة في تبويب "إدخال"</small>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        :root { --primary: #4361ee; --success: #10b981; --danger: #ef4444; }
        .quill-wrapper { height: 500px; background: #fff; }
        .ql-container { height: calc(100% - 42px); font-size: 1.05rem; }
        .ql-editor { min-height: 100%; padding: 1rem; }
        [dir="rtl"] .ql-editor { direction: rtl !important; text-align: right !important; }
        [dir="ltr"] .ql-editor { direction: ltr !important; text-align: left !important; }
        [dir="ltr"] .ql-editor p, [dir="ltr"] .ql-editor .ql-direction-rtl { direction: ltr !important; text-align: left !important; }
        .ql-toolbar { border-bottom: 1px solid #dee2e6; background: #f8f9fa; }
        .dropzone { cursor: pointer; transition: all 0.2s ease; }
        .dropzone.dragover { background: #ebf2ff !important; border-color: var(--primary) !important; }
        .focus-ring:focus { box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15); }
        .btn[disabled] { opacity: .7; cursor: not-allowed; }

        .img-counter, .text-counter{
            font-size: .8rem;
            padding: .35rem .5rem;
            border-radius: .5rem;
        }
        #quill-toolbar .ql-image[disabled]{ opacity:.5; cursor:not-allowed; }

        /* خرائط خطوط Quill */
        .ql-font-cairo   { font-family: "Cairo", sans-serif; }
        .ql-font-tajawal { font-family: "Tajawal", sans-serif; }
        .ql-font-system  { font-family: system-ui, sans-serif; } /* لو احتجتها */

        /* وسم عناصر القوائم في pickers (اختياري للوضوح) */
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="cairo"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="cairo"]::before { content: "Cairo"; }
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="tajawal"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="tajawal"]::before { content: "Tajawal"; }
    </style>
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

            // تهيئة Quill — إضافة خطوط/أحجام/line-height
            const Font = Quill.import('formats/font');
            Font.whitelist = ['cairo', 'tajawal', 'system', 'default'];
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
                placeholder: 'اكتب محتوى الخبر هنا... (يمكنك إضافة صور متعددة)',
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

            // اتجاه افتراضي حسب لغة الصفحة
            const pageDir = document.documentElement.getAttribute('dir') || 'rtl';
            if (pageDir === 'rtl') {
                quill.format('direction', 'rtl');
                quill.format('align', 'right');
            } else {
                quill.format('direction', false);
                quill.format('align', false);
                // إزالة أي RTL موروث
                document.querySelector('#quill-editor .ql-editor').style.direction = 'ltr';
                document.querySelector('#quill-editor .ql-editor').style.textAlign = 'left';
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
                badge.textContent = `الحروف: ${plain.length} | الكلمات: ${countWords(plain)}`;
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
            quill.on('text-change', refreshCounters);

            // اختيار صور متعددة من المتصفح
            el.quillImageInput.addEventListener('change', async () => {
                try {
                    const files = Array.from(el.quillImageInput.files || []);
                    if (!files.length) return;

                    let slots = remainingSlots();
                    if (slots <= 0) return warn('وصلت للحد الأقصى', `الحد هو {{ $MAX_IMAGES }} صور للخبر.`);

                    for (const file of files) {
                        if (slots <= 0) { warn('تم تجاوز الحد', `تم إدراج {{ $MAX_IMAGES }} صور كحد أقصى والباقي تم تجاهله.`); break; }
                        if (!file.type.startsWith('image/')) continue;
                        if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('حجم الصورة كبير', '2MB حد أقصى للصورة — تم تجاهل الكبيرة.'); continue; }
                        try {
                            const url = await uploadQuillImage(file);
                            insertImageAtCursor(url);
                            slots--;
                        } catch (e) { err('خطأ', e.message || 'فشل رفع الصورة'); }
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
                refreshCounters();
            }

            async function uploadQuillImage(file) {
                if (!file.type.startsWith('image/')) throw new Error('صورة فقط');
                if (file.size > {{ $MAX_IMAGE_BYTES }}) throw new Error('الحد الأقصى للصورة 2MB');

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
                if (slots <= 0) return warn('وصلت للحد الأقصى', `الحد هو {{ $MAX_IMAGES }} صور للخبر.`);

                for (const file of files) {
                    if (slots <= 0) { warn('تم تجاوز الحد', `تم إدراج {{ $MAX_IMAGES }} صور كحد أقصى والباقي تم تجاهله.`); break; }
                    if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('حجم الصورة كبير', '2MB حد أقصى للصورة.'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('خطأ', e.message || 'فشل رفع صورة بالسحب والإفلات'); }
                }
                refreshCounters();
            });
            quillEditorArea.addEventListener('paste', async (e) => {
                const items = Array.from(e.clipboardData?.items || []);
                const images = items.filter(it => it.type && it.type.startsWith('image/'));
                if (!images.length) return;
                e.preventDefault();
                let slots = remainingSlots();
                if (slots <= 0) return warn('وصلت للحد الأقصى', `الحد هو {{ $MAX_IMAGES }} صور للخبر.`);
                for (const it of images) {
                    if (slots <= 0) { warn('تم تجاوز الحد', `تم إدراج {{ $MAX_IMAGES }} صور كحد أقصى والباقي تم تجاهله.`); break; }
                    const file = it.getAsFile();
                    if (file.size > {{ $MAX_IMAGE_BYTES }}) { warn('حجم الصورة كبير', '2MB حد أقصى للصورة.'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('خطأ', e.message || 'فشل رفع صورة مُلصقة'); }
                }
                refreshCounters();
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

            const update = () => { updatePreview(); autoSave(); refreshCounters(); };
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
                if (!file.type.startsWith('image/')) return err('خطأ', 'صورة فقط');
                if (file.size > {{ $MAX_IMAGE_BYTES }}) return err('خطأ', 'الحد 2MB');
                const reader = new FileReader();
                reader.onload = () => {
                    el.coverPreview.innerHTML = `<img src="${reader.result}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">`;
                    updatePreview(); autoSave(); refreshCounters();
                };
                reader.readAsDataURL(file);
            }
            function handlePDF(file) {
                if (file.type !== 'application/pdf') return err('خطأ', 'PDF فقط');
                if (file.size > 10 * 1024 * 1024) return err('خطأ', 'الحد 10MB');
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

            // تحويل أي صور Base64 في Quill إلى روابط (رفعها) واحترام الحد + تأكيد قبل حذف الزائد
            async function replaceBase64ImagesInEditor() {
                const container = document.createElement('div');
                container.innerHTML = quill.root.innerHTML;

                // استبدال أي صور base64 برفعها
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

                // فحص العدد النهائي
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

                // تنظيف وتحويل Base64 + التأكيد قبل حذف الزائد
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
        });
    </script>
@endpush
