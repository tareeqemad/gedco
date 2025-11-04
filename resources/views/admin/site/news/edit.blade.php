@extends('layouts.admin')
@section('title', 'تعديل الخبر')

@section('content')
    @php
        $breadcrumbTitle     = 'تعديل الخبر #' . $item->id;
        $breadcrumbParent    = 'الأخبار';
        $breadcrumbParentUrl = route('admin.news.index');
        $tagsArr = is_array($item->tags ?? null) ? $item->tags : (is_string($item->tags ?? null) ? json_decode($item->tags, true) : []);
        if (!is_array($tagsArr)) $tagsArr = [];
    @endphp

    <div class="container-fluid p-0">
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <i class="ri-edit-box-line text-warning fs-6"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">تعديل الخبر #{{ $item->id }}</h6>
                </div>
                <ul class="nav nav-tabs nav-tabs-sm border-0" id="newsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-1" data-bs-toggle="tab" data-bs-target="#form-content" type="button">
                            <i class="ri-edit-line me-1"></i> تعديل
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-1" data-bs-toggle="tab" data-bs-target="#preview-content" type="button">
                            <i class="ri-eye-line me-1"></i> معاينة
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="newsTabContent">
            <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-3 bg-white">
                            <div class="card-body p-4">
                                <form id="newsForm" action="{{ route('admin.news.update', $item) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')

                                    <!-- العنوان -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-heading fs-6 text-primary"></i>
                                            عنوان الخبر <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" id="titleInput"
                                               class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-primary @error('title') is-invalid @enderror"
                                               placeholder="أدخل عنوانًا..." value="{{ old('title', $item->title) }}" required>
                                        @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small"><span id="titleCount">{{ strlen(old('title', $item->title ?? '')) }}</span>/255</div>
                                    </div>

                                    <!-- تاريخ + حالة + مميّز -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-medium text-secondary">
                                                <i class="ri-calendar-line fs-6 text-info"></i> تاريخ النشر <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="published_at" id="dateInput"
                                                   class="form-control rounded-3 border-0 shadow-sm focus-ring focus-ring-info @error('published_at') is-invalid @enderror"
                                                   value="{{ old('published_at', optional($item->published_at)->format('Y-m-d')) }}" required>
                                            @error('published_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium text-secondary">الحالة</label>
                                            @php $stOld = old('status', $item->status ?? 'published'); @endphp
                                            <select name="status" id="statusInput" class="form-select rounded-3 shadow-sm">
                                                <option value="published" @selected($stOld==='published')>منشور</option>
                                                <option value="draft"     @selected($stOld==='draft')>مسودة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="featured" id="featuredInput" value="1" @checked(old('featured', $item->featured ?? false))>
                                                <label class="form-check-label" for="featuredInput">مقالة مميّزة</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- الوسوم -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary">
                                            <i class="ri-price-tag-3-line text-success"></i> وسوم (افصل بفاصلة ,)
                                        </label>
                                        @php
                                            $tagsStr = old('tags') ? (implode(', ', json_decode(old('tags'), true) ?: [])) : (implode(', ', $tagsArr));
                                        @endphp
                                        <input type="text" id="tagsInput" class="form-control rounded-3 border-0 shadow-sm"
                                               placeholder="سياسة, طاقة, شركات..." value="{{ $tagsStr }}">
                                        <input type="hidden" name="tags" id="tagsHidden" value='@json($tagsArr)'>
                                        <div class="form-text small text-muted">تُحفَظ كـ JSON (اختياري).</div>
                                    </div>

                                    <!-- المحتوى (Quill) -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary d-flex align-items-center gap-1">
                                            <i class="ri-file-text-line fs-6 text-success"></i>
                                            المحتوى
                                        </label>
                                        <div class="quill-shell border rounded-3 shadow-sm">
                                            <div id="quill-toolbar">
                                                <span class="ql-formats"><select class="ql-header"></select></span>
                                                <span class="ql-formats">
                                                    <button class="ql-bold"></button>
                                                    <button class="ql-italic"></button>
                                                    <button class="ql-underline"></button>
                                                    <button class="ql-link"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-list" value="ordered"></button>
                                                    <button class="ql-list" value="bullet"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-undo" type="button">↶</button>
                                                    <button class="ql-redo" type="button">↷</button>
                                                </span>
                                            </div>
                                            <div id="quill-editor"></div>
                                        </div>
                                        <textarea name="body" id="bodyInput" class="d-none">{{ old('body', $item->body) }}</textarea>
                                        @error('body') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted small mt-1"><span id="wordCount">0</span> كلمة</div>
                                    </div>

                                    <!-- الغلاف الحالي -->
                                    @if($item->cover_path ?? $item->cover_url ?? false)
                                        <div id="currentCoverBox" class="mb-3 p-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between border">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $item->cover_url ?? Storage::url($item->cover_path) }}" alt="cover" style="width:90px;height:64px;object-fit:cover;border-radius:6px">
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

                                    <!-- صورة غلاف جديدة -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium text-secondary">استبدال صورة الغلاف (اختياري)</label>
                                        <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle" id="coverDrop">
                                            <input type="file" name="cover" id="coverInput" class="visually-hidden" accept="image/*">
                                            <div class="text-primary">
                                                <i class="ri-image-add-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    اسحب صورة هنا أو
                                                    <label for="coverInput" class="text-primary m-0" style="text-decoration: underline; cursor: pointer;">اختر</label>
                                                </p>
                                                <small class="text-muted">png/jpg • حتى 2MB • نسبة 16:9 مفضّلة</small>
                                            </div>
                                        </div>
                                        <div id="coverPreview" class="mt-3"></div>
                                        @error('cover') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- PDF الحالي -->
                                    @if($item->pdf_path ?? $item->pdf_url ?? false)
                                        <div id="currentPdfBox" class="mb-2 p-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between border">
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
                                        <label class="form-label fw-medium text-secondary">استبدال PDF (اختياري)</label>
                                        <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle" id="pdfDrop">
                                            <input type="file" name="pdf" id="pdfInput" class="visually-hidden" accept="application/pdf">
                                            <div class="text-primary">
                                                <i class="ri-upload-cloud-2-line fs-1 mb-2 d-block"></i>
                                                <p class="mb-1 fw-medium">
                                                    اسحب الملف هنا أو
                                                    <label for="pdfInput" class="text-primary m-0" style="text-decoration: underline; cursor: pointer;">اختر ملف</label>
                                                </p>
                                                <small class="text-muted">PDF • حتى 10 ميجابايت</small>
                                            </div>
                                        </div>
                                        <div id="pdfPreview" class="mt-3"></div>
                                        @error('pdf') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- الأزرار -->
                                    <div class="d-flex gap-2 mt-4">
                                        <button type="submit" class="btn btn-success px-4 d-flex align-items-center gap-2 shadow-sm">
                                            <i class="ri-check-line"></i> تحديث الخبر
                                        </button>
                                        <button type="button" id="saveDraft" class="btn btn-outline-secondary px-4 d-flex align-items-center gap-2">
                                            <i class="ri-draft-line"></i> حفظ مسودة محلية
                                        </button>
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-link text-muted text-decoration-none">رجوع</a>
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
                                    <small>ابدأ التعديل لرؤية المعاينة</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- row -->
            </div>

            <div class="tab-pane fade" id="preview-content" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="ri-file-search-line fs-5 d-block mb-2"></i>
                            <small>ابدأ التعديل في تبويب "تعديل" لترى المعاينة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- tab-content -->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        :root{--primary:#4361ee;--info:#3f83f8;--success:#10b981;--danger:#ef4444;--warning:#f59e0b;}
        .quill-shell{height:420px;background:#fff;border-radius:.5rem;overflow:hidden;}
        .quill-shell .ql-toolbar{border-top-left-radius:.5rem;border-top-right-radius:.5rem;}
        .quill-shell .ql-container{height:calc(100% - 42px);border-bottom-left-radius:.5rem;border-bottom-right-radius:.5rem;}
        .quill-shell .ql-editor{height:100%;overflow-y:auto;direction:rtl;text-align:right;}
        .form-control:focus{box-shadow:0 0 0 .2rem rgba(67,97,238,.15);}
        #pdfDrop,#coverDrop{cursor:pointer;}
        #pdfDrop.dragover,#coverDrop.dragover{background:#ebf2ff !important;border-color:var(--primary) !important;}
        #quill-toolbar .ql-undo,#quill-toolbar .ql-redo{
            border:1px solid #ced4da;border-radius:.375rem;padding:0 .5rem;line-height:26px;background:#fff;cursor:pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const draftKey = 'news_edit_draft_{{ $item->id }}';
            let draftTimeout;

            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: { toolbar: { container: '#quill-toolbar' }, history: { delay:500, maxStack:100, userOnly:true } },
                placeholder: 'اكتب محتوى الخبر هنا...'
            });
            const initialHtml = @json(old('body', $item->body ?? ''));
            if (initialHtml) quill.root.innerHTML = initialHtml;
            document.querySelector('#quill-toolbar .ql-undo')?.addEventListener('click', ()=> quill.history.undo());
            document.querySelector('#quill-toolbar .ql-redo')?.addEventListener('click', ()=> quill.history.redo());

            const titleInput = document.getElementById('titleInput');
            const dateInput  = document.getElementById('dateInput');
            const statusInput= document.getElementById('statusInput');
            const featuredInput = document.getElementById('featuredInput');
            const pdfInput   = document.getElementById('pdfInput');
            const pdfDrop    = document.getElementById('pdfDrop');
            const pdfPreview = document.getElementById('pdfPreview');
            const coverInput = document.getElementById('coverInput');
            const coverDrop  = document.getElementById('coverDrop');
            const coverPreview = document.getElementById('coverPreview');
            const removeCurrentPdfInp = document.getElementById('removeCurrentPdf');
            const currentPdfBox = document.getElementById('currentPdfBox');
            const removeCurrentCoverInp = document.getElementById('removeCurrentCover');
            const currentCoverBox = document.getElementById('currentCoverBox');
            const tagsInput  = document.getElementById('tagsInput');
            const tagsHidden = document.getElementById('tagsHidden');

            // مسودة
            const savedDraft = localStorage.getItem(draftKey);
            if (savedDraft) {
                const d = JSON.parse(savedDraft);
                if (d.title) titleInput.value = d.title;
                if (d.date)  dateInput.value  = d.date;
                if (d.status) statusInput.value = d.status;
                if ('featured' in d) featuredInput.checked = !!d.featured;
                if (d.body)  quill.root.innerHTML = d.body;
                if (d.removeCurrentPdf && removeCurrentPdfInp){ removeCurrentPdfInp.value='1'; currentPdfBox?.remove(); }
                if (d.removeCurrentCover && removeCurrentCoverInp){ removeCurrentCoverInp.value='1'; currentCoverBox?.remove(); }
                if (d.tagsString) { tagsInput.value = d.tagsString; syncTagsHidden(); }
            } else {
                syncTagsHidden();
            }

            // UI
            updateAllPreviews(); updateWordCount();
            titleInput.addEventListener('input', ()=>{ document.getElementById('titleCount').textContent = titleInput.value.length; updateAllPreviews(); autoSave(); });
            dateInput.addEventListener('change', ()=>{ updateAllPreviews(); autoSave(); });
            statusInput.addEventListener('change', autoSave);
            featuredInput.addEventListener('change', autoSave);
            tagsInput.addEventListener('input', ()=>{ syncTagsHidden(); autoSave(); });
            quill.on('text-change', ()=>{ updateAllPreviews(); updateWordCount(); autoSave(); });

            function syncTagsHidden(){
                const raw = (tagsInput.value||'').split(',').map(s=>s.trim()).filter(Boolean);
                tagsHidden.value = raw.length ? JSON.stringify(raw) : '';
            }
            function updateWordCount(){
                const words = quill.getText().trim() ? quill.getText().trim().split(/\s+/).length : 0;
                document.getElementById('wordCount').textContent = words;
            }
            function updateAllPreviews(){
                const title   = titleInput.value || 'عنوان الخبر';
                const date    = dateInput.value ? new Date(dateInput.value).toLocaleDateString('ar-EG', { year:'numeric', month:'long', day:'numeric' }) : 'تاريخ النشر';
                const content = quill.root.innerHTML;
                const previewHTML = `
                    <article class="p-3">
                        <h5 class="fw-bold text-primary mb-2">${title}</h5>
                        <div class="text-muted small mb-3 d-flex align-items-center gap-1">
                            <i class="ri-calendar-line"></i> <span>${date}</span>
                        </div>
                        <div class="content-preview lh-lg text-dark" style="font-size:.95rem;">
                            ${content || '<p class="text-muted small">لا يوجد محتوى بعد...</p>'}
                        </div>
                    </article>`;
                ['livePreview','fullPreview'].forEach(id=>{ const el = document.getElementById(id); if (el) el.innerHTML = previewHTML; });
            }
            function autoSave(){
                clearTimeout(draftTimeout);
                draftTimeout = setTimeout(()=>{
                    const draft = {
                        title: titleInput.value,
                        date:  dateInput.value,
                        status: statusInput.value,
                        featured: featuredInput.checked,
                        body:  quill.root.innerHTML,
                        removeCurrentPdf: removeCurrentPdfInp?.value==='1',
                        removeCurrentCover: removeCurrentCoverInp?.value==='1',
                        tagsString: tagsInput.value
                    };
                    localStorage.setItem(draftKey, JSON.stringify(draft));
                }, 600);
            }

            // حذف الحالي
            document.getElementById('btnRemoveCurrentPdf')?.addEventListener('click', ()=>{
                Swal.fire({title:'تأكيد', text:'حذف ملف PDF الحالي؟', icon:'warning', showCancelButton:true, confirmButtonText:'نعم'}).then(res=>{
                    if(res.isConfirmed){ removeCurrentPdfInp.value='1'; currentPdfBox?.remove(); autoSave(); }
                });
            });
            document.getElementById('btnRemoveCover')?.addEventListener('click', ()=>{
                Swal.fire({title:'تأكيد', text:'حذف صورة الغلاف الحالية؟', icon:'warning', showCancelButton:true, confirmButtonText:'نعم'}).then(res=>{
                    if(res.isConfirmed){ removeCurrentCoverInp.value='1'; currentCoverBox?.remove(); autoSave(); }
                });
            });

            // PDF جديد
            pdfDrop.addEventListener('click', e=>{ if(!e.target.closest('.btn-close')) pdfInput.click(); });
            ['dragover','dragenter'].forEach(t=>pdfDrop.addEventListener(t, e=>{ e.preventDefault(); pdfDrop.classList.add('dragover'); }));
            ['dragleave','dragend','drop'].forEach(t=>pdfDrop.addEventListener(t, e=>{ e.preventDefault(); pdfDrop.classList.remove('dragover'); }));
            pdfDrop.addEventListener('drop', e=>{ e.preventDefault(); const f = e.dataTransfer.files?.[0]; if (f) handlePDF(f); });
            pdfInput.addEventListener('change', ()=>{ const f = pdfInput.files?.[0]; if(f) handlePDF(f); });
            function handlePDF(file){
                if (file.type !== 'application/pdf'){ Swal.fire('خطأ','PDF فقط','error'); pdfInput.value=''; pdfPreview.innerHTML=''; return; }
                if (file.size > 10*1024*1024){ Swal.fire('خطأ','الحد 10MB','error'); pdfInput.value=''; pdfPreview.innerHTML=''; return; }
                const dt = new DataTransfer(); dt.items.add(file); pdfInput.files = dt.files;
                pdfPreview.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center gap-2 p-2 mb-0 rounded">
                        <i class="ri-file-pdf-line fs-5"></i>
                        <div><strong>${file.name}</strong><br><small>${(file.size/1024/1024).toFixed(2)} ميجابايت</small></div>
                        <button type="button" class="btn-close btn-close-sm ms-auto" onclick="removeNewPDF()"></button>
                    </div>`;
                autoSave();
            }
            window.removeNewPDF = function(){ pdfInput.value=''; pdfPreview.innerHTML=''; autoSave(); };

            // COVER جديد
            coverDrop.addEventListener('click', e=>{ if(!e.target.closest('.btn-close')) coverInput.click(); });
            ['dragover','dragenter'].forEach(t=>coverDrop.addEventListener(t, e=>{ e.preventDefault(); coverDrop.classList.add('dragover'); }));
            ['dragleave','dragend','drop'].forEach(t=>coverDrop.addEventListener(t, e=>{ e.preventDefault(); coverDrop.classList.remove('dragover'); }));
            coverDrop.addEventListener('drop', e=>{ e.preventDefault(); const f = e.dataTransfer.files?.[0]; if (f) handleCover(f); });
            coverInput.addEventListener('change', ()=>{ const f = coverInput.files?.[0]; if(f) handleCover(f); });
            function handleCover(file){
                if(!file.type.startsWith('image/')){ Swal.fire('خطأ','صورة فقط','error'); coverInput.value=''; coverPreview.innerHTML=''; return; }
                if(file.size > 2*1024*1024){ Swal.fire('خطأ','الحد 2MB','error'); coverInput.value=''; coverPreview.innerHTML=''; return; }
                const dt=new DataTransfer(); dt.items.add(file); coverInput.files=dt.files;
                const r=new FileReader(); r.onload=()=>{ coverPreview.innerHTML = `<img src="${r.result}" class="w-100 rounded" style="max-height:260px;object-fit:cover">`; };
                r.readAsDataURL(file);
                autoSave();
            }

            // submit
            document.getElementById('newsForm').addEventListener('submit', function(){
                document.getElementById('bodyInput').value = quill.root.innerHTML;
                localStorage.removeItem(draftKey);
            });

            document.getElementById('saveDraft')?.addEventListener('click', ()=>{ autoSave(); Swal.fire('تم الحفظ','تم حفظ مسودة محلية بالمتصفح','success'); });
        });
    </script>
@endpush
