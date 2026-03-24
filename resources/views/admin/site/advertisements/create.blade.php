@extends('layouts.admin')
@section('title', __('admin.advertisements.create_title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.advertisements.create_title');
        $breadcrumbParent    = __('admin.advertisements.title');
        $breadcrumbParentUrl = route('admin.advertisements.index');

        // حدود الصور داخل المحرر
        $MAX_IMAGES = 8;
        $MAX_IMAGE_BYTES = 2 * 1024 * 1024; // 2MB
    @endphp

    <div class="container-fluid p-0" id="ad-create-page">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-megaphone"
                :title="__('admin.advertisements.create_title')"
                :back-route="route('admin.advertisements.index')">
                <x-slot:actions>
                    <div class="news-tabs" id="adTabs" role="tablist">
                        <button class="news-tab active" data-bs-toggle="tab" data-bs-target="#form-content" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-pencil-square"></i> {{ __('admin.advertisements.form_tab_input') }}
                        </button>
                        <button class="news-tab" data-bs-toggle="tab" data-bs-target="#preview-content" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-eye"></i> {{ __('admin.advertisements.form_tab_preview') }}
                        </button>
                    </div>
                </x-slot:actions>
            </x-admin.card-header-form>

            <div class="tab-content" id="adTabContent">
                <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                    <div class="card-body p-3 p-md-4">
                                <form id="adForm" action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- input مخفي لرفع صور Quill -->
                                    <input type="file" id="quillImageInput" accept="image/*" multiple class="visually-hidden">

                                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                        <i class="bi bi-info-circle"></i> {{ __('admin.common.basic_info') }}
                                    </h6>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('admin.advertisements.form_title') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="TITLE" id="titleInput"
                                               class="form-control rounded-3 border-0 bg-light @error('TITLE') is-invalid @enderror"

                                               placeholder="{{ __('admin.advertisements.form_title_placeholder') }}" value="{{ old('TITLE') }}">
                                        @error('TITLE') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small"><span id="titleCount">{{ old('TITLE') ? strlen(old('TITLE')) : 0 }}</span>/255</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('admin.advertisements.form_date_news') }}</label>
                                        <input type="date" name="DATE_NEWS" id="dateInput"
                                               class="form-control rounded-3 border-0 bg-light @error('DATE_NEWS') is-invalid @enderror"

                                               value="{{ old('DATE_NEWS', now()->format('Y-m-d')) }}">
                                        @error('DATE_NEWS') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title mt-3">
                                        <i class="bi bi-file-text"></i> {{ __('admin.advertisements.form_content') }}
                                    </h6>

                                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                        <small class="text-muted" style="font-size: 0.72rem;">{{ __('admin.ui.content_info_short', ['max' => $MAX_IMAGES]) }}</small>
                                        <div class="d-flex gap-2">
                                            <span id="imgCounter" class="stat-chip" style="font-size: 0.65rem;">0 / {{ $MAX_IMAGES }} {{ __('admin.ui.images_suffix') }}</span>
                                            <span id="textCounter" class="stat-chip" style="font-size: 0.65rem;">الحروف: 0 | الكلمات: 0</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">

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

                                        <textarea name="BODY" id="bodyInput" class="d-none">{{ old('BODY') }}</textarea>
                                        @error('BODY') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- PDF -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="bi bi-file-pdf fs-6 text-danger"></i> {{ __('admin.advertisements.form_pdf') }}
                                        </label>
                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="pdfDrop">
                                            <input type="file" name="PDF" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="bi bi-cloud-upload fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    {{ __('admin.advertisements.form_pdf_drag') }}
                                                    <label for="pdfInput" class="text-primary" style="text-decoration: underline; cursor: pointer;">{{ __('admin.advertisements.form_pdf_select') }}</label>
                                                </p>
                                                <small class="text-muted">{{ __('admin.advertisements.form_pdf_formats') }}</small>
                                            </div>
                                        </div>
                                        <div id="pdfPreview" class="mt-3"></div>
                                        @error('PDF') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex flex-wrap gap-2 mt-5">
                                        <button type="button" id="submitBtn" class="btn btn-save d-flex align-items-center gap-2">
                                            <i class="bi bi-check"></i>
                                            <span id="submitText">{{ __('admin.advertisements.form_publish') }}</span>
                                            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <button type="button" id="saveDraft" class="btn btn-outline-secondary px-4 d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark"></i> {{ __('admin.advertisements.form_save_draft') }}
                                        </button>
                                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-cancel">{{ __('admin.advertisements.form_cancel') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Preview Tab --}}
                <div class="tab-pane fade" id="preview-content" role="tabpanel">
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-eye fs-4 d-block mb-2"></i>
                            <small>{{ __('admin.advertisements.form_preview_start_writing') }}</small>
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
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const DRAFT_KEY = 'ad_create_draft';
            const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const MAX_IMAGES = {{ $MAX_IMAGES }};
            const MAX_IMAGE_BYTES = {{ $MAX_IMAGE_BYTES }};

            let draftTimeout, quill, isSubmitting = false, isPickingImages = false;

            const el = {
                title: document.getElementById('titleInput'),
                date: document.getElementById('dateInput'),
                pdfInput: document.getElementById('pdfInput'),
                pdfDrop: document.getElementById('pdfDrop'),
                pdfPreview: document.getElementById('pdfPreview'),
                bodyInput: document.getElementById('bodyInput'),
                fullPreview: document.getElementById('fullPreview'),
                form: document.getElementById('adForm'),
                submitBtn: document.getElementById('submitBtn'),
                submitText: document.getElementById('submitText'),
                submitSpinner: document.getElementById('submitSpinner'),
                saveDraftBtn: document.getElementById('saveDraft'),
                quillImageInput: document.getElementById('quillImageInput'),
                titleCount: document.getElementById('titleCount'),
            };

            const warn = (t, m) => Swal.fire(t, m, 'warning');
            const err  = (t, m) => Swal.fire(t, m, 'error');

            // Quill: خطوط/أحجام/Line-height
            const Font = Quill.import('formats/font'); Font.whitelist = ['cairo','tajawal','system']; Quill.register(Font, true);
            const Size = Quill.import('attributors/style/size'); Size.whitelist = ['12px','14px','16px','18px','24px','32px']; Quill.register(Size, true);
            const Parchment = Quill.import('parchment');
            const LineHeight = new Parchment.Attributor.Style('lineheight','line-height',{ scope:Parchment.Scope.BLOCK, whitelist:['1.4','1.6','1.8','2']});
            Quill.register(LineHeight, true);

            quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: @json(__('admin.advertisements.form_content_placeholder')),
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
                formats: ['font','size','lineheight','header','bold','italic','underline','strike','blockquote','code','code-block','list','indent','align','direction','color','background','link','image']
            });

            document.querySelector('.ql-undo')?.addEventListener('click', () => quill.history.undo());
            document.querySelector('.ql-redo')?.addEventListener('click', () => quill.history.redo());

            // RTL افتراضي
            quill.format('direction','rtl'); quill.format('align','right');

            function styleQuillImages() {
                document.querySelectorAll('#quill-editor .ql-editor img').forEach(img=>{
                    img.removeAttribute('width'); img.removeAttribute('height');
                    img.style.maxWidth='100%'; img.style.height='auto'; img.style.maxHeight='420px';
                    img.style.objectFit='contain'; img.style.display='block'; img.style.margin='.5rem 0';
                });
            }

            const currentImageCount = () => (quill?.root?.querySelectorAll('img')?.length || 0);
            const remainingSlots = () => Math.max(0, MAX_IMAGES - currentImageCount());
            const imgBtn = document.querySelector('#quill-toolbar .ql-image');

            function countWords(text){ return text.trim().split(/\s+/).filter(Boolean).length; }

            function updateTextCounter() {
                const badge = document.getElementById('textCounter'); if (!badge) return;
                let plain = quill.getText() || ''; if (plain.endsWith('\n')) plain = plain.slice(0,-1);
                badge.textContent = `{{ __('admin.common.characters') }} ${plain.length} | {{ __('admin.common.words') }} ${countWords(plain)}`;
            }

            function updateImageCounter() {
                const badge = document.getElementById('imgCounter'); if (!badge) return;
                const count = currentImageCount();
                badge.textContent = `${count} / ${MAX_IMAGES}`;
                badge.classList.remove('stat-chip-primary','stat-chip-warning','stat-chip-danger');
                badge.classList.add(count < MAX_IMAGES ? 'stat-chip-primary' : (count === MAX_IMAGES ? 'stat-chip-warning' : 'stat-chip-danger'));
                if (imgBtn) {
                    imgBtn.disabled = count >= MAX_IMAGES;
                    imgBtn.setAttribute('aria-disabled', imgBtn.disabled ? 'true' : 'false');
                    imgBtn.title = imgBtn.disabled ? `{{ __('admin.common.err_reached_limit') }}` : '{{ __('admin.common.quill_add_images') }}';
                }
            }
            function refreshCounters(){ updateTextCounter(); updateImageCounter(); }
            quill.on('text-change', ()=>{ refreshCounters(); styleQuillImages(); });
            refreshCounters();

            // اختيار صور من المتصفح
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
                        try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                        catch (e) { err('{{ __('admin.ui.error') }}', e.message || '{{ __('admin.common.err_upload_failed') }}'); }
                    }
                } finally { isPickingImages = false; refreshCounters(); }
            });

            function insertImageAtCursor(url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url, 'user');
                quill.setSelection(range.index + 1, 0, 'user');
                styleQuillImages(); refreshCounters();
            }

            async function uploadQuillImage(file) {
                if (!file.type.startsWith('image/')) throw new Error('{{ __('admin.common.err_images_only') }}');
                if (file.size > MAX_IMAGE_BYTES) throw new Error('{{ __('admin.common.err_image_max_size') }}');

                const fd = new FormData(); fd.append('image', file);
                const res = await fetch('/admin/uploads/quill-image/ads', {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN': CSRF }
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
            ['dragover','dragenter'].forEach(evt => quillEditorArea.addEventListener(evt, e => { e.preventDefault(); e.dataTransfer.dropEffect='copy'; }));
            quillEditorArea.addEventListener('drop', async (e)=>{
                e.preventDefault();
                const files = Array.from(e.dataTransfer.files || []).filter(f=>f.type.startsWith('image/'));
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
            quillEditorArea.addEventListener('paste', async (e)=>{
                const items = Array.from(e.clipboardData?.items || []).filter(it=>it.type && it.type.startsWith('image/'));
                if (!items.length) return;
                e.preventDefault();
                let slots = remainingSlots();
                if (slots <= 0) return warn('{{ __('admin.common.err_max_images_reached') }}', `{{ __('admin.common.err_max_images_limit', ['max' => '${MAX_IMAGES}']) }}`);
                for (const it of items) {
                    if (slots <= 0) { warn('{{ __('admin.common.err_limit_exceeded') }}', `{{ __('admin.common.err_max_images_inserted', ['max' => '${MAX_IMAGES}']) }}`); break; }
                    const file = it.getAsFile();
                    if (file.size > MAX_IMAGE_BYTES) { warn('{{ __('admin.common.err_image_too_large') }}', '{{ __('admin.common.err_image_max_2mb_short') }}'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('{{ __('admin.ui.error') }}', e.message || '{{ __('admin.common.err_paste_upload_failed') }}'); }
                }
                refreshCounters();
            });

            // عدّاد عنوان + حفظ مسودة + معاينة
            const saved = localStorage.getItem(DRAFT_KEY);
            if (saved) {
                try {
                    const d = JSON.parse(saved);
                    if (d.title) el.title.value = d.title;
                    if (d.date) el.date.value = d.date;
                    if (d.body) quill.root.innerHTML = d.body;
                    if (d.pdf) el.pdfPreview.innerHTML = d.pdf;
                    el.titleCount.textContent = el.title.value.length || 0;
                } catch {}
            }

            function updateTextAndPreview() {
                el.titleCount.textContent = el.title.value.length || 0;
                updatePreview(); autoSave();
                refreshCounters();
            }
            el.title.addEventListener('input', updateTextAndPreview);
            el.date.addEventListener('change', updateTextAndPreview);
            quill.on('text-change', updateTextAndPreview);

            function updatePreview(){
                const title = el.title.value || @json(__('admin.advertisements.form_title'));
                const date  = el.date.value ? new Date(el.date.value).toLocaleDateString('ar-EG',{year:'numeric',month:'long',day:'numeric'}) : @json(__('admin.advertisements.form_date_news'));
                const content = quill.root.innerHTML || '<p class="text-muted">{{ __('admin.news.preview_start_writing') }}</p>';
                const pdfBadge = el.pdfPreview.innerHTML ? `<div class="mt-3"><span class="badge bg-danger-subtle text-danger"><i class="bi bi-file-pdf me-1"></i> PDF</span></div>` : '';

                const html = `
            <article class="p-3">
                <h5 class="fw-bold text-primary mb-2">${title}</h5>
                <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                    <i class="bi bi-calendar"></i> <span>${date}</span>
                </div>
                <div class="content-preview lh-lg" style="font-size:.95rem;">${content}</div>
                ${pdfBadge}
            </article>`;
                el.fullPreview.innerHTML = html;
            }

            function autoSave(){
                clearTimeout(draftTimeout);
                draftTimeout = setTimeout(()=>{
                    const draft = {
                        title: el.title.value,
                        date: el.date.value,
                        body: quill.root.innerHTML,
                        pdf: el.pdfPreview.innerHTML
                    };
                    localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                }, 800);
            }

            // Dropzone PDF
            setupDropzone(el.pdfDrop, el.pdfInput, handlePDF, 10*1024*1024, 'application/pdf');
            function setupDropzone(dropzone, input, handler, maxSize, accept){
                dropzone.addEventListener('click', ()=> input.click());
                ['dragover','dragenter'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('dragover'); }));
                ['dragleave','dragend','drop'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('dragover'); }));
                dropzone.addEventListener('drop', e => { const f = e.dataTransfer.files[0]; if (f) handler(f); });
                input.addEventListener('change', ()=> { const f = input.files[0]; if (f) handler(f); });
            }
            function handlePDF(file){
                if (file.type !== 'application/pdf') { err('{{ __('admin.ui.error') }}','{{ __('admin.common.err_pdf_only') }}'); el.pdfInput.value=''; el.pdfPreview.innerHTML=''; return; }
                if (file.size > 10*1024*1024) { err('{{ __('admin.ui.error') }}','{{ __('admin.common.err_pdf_max_10mb') }}'); el.pdfInput.value=''; el.pdfPreview.innerHTML=''; return; }
                const reader = new FileReader();
                reader.onload = ()=>{
                    el.pdfPreview.innerHTML = `
                <div class="alert alert-success d-flex align-items-center justify-content-between p-2 rounded shadow-sm">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-pdf fs-5"></i>
                        <div><strong>${file.name}</strong><br><small>${(file.size/1024/1024).toFixed(2)} MB</small></div>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" onclick="removePDF()"></button>
                </div>`;
                    updatePreview(); autoSave();
                };
                reader.readAsArrayBuffer(file); // للسرعة (ما نعرض المحتوى)
            }
            window.removePDF = ()=>{ el.pdfInput.value=''; el.pdfPreview.innerHTML=''; updatePreview(); autoSave(); };

            // معالجة صور base64 داخل Quill قبل الإرسال (رفعها ثم استبدال src) واحترام حد الصور
            async function replaceBase64ImagesInEditor() {
                const container = document.createElement('div');
                container.innerHTML = quill.root.innerHTML;

                const imgs = Array.from(container.querySelectorAll('img[src^="data:"]'));
                for (const img of imgs) {
                    try {
                        const file = dataURLtoFile(img.src, 'inline.png');
                        if (file.size > MAX_IMAGE_BYTES) { await warn('{{ __('admin.common.err_large_image') }}','{{ __('admin.common.err_pasted_image_ignored') }}'); img.remove(); continue; }
                        const url = await uploadQuillImage(file);
                        img.src = url;
                    } catch (e) { console.warn('Failed to replace base64 image:', e); img.remove(); }
                }

                const finalImgs = Array.from(container.querySelectorAll('img'));
                if (finalImgs.length > MAX_IMAGES) {
                    const extra = finalImgs.length - MAX_IMAGES;
                    const { isConfirmed } = await Swal.fire({
                        title:'{{ __('admin.common.err_excess_images') }}',
                        html:`{{ __('admin.common.err_excess_images_html', ['count' => '${finalImgs.length}', 'max' => '${MAX_IMAGES}', 'extra' => '${extra}']) }}`,
                        icon:'warning', showCancelButton:true, confirmButtonText:'{{ __('admin.ui.yes') }}', cancelButtonText:'{{ __('admin.actions.cancel') }}'
                    });
                    if (!isConfirmed) return null;
                    finalImgs.slice(MAX_IMAGES).forEach(img => img.remove());
                }
                return container.innerHTML;
            }
            function dataURLtoFile(dataUrl, filename){
                const arr=dataUrl.split(','), mime=arr[0].match(/:(.*?);/)[1], bstr=atob(arr[1]); let n=bstr.length;
                const u8=new Uint8Array(n); while(n--) u8[n]=bstr.charCodeAt(n); return new File([u8], filename, {type:mime});
            }

            // إرسال (Ajax-ish UX): قفل زر + سبينر + أخطاء 422
            el.submitBtn.addEventListener('click', onSubmitClick, { once:true });
            async function onSubmitClick(){
                if (isSubmitting) return; isSubmitting = true;
                el.submitBtn.disabled = true; el.submitText.classList.add('d-none'); el.submitSpinner.classList.remove('d-none');

                const cleanedHtml = await replaceBase64ImagesInEditor();
                if (cleanedHtml === null) {
                    isSubmitting = false; el.submitBtn.disabled=false; el.submitText.classList.remove('d-none'); el.submitSpinner.classList.add('d-none');
                    el.submitBtn.addEventListener('click', onSubmitClick, { once:true }); return;
                }
                el.bodyInput.value = cleanedHtml;

                // امسح أخطاء قديمة
                clearFieldError(el.title); clearFieldError(el.date); clearQuillError(document.querySelector('.quill-wrapper')); clearFieldError(el.pdfDrop);

                try{
                    const res = await fetch(el.form.action, {
                        method:'POST',
                        body: new FormData(el.form),
                        credentials:'same-origin',
                        headers:{ 'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN': CSRF }
                    });

                    if (res.status === 422) {
                        const data = await res.json(), errs = data?.errors || {};
                        let first = null;
                        if (errs.TITLE?.[0]) { setFieldError(el.title, errs.TITLE[0]); first = first || el.title; }
                        if (errs.DATE_NEWS?.[0]) { setFieldError(el.date, errs.DATE_NEWS[0]); first = first || el.date; }
                        if (errs.BODY?.[0]) { setQuillError(document.querySelector('.quill-wrapper'), errs.BODY[0]); first = first || document.querySelector('.quill-wrapper'); }
                        if (errs.PDF?.[0]) { setFieldError(el.pdfDrop, errs.PDF[0]); first = first || el.pdfDrop; }
                        if (first?.scrollIntoView) first.scrollIntoView({behavior:'smooth',block:'center'});

                        isSubmitting=false; el.submitBtn.disabled=false; el.submitText.classList.remove('d-none'); el.submitSpinner.classList.add('d-none');
                        el.submitBtn.addEventListener('click', onSubmitClick, { once:true }); return;
                    }

                    if (!res.ok) {
                        let msg = `HTTP ${res.status}`; try { msg = (await res.json()).message || msg; } catch(_){}
                        throw new Error(msg);
                    }

                    const data = await res.json();
                    localStorage.removeItem(DRAFT_KEY);
                    window.location.href = data.redirect || "{{ route('admin.advertisements.index') }}";

                } catch (e) {
                    console.error(e);
                    Swal.fire('{{ __('admin.ui.error') }}','{{ __('admin.ui.publish_failed') }}','error');
                    isSubmitting=false; el.submitBtn.disabled=false; el.submitText.classList.remove('d-none'); el.submitSpinner.classList.add('d-none');
                    el.submitBtn.addEventListener('click', onSubmitClick, { once:true });
                }
            }

            // حفظ مسودة يدوي
            el.saveDraftBtn.addEventListener('click', ()=>{ autoSave(); Swal.fire({title:'{{ __('admin.ui.done') }}',text:'{{ __('admin.ui.draft_saved') }}',icon:'success',timer:1500,showConfirmButton:false}); });

            // أدوات أخطاء الحقول
            function clearFieldError(elm){ if(!elm) return; elm.classList.remove('is-invalid'); const n=elm.nextElementSibling; if(n?.classList?.contains('invalid-feedback')) n.remove(); }
            function setFieldError(elm,msg){ if(!elm) return; clearFieldError(elm); elm.classList.add('is-invalid'); const fb=document.createElement('div'); fb.className='invalid-feedback d-block'; fb.textContent=msg; elm.insertAdjacentElement('afterend', fb); }
            function setQuillError(wrapper,msg){ wrapper.classList.add('border','border-danger'); let fb=document.getElementById('quillErrorFb'); if(!fb){ fb=document.createElement('div'); fb.id='quillErrorFb'; fb.className='invalid-feedback d-block mt-2'; wrapper.insertAdjacentElement('afterend', fb);} fb.textContent=msg; }
            function clearQuillError(wrapper){ wrapper.classList.remove('border','border-danger'); const fb=document.getElementById('quillErrorFb'); if(fb) fb.remove(); }

            // Dropzone PDF (سحب/إفلات/اختيار)
            function setupDropzone(dropzone,input,handler,maxSize,accept){
                dropzone.addEventListener('click', ()=> input.click());
                ['dragover','dragenter'].forEach(e=> dropzone.addEventListener(e, ev=>{ ev.preventDefault(); dropzone.classList.add('dragover'); }));
                ['dragleave','dragend','drop'].forEach(e=> dropzone.addEventListener(e, ev=>{ ev.preventDefault(); dropzone.classList.remove('dragover'); }));
                dropzone.addEventListener('drop', e=>{ const f=e.dataTransfer.files[0]; if(f) handler(f); });
                input.addEventListener('change', ()=>{ const f=input.files[0]; if(f) handler(f); });
            }

            // معاينة أولية
            updatePreview();

            function autoSave(){
                clearTimeout(draftTimeout);
                draftTimeout = setTimeout(()=>{
                    const draft = {
                        title: el.title.value,
                        date: el.date.value,
                        body: quill.root.innerHTML,
                        pdf: el.pdfPreview.innerHTML
                    };
                    localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                }, 800);
            }

        });
    </script>
@endpush
