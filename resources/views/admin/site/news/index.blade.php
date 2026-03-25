@php
    $breadcrumbTitle     = __('admin.menu.news');
    $breadcrumbParent    = __('admin.menu.content_management');
    $breadcrumbParentUrl = route('admin.news.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.menu.news'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-newspaper"
                :title="__('admin.menu.news')"
                :create-route="route('admin.news.create')"
                :create-label="__('admin.news.add_new_news')"
                create-permission="news.create" />

            <!-- Filters Section -->
            <div class="card-body p-3">
                <form id="filterForm" class="news-filters" action="{{ route('admin.news.index') }}" method="get">
                    <div class="row g-2 g-md-3 mb-3">
                        <!-- Search -->
                        <div class="col-12 col-lg-4 col-md-6">
                            <label class="form-label small text-muted mb-1">
                                <i class="bi bi-search me-1"></i> {{ __('admin.messages.search_by_title_content') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="q" class="form-control rounded-3"
                                       placeholder="{{ __('admin.messages.search_by_title_content') }}"
                                       value="{{ $q ?? '' }}">
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="col-6 col-lg-2 col-md-3">
                            <label class="form-label small text-muted mb-1">
                                <i class="bi bi-calendar-event me-1 d-none d-sm-inline"></i> {{ __('admin.activity_logs.date_from') }}
                            </label>
                            <input type="date" name="date_from" class="form-control rounded-3"
                                   value="{{ $dateFrom ?? '' }}">
                        </div>
                        <div class="col-6 col-lg-2 col-md-3">
                            <label class="form-label small text-muted mb-1">
                                <i class="bi bi-calendar-check me-1 d-none d-sm-inline"></i> {{ __('admin.activity_logs.date_to') }}
                            </label>
                            <input type="date" name="date_to" class="form-control rounded-3"
                                   value="{{ $dateTo ?? '' }}">
                        </div>

                        <!-- Status -->
                        <div class="col-6 col-lg-2 col-md-4">
                            <label class="form-label small text-muted mb-1">
                                <i class="bi bi-funnel me-1 d-none d-sm-inline"></i> {{ __('admin.news.status') }}
                            </label>
                            <select name="status" class="form-select rounded-3">
                                <option value="">{{ __('admin.news.status_all') }}</option>
                                <option value="published" @selected(($status ?? '')==='published')>{{ __('admin.news.status_published') }}</option>
                                <option value="draft" @selected(($status ?? '')==='draft')>{{ __('admin.news.status_draft') }}</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="col-6 col-lg-2 col-md-4">
                            <label class="form-label small text-muted mb-1">
                                <i class="bi bi-sort-down me-1 d-none d-sm-inline"></i> {{ __('admin.labels.order') }}
                            </label>
                            <select name="sort" class="form-select rounded-3">
                                <option value="published_at" @selected(($sort ?? '')==='published_at')>{{ __('admin.news.sort_latest') }}</option>
                                <option value="title" @selected(($sort ?? '')==='title')>{{ __('admin.news.sort_title') }}</option>
                                <option value="views" @selected(($sort ?? '')==='views')>{{ __('admin.news.sort_most_viewed') }}</option>
                                <option value="featured" @selected(($sort ?? '')==='featured')>{{ __('admin.news.sort_featured') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-2 align-items-sm-center justify-content-center">
                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center w-100 w-sm-auto">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="featured" value="1" 
                                       id="featuredChk" {{ request('featured') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="featuredChk">
                                    {{ __('admin.news.featured_articles') }}
                                </label>
                            </div>
                            <select name="dir" class="form-select rounded-3 w-100 w-sm-auto" style="max-width: 150px;">
                                <option value="desc" @selected(($dir ?? 'desc')==='desc')>{{ __('admin.news.sort_desc') }}</option>
                                <option value="asc" @selected(($dir ?? 'asc')==='asc')>{{ __('admin.news.sort_asc') }}</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2 justify-content-center w-100 w-sm-auto">
                            <button class="btn btn-outline-secondary rounded-3 shadow-sm" type="submit" style="min-width: 120px;">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.query') }}
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-danger rounded-3 shadow-sm" style="min-width: 120px;">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.actions.clear') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- News Cards -->
            <div id="cardsWrap" class="news-cards-container">
                @include('admin.site.news.partials.cards', ['items'=>$items])
                @include('admin.site.news.partials.pagination', ['items'=>$items])
            </div>
        </x-admin.card>
    </div>
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/news.css') }}">
@endpush

@endsection


@push('scripts')
    <script>
        (function(){
            'use strict';
            
            const form = document.getElementById('filterForm');
            const wrap = document.getElementById('cardsWrap');
            const searchInput = form?.querySelector('input[name="q"]');
            let searchTimeout = null;
            let isSearching = false;

            // إنشاء loading indicator
            function createLoadingIndicator() {
                const loader = document.createElement('div');
                loader.id = 'searchLoader';
                loader.className = 'text-center py-4';
                loader.innerHTML = `
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">جاري البحث...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">جاري البحث...</p>
                `;
                return loader;
            }

            // إظهار/إخفاء loading
            function showLoading() {
                if (isSearching) return;
                isSearching = true;
                const loader = createLoadingIndicator();
                wrap.innerHTML = '';
                wrap.appendChild(loader);
            }

            function hideLoading() {
                isSearching = false;
                const loader = document.getElementById('searchLoader');
                if (loader) {
                    loader.remove();
                }
            }

            // AJAX helper
            const ajax = async (u) => {
                const res = await fetch(u, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });
                if (!res.ok) {
                    const ct = res.headers.get('content-type') || '';
                    if (ct.includes('text/html') || !ct.includes('application/json')) {
                        throw new Error('HTMLResponse');
                    }
                    throw new Error('HTTP ' + res.status);
                }
                return res.json();
            };

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Refresh function
            function refresh(showLoader = true) {
                if (showLoader) {
                    showLoading();
                }
                
                const url = form.action + '?' + new URLSearchParams(new FormData(form)).toString();
                ajax(url)
                    .then(({html, pagination}) => {
                        hideLoading();
                        wrap.innerHTML = html + pagination;
                        window.scrollTo({top: 0, behavior: 'smooth'});
                        
                        // إعادة تهيئة date pickers بعد التحديث
                        if (typeof initAdminDatePickers === 'function') {
                            setTimeout(() => {
                                initAdminDatePickers();
                            }, 100);
                        }
                    })
                    .catch(() => {
                        hideLoading();
                        form.submit(); // فول-باك لو JSON فشل
                    });
            }

            // Debounced search function
            const debouncedSearch = debounce(() => {
                refresh(true);
            }, 500); // 500ms delay

            // Event listeners
            if (form) {
                // Submit form - البحث فقط عند الضغط على تطبيق
                form.addEventListener('submit', (e) => { 
                    e.preventDefault(); 
                    refresh(true); 
                });

                // Change events (selects, checkboxes, dates) - لا شيء تلقائي
                // البحث فقط عند الضغط على تطبيق
                
                // Enter key في حقل البحث - يبحث فوراً
                if (searchInput) {
                    searchInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            refresh(true);
                        }
                    });
                }
            }

            // Pagination clicks
            document.addEventListener('click', function(e){
                const a = e.target.closest('.pagination a');
                if (!a) return;
                e.preventDefault();
                showLoading();
                ajax(a.href)
                    .then(({html, pagination}) => {
                        hideLoading();
                        wrap.innerHTML = html + pagination;
                        window.scrollTo({top: 0, behavior: 'smooth'});
                        
                        // إعادة تهيئة date pickers
                        if (typeof initAdminDatePickers === 'function') {
                            setTimeout(() => {
                                initAdminDatePickers();
                            }, 100);
                        }
                    })
                    .catch(() => { 
                        hideLoading();
                        window.location.href = a.href; 
                    });
            });

            // حذف خبر
            window.confirmDelete = function(btn, id) {
                const url = btn.getAttribute('data-delete-url');

                Swal.fire({
                    title: '{{ __('admin.news.delete_confirm_title') }}',
                    text: '{{ __('admin.news.delete_confirm_text') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('admin.news.delete_confirm_yes') }}',
                    cancelButtonText: '{{ __('admin.actions.cancel') }}',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger px-4',
                        cancelButton: 'btn btn-secondary px-4 me-2'
                    }
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    const originalHtml = btn.innerHTML;
                    btn.disabled = true;
                    btn.classList.add('disabled');
                    btn.setAttribute('aria-busy','true');

                    fetch(url, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelector(`[data-news-id="${id}"]`)?.remove();
                                Swal.fire('{{ __('admin.news.delete_success') }}', data.message, 'success');
                                // إعادة تحميل النتائج بعد الحذف
                                refresh(false);
                            } else {
                                Swal.fire('{{ __('admin.news.delete_error') }}', data.message || '{{ __('admin.news.delete_error') }}', 'error');
                                btn.disabled = false;
                                btn.classList.remove('disabled');
                                btn.removeAttribute('aria-busy');
                                btn.innerHTML = originalHtml;
                            }
                        })
                        .catch(() => {
                            Swal.fire('{{ __('admin.news.delete_error') }}', '{{ __('admin.news.delete_connection_error') }}', 'error');
                            btn.disabled = false;
                            btn.classList.remove('disabled');
                            btn.removeAttribute('aria-busy');
                            btn.innerHTML = originalHtml;
                        });
                });
            };
        })();
    </script>
@endpush
