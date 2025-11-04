{{-- resources/views/admin/site/tenders/create.blade.php --}}
@extends('layouts.admin')
@section('title','إضافة عطاء')

@section('content')
    <div class="container-fluid p-0">
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <i class="ri-add-circle-line text-primary fs-6"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">إضافة عطاء</h6>
                </div>
                <a href="{{ route('admin.tenders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-go-back-line me-1"></i> رجوع للقائمة
                </a>
            </div>
        </div>

        <form id="tenderForm" action="{{ route('admin.tenders.store') }}" method="POST" novalidate>
            @csrf

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-3 bg-white">
                        <div class="card-body p-4 row g-3">

                            <div class="col-md-4">
                                <label class="form-label">MNEWS_ID</label>
                                <input type="number" name="mnews_id" class="form-control @error('mnews_id') is-invalid @enderror"
                                       value="{{ old('mnews_id') }}" placeholder="مثال: 57">
                                @error('mnews_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">COLUMN_NAME_1</label>
                                <input type="text" name="column_name_1" class="form-control @error('column_name_1') is-invalid @enderror"
                                       value="{{ old('column_name_1') }}" placeholder="body / title / pdf ...">
                                @error('column_name_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">THE_DATE_1 <span class="text-muted">(نصي)</span></label>
                                <input type="text" name="the_date_1" class="form-control @error('the_date_1') is-invalid @enderror"
                                       value="{{ old('the_date_1') }}" placeholder="مثال: 6/20/2016 11:55 AM">
                                @error('the_date_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">COULM_SERIAL</label>
                                <input type="number" name="coulm_serial" class="form-control @error('coulm_serial') is-invalid @enderror"
                                       value="{{ old('coulm_serial') }}" placeholder="مثال: 57">
                                @error('coulm_serial')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">EVENT_1</label>
                                <input type="text" name="event_1" class="form-control @error('event_1') is-invalid @enderror"
                                       value="{{ old('event_1') }}" placeholder="update / delete">
                                @error('event_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">THE_USER_1</label>
                                <input type="text" name="the_user_1" class="form-control @error('the_user_1') is-invalid @enderror"
                                       value="{{ old('the_user_1') }}" placeholder="admin@gedco">
                                @error('the_user_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    {{-- OLD_VALUE_1 --}}
                    <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                        <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-file-text-line text-secondary"></i>
                                <span class="fw-semibold">النص القديم (OLD_VALUE_1)</span>
                            </div>
                            <small class="text-muted">HTML مدعوم</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="quill-shell border rounded-3 shadow-sm">
                                <div id="toolbar-old" class="ql-toolbar ql-snow">
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
                                        <select class="ql-align"></select>
                                        <button class="ql-clean"></button>
                                    </span>
                                </div>
                                <div id="editor-old" class="ql-container ql-snow"></div>
                            </div>
                            <textarea name="old_value_1" id="old_value_1" class="d-none">{{ old('old_value_1') }}</textarea>
                            @error('old_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- NEW_VALUE_1 --}}
                    <div class="card border-0 shadow-sm rounded-3 bg-white">
                        <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-file-text-line text-success"></i>
                                <span class="fw-semibold">النص الجديد (NEW_VALUE_1)</span>
                            </div>
                            <small class="text-muted">HTML مدعوم</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="quill-shell border rounded-3 shadow-sm">
                                <div id="toolbar-new" class="ql-toolbar ql-snow">
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
                                        <select class="ql-align"></select>
                                        <button class="ql-clean"></button>
                                    </span>
                                </div>
                                <div id="editor-new" class="ql-container ql-snow"></div>
                            </div>
                            <textarea name="new_value_1" id="new_value_1" class="d-none">{{ old('new_value_1') }}</textarea>
                            @error('new_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="ri-check-line me-1"></i> حفظ
                </button>
                <a href="{{ route('admin.tenders.index') }}" class="btn btn-light px-4">إلغاء</a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    {{-- نفس أسلوب الاستدعاء المستخدم عندك --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.bubble.css') }}">
    <style>
        :root{ --primary:#4361ee; }
        .quill-shell{ height: 360px; background:#fff; border-radius:.5rem; overflow:hidden; }
        .quill-shell .ql-container{ height: calc(100% - 42px); }
        .ql-editor{ direction: rtl; text-align: right; }
        .btn-primary{ background:var(--primary); border:none; }
        .btn-primary:hover{ background:#3b56d7; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commonModules = id => ({
                toolbar: { container: id },
                history: { delay: 400, maxStack: 100, userOnly: true }
            });

            // OLD editor
            const qOld = new Quill('#editor-old', {
                theme: 'snow',
                modules: commonModules('#toolbar-old'),
                placeholder: 'الصق/اكتب النص القديم هنا...'
            });

            // NEW editor
            const qNew = new Quill('#editor-new', {
                theme: 'snow',
                modules: commonModules('#toolbar-new'),
                placeholder: 'الصق/اكتب النص الجديد هنا...'
            });

            // preload from old()
            const oldHidden = document.getElementById('old_value_1');
            const newHidden = document.getElementById('new_value_1');
            if (oldHidden.value) qOld.root.innerHTML = oldHidden.value;
            if (newHidden.value) qNew.root.innerHTML = newHidden.value;

            // on submit → dump HTML to hidden fields
            document.getElementById('tenderForm').addEventListener('submit', function () {
                oldHidden.value = qOld.root.innerHTML.trim();
                newHidden.value = qNew.root.innerHTML.trim();
            });
        });
    </script>
@endpush
