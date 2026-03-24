@extends('layouts.admin')
@section('title', __('admin.news.edit_title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.news.edit_title');
        $breadcrumbParent    = __('admin.menu.news');
        $breadcrumbParentUrl = route('admin.news.index');
        $MAX_IMAGES = 8;
        $MAX_IMAGE_BYTES = 2 * 1024 * 1024;
    @endphp

    <div class="container-fluid p-0" id="news-edit-page">
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary fs-5"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">{{ __('admin.news.edit_title') }}: {{ $news->title }}</h6>
                </div>
                <ul class="nav nav-tabs nav-tabs-sm border-0" id="newsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                            <button class="nav-link active px-3 py-1 rounded-3" data-bs-toggle="tab" data-bs-target="#form-content" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-pencil me-1"></i> {{ __('admin.news.form_tab_input') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-1 rounded-3" data-bs-toggle="tab" data-bs-target="#preview-content" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-eye me-1"></i> {{ __('admin.news.form_tab_preview') }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="newsTabContent">
            {{-- تبويب الإدخال --}}
            <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3 bg-white">
                            <div class="card-body p-4">
                                <form id="newsForm" method="POST" enctype="multipart/form-data"
                                      action="{{ route('admin.news.update', $news) }}">
                                    @csrf @method('PUT')

                                    <input type="file" id="quillImageInput" accept="image/*" multiple class="visually-hidden">

                                    {{-- العنوان --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="bi bi-type-h1 fs-6 text-primary"></i> {{ __('admin.news.form_title') }}
                                        </label>
                                        <input type="text" name="title" id="titleInput"
                                               class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-primary @error('title') is-invalid @enderror"
                                               placeholder="{{ __('admin.news.form_title_placeholder') }}" value="{{ old('title', $news->title) }}">
                                        @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- تاريخ + حالة + مميز --}}
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                                <i class="bi bi-calendar fs-6 text-info"></i> {{ __('admin.news.publish_date') }}
                                            </label>
                                            <input type="date" name="published_at" id="dateInput"
                                                   class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-info @error('published_at') is-invalid @enderror"
                                                   value="{{ old('published_at', optional($news->published_at)->format('Y-m-d')) }}">
                                            @error('published_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium text-secondary">{{ __('admin.news.status') }}</label>
                                            <select name="status" id="statusInput" class="form-select rounded-3 shadow-sm @error('status') is-invalid @enderror">
                                                <option value="published" @selected(old('status', $news->status)==='published')>{{ __('admin.labels.published') }}</option>
                                                <option value="draft"     @selected(old('status', $news->status)==='draft')>{{ __('admin.labels.draft') }}</option>
                                            </select>
                                            @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="featured" id="featuredInput" value="1" @checked(old('featured', $news->featured))>
                                                <label class="form-check-label fw-medium" for="featuredInput">{{ __('admin.news.form_featured') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- اللغة - مخفية، يتم تحديدها تلقائياً حسب لغة لوحة التحكم --}}
                                    @php
                                        $currentDirection = session('direction', 'rtl');
                                        $defaultLang = $currentDirection === 'rtl' ? 'ar' : 'en';
                                        // في التعديل، نستخدم لغة السجل الحالي
                                        $currentLang = old('language', $news->language ?? $defaultLang);
                                    @endphp
                                    <input type="hidden" name="language" value="{{ $currentLang }}">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="form-label mb-0" style="font-size: 0.82rem; color: #24364A; font-weight: 600;">{{ __('admin.labels.language') }}:</label>
                                            <span class="stat-chip">
                                                <i class="bi bi-globe"></i>
                                                {{ $currentLang === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
                                            </span>
                                        </div>
                                        @error('language') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- المحتوى (Quill) --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-2 flex-wrap">
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-file-text fs-6 text-success"></i> {{ __('admin.news.form_content') }}
                                        </span>
                                            <small class="text-muted">{{ __('admin.ui.content_info', ['max' => $MAX_IMAGES]) }}</small>
                                            <span id="imgCounter" class="badge img-counter bg-primary">0 / {{ $MAX_IMAGES }}</span>
                                            <span id="textCounter" class="badge text-counter bg-secondary ms-1">{{ __('admin.common.characters') }} 0 | {{ __('admin.common.words') }} 0</span>
                                        </label>

                                        <div class="quill-wrapper border rounded-3 shadow-sm overflow-hidden">
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

                                        <textarea name="body" id="bodyInput" class="d-none">{{ old('body', $news->body) }}</textarea>
                                        @error('body') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- صورة الغلاف --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="bi bi-image fs-6 text-primary"></i> {{ __('admin.news.cover_image') }}
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="coverDrop">
                                            <input type="file" name="cover" id="coverInput" class="visually-hidden" accept="image/*">
                                            <div class="text-primary">
                                                <i class="bi bi-image fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    {{ __('admin.common.drag_image_or') }}
                                                    <label for="coverInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.common.select_file') }}</label>
                                                </p>
                                                <small class="text-muted">{{ __('admin.news.form_cover_formats') }}</small>
                                            </div>
                                        </div>
                                        <div id="coverPreview" class="mt-3">
                                            @php
                                                $coverUrl = $news->cover_url ?? null;
                                            @endphp

                                            @if($coverUrl)
                                                <img src="{{ $coverUrl }}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_cover" id="removeCover" value="1">
                                                    <label class="form-check-label small" for="removeCover">{{ __('admin.news.form_remove_cover') }}</label>
                                                </div>
                                            @endif
                                        </div>
                                        @error('cover') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- PDF --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="bi bi-file-pdf fs-6 text-danger"></i> {{ __('admin.news.form_pdf') }}
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="pdfDrop">
                                            <input type="file" name="pdf" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="bi bi-cloud-upload fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    {{ __('admin.news.form_pdf_drag') }}
                                                    <label for="pdfInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.news.form_pdf_select') }}</label>
                                                </p>
                                                <small class="text-muted">{{ __('admin.news.form_pdf_formats') }}</small>
                                            </div>
                                        </div>
                                        <div id="pdfPreview" class="mt-3">
                                            @php $pdfUrl = $news->pdf_path ? Storage::disk('public')->url($news->pdf_path) : null; @endphp
                                            @if($pdfUrl)
                                                <div class="alert alert-success d-flex align-items-center justify-content-between p-2 rounded shadow-sm">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="bi bi-file-pdf fs-5"></i>
                                                        <div><strong>{{ __('admin.news.form_pdf') }}</strong><br><small><a href="{{ $pdfUrl }}" target="_blank" rel="noopener">{{ __('admin.advertisements.view_file') }}</a></small></div>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="remove_pdf" id="removePdf" value="1">
                                                        <label class="form-check-label small" for="removePdf">{{ __('admin.news.form_remove_pdf') }}</label>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @error('pdf') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- الأزرار --}}
                                    <div class="d-flex flex-wrap gap-2 mt-4 form-actions">
                                        <button type="button" id="submitBtn" class="btn btn-save d-flex align-items-center gap-2">
                                            <i class="bi bi-check"></i>
                                            <span id="submitText">{{ __('admin.news.form_update') }}</span>
                                            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-cancel">{{ __('admin.news.form_cancel') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> {{-- /#form-content --}}
            </div>

            {{-- تبويب المعاينة (أخ للتبويب الأول، ليس بداخله) --}}
            <div class="tab-pane fade" id="preview-content" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-header bg-light py-2 px-3">
                        <h6 class="mb-0 fw-semibold text-primary"><i class="bi bi-eye me-1"></i> {{ __('admin.news.form_preview_title') }}</h6>
                    </div>
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-eye fs-4 d-block mb-2"></i>
                            <small>{{ __('admin.news.form_preview_edit_in_tab') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- /.tab-content --}}
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/news.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@push('scripts')
    {{-- استخدم نفس سكربت create (رفع الصور/العدادات/replaceBase64...) مع init لملء quill من body --}}
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const DRAFT_KEY = 'news_edit_{{ $news->id }}';
            const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const MAX_IMAGES = {{ $MAX_IMAGES }};
            const MAX_IMAGE_BYTES = {{ $MAX_IMAGE_BYTES }};
            let quill, isSubmitting=false, isPickingImages=false, coverSrc='';
            const SERVER_COVER_URL  = @json($news->cover_url);
            const SERVER_COVER_PATH = @json($news->cover_path);

            if (SERVER_COVER_URL) {
                coverSrc = SERVER_COVER_URL; // استخدمه فوراً للمعاينة
            }
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
                quillImageInput: document.getElementById('quillImageInput'),
            };

            const warn=(t,m)=>Swal.fire(t,m,'warning');
            const err =(t,m)=>Swal.fire(t,m,'error');

            function clearFieldError(elm){ if(!elm)return; elm.classList.remove('is-invalid'); const n=elm.nextElementSibling; if(n?.classList?.contains('invalid-feedback')) n.remove(); }
            function setFieldError(elm,msg){ if(!elm)return; clearFieldError(elm); const fb=document.createElement('div'); fb.className='invalid-feedback d-block'; fb.textContent=msg; elm.insertAdjacentElement('afterend', fb); }
            function setQuillError(c,msg){ c.classList.add('border','border-danger'); let ex=document.getElementById('quillErrorFb'); if(!ex){ ex=document.createElement('div'); ex.id='quillErrorFb'; ex.className='invalid-feedback d-block mt-2'; c.insertAdjacentElement('afterend', ex); } ex.textContent=msg; }
            function clearQuillError(c){ c.classList.remove('border','border-danger'); const ex=document.getElementById('quillErrorFb'); if(ex) ex.remove(); }

            // 1) التقط صورة الغلاف من السيرفر عند التحميل (قبل أي استعادة مسودة)
            {
                const initialCoverImg = document.querySelector('#coverPreview img');
                if (initialCoverImg) coverSrc = initialCoverImg.src;
            }

            // تنميق صور Quill
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

            // Quill init
            const Font = Quill.import('formats/font'); Font.whitelist=['cairo','tajawal','system']; Quill.register(Font,true);
            const Size = Quill.import('attributors/style/size'); Size.whitelist=['12px','14px','16px','18px','24px','32px']; Quill.register(Size,true);
            const Parchment = Quill.import('parchment');
            const LineHeight = new Parchment.Attributor.Style('lineheight','line-height',{scope:Parchment.Scope.BLOCK,whitelist:['1.4','1.6','1.8','2']});
            Quill.register(LineHeight,true);

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
                    history: { delay: 1000, maxStack: 50, userOnly:true }
                },
                formats: [
                    'font','size','lineheight','header','bold','italic','underline','strike','blockquote','code','code-block',
                    'list','indent','align','direction','color','background','link','image'
                ]
            });

            // Undo/Redo
            document.querySelector('.ql-undo')?.addEventListener('click', () => quill.history.undo());
            document.querySelector('.ql-redo')?.addEventListener('click', () => quill.history.redo());

            // line-height
            document.querySelector('.ql-lineheight')?.addEventListener('change', e => quill.format('lineheight', e.target.value || false));

            // اتجاه افتراضي
            quill.format('direction', 'rtl'); quill.format('align','right');

            // تحميل body إلى Quill + تنميق الصور
            (function initBody(){
                const html = el.bodyInput.value || '';
                if (html) {
                    quill.setContents(quill.clipboard.convert(html), 'silent');
                    styleQuillImages();
                }
            })();

            // عدّادات صور/نص
            const currentImageCount = () => (quill?.root?.querySelectorAll('img')?.length || 0);
            const remainingSlots = () => Math.max(0, MAX_IMAGES - currentImageCount());
            const imgBtn = document.querySelector('#quill-toolbar .ql-image');

            function countWords(text){ return text.trim().split(/\s+/).filter(Boolean).length; }
            function updateTextCounter(){
                const badge=document.getElementById('textCounter'); if(!badge) return;
                let plain=quill.getText()||''; if(plain.endsWith('\n')) plain=plain.slice(0,-1);
                badge.textContent = `{{ __('admin.common.characters') }} ${plain.length} | {{ __('admin.common.words') }} ${countWords(plain)}`;
            }
            function updateImageCounter(){
                const badge=document.getElementById('imgCounter'); if(!badge) return;
                const count=currentImageCount();
                badge.textContent = `${count} / ${MAX_IMAGES}`;
                badge.classList.remove('bg-primary','bg-warning','bg-danger');
                badge.classList.add(count<MAX_IMAGES?'bg-primary':(count===MAX_IMAGES?'bg-warning':'bg-danger'));
                if(imgBtn){ imgBtn.disabled = count>=MAX_IMAGES; imgBtn.setAttribute('aria-disabled', imgBtn.disabled ? 'true' : 'false'); }
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
                    if (slots <= 0) return warn('{{ __('admin.common.err_max_images_reached') }}', `{{ __('admin.common.err_max_images_limit', ['max' => '${MAX_IMAGES}']) }}`);

                    for (const file of files) {
                        if (slots <= 0) { warn('{{ __('admin.common.err_limit_exceeded') }}', `{{ __('admin.common.err_max_images_inserted', ['max' => '${MAX_IMAGES}']) }}`); break; }
                        if (!file.type.startsWith('image/')) continue;
                        if (file.size > MAX_IMAGE_BYTES) { warn('{{ __('admin.common.err_image_too_large') }}', '{{ __('admin.common.err_image_max_2mb') }}'); continue; }
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

            function insertImageAtCursor(url){
                const r = quill.getSelection(true);
                quill.insertEmbed(r.index, 'image', url, 'user');
                quill.setSelection(r.index + 1, 0, 'user');
                styleQuillImages();
                refreshCounters();
            }

            async function uploadQuillImage(file) {
                if (!file.type.startsWith('image/')) throw new Error('{{ __('admin.common.err_images_only') }}');
                if (file.size > MAX_IMAGE_BYTES) throw new Error('{{ __('admin.common.err_image_max_size') }}');

                const fd = new FormData(); fd.append('image', file);
                const res = await fetch("/admin/uploads/quill-image", {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':CSRF }
                });
                if (!res.ok) {
                    let msg = `HTTP ${res.status}`; try { msg = (await res.json()).message || msg; } catch(_) {}
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
                if (slots <= 0) return warn('{{ __('admin.common.err_max_images_reached') }}', `{{ __('admin.common.err_max_images_limit', ['max' => '${MAX_IMAGES}']) }}`);

                for (const file of files) {
                    if (slots <= 0) { warn('{{ __('admin.common.err_limit_exceeded') }}', `{{ __('admin.common.err_max_images_inserted', ['max' => '${MAX_IMAGES}']) }}`); break; }
                    if (file.size > MAX_IMAGE_BYTES) { warn('{{ __('admin.common.err_image_too_large') }}', '{{ __('admin.common.err_image_max_2mb_short') }}'); continue; }
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
                if (slots <= 0) return warn('{{ __('admin.common.err_max_images_reached') }}', `{{ __('admin.common.err_max_images_limit', ['max' => '${MAX_IMAGES}']) }}`);
                for (const it of images) {
                    if (slots <= 0) { warn('{{ __('admin.common.err_limit_exceeded') }}', `{{ __('admin.common.err_max_images_inserted', ['max' => '${MAX_IMAGES}']) }}`); break; }
                    const file = it.getAsFile();
                    if (file.size > MAX_IMAGE_BYTES) { warn('{{ __('admin.common.err_image_too_large') }}', '{{ __('admin.common.err_image_max_2mb_short') }}'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('{{ __('admin.ui.error') }}', e.message || '{{ __('admin.common.err_paste_upload_failed') }}'); }
                }
                refreshCounters();
            });

            // 2) استعادة المسودة بدون الدعس على صورة السيرفر لو المسودة فاضية
            const saved = sessionStorage.getItem(DRAFT_KEY);
            if (saved) {
                const d = JSON.parse(saved);
                if (d.title)    el.title.value = d.title;
                if (d.date)     el.date.value  = d.date;
                if (d.status)   el.status.value= d.status;
                if (d.featured) el.featured.checked = true;

                // طبّق الغلاف من المسودة فقط إذا فيها <img>
                if (d.cover && /<img/i.test(d.cover)) {
                    el.coverPreview.innerHTML = d.cover;
                    const img = el.coverPreview.querySelector('img');
                    if (img) coverSrc = img.src;
                }

                if (d.pdf) el.pdfPreview.innerHTML = d.pdf;
            }

            // 3) تحديث المعاينة يعتمد على coverSrc فقط (شِل القراءة من DOM)
            const update = () => { updatePreview(); autoSave(); refreshCounters(); };
            el.title.addEventListener('input', update);
            el.date.addEventListener('change', update);
            el.status.addEventListener('change', update);
            el.featured.addEventListener('change', update);
            quill.on('text-change', update);

            function updatePreview() {
                const title = el.title.value || '{{ __('admin.news.news_title') }}';
                const date = el.date.value ? new Date(el.date.value).toLocaleDateString('ar-EG', { year:'numeric', month:'long', day:'numeric' }) : '';
                const content = quill.root.innerHTML || '<p class="text-muted">{{ __('admin.news.preview_start_writing') }}</p>';
                const featured = el.featured.checked ? '<span class="badge bg-warning text-dark px-2 py-1 rounded-pill small ms-2">{{ __('admin.news.featured_badge') }}</span>' : '';
                const status = el.status.value === 'draft' ? '<span class="badge bg-secondary text-white px-2 py-1 rounded-pill small ms-2">{{ __('admin.news.draft_badge') }}</span>' : '';

                el.fullPreview.innerHTML = `
        <article class="p-3">
            ${coverSrc ? `<div class="mb-3"><img src="${coverSrc}" class="w-100 rounded" style="max-height:200px; object-fit:cover;"></div>` : ''}
            <h5 class="fw-bold text-primary mb-2">${title} ${featured} ${status}</h5>
            <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                <i class="bi bi-calendar"></i> <span>${date || '{{ __('admin.news.publish_date') }}'}</span>
            </div>
            <div class="content-preview lh-lg" style="font-size:.95rem;">${content}</div>
            ${el.pdfPreview.innerHTML ? `<div class="mt-3"><span class="badge bg-danger-subtle text-danger"><i class="bi bi-file-pdf"></i> {{ __('admin.ui.pdf_attachment') }}</span></div>` : ''}
        </article>`;
            }

            function autoSave() {
                clearTimeout(window.__draftTimeout);
                window.__draftTimeout = setTimeout(() => {
                    const draft = {
                        title: el.title.value,
                        date: el.date.value,
                        status: el.status.value,
                        featured: el.featured.checked,
                        cover: el.coverPreview.innerHTML, // نخزّن DOM، بس ما نطبّقه إلا لو فيه <img>
                        pdf: el.pdfPreview.innerHTML
                    };
                    sessionStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                }, 800);
            }

            // Dropzones
            setupDropzone(el.coverDrop, el.coverInput, handleCover, MAX_IMAGE_BYTES, 'image/*');
            setupDropzone(el.pdfDrop, el.pdfInput, handlePDF, 10 * 1024 * 1024, 'application/pdf');

            function setupDropzone(dropzone, input, handler, maxSize, accept) {
                dropzone.addEventListener('click', () => input.click());
                ['dragover','dragenter'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('dragover'); }));
                ['dragleave','dragend','drop'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('dragover'); }));
                dropzone.addEventListener('drop', e => { const f = e.dataTransfer.files[0]; if (f) handler(f); });
                input.addEventListener('change', () => { const f = input.files[0]; if (f) handler(f); });
            }
            function handleCover(file) {
                if (!file.type.startsWith('image/')) return err('{{ __('admin.ui.error') }}', '{{ __('admin.common.err_images_only') }}');
                if (file.size > MAX_IMAGE_BYTES) return err('{{ __('admin.ui.error') }}', '{{ __('admin.common.err_cover_max_2mb') }}');
                const reader = new FileReader();
                reader.onload = () => {
                    coverSrc = reader.result;
                    el.coverPreview.innerHTML = `<img src="${coverSrc}" class="w-100 rounded shadow-sm" style="max-height:220px; object-fit:cover;">`;
                    const chk=document.getElementById('removeCover'); if(chk) chk.checked=false;
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
                const chk=document.getElementById('removePdf'); if(chk) chk.checked=false;
                updatePreview(); autoSave();
            }
            window.removePDF = () => { el.pdfInput.value = ''; el.pdfPreview.innerHTML = ''; updatePreview(); autoSave(); };

            // 4) لو المستخدم اختار "إزالة الغلاف" صفّر كل شيء
            document.getElementById('removeCover')?.addEventListener('change', (e) => {
                if (e.target.checked) {
                    coverSrc = '';
                    el.coverPreview.innerHTML = '';
                    updatePreview();
                    autoSave();
                }
            });

            // استبدال base64 + حد الصور قبل الإرسال
            async function replaceBase64ImagesInEditor() {
                const container = document.createElement('div');
                container.innerHTML = quill.root.innerHTML;

                const imgs = Array.from(container.querySelectorAll('img[src^="data:"]'));
                for (const img of imgs) {
                    try {
                        const file = dataURLtoFile(img.src, 'inline.png');
                        if (file.size > MAX_IMAGE_BYTES) { await warn('{{ __('admin.common.err_pasted_image_large') }}', '{{ __('admin.common.err_pasted_image_ignored') }}'); img.remove(); continue; }
                        const url = await uploadQuillImage(file); img.src = url;
                    } catch (e) { console.warn('Failed to replace base64 image:', e); img.remove(); }
                }

                const finalImgs = Array.from(container.querySelectorAll('img'));
                if (finalImgs.length > MAX_IMAGES) {
                    const extra = finalImgs.length - MAX_IMAGES;
                    const { isConfirmed } = await Swal.fire({
                        title: '{{ __('admin.common.err_excess_images') }}',
                        html: `{{ __('admin.common.err_excess_images_html', ['count' => '${finalImgs.length}', 'max' => '${MAX_IMAGES}', 'extra' => '${extra}']) }}`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '{{ __('admin.common.err_yes_delete_excess') }}',
                        cancelButtonText: '{{ __('admin.actions.cancel') }}',
                    });
                    if (!isConfirmed) return null;
                    finalImgs.slice(MAX_IMAGES).forEach(img => img.remove());
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

            // إرسال + عرض أخطاء 422
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

                const formData = new FormData(el.form);
                if (!formData.has('_method')) formData.append('_method', 'PUT');

                ['titleInput','dateInput','statusInput','coverDrop','pdfDrop'].forEach(id => clearFieldError(el[id] || document.getElementById(id)));
                clearQuillError(document.querySelector('.quill-wrapper'));

                try {
                    const res = await fetch(el.form.action, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
                    });

                    if (res.status === 422) {
                        const data = await res.json();
                        const errs = data?.errors || {};
                        let first = null;

                        if (errs.title?.[0]) { setFieldError(el.title, errs.title[0]); first = first || el.title; }
                        if (errs.published_at?.[0]) { setFieldError(el.date, errs.published_at[0]); first = first || el.date; }
                        if (errs.status?.[0]) { setFieldError(el.status, errs.status[0]); first = first || el.status; }
                        if (errs.body?.[0]) { setQuillError(document.querySelector('.quill-wrapper'), errs.body[0]); first = first || document.querySelector('.quill-wrapper'); }
                        if (errs.cover?.[0]) { setFieldError(el.coverDrop, errs.cover[0]); first = first || el.coverDrop; }
                        if (errs.pdf?.[0])   { setFieldError(el.pdfDrop,   errs.pdf[0]);   first = first || el.pdfDrop; }

                        if (first?.scrollIntoView) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        throw new Error('VALIDATION_ERROR');
                    }

                    if (!res.ok) {
                        let msg = `HTTP ${res.status}`; try { msg = (await res.json()).message || msg; } catch (_) {}
                        throw new Error(msg);
                    }

                    const data = await res.json();
                    window.location.href = data.redirect || "{{ $breadcrumbParentUrl }}";
                } catch (error) {
                    if (error.message !== 'VALIDATION_ERROR') {
                        console.error('Update failed:', error);
                        Swal.fire('{{ __('admin.ui.error') }}', '{{ __('admin.ui.update_failed') }}', 'error');
                    }
                } finally {
                    isSubmitting = false;
                    el.submitBtn.disabled = false;
                    el.submitText.classList.remove('d-none');
                    el.submitSpinner.classList.add('d-none');
                    el.submitBtn.addEventListener('click', onSubmitClick, { once: true });
                }
            }

            // معاينة أولية
            updatePreview();
        });
    </script>
@endpush
