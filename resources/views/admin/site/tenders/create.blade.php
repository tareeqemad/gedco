@php
    $breadcrumbTitle     = __('admin.tenders.create_title');
    $breadcrumbParent    = __('admin.tenders.title');
    $breadcrumbParentUrl = route('admin.tenders.index');

    $MAX_IMAGES = 8;
    $MAX_IMAGE_BYTES = 2 * 1024 * 1024;
@endphp
@extends('layouts.admin')
@section('title', __('admin.tenders.create_title'))

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-plus-circle"
                :title="__('admin.tenders.create_title')"
                :back-route="route('admin.tenders.index')"
                :back-label="__('admin.tenders.back_to_list')" />

            <div class="card-body p-3 p-md-4">
                <form id="tenderForm" action="{{ route('admin.tenders.store') }}" method="POST" novalidate>
                    @csrf

                    {{-- Basic Info --}}
                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                        <i class="bi bi-info-circle"></i> {{ __('admin.tenders.form_basic_info') }}
                    </h6>

                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.tenders.form_title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="column_name_1"
                               class="form-control rounded-3 border-0 bg-light @error('column_name_1') is-invalid @enderror"
                               placeholder="{{ __('admin.tenders.form_title_placeholder') }}"
                               value="{{ old('column_name_1') }}" required>
                        @error('column_name_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.tenders.form_date') }}</label>
                        <input type="date" name="the_date_1"
                               class="form-control rounded-3 border-0 bg-light @error('the_date_1') is-invalid @enderror"
                               value="{{ old('the_date_1', now()->format('Y-m-d')) }}">
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
                        <textarea name="new_value_1" id="new_value_1" class="d-none">{{ old('new_value_1') }}</textarea>
                        @error('new_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <button type="submit" class="btn btn-save d-flex align-items-center gap-2">
                            <i class="bi bi-check-lg"></i> {{ __('admin.tenders.form_save') }}
                        </button>
                        <a href="{{ route('admin.tenders.index') }}" class="btn btn-cancel">
                            {{ __('admin.tenders.form_cancel') }}
                        </a>
                    </div>
                </form>
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

            // Preload from old()
            const hidden = document.getElementById('new_value_1');
            if (hidden.value) quill.root.innerHTML = hidden.value;

            // On submit → dump HTML to hidden field
            document.getElementById('tenderForm').addEventListener('submit', function () {
                hidden.value = quill.root.innerHTML.trim();
            });
        });
    </script>
@endpush
