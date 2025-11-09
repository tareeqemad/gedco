@extends('layouts.admin')
@section('title', 'تعديل إعلان')

@section('content')
    @php
        $breadcrumbTitle     = 'تعديل إعلان';
        $breadcrumbParent    = 'الإعلانات والوظائف';
        $breadcrumbParentUrl = route('admin.advertisements.index');

        // حدود الصور داخل المحرر (نفس create)
        $MAX_IMAGES = 8;
        $MAX_IMAGE_BYTES = 2 * 1024 * 1024; // 2MB

        // رابط نسبي للـ PDF الحالي (لو موجود)
        $rel = function (?string $url) {
            if (!$url) return null;
            $parts = parse_url($url);
            $path  = $parts['path']  ?? '/';
            $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
            return $query ? ($path . $query) : $path;
        };
        $currentPdfRelative = $ad->PDF ? $rel(Storage::url($ad->PDF)) : null;
    @endphp

    <div class="container-fluid p-0" id="ad-edit-page">
        <!-- هيدر + تبويبات -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-edit-2-line text-primary fs-5"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">تعديل إعلان: <span class="text-primary">#{{ $ad->ID_ADVER ?? $ad->id }}</span></h6>
                </div>
                <ul class="nav nav-tabs nav-tabs-sm border-0" id="adTabs" role="tablist">
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

        <div class="tab-content" id="adTabContent">
            <!-- تبويب الإدخال -->
            <div class="tab-pane fade show active" id="form-content">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3 bg-white">
                            <div class="card-body p-4">
                                <form id="adForm" action="{{ route('admin.advertisements.update', $ad) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- input مخفي لرفع صور Quill -->
                                    <input type="file" id="quillImageInput" accept="image/*" multiple class="visually-hidden">

                                    <!-- العنوان -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-heading fs-6 text-primary"></i> عنوان الإعلان
                                        </label>
                                        <input type="text" name="TITLE" id="titleInput"
                                               class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-primary @error('TITLE') is-invalid @enderror"
                                               placeholder="أدخل عنوانًا..." value="{{ old('TITLE', $ad->TITLE) }}">
                                        @error('TITLE') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small"><span id="titleCount">{{ strlen(old('TITLE', $ad->TITLE ?? '')) }}</span>/255</div>
                                    </div>

                                    <!-- تاريخ الخبر -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-calendar-line fs-6 text-info"></i> تاريخ الخبر
                                        </label>
                                        <input type="date" name="DATE_NEWS" id="dateInput"
                                               class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-info @error('DATE_NEWS') is-invalid @enderror"
                                               value="{{ old('DATE_NEWS', optional(\Carbon\Carbon::parse($ad->DATE_NEWS))->format('Y-m-d')) }}">
                                        @error('DATE_NEWS') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- المحتوى (Quill) + عدّادات -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-2 flex-wrap">
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <i class="ri-file-text-line fs-6 text-success"></i> محتوى الإعلان
                                        </span>
                                            <small class="text-muted">(حتى {{ $MAX_IMAGES }} صور × 2MB كحد أقصى — ويمكن بدون صور)</small>
                                            <span id="imgCounter" class="badge img-counter bg-primary">0 / {{ $MAX_IMAGES }}</span>
                                            <span id="textCounter" class="badge text-counter bg-secondary ms-1">الحروف: 0 | الكلمات: 0</span>
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
                                                <button type="button" class="ql-undo" title="Undo">↶</button>
                                                <button type="button" class="ql-redo" title="Redo">↷</button>
                                            </span>
                                            </div>

                                            <div id="quill-editor" class="ql-container ql-snow"></div>
                                        </div>

                                        <textarea name="BODY" id="bodyInput" class="d-none">{{ old('BODY', $ad->BODY) }}</textarea>
                                        @error('BODY') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- PDF الحالي + استبدال -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-pdf-line fs-6 text-danger"></i> ملف PDF (اختياري)
                                        </label>

                                        @if($currentPdfRelative)
                                            <div class="alert alert-info d-flex align-items-center justify-content-between p-2 rounded shadow-sm mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ri-attachment-2"></i>
                                                    <a href="{{ $currentPdfRelative }}" target="_blank" rel="noopener" class="text-decoration-underline">
                                                        عرض الملف الحالي
                                                    </a>
                                                </div>
                                                <div class="form-check ms-2">
                                                    <input class="form-check-input" type="checkbox" id="removePdf" name="remove_pdf" value="1">
                                                    <label class="form-check-label" for="removePdf">حذف المرفق الحالي</label>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="dropzone border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle transition" id="pdfDrop">
                                            <input type="file" name="PDF" id="pdfInput" class="visually-hidden" accept="application/pdf">
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
                                        @error('PDF') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- أزرار -->
                                    <div class="d-flex flex-wrap gap-2 mt-5">
                                        <button type="button" id="submitBtn" class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm">
                                            <i class="ri-save-3-line"></i>
                                            <span id="submitText">حفظ التعديلات</span>
                                            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-link text-muted">إلغاء</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- /col -->
                </div> <!-- /row -->
            </div> <!-- /form tab -->

            <!-- تبويب المعاينة الكاملة -->
            <div class="tab-pane fade" id="preview-content">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-header bg-light py-2 px-3">
                        <h6 class="mb-0 fw-semibold text-primary"><i class="ri-file-search-line me-1"></i> معاينة كاملة</h6>
                    </div>
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="ri-file-search-line fs-4 d-block mb-2"></i>
                            <small>ابدأ التعديل في تبويب "إدخال"</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /tab-content -->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        :root { --primary:#4361ee; --success:#10b981; --danger:#ef4444; }

        .quill-wrapper{height:480px;background:#fff;display:flex;flex-direction:column;}
        #quill-toolbar{flex:0 0 auto;}
        #quill-editor{flex:1 1 auto;min-height:0;}
        .ql-container{height:100%!important;font-size:1.05rem;overflow-y:auto;}
        .ql-editor{direction:rtl;text-align:right;min-height:100%;padding:1rem;overflow-wrap:anywhere;word-break:break-word;}

        .ql-editor img,.content-preview img{
            max-width:100%!important;height:auto!important;max-height:420px!important;object-fit:contain!important;display:block;margin:.5rem 0;
        }
        .ql-editor .ql-video,.ql-editor iframe{width:100%!important;max-width:100%!important;height:auto;aspect-ratio:16/9;}

        .dropzone { cursor:pointer; transition: all .2s ease; }
        .dropzone.dragover { background:#ebf2ff!important; border-color:var(--primary)!important; }
        .focus-ring:focus { box-shadow:0 0 0 .2rem rgba(67,97,238,.15); }
        .btn[disabled]{ opacity:.7; cursor:not-allowed; }

        .img-counter,.text-counter{ font-size:.8rem; padding:.35rem .5rem; border-radius:.5rem; }
        #quill-toolbar .ql-image[disabled]{ opacity:.5; cursor:not-allowed; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const DRAFT_KEY = 'ad_edit_draft_{{ $ad->ID_ADVER ?? $ad->id }}';
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
                quillImageInput: document.getElementById('quillImageInput'),
                titleCount: document.getElementById('titleCount'),
                removePdf: document.getElementById('removePdf'),
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
                placeholder: 'حرّر محتوى الإعلان...',
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
            quill.format('direction','rtl'); quill.format('align','right');

            // حمّل القيمة الحالية للمحتوى
            (function preloadBody(){
                const serverBody = @json(old('BODY', $ad->BODY));
                if (serverBody) quill.root.innerHTML = serverBody;
            })();

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
                badge.textContent = `الحروف: ${plain.length} | الكلمات: ${countWords(plain)}`;
            }

            function updateImageCounter() {
                const badge = document.getElementById('imgCounter'); if (!badge) return;
                const count = currentImageCount();
                badge.textContent = `${count} / ${MAX_IMAGES}`;
                badge.classList.remove('bg-primary','bg-warning','bg-danger');
                badge.classList.add(count < MAX_IMAGES ? 'bg-primary' : (count === MAX_IMAGES ? 'bg-warning' : 'bg-danger'));
                if (imgBtn) {
                    imgBtn.disabled = count >= MAX_IMAGES;
                    imgBtn.setAttribute('aria-disabled', imgBtn.disabled ? 'true' : 'false');
                    imgBtn.title = imgBtn.disabled ? `وصلت للحد (${MAX_IMAGES})` : 'إضافة صور متعددة (اختيار/سحب/لصق)';
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
                    if (slots <= 0) return warn('وصلت للحد الأقصى', `الحد هو ${MAX_IMAGES} صور.`);

                    for (const file of files) {
                        if (slots <= 0) { warn('تم تجاوز الحد', `تم إدراج ${MAX_IMAGES} صور كحد أقصى والباقي تم تجاهله.`); break; }
                        if (!file.type.startsWith('image/')) continue;
                        if (file.size > MAX_IMAGE_BYTES) { warn('حجم الصورة كبير', '2MB حد أقصى للصورة — تم تجاهل الكبيرة.'); continue; }
                        try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                        catch (e) { err('خطأ', e.message || 'فشل رفع الصورة'); }
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
                if (!file.type.startsWith('image/')) throw new Error('صورة فقط');
                if (file.size > MAX_IMAGE_BYTES) throw new Error('الحد الأقصى للصورة 2MB');

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
                if (!data.ok || !data.url) throw new Error('استجابة غير متوقعة');
                return data.url; // رابط نسبي
            }

            // سحب/إفلات + لصق صور
            const quillEditorArea = document.querySelector('#quill-editor .ql-editor');
            ['dragover','dragenter'].forEach(evt => quillEditorArea.addEventListener(evt, e => { e.preventDefault(); e.dataTransfer.dropEffect='copy'; }));
            quillEditorArea.addEventListener('drop', async (e)=>{
                e.preventDefault();
                const files = Array.from(e.dataTransfer.files || []).filter(f=>f.type.startsWith('image/'));
                if (!files.length) return;
                let slots = remainingSlots();
                if (slots <= 0) return warn('وصلت للحد الأقصى', `الحد هو ${MAX_IMAGES} صور.`);
                for (const file of files) {
                    if (slots <= 0) { warn('تم تجاوز الحد', `تم إدراج ${MAX_IMAGES} صور كحد أقصى.`); break; }
                    if (file.size > MAX_IMAGE_BYTES) { warn('حجم الصورة كبير', '2MB حد أقصى للصورة.'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('خطأ', e.message || 'فشل رفع صورة بالسحب والإفلات'); }
                }
                refreshCounters();
            });
            quillEditorArea.addEventListener('paste', async (e)=>{
                const items = Array.from(e.clipboardData?.items || []).filter(it=>it.type && it.type.startsWith('image/'));
                if (!items.length) return;
                e.preventDefault();
                let slots = remainingSlots();
                if (slots <= 0) return warn('وصلت للحد الأقصى', `الحد هو ${MAX_IMAGES} صور.`);
                for (const it of items) {
                    if (slots <= 0) { warn('تم تجاوز الحد', `تم إدراج ${MAX_IMAGES} صور كحد أقصى.`); break; }
                    const file = it.getAsFile();
                    if (file.size > MAX_IMAGE_BYTES) { warn('حجم الصورة كبير', '2MB حد أقصى للصورة.'); continue; }
                    try { const url = await uploadQuillImage(file); insertImageAtCursor(url); slots--; }
                    catch (e) { err('خطأ', e.message || 'فشل رفع صورة مُلصقة'); }
                }
                refreshCounters();
            });

            // مسودة محلية (Safe)
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

            function updatePreview(){
                const title = el.title.value || 'عنوان الإعلان';
                const date  = el.date.value ? new Date(el.date.value).toLocaleDateString('ar-EG',{year:'numeric',month:'long',day:'numeric'}) : 'تاريخ الخبر';
                const content = quill.root.innerHTML || '<p class="text-muted">ابدأ التعديل...</p>';
                const pdfBadge = (el.pdfPreview.innerHTML || {{ $currentPdfRelative ? 'true' : 'false' }}) ? `<div class="mt-3"><span class="badge bg-danger-subtle text-danger"><i class="ri-file-pdf-line me-1"></i> مرفق PDF</span></div>` : '';

                const html = `
            <article class="p-3">
                <h5 class="fw-bold text-primary mb-2">${title}</h5>
                <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                    <i class="ri-calendar-line"></i> <span>${date}</span>
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

            function updateTextAndPreview() {
                el.titleCount.textContent = el.title.value.length || 0;
                updatePreview(); autoSave(); refreshCounters();
            }
            el.title.addEventListener('input', updateTextAndPreview);
            el.date.addEventListener('change', updateTextAndPreview);
            quill.on('text-change', updateTextAndPreview);
            updatePreview();

            // Dropzone PDF
            setupDropzone(el.pdfDrop, el.pdfInput, handlePDF, 10*1024*1024, 'application/pdf');
            function setupDropzone(dropzone, input, handler, maxSize, accept){
                dropzone.addEventListener('click', ()=> input.click());
                ['dragover','dragenter'].forEach(e=> dropzone.addEventListener(e, ev=>{ ev.preventDefault(); dropzone.classList.add('dragover'); }));
                ['dragleave','dragend','drop'].forEach(e=> dropzone.addEventListener(e, ev=>{ ev.preventDefault(); dropzone.classList.remove('dragover'); }));
                dropzone.addEventListener('drop', e=>{ const f=e.dataTransfer.files[0]; if(f) handler(f); });
                input.addEventListener('change', ()=>{ const f=input.files[0]; if(f) handler(f); });
            }
            function handlePDF(file){
                if (file.type !== 'application/pdf') { err('خطأ','PDF فقط'); el.pdfInput.value=''; el.pdfPreview.innerHTML=''; return; }
                if (file.size > 10*1024*1024) { err('خطأ','الحد 10MB'); el.pdfInput.value=''; el.pdfPreview.innerHTML=''; return; }
                const reader = new FileReader();
                reader.onload = ()=>{
                    el.pdfPreview.innerHTML = `
                <div class="alert alert-success d-flex align-items-center justify-content-between p-2 rounded shadow-sm">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-file-pdf-line fs-5"></i>
                        <div><strong>${file.name}</strong><br><small>${(file.size/1024/1024).toFixed(2)} MB</small></div>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" onclick="removePDF()"></button>
                </div>`;
                    // إذا اخترت ملف جديد، اشطب خيار الحذف الحالي تلقائيًا
                    if (el.removePdf) el.removePdf.checked = false;
                    updatePreview(); autoSave();
                };
                reader.readAsArrayBuffer(file);
            }
            window.removePDF = ()=>{ el.pdfInput.value=''; el.pdfPreview.innerHTML=''; updatePreview(); autoSave(); };

            // استبدال صور base64 داخل Quill قبل الإرسال
            async function replaceBase64ImagesInEditor() {
                const container = document.createElement('div');
                container.innerHTML = quill.root.innerHTML;

                const imgs = Array.from(container.querySelectorAll('img[src^="data:"]'));
                for (const img of imgs) {
                    try {
                        const file = dataURLtoFile(img.src, 'inline.png');
                        if (file.size > MAX_IMAGE_BYTES) { await warn('صورة كبيرة','تجاوزت 2MB وتم تجاهلها.'); img.remove(); continue; }
                        const url = await uploadQuillImage(file);
                        img.src = url;
                    } catch (e) { console.warn('تعذر استبدال صورة base64:', e); img.remove(); }
                }

                const finalImgs = Array.from(container.querySelectorAll('img'));
                if (finalImgs.length > MAX_IMAGES) {
                    const extra = finalImgs.length - MAX_IMAGES;
                    const { isConfirmed } = await Swal.fire({
                        title:'عدد الصور زائد',
                        html:`لديك <b>${finalImgs.length}</b> صورة، والحد <b>${MAX_IMAGES}</b>.<br>هل تريد حذف <b>${extra}</b> صورة زائدة؟`,
                        icon:'warning', showCancelButton:true, confirmButtonText:'نعم', cancelButtonText:'إلغاء'
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

            // إرسال (Ajax UX) — نفس create
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
                        method:'POST', // مع @method('PUT') في النموذج
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
                    Swal.fire('خطأ','تعذّر الحفظ. تأكد من الاتصال وحجم الملفات.','error');
                    isSubmitting=false; el.submitBtn.disabled=false; el.submitText.classList.remove('d-none'); el.submitSpinner.classList.add('d-none');
                    el.submitBtn.addEventListener('click', onSubmitClick, { once:true });
                }
            }

            // أدوات أخطاء الحقول
            function clearFieldError(elm){ if(!elm) return; elm.classList.remove('is-invalid'); const n=elm.nextElementSibling; if(n?.classList?.contains('invalid-feedback')) n.remove(); }
            function setFieldError(elm,msg){ if(!elm) return; clearFieldError(elm); elm.classList.add('is-invalid'); const fb=document.createElement('div'); fb.className='invalid-feedback d-block'; fb.textContent=msg; elm.insertAdjacentElement('afterend', fb); }
            function setQuillError(wrapper,msg){ wrapper.classList.add('border','border-danger'); let fb=document.getElementById('quillErrorFb'); if(!fb){ fb=document.createElement('div'); fb.id='quillErrorFb'; fb.className='invalid-feedback d-block mt-2'; wrapper.insertAdjacentElement('afterend', fb);} fb.textContent=msg; }
            function clearQuillError(wrapper){ wrapper.classList.remove('border','border-danger'); const fb=document.getElementById('quillErrorFb'); if(fb) fb.remove(); }
        });
    </script>
@endpush
