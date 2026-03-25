@php
    $breadcrumbTitle     = __('admin.tenders.edit_title') . ' #' . $tender->id;
    $breadcrumbParent    = __('admin.tenders.title');
    $breadcrumbParentUrl = route('admin.tenders.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.tenders.edit_title') . ' #' . $tender->id)

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-pencil-square"
                :title="__('admin.tenders.edit_title') . ' #' . $tender->id"
                :back-route="route('admin.tenders.index')"
                :back-label="__('admin.tenders.back_to_list')">
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

            <div class="tab-content" id="tenderTabContent">
                {{-- Edit Tab --}}
                <div class="tab-pane fade show active" id="form-content" role="tabpanel">
                    <div class="card-body p-3 p-md-4">
                        <form id="tenderForm" action="{{ route('admin.tenders.update', $tender->id) }}" method="POST" novalidate>
                            @csrf @method('PUT')

                            {{-- Metadata badges --}}
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                @if($tender->the_user_1)
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                        <i class="bi bi-person me-1"></i> {{ __('admin.tenders.created_by') }}: {{ $tender->the_user_1 }}
                                    </span>
                                @endif
                                @if($tender->created_at)
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                        <i class="bi bi-clock me-1"></i> {{ $tender->created_at->translatedFormat('d M Y - h:i A') }}
                                    </span>
                                @endif
                                @if($tender->mnews_id)
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                        <i class="bi bi-hash me-1"></i> {{ __('admin.tenders.tender_number') }}: {{ $tender->mnews_id }}
                                    </span>
                                @endif
                            </div>

                            {{-- Basic Info --}}
                            <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                <i class="bi bi-info-circle"></i> {{ __('admin.tenders.form_basic_info') }}
                            </h6>

                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.tenders.form_title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="column_name_1"
                                       class="form-control rounded-3 border-0 bg-light @error('column_name_1') is-invalid @enderror"
                                       placeholder="{{ __('admin.tenders.form_title_placeholder') }}"
                                       value="{{ old('column_name_1', $tender->column_name_1) }}" required>
                                @error('column_name_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.tenders.form_date') }}</label>
                                <input type="date" name="the_date_1"
                                       class="form-control rounded-3 border-0 bg-light @error('the_date_1') is-invalid @enderror"
                                       value="{{ old('the_date_1', $tender->the_date_1) }}">
                                @error('the_date_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            {{-- Content --}}
                            <h6 class="fw-bold d-flex align-items-center gap-2 section-title mt-4">
                                <i class="bi bi-file-earmark-text"></i> {{ __('admin.tenders.form_content') }}
                            </h6>

                            <div class="mb-3">
                                <div class="quill-wrapper border rounded-3 shadow-sm overflow-hidden">
                                    <div id="quill-toolbar" class="px-2 py-1">
                                        <span class="ql-formats"><select class="ql-header"><option selected></option><option value="2"></option><option value="3"></option></select></span>
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
                                        </span>
                                        <span class="ql-formats">
                                            <button class="ql-clean"></button>
                                        </span>
                                    </div>
                                    <div id="quill-editor"></div>
                                </div>
                                <textarea name="new_value_1" id="new_value_1" class="d-none">{{ old('new_value_1', $tender->new_value_1) }}</textarea>
                                @error('new_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <button type="submit" class="btn btn-save d-flex align-items-center gap-2">
                                    <i class="bi bi-check-lg"></i> {{ __('admin.tenders.form_update') }}
                                </button>
                                <a href="{{ route('admin.tenders.index') }}" class="btn btn-cancel">
                                    {{ __('admin.tenders.back') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Preview Tab --}}
                <div class="tab-pane fade" id="preview-content" role="tabpanel">
                    <div class="card-body p-4" id="fullPreview">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-eye fs-5 d-block mb-2"></i>
                            <small>{{ __('admin.tenders.form_preview_start_editing') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: {
                    toolbar: { container: '#quill-toolbar' },
                    history: { delay: 400, maxStack: 100, userOnly: true }
                },
                placeholder: '{{ __('admin.tenders.form_content_placeholder') }}'
            });

            // Preload content
            const hidden = document.getElementById('new_value_1');
            if (hidden.value) quill.root.innerHTML = hidden.value;

            // Live preview
            function renderPreview() {
                const title = document.querySelector('input[name="column_name_1"]').value || '{{ __('admin.tenders.no_title') }}';
                const date = document.querySelector('input[name="the_date_1"]').value || '-';
                const content = quill.root.innerHTML?.trim();

                document.getElementById('fullPreview').innerHTML = `
                    <article>
                        <h5 class="fw-bold text-primary mb-2">${title}</h5>
                        <div class="d-flex gap-3 text-muted small mb-3">
                            <span><i class="bi bi-calendar me-1"></i> ${date}</span>
                            <span><i class="bi bi-person me-1"></i> {{ $tender->the_user_1 ?? '-' }}</span>
                        </div>
                        <hr>
                        <div class="article-html">${content || '<p class="text-muted">{{ __('admin.tenders.no_content') }}</p>'}</div>
                    </article>`;
            }

            quill.on('text-change', renderPreview);
            document.querySelector('input[name="column_name_1"]').addEventListener('input', renderPreview);
            document.querySelector('input[name="the_date_1"]').addEventListener('change', renderPreview);
            renderPreview();

            // On submit → dump HTML
            document.getElementById('tenderForm').addEventListener('submit', function () {
                hidden.value = quill.root.innerHTML.trim();
            });
        });
    </script>
@endpush
