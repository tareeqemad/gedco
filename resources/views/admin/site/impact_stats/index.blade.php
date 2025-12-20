@extends('layouts.admin')

@section('title', __('admin.impact_stats.title'))

@section('content')
    <div class="container-fluid py-4">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none">{{ __('admin.impact_stats.dashboard') }}</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">{{ __('admin.impact_stats.title') }}</li>
            </ol>
        </nav>

        <!-- Title -->
        <h1 class="h3 mb-4 text-gray-800 d-flex align-items-center gap-2">
            <i class="bi bi-graph-up text-primary"></i>
            {{ __('admin.impact_stats.title') }}
        </h1>

        <!-- رسالة نجاح -->
        @if(session('ok'))
            <div class="alert alert-success alert-dismissible fade show rounded-pill border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('ok') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- الكروت -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="stats-cards">
            @foreach($items as $it)
                <div class="col" data-id="{{ $it->id }}" data-order="{{ $it->sort_order }}">
                    <div class="card h-100 border-0 shadow-sm rounded-3 hover-lift position-relative drag-handle">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-1 fw-semibold sort-badge">#{{ $it->sort_order }}</span>
                                <span class="status-badge badge rounded-pill px-3 py-2 {{ $it->is_active ? 'bg-teal text-white' : 'bg-secondary' }}" data-id="{{ $it->id }}">
                                <i class="bi bi-circle-fill small me-1"></i>
                                {{ $it->is_active ? __('admin.impact_stats.status_active') : __('admin.impact_stats.status_inactive') }}
                            </span>
                            </div>
                            @php
                                $adminDirection = session('direction', 'rtl');
                                $displayTitle = ($adminDirection === 'rtl') ? $it->title_ar : ($it->title_en ?? $it->title_ar);
                            @endphp
                            <h5 class="card-title mb-2 fw-bold text-dark">{{ $displayTitle }}</h5>
                            <p class="display-6 fw-bold text-danger mb-3">
                                ${{ number_format($it->amount_usd, 1) }}
                            </p>
                            <div class="d-flex gap-2">
                                <button class="toggle-btn btn btn-sm w-100 d-flex align-items-center justify-content-center gap-1 {{ $it->is_active ? 'btn-teal' : 'btn-outline-secondary' }}"
                                        data-id="{{ $it->id }}" data-active="{{ $it->is_active ? '1' : '0' }}">
                                    <i class="bi bi-power"></i>
                                    {{ $it->is_active ? __('admin.impact_stats.deactivate') : __('admin.impact_stats.activate') }}
                                </button>

                                <!-- Edit -->
                                <button type="button" class="btn btn-sm btn-warning flex-fill edit-btn d-flex align-items-center justify-content-center gap-1"
                                        data-id="{{ $it->id }}"
                                        data-title-ar="{{ $it->title_ar }}"
                                        data-title-en="{{ $it->title_en ?: '' }}"
                                        data-amount="{{ $it->amount_usd }}"
                                        data-active="{{ $it->is_active ? '1' : '0' }}">
                                    <i class="bi bi-pencil"></i> {{ __('admin.impact_stats.edit') }}
                                </button>

                                <!-- Delete -->
                                <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center justify-content-center"
                                        data-id="{{ $it->id }}"
                                        data-title-ar="{{ $it->title_ar }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="drag-handle-icon position-absolute top-0 end-0 p-3 text-muted opacity-50">
                            <i class="bi bi-grip-vertical"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow-lg">
                    <div class="modal-header bg-white border-bottom">
                        <h5 class="modal-title text-dark fw-bold"><i class="bi bi-plus-lg me-2 text-success"></i> {{ __('admin.impact_stats.add_new_statistic') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.arabic') }}</label>
                                <input type="text" name="title_ar" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.english') }}</label>
                                <input type="text" name="title_en" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('admin.impact_stats.form_amount') }}</label>
                                <input type="number" step="0.1" name="amount_usd" class="form-control" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="newActive" checked>
                                <label class="form-check-label" for="newActive">{{ __('admin.impact_stats.form_active') }}</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.impact_stats.cancel') }}</button>
                            <button type="submit" class="btn btn-success save-btn">
                                <span class="spinner d-none"><i class="bi bi-arrow-repeat spinner-icon"></i></span>
                                {{ __('admin.impact_stats.add') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow-lg">
                    <div class="modal-header bg-white border-bottom">
                        <h5 class="modal-title text-dark fw-bold"><i class="bi bi-pencil me-2 text-primary"></i> {{ __('admin.impact_stats.edit_statistic') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editForm" class="edit-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="id" id="editId">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.arabic') }}</label>
                                <input type="text" name="title_ar" id="editTitleAr" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.english') }}</label>
                                <input type="text" name="title_en" id="editTitleEn" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('admin.impact_stats.form_amount') }}</label>
                                <input type="number" step="0.1" name="amount_usd" id="editAmount" class="form-control" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editActive">
                                <label class="form-check-label" for="editActive">{{ __('admin.impact_stats.form_active') }}</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.impact_stats.cancel') }}</button>
                            <button type="submit" class="btn btn-primary save-btn">
                                <span class="spinner d-none"><i class="bi bi-arrow-repeat spinner-icon"></i></span>
                                {{ __('admin.impact_stats.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3">
                    <div class="modal-header bg-white border-bottom">
                        <h5 class="modal-title text-dark fw-bold"><i class="bi bi-trash me-2 text-danger"></i> {{ __('admin.impact_stats.confirm_delete') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('admin.impact_stats.confirm_delete_text') }} <strong id="deleteTitle"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.impact_stats.cancel') }}</button>
                        <form id="deleteForm" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger delete-confirm">
                                <span class="spinner d-none"><i class="bi bi-arrow-repeat spinner-icon"></i></span>
                                {{ __('admin.impact_stats.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- زر إضافة عائم -->
        <button class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4 shadow-lg d-flex align-items-center justify-content-center"
                style="width:60px;height:60px;z-index:1050;background:#1c3d5a;border:none;"
                data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @push('scripts')
        @vite(['resources/js/impact-stats.js'])
    @endpush
@endsection
