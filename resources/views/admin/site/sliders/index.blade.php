@php
    $breadcrumbTitle     = __('admin.menu.slider');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.slider.title'))

@section('content')
    <div class="container-fluid">
        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <!-- Header Section -->
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-3 flex-wrap" style="flex: 1;">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2 flex-wrap" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-images"></i>
                                <span>{{ __('admin.slider.slider_items') }}</span>
                            </h5>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-white-50" style="font-size: 0.9rem;">
                            <i class="bi bi-images"></i>
                            <span>{{ __('admin.slider.total_slides') }}:</span>
                            <strong class="text-white" id="sliderTotalCount">{{ $sliders->total() }}</strong>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-1">
                            {{ $currentLanguage === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
                        </span>
                        <a href="{{ route('admin.sliders.create') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            <span class="d-none d-md-inline">{{ __('admin.slider.add_new_slide') }}</span>
                            <span class="d-md-none">{{ __('admin.actions.add') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card-body p-3">
                <div class="slider-filters">
            <form id="sliderFilterForm" class="slider-filter-form">
                <div class="slider-filter-row">
                    <div class="slider-filter-group">
                        <input type="text" 
                               name="search" 
                               id="sliderSearchInput"
                               class="form-control rounded-3"
                               placeholder="{{ __('admin.slider.search_placeholder') }}"
                               value="{{ $search ?? '' }}">
                    </div>
                    <div class="slider-filter-group">
                        <select name="status" id="sliderStatusSelect" class="form-select rounded-3">
                            <option value="">{{ __('admin.slider.filter_all') }}</option>
                            <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>
                                {{ __('admin.slider.filter_active') }}
                            </option>
                            <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>
                                {{ __('admin.slider.filter_inactive') }}
                            </option>
                        </select>
                    </div>
                    <div class="slider-filter-group">
                        <button type="submit" class="btn btn-primary rounded-3" id="sliderSearchBtn">
                            <i class="bi bi-search me-1"></i>
                            {{ __('admin.slider.search_button') }}
                        </button>
                    </div>
                </div>
            </form>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="slider-loading" id="sliderLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
            </div>
            <!-- Table Container -->
            <div class="p-0">
                <div class="slider-table-wrapper">
                    <div class="table-responsive">
                        <table class="table slider-table" id="sliderTable">
                            <thead>
                                <tr>
                                    <th style="width: 60px;" class="text-center">#</th>
                                    <th style="width: 150px;" class="text-center">{{ __('admin.slider.table_bg_image') }}</th>
                                    <th>{{ __('admin.slider.table_title') }}</th>
                                    <th style="width: 100px;" class="text-center">{{ __('admin.slider.table_order') }}</th>
                                    <th style="width: 100px;" class="text-center">{{ __('admin.slider.table_status') }}</th>
                                    <th style="width: 120px;" class="text-center">{{ __('admin.slider.table_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="sliderGrid">
                                @include('admin.site.sliders.partials.cards', ['sliders' => $sliders])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($sliders->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div id="sliderPagination">
                        @include('admin.site.sliders.partials.pagination', ['sliders' => $sliders])
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modals -->
    @foreach($sliders as $slider)
        <div class="modal fade" id="deleteModal-{{ $slider->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content shadow-lg border-0 rounded-4">
                        <div class="modal-header bg-gradient-primary text-white border-0 py-3 px-4 rounded-top-4">
                            <h5 class="modal-title text-white fw-bold mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                {{ __('admin.slider.delete_modal_title') }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-4 text-center">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                            <p class="mb-2 text-muted mt-3">{{ __('admin.slider.delete_confirm') }}</p>
                            <p class="fw-semibold text-dark mb-0">"{{ Str::limit($slider->title, 40) }}"</p>
                            <small class="text-danger d-block mt-2">{{ __('admin.slider.delete_irreversible') }}</small>
                        </div>
                        <div class="modal-footer border-0 pt-2 pb-4 px-4 justify-content-center gap-2">
                            <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">
                                {{ __('admin.common.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-danger rounded-3 px-4 shadow-sm delete-submit-btn">
                                <i class="bi bi-trash me-1"></i>
                                {{ __('admin.slider.delete_final') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/sliders.css') }}">
    @endpush

    @push('scripts')
    <script>
        (function() {
            'use strict';

            const filterForm = document.getElementById('sliderFilterForm');
            const searchInput = document.getElementById('sliderSearchInput');
            const statusSelect = document.getElementById('sliderStatusSelect');
            const searchBtn = document.getElementById('sliderSearchBtn');
            const sliderGrid = document.getElementById('sliderGrid');
            const sliderPagination = document.getElementById('sliderPagination');
            const sliderLoading = document.getElementById('sliderLoading');
            const sliderTotalCount = document.getElementById('sliderTotalCount');

            let isLoading = false;

            function showLoading() {
                isLoading = true;
                sliderLoading.classList.add('active');
                sliderGrid.style.opacity = '0.5';
                sliderPagination.style.opacity = '0.5';
            }

            function hideLoading() {
                isLoading = false;
                sliderLoading.classList.remove('active');
                sliderGrid.style.opacity = '1';
                sliderPagination.style.opacity = '1';
            }

            function buildUrl(params) {
                const url = new URL(window.location.href);
                Object.keys(params).forEach(key => {
                    if (params[key]) {
                        url.searchParams.set(key, params[key]);
                    } else {
                        url.searchParams.delete(key);
                    }
                });
                url.searchParams.delete('page'); // Reset to page 1 on new search
                return url.toString();
            }

            function loadSliders(url) {
                if (isLoading) return;
                
                showLoading();
                
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    sliderGrid.innerHTML = data.html;
                    sliderPagination.innerHTML = data.pagination;
                    sliderTotalCount.textContent = data.total;
                    window.history.pushState({}, '', url);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Re-initialize delete forms
                    initDeleteForms();
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (window.adminNotifications) {
                        window.adminNotifications.error('حدث خطأ أثناء تحميل البيانات');
                    }
                })
                .finally(() => {
                    hideLoading();
                });
            }

            function initDeleteForms() {
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = form.querySelector('.delete-submit-btn');
                        if (submitBtn && !submitBtn.disabled) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>جاري الحذف...';
                            submitBtn.classList.add('disabled');
                        }
                    });
                });
            }

            // Form submit
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(filterForm);
                const params = Object.fromEntries(formData);
                const url = buildUrl(params);
                loadSliders(url);
            });

            // Pagination links
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    const url = paginationLink.href;
                    const urlObj = new URL(url);
                    
                    // Update form values from URL
                    if (urlObj.searchParams.get('search')) {
                        searchInput.value = urlObj.searchParams.get('search');
                    }
                    if (urlObj.searchParams.get('status')) {
                        statusSelect.value = urlObj.searchParams.get('status');
                    }
                    
                    loadSliders(url);
                }
            });

            // Initialize delete forms on page load
            initDeleteForms();
        })();
    </script>
    @endpush
@endsection
