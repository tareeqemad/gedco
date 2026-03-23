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
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="stats-cards">
                @foreach($items as $it)
                    <div class="col" data-id="{{ $it->id }}" data-order="{{ $it->sort_order }}">
                        <div class="impact-stat-card position-relative" style="cursor: move;">
                            {{-- Top strip --}}
                            <div class="impact-strip {{ $it->is_active ? '' : 'inactive' }}"></div>

                            {{-- Meta row --}}
                            <div class="d-flex justify-content-between align-items-center px-3 pt-2">
                                <span class="stat-chip sort-badge" style="font-size: 0.65rem;">#{{ $it->sort_order }}</span>
                                <span class="status-badge stat-chip {{ $it->is_active ? 'impact-active' : 'impact-inactive' }}" data-id="{{ $it->id }}" style="font-size: 0.65rem;">
                                    {{ $it->is_active ? __('admin.impact_stats.status_active') : __('admin.impact_stats.status_inactive') }}
                                </span>
                            </div>

                            {{-- Content --}}
                            <div class="px-3 py-2">
                                @php
                                    $adminDirection = session('direction', 'rtl');
                                    $displayTitle = ($adminDirection === 'rtl') ? $it->title_ar : ($it->title_en ?? $it->title_ar);
                                @endphp
                                <h6 class="fw-bold mb-2" style="color: #24364A; font-size: 0.92rem;">{{ $displayTitle }}</h6>
                                <div class="impact-amount">${{ number_format($it->amount_usd, 0) }}</div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex align-items-center gap-1 px-3 pb-3">
                                <button class="toggle-btn btn btn-sm btn-outline-secondary flex-fill"
                                        data-id="{{ $it->id }}" data-active="{{ $it->is_active ? '1' : '0' }}">
                                    <i class="bi bi-power me-1"></i>{{ $it->is_active ? __('admin.impact_stats.deactivate') : __('admin.impact_stats.activate') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary edit-btn"
                                        data-id="{{ $it->id }}"
                                        data-title-ar="{{ $it->title_ar }}"
                                        data-title-en="{{ $it->title_en ?: '' }}"
                                        data-amount="{{ $it->amount_usd }}"
                                        data-active="{{ $it->is_active ? '1' : '0' }}"
                                        title="{{ __('admin.impact_stats.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                        data-id="{{ $it->id }}"
                                        data-title-ar="{{ $it->title_ar }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        title="{{ __('admin.impact_stats.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <div class="drag-handle-icon position-absolute top-0 end-0 p-2 opacity-25" style="cursor: move;">
                                <i class="bi bi-grip-vertical"></i>
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
                background: #FFFFFF;
                border: 1px solid #E6ECF2;
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.2s ease;
            }
            .impact-stat-card:hover {
                box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            }
            .impact-stat-card:hover .drag-handle-icon {
                opacity: 0.5 !important;
            }

            /* Top color strip */
            .impact-strip {
                height: 4px;
                background: #0F67C6;
            }
            .impact-strip.inactive {
                background: #CDD9E3;
            }

            /* Amount */
            .impact-amount {
                font-size: 1.5rem;
                font-weight: 800;
                color: #D94A38;
                letter-spacing: -0.5px;
                line-height: 1.2;
            }

            /* Status chips */
            .impact-active {
                color: #16A34A !important;
                background: rgba(22,163,74,0.08) !important;
                border-color: rgba(22,163,74,0.15) !important;
            }
            .impact-inactive {
                color: #7A8CA2 !important;
                background: #F0F4F8 !important;
                border-color: #E6ECF2 !important;
            }

            /* Action buttons inside cards */
            .impact-stat-card .btn-sm {
                width: auto !important;
                height: 30px !important;
                font-size: 0.72rem !important;
                padding: 0 0.6rem !important;
            }
            .impact-stat-card .btn-outline-secondary {
                border-color: #CDD9E3 !important;
                color: #5B7088 !important;
            }
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/impact-stats.js'])
    @endpush
@endsection
