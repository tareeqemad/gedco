@php
    $breadcrumbTitle     = __('admin.tenders.edit_title') . ' #' . $tender->id;
    $breadcrumbParent    = __('admin.tenders.title');
    $breadcrumbParentUrl = route('admin.tenders.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.tenders.edit_title') . ' #' . $tender->id)

@section('content')
    <div class="container-fluid p-0">
        <!-- Header + Tabs -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-pencil-square"
                :title="__('admin.tenders.edit_title') . ' #' . $tender->id">
                <x-slot:actions>
                    <ul class="nav nav-tabs nav-tabs-sm border-0" id="tenderTabs" role="tablist" style="background: rgba(255, 255, 255, 0.15); border-radius: 0.5rem; padding: 0.25rem;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-3 py-1 text-white" id="form-tab" data-bs-toggle="tab" data-bs-target="#form-content" type="button" role="tab" aria-selected="true">
                                <i class="bi bi-pencil me-1"></i> {{ __('admin.tenders.tab_edit') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3 py-1 text-white" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-content" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-eye me-1"></i> {{ __('admin.tenders.tab_preview') }}
                            </button>
                        </li>
                    </ul>
                </x-slot:actions>
            </x-admin.card-header-form>
        </x-admin.card>

        <div class="tab-content" id="tenderTabContent">
            <!-- تبويب التعديل -->
            <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                <form id="tenderForm" action="{{ route('admin.tenders.update', $tender->id) }}" method="POST" novalidate>
                    @csrf @method('PUT')

                    <div class="row g-4">
                        <!-- الحقول الأساسية -->
                        <div class="col-lg-5">
                            <x-admin.card>
                                <x-admin.card-header-form
                                    icon="bi-info-circle"
                                    :title="__('admin.tenders.form_basic_info') ?? 'المعلومات الأساسية'" />
                                <div class="card-body p-4 row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-hash"></i>{{ __('admin.tenders.form_mnews_id') }}
                                        </label>
                                        <input type="number" name="mnews_id"
                                               class="form-control rounded-3  @error('mnews_id') is-invalid @enderror"

                                               value="{{ old('mnews_id', $tender->mnews_id) }}" 
                                               placeholder="{{ __('admin.tenders.form_example_number') }}">
                                        @error('mnews_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-file-text"></i>{{ __('admin.tenders.form_column_name_1') }}
                                        </label>
                                        <input type="text" name="column_name_1"
                                               class="form-control rounded-3  @error('column_name_1') is-invalid @enderror"

                                               value="{{ old('column_name_1', $tender->column_name_1) }}"
                                               placeholder="{{ __('admin.tenders.form_column_placeholder') }}">
                                        @error('column_name_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-calendar"></i>{{ __('admin.tenders.form_the_date_1') }} 
                                            <span class="text-muted small">{{ __('admin.tenders.form_date_text') }}</span>
                                        </label>
                                        <input type="date" name="the_date_1"
                                               class="form-control rounded-3  @error('the_date_1') is-invalid @enderror"

                                               value="{{ old('the_date_1', $tender->the_date_1) }}"
                                               placeholder="{{ __('admin.tenders.form_example_date') }}">
                                        @error('the_date_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-123"></i>{{ __('admin.tenders.form_coulm_serial') }}
                                        </label>
                                        <input type="number" name="coulm_serial"
                                               class="form-control rounded-3  @error('coulm_serial') is-invalid @enderror"

                                               value="{{ old('coulm_serial', $tender->coulm_serial) }}" 
                                               placeholder="{{ __('admin.tenders.form_example_number') }}">
                                        @error('coulm_serial')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-calendar-event"></i>{{ __('admin.tenders.form_event_1') }}
                                        </label>
                                        <input type="text" name="event_1"
                                               class="form-control rounded-3  @error('event_1') is-invalid @enderror"

                                               value="{{ old('event_1', $tender->event_1) }}" 
                                               placeholder="{{ __('admin.tenders.form_event_placeholder') }}">
                                        @error('event_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-person"></i>{{ __('admin.tenders.form_the_user_1') }}
                                        </label>
                                        <input type="text" name="the_user_1"
                                               class="form-control rounded-3  @error('the_user_1') is-invalid @enderror"

                                               value="{{ old('the_user_1', $tender->the_user_1) }}" 
                                               placeholder="{{ __('admin.tenders.form_example_user') }}">
                                        @error('the_user_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </x-admin.card>
                        </div>

                        <!-- المحرران (OLD / NEW) -->
                        <div class="col-lg-7">
                            {{-- OLD_VALUE_1 --}}
                            <x-admin.card class="mb-4">
                                <x-admin.card-header-form
                                    icon="bi-file-text"
                                    :title="__('admin.tenders.form_old_value')">
                                    <x-slot:actions>
                                        <small class="text-white-50">{{ __('admin.tenders.form_html_supported') }}</small>
                                    </x-slot:actions>
                                </x-admin.card-header-form>
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
                                    <textarea name="old_value_1" id="old_value_1" class="d-none">{{ old('old_value_1', $tender->old_value_1) }}</textarea>
                                    @error('old_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                </div>
                            </x-admin.card>

                            {{-- NEW_VALUE_1 --}}
                            <x-admin.card>
                                <x-admin.card-header-form
                                    icon="bi-file-text"
                                    :title="__('admin.tenders.form_new_value')">
                                    <x-slot:actions>
                                        <small class="text-white-50">{{ __('admin.tenders.form_html_supported') }}</small>
                                    </x-slot:actions>
                                </x-admin.card-header-form>
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
                                    <textarea name="new_value_1" id="new_value_1" class="d-none">{{ old('new_value_1', $tender->new_value_1) }}</textarea>
                                    @error('new_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                </div>
                            </x-admin.card>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <button type="submit" class="btn btn-save d-flex align-items-center gap-2">
                            <i class="bi bi-check-lg"></i>{{ __('admin.tenders.form_update') }}
                        </button>
                        <a href="{{ route('admin.tenders.index') }}" class="btn btn-cancel">
                            {{ __('admin.tenders.back') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- تبويب المعاينة الكاملة -->
            <div class="tab-pane fade" id="preview-content" role="tabpanel">
                <x-admin.card>
                    <x-admin.card-header-form
                        icon="bi-eye"
                        :title="__('admin.tenders.tab_preview')" />
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-eye fs-5 d-block mb-2"></i>
                            <small>{{ __('admin.tenders.form_preview_start_editing') }}</small>
                        </div>
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const commonModules = (toolbarId) => ({
                    toolbar: { container: toolbarId },
                    history: { delay: 400, maxStack: 100, userOnly: true }
                });

                // init editors
                const qOld = new Quill('#editor-old', {
                    theme: 'snow',
                    modules: commonModules('#toolbar-old'),
                    placeholder: '{{ __('admin.tenders.form_old_value_placeholder') }}'
                });
                const qNew = new Quill('#editor-new', {
                    theme: 'snow',
                    modules: commonModules('#toolbar-new'),
                    placeholder: '{{ __('admin.tenders.form_new_value_placeholder') }}'
                });

                // preload from hidden (old())
                const oldHidden = document.getElementById('old_value_1');
                const newHidden = document.getElementById('new_value_1');
                if (oldHidden.value) qOld.root.innerHTML = oldHidden.value;
                if (newHidden.value) qNew.root.innerHTML = newHidden.value;

                // live preview (جهة التبويب الثاني)
                function renderPreview(){
                    const title = @json('عطاء #' . $tender->id);
                    const meta  = [
                        'MNEWS_ID: {{ $tender->mnews_id ?? "-" }}',
                        'USER: {{ $tender->the_user_1 ?? "-" }}',
                        'DATE: {{ $tender->the_date_1 ?? "-" }}',
                        'EVENT: {{ $tender->event_1 ?? "-" }}'
                    ].join(' • ');

                    const oldHtml = qOld.root.innerHTML?.trim();
                    const newHtml = qNew.root.innerHTML?.trim();

                    const html = `
                        <article class="p-2">
                            <h5 class="fw-bold text-primary mb-1">${title}</h5>
                            <div class="text-muted small mb-3">${meta}</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-2">
                                        <div class="text-secondary fw-semibold mb-2">OLD_VALUE_1</div>
                                        ${oldHtml || '<p class="text-muted small m-0">فارغ…</p>'}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-2">
                                        <div class="text-success fw-semibold mb-2">NEW_VALUE_1</div>
                                        ${newHtml || '<p class="text-muted small m-0">فارغ…</p>'}
                                    </div>
                                </div>
                            </div>
                        </article>`;
                    const box = document.getElementById('fullPreview');
                    if (box) box.innerHTML = html;
                }
                qOld.on('text-change', renderPreview);
                qNew.on('text-change', renderPreview);
                renderPreview();

                // on submit → dump html to hidden fields
                document.getElementById('tenderForm').addEventListener('submit', function () {
                    oldHidden.value = qOld.root.innerHTML.trim();
                    newHidden.value = qNew.root.innerHTML.trim();
                });
            });
        </script>
    @endpush
@endsection
