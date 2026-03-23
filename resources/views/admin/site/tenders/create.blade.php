@php
    $breadcrumbTitle     = __('admin.tenders.create_title');
    $breadcrumbParent    = __('admin.tenders.title');
    $breadcrumbParentUrl = route('admin.tenders.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.tenders.create_title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-plus-circle"
                :title="__('admin.tenders.create_title')"
                :back-route="route('admin.tenders.index')"
                :back-label="__('admin.tenders.back_to_list')" />
        </x-admin.card>

        <form id="tenderForm" action="{{ route('admin.tenders.store') }}" method="POST" novalidate>
            @csrf

            <div class="row g-4">
                <div class="col-lg-5">
                    <x-admin.card>
                        <x-admin.card-header-form
                            icon="bi-info-circle"
                            :title="__('admin.tenders.form_basic_info')" />
                        <div class="card-body p-4 row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-hash text-primary"></i>{{ __('admin.tenders.form_mnews_id') }}
                                </label>
                                <input type="number" name="mnews_id" 
                                       class="form-control rounded-3  @error('mnews_id') is-invalid @enderror"
                                       style="height: 45px;"
                                       value="{{ old('mnews_id') }}" 
                                       placeholder="{{ __('admin.tenders.form_example_number') }}">
                                @error('mnews_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-file-text text-primary"></i>{{ __('admin.tenders.form_column_name_1') }}
                                </label>
                                <input type="text" name="column_name_1" 
                                       class="form-control rounded-3  @error('column_name_1') is-invalid @enderror"
                                       style="height: 45px;"
                                       value="{{ old('column_name_1') }}" 
                                       placeholder="{{ __('admin.tenders.form_column_placeholder') }}">
                                @error('column_name_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar text-primary"></i>{{ __('admin.tenders.form_the_date_1') }} 
                                    <span class="text-muted small">{{ __('admin.tenders.form_date_text') }}</span>
                                </label>
                                <input type="date" name="the_date_1" 
                                       class="form-control rounded-3  @error('the_date_1') is-invalid @enderror"
                                       style="height: 45px;"
                                       value="{{ old('the_date_1') }}" 
                                       placeholder="{{ __('admin.tenders.form_example_date') }}">
                                @error('the_date_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-123 text-primary"></i>{{ __('admin.tenders.form_coulm_serial') }}
                                </label>
                                <input type="number" name="coulm_serial" 
                                       class="form-control rounded-3  @error('coulm_serial') is-invalid @enderror"
                                       style="height: 45px;"
                                       value="{{ old('coulm_serial') }}" 
                                       placeholder="{{ __('admin.tenders.form_example_number') }}">
                                @error('coulm_serial')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar-event text-primary"></i>{{ __('admin.tenders.form_event_1') }}
                                </label>
                                <input type="text" name="event_1" 
                                       class="form-control rounded-3  @error('event_1') is-invalid @enderror"
                                       style="height: 45px;"
                                       value="{{ old('event_1') }}" 
                                       placeholder="{{ __('admin.tenders.form_event_placeholder') }}">
                                @error('event_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-person text-primary"></i>{{ __('admin.tenders.form_the_user_1') }}
                                </label>
                                <input type="text" name="the_user_1" 
                                       class="form-control rounded-3  @error('the_user_1') is-invalid @enderror"
                                       style="height: 45px;"
                                       value="{{ old('the_user_1') }}" 
                                       placeholder="{{ __('admin.tenders.form_example_user') }}">
                                @error('the_user_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </x-admin.card>
                </div>

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
                            <textarea name="old_value_1" id="old_value_1" class="d-none">{{ old('old_value_1') }}</textarea>
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
                            <textarea name="new_value_1" id="new_value_1" class="d-none">{{ old('new_value_1') }}</textarea>
                            @error('new_value_1')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                        </div>
                    </x-admin.card>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4 shadow-sm rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-check-lg"></i>{{ __('admin.tenders.form_save') }}
                </button>
                <a href="{{ route('admin.tenders.index') }}" class="btn btn-outline-secondary px-4 rounded-3">
                    {{ __('admin.tenders.form_cancel') }}
                </a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/quill/quill.bubble.css') }}">
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
                placeholder: '{{ __('admin.tenders.form_old_value_placeholder') }}'
            });

            // NEW editor
            const qNew = new Quill('#editor-new', {
                theme: 'snow',
                modules: commonModules('#toolbar-new'),
                placeholder: '{{ __('admin.tenders.form_new_value_placeholder') }}'
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
