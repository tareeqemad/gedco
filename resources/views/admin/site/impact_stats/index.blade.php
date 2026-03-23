@php
    $breadcrumbTitle     = __('admin.impact_stats.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.impact_stats.title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-graph-up-arrow"
                :title="__('admin.impact_stats.title')">
                <x-slot:badge>
                    @if($items->count() > 0)
                        <span class="badge bg-white text-primary rounded-pill" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                            {{ $items->count() }}
                        </span>
                    @endif
                </x-slot:badge>
                <x-slot:actions>
                    <button class="btn btn-light btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle me-1"></i>
                        <span class="d-none d-md-inline">{{ __('admin.impact_stats.add_new_statistic') }}</span>
                        <span class="d-md-none">{{ __('admin.impact_stats.add') }}</span>
                    </button>
                </x-slot:actions>
            </x-admin.card-header-index>

            <!-- الكروت -->
            @if($items->count() > 0)
            <div class="card-body p-3">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="stats-cards">
                @foreach($items as $it)
                    <div class="col" data-id="{{ $it->id }}" data-order="{{ $it->sort_order }}">
                        <div class="card h-100 border-0 shadow-sm rounded-4 bg-white position-relative impact-stat-card" style="transition: all 0.3s ease;">
                            <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-semibold sort-badge">#{{ $it->sort_order }}</span>
                                    <span class="status-badge badge rounded-pill px-3 py-1 {{ $it->is_active ? 'bg-success' : 'bg-secondary' }}" data-id="{{ $it->id }}">
                                        <i class="bi bi-circle-fill small me-1"></i>
                                        {{ $it->is_active ? __('admin.impact_stats.status_active') : __('admin.impact_stats.status_inactive') }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                @php
                                    $adminDirection = session('direction', 'rtl');
                                    $displayTitle = ($adminDirection === 'rtl') ? $it->title_ar : ($it->title_en ?? $it->title_ar);
                                @endphp
                                <h5 class="card-title mb-3 fw-bold text-dark">{{ $displayTitle }}</h5>
                                <p class="display-6 fw-bold text-danger mb-4">
                                    ${{ number_format($it->amount_usd, 1) }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="toggle-btn btn btn-sm flex-fill d-flex align-items-center justify-content-center gap-1 rounded-3 shadow-sm {{ $it->is_active ? 'btn-success' : 'btn-outline-secondary' }}"
                                            data-id="{{ $it->id }}" data-active="{{ $it->is_active ? '1' : '0' }}">
                                        <i class="bi bi-power"></i>
                                        <span class="d-none d-lg-inline">{{ $it->is_active ? __('admin.impact_stats.deactivate') : __('admin.impact_stats.activate') }}</span>
                                        <span class="d-lg-none">{{ $it->is_active ? __('admin.impact_stats.deactivate') : __('admin.impact_stats.activate') }}</span>
                                    </button>

                                    <!-- Edit -->
                                    <button type="button" class="btn btn-sm btn-outline-warning flex-fill edit-btn d-flex align-items-center justify-content-center gap-1 rounded-3 shadow-sm"
                                            data-id="{{ $it->id }}"
                                            data-title-ar="{{ $it->title_ar }}"
                                            data-title-en="{{ $it->title_en ?: '' }}"
                                            data-amount="{{ $it->amount_usd }}"
                                            data-active="{{ $it->is_active ? '1' : '0' }}">
                                        <i class="bi bi-pencil me-1"></i><span class="d-none d-lg-inline">{{ __('admin.impact_stats.edit') }}</span><span class="d-lg-none">{{ __('admin.impact_stats.edit') }}</span>
                                    </button>

                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-outline-danger delete-btn d-flex align-items-center justify-content-center rounded-3 shadow-sm"
                                            data-id="{{ $it->id }}"
                                            data-title-ar="{{ $it->title_ar }}"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            title="{{ __('admin.impact_stats.delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="drag-handle-icon position-absolute top-0 end-0 p-2 text-white opacity-75" style="cursor: move;">
                                <i class="bi bi-grip-vertical fs-5"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            </div>
        @else
            <div class="card-body text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-graph-up-arrow" style="font-size: 4rem; opacity: 0.5;"></i>
                    <p class="mb-2 mt-3">{{ __('admin.impact_stats.no_data') ?? 'لا توجد إحصائيات' }}</p>
                    <button class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle me-2"></i>{{ __('admin.impact_stats.add_new_statistic') }}
                    </button>
                </div>
            </div>
        @endif
        </x-admin.card>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-gradient-primary text-white border-0 py-3 px-4">
                        <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-plus-lg"></i>{{ __('admin.impact_stats.add_new_statistic') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createForm">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-type-h1 text-primary"></i>{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.arabic') }}
                                </label>
                                <input type="text" name="title_ar" class="form-control rounded-3 border-0 bg-light" style="height: 45px;" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-type-h1 text-primary"></i>{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.english') }}
                                </label>
                                <input type="text" name="title_en" class="form-control rounded-3 border-0 bg-light" style="height: 45px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-currency-dollar text-primary"></i>{{ __('admin.impact_stats.form_amount') }}
                                </label>
                                <input type="number" step="0.1" name="amount_usd" class="form-control rounded-3 border-0 bg-light" style="height: 45px;" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="newActive" checked>
                                <label class="form-check-label fw-semibold text-dark" for="newActive">
                                    <i class="bi bi-toggle-on text-primary me-1"></i>{{ __('admin.impact_stats.form_active') }}
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-2 pb-4 px-4">
                            <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">{{ __('admin.impact_stats.cancel') }}</button>
                            <button type="submit" class="btn btn-primary rounded-3 shadow-sm save-btn d-flex align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <i class="bi bi-check-lg"></i>{{ __('admin.impact_stats.add') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-gradient-primary text-white border-0 py-3 px-4">
                        <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-pencil-square"></i>{{ __('admin.impact_stats.edit_statistic') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editForm" class="edit-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="id" id="editId">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-type-h1 text-primary"></i>{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.arabic') }}
                                </label>
                                <input type="text" name="title_ar" id="editTitleAr" class="form-control rounded-3 border-0 bg-light" style="height: 45px;" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-type-h1 text-primary"></i>{{ __('admin.impact_stats.form_title') }} - {{ __('admin.labels.english') }}
                                </label>
                                <input type="text" name="title_en" id="editTitleEn" class="form-control rounded-3 border-0 bg-light" style="height: 45px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                    <i class="bi bi-currency-dollar text-primary"></i>{{ __('admin.impact_stats.form_amount') }}
                                </label>
                                <input type="number" step="0.1" name="amount_usd" id="editAmount" class="form-control rounded-3 border-0 bg-light" style="height: 45px;" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editActive">
                                <label class="form-check-label fw-semibold text-dark" for="editActive">
                                    <i class="bi bi-toggle-on text-primary me-1"></i>{{ __('admin.impact_stats.form_active') }}
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-2 pb-4 px-4">
                            <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">{{ __('admin.impact_stats.cancel') }}</button>
                            <button type="submit" class="btn btn-primary rounded-3 shadow-sm save-btn d-flex align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <i class="bi bi-check-lg"></i>{{ __('admin.impact_stats.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-gradient-primary text-white border-0 rounded-top-4 pb-2">
                        <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>{{ __('admin.impact_stats.confirm_delete') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-2 pb-3 text-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger display-5 mb-3"></i>
                        <p class="mb-2 text-muted small">{{ __('admin.impact_stats.confirm_delete_text') }}</p>
                        <p class="fw-semibold text-dark mb-0" id="deleteTitle"></p>
                        <small class="text-danger d-block mt-2">{{ __('admin.impact_stats.delete_irreversible') ?? 'هذا الإجراء لا يمكن التراجع عنه' }}</small>
                    </div>
                    <div class="modal-footer border-0 pt-2 justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-4 rounded-3" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>{{ __('admin.impact_stats.cancel') }}
                        </button>
                        <form id="deleteForm" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm px-4 rounded-3 shadow-sm delete-confirm">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <i class="bi bi-trash me-1"></i>{{ __('admin.impact_stats.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <style>
            .impact-stat-card {
                transition: all 0.3s ease;
            }
            
            .impact-stat-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
            }
            
            .drag-handle-icon {
                transition: opacity 0.2s ease;
            }
            
            .impact-stat-card:hover .drag-handle-icon {
                opacity: 1 !important;
            }
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/impact-stats.js'])
    @endpush
@endsection
