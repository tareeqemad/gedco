@php
    $breadcrumbTitle     = __('admin.advertisements.title');
    $breadcrumbParent    = __('admin.menu.content_management');
    $breadcrumbParentUrl = route('admin.news.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.advertisements.title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-megaphone"
                :title="__('admin.advertisements.title')"
                :create-route="route('admin.advertisements.create')"
                :create-label="__('admin.advertisements.add_advertisement')"
                create-permission="advertisements.create">
                <x-slot:badge>
                    <span class="badge bg-white text-primary rounded-pill" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;" id="total-count">{{ $ads->total() }}</span>
                </x-slot:badge>
            </x-admin.card-header-index>

            <!-- Filters Section -->
            <div class="card-body p-3">
                <form method="get" id="filterForm">
                    {{-- Basic filters --}}
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div style="flex: 1 1 35%;">
                            <input type="text" name="q" value="{{ $q }}"
                                   class="form-control rounded-3"
                                   placeholder="{{ __('admin.advertisements.search_placeholder') }}">
                        </div>
                        <div style="flex: 0 1 18%;">
                            <select name="user" class="form-select rounded-3">
                                <option value="">{{ __('admin.advertisements.all_users') }}</option>
                                @foreach($distinctUsers as $u)
                                    <option value="{{ $u }}" @selected($user === $u)>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="flex: 0 1 14%; position: relative;">
                            <label class="date-field-label">{{ __('admin.activity_logs.date_from') }}</label>
                            <input type="date" name="date_from" value="{{ $dateFrom }}"
                                   class="form-control rounded-3">
                        </div>
                        <div style="flex: 0 1 14%; position: relative;">
                            <label class="date-field-label">{{ __('admin.activity_logs.date_to') }}</label>
                            <input type="date" name="date_to" value="{{ $dateTo }}"
                                   class="form-control rounded-3">
                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="submit" class="btn btn-primary rounded-3">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.query') }}
                            </button>
                            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.actions.clear') }}
                            </a>
                        </div>
                    </div>

                    {{-- Advanced filters (collapsed) --}}
                    <div class="mt-2">
                        <button type="button" class="btn btn-link btn-sm text-muted p-0" style="font-size: 0.75rem; text-decoration: none;"
                                data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                            <i class="bi bi-sliders me-1"></i> {{ __('admin.advertisements.advanced_options') }}
                        </button>
                        <div class="collapse {{ ($sort !== 'DATE_NEWS' || $dir !== 'desc' || $perPage != 20) ? 'show' : '' }}" id="advancedFilters">
                            <div class="d-flex gap-2 align-items-center flex-wrap mt-2 p-2 rounded-3" style="background: #F8FBFD; border: 1px solid #E6ECF2;">
                                <div style="flex: 0 1 auto;">
                                    <label class="form-label mb-0 me-1" style="font-size: 0.72rem; color: #7A8CA2;">{{ __('admin.advertisements.sort_label') }}</label>
                                    <select name="sort" class="form-select form-select-sm rounded-3 d-inline-block" style="width: auto; font-size: 0.78rem;">
                                        <option value="DATE_NEWS" @selected($sort==='DATE_NEWS')>{{ __('admin.advertisements.sort_date_news') }}</option>
                                        <option value="INSERT_DATE" @selected($sort==='INSERT_DATE')>{{ __('admin.advertisements.sort_insert_date') }}</option>
                                        <option value="UPDATE_DATE" @selected($sort==='UPDATE_DATE')>{{ __('admin.advertisements.sort_update_date') }}</option>
                                        <option value="ID_ADVER" @selected($sort==='ID_ADVER')>{{ __('admin.advertisements.sort_id') }}</option>
                                    </select>
                                </div>
                                <div style="flex: 0 1 auto;">
                                    <select name="dir" class="form-select form-select-sm rounded-3" style="width: auto; font-size: 0.78rem;">
                                        <option value="desc" @selected($dir==='desc')>{{ __('admin.advertisements.sort_desc') }}</option>
                                        <option value="asc" @selected($dir==='asc')>{{ __('admin.advertisements.sort_asc') }}</option>
                                    </select>
                                </div>
                                <div style="flex: 0 1 auto;">
                                    <label class="form-label mb-0 me-1" style="font-size: 0.72rem; color: #7A8CA2;">{{ __('admin.advertisements.per_page_label') }}</label>
                                    <select name="per_page" class="form-select form-select-sm rounded-3 d-inline-block" style="width: auto; font-size: 0.78rem;">
                                        @foreach([10,20,50,100] as $pp)
                                            <option value="{{ $pp }}" @selected($perPage==$pp)>{{ $pp }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="vr mx-1" style="height: 20px;"></div>
                                <div class="d-flex gap-2 flex-wrap" style="font-size: 0.75rem;">
                                    <span class="text-muted fw-semibold">{{ __('admin.advertisements.columns_label') }}</span>
                                    <label class="form-check form-check-inline mb-0">
                                        <input class="form-check-input col-toggle" type="checkbox" data-cols="3" checked>
                                        <span class="form-check-label" style="font-size: 0.72rem;">{{ __('admin.advertisements.col_toggle_date_news') }}</span>
                                    </label>
                                    <label class="form-check form-check-inline mb-0">
                                        <input class="form-check-input col-toggle" type="checkbox" data-cols="4" checked>
                                        <span class="form-check-label" style="font-size: 0.72rem;">{{ __('admin.advertisements.col_toggle_added_by') }}</span>
                                    </label>
                                    <label class="form-check form-check-inline mb-0">
                                        <input class="form-check-input col-toggle" type="checkbox" data-cols="5" checked>
                                        <span class="form-check-label" style="font-size: 0.72rem;">{{ __('admin.advertisements.col_toggle_last_update') }}</span>
                                    </label>
                                    <label class="form-check form-check-inline mb-0">
                                        <input class="form-check-input col-toggle" type="checkbox" data-cols="6" checked>
                                        <span class="form-check-label" style="font-size: 0.72rem;">{{ __('admin.advertisements.col_toggle_file') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Table -->
            <div class="table-responsive">
                <div id="table-container" aria-live="polite">
                    @include('admin.site.advertisements.partials.table')
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                <div id="pagination-container">
                    @include('admin.site.advertisements.partials.pagination')
                </div>
            </div>
        </x-admin.card>
    </div>
@endsection

@push('scripts')
    <script>
        let activeController = null;
        let debounceTimer;

        const tableContainer = document.getElementById('table-container');
        const paginationContainer = document.getElementById('pagination-container');
        const totalCount = document.getElementById('total-count');
        const filterForm = document.getElementById('filterForm');

        function showLoading() {
            tableContainer.innerHTML = `
                <div class="p-3">
                    <div class="skel" style="height:42px; margin-bottom:.5rem; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite;"></div>
                    <div class="skel" style="height:42px; margin-bottom:.5rem; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite;"></div>
                    <div class="skel" style="height:42px; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite;"></div>
                </div>`;
        }

        function highlightQuery(rootSelector = '#table-container') {
            const url = new URL(location.href);
            const q = url.searchParams.get('q');
            if (!q) return;
            const esc = s => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const pattern = new RegExp('(' + esc(q) + ')', 'gi');

            document.querySelectorAll(rootSelector + ' td, ' + rootSelector + ' .line-clamp-2').forEach(el => {
                if (el.querySelector('button, a.btn, i, svg')) return;
                const txt = el.innerHTML;
                const newHtml = txt.replace(pattern, '<mark>$1</mark>');
                if (newHtml !== txt) el.innerHTML = newHtml;
            });
        }

        function updateTable(data, newUrl = null) {
            tableContainer.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            totalCount.textContent = data.total;
            if (newUrl) history.pushState(null, '', newUrl);
            highlightQuery('#table-container');
        }

        function fetchData(relativeUrl, pushUrl = false){
            if (activeController) activeController.abort();
            activeController = new AbortController();

            fetch(relativeUrl, {
                method:'GET',
                signal: activeController.signal,
                headers:{ 'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
            })
                .then(r=>{ if(!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
                .then(d=>updateTable(d, pushUrl?relativeUrl:null))
                .catch(err=>{ if(err.name==='AbortError') return; console.error('AJAX Error:', err); });
        }

        document.addEventListener('DOMContentLoaded', ()=>{
            // Pagination
            document.body.addEventListener('click', e=>{
                const link = e.target.closest('.pagination-link'); if(!link) return;
                e.preventDefault();
                const raw = link.getAttribute('data-url') || link.getAttribute('href') || '';
                if (!raw) return;
                const u = new URL(raw, location.origin);
                const rel = u.pathname + u.search;
                showLoading();
                fetchData(rel, true);
            });

            // Filters
            if (filterForm) {
                filterForm.querySelectorAll('input, select').forEach(i=>{
                    i.addEventListener('change', submitFilters);
                    if(i.name==='q'){
                        i.addEventListener('input', ()=>{
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(submitFilters, 500);
                        });
                    }
                });
            }

            // Toggle columns
            document.addEventListener('change', (e)=>{
                if(!e.target.classList.contains('col-toggle')) return;
                const idx = parseInt(e.target.dataset.cols,10);
                document.querySelectorAll('.responsive-table tr').forEach(tr=>{
                    const cell = tr.querySelector(`:scope > *:nth-child(${idx})`);
                    if(cell){ cell.style.display = e.target.checked ? '' : 'none'; }
                });
            });
        });

        function submitFilters(){
            const params = new URLSearchParams(new FormData(filterForm));
            const url = location.pathname + '?' + params.toString();
            showLoading();
            fetchData(url, true);
        }

        // Delete with SweetAlert
        window.confirmDelete = function(form, adId = null) {
            Swal.fire({
                title:'{{ __('admin.advertisements.delete_confirm_title') }}',
                text:'{{ __('admin.advertisements.delete_confirm_text') }}',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'{{ __('admin.advertisements.delete_confirm_yes') }}',
                cancelButtonText:'{{ __('admin.actions.cancel') }}',
                reverseButtons:true,
                customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary ms-2' },
                buttonsStyling:false
            }).then((result)=>{
                if(!result.isConfirmed) return;

                const btn = form.querySelector('button[type="submit"]');
                if (btn) { btn.disabled = true; btn.setAttribute('data-original-text', btn.innerHTML); btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('admin.advertisements.delete_processing') }}'; }

                let rowEl = null;
                if (adId !== null) {
                    rowEl = document.getElementById('ad-row-' + adId);
                    if (rowEl) rowEl.classList.add('soft-dim');
                }

                const formData = new FormData(form);
                fetch(new URL(form.action, location.origin), {
                    method:'DELETE',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'application/json'
                    },
                    body: formData
                })
                    .then(r=>r.json())
                    .then(data=>{
                        if (data?.success) {
                            if (window.adminNotifications) {
                                window.adminNotifications.success(data.message || '{{ __('admin.advertisements.delete_success_message') }}');
                            } else {
                                Swal.fire('{{ __('admin.advertisements.delete_success') }}', data.message || '{{ __('admin.advertisements.delete_success_message') }}', 'success');
                            }
                            if (rowEl) {
                                rowEl.classList.add('fade-out');
                                setTimeout(()=>{
                                    rowEl.remove();
                                    const current = location.pathname + location.search;
                                    fetchData(current, false);
                                }, 250);
                            } else {
                                const current = location.pathname + location.search;
                                fetchData(current, false);
                            }
                        } else {
                            if (window.adminNotifications) {
                                window.adminNotifications.error(data?.message || '{{ __('admin.advertisements.delete_warning_message') }}');
                            } else {
                                Swal.fire('{{ __('admin.advertisements.delete_warning') }}', data?.message || '{{ __('admin.advertisements.delete_warning_message') }}', 'warning');
                            }
                            if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || '{{ __('admin.advertisements.delete') }}'; btn.removeAttribute('data-original-text'); }
                            if (rowEl) rowEl.classList.remove('soft-dim');
                        }
                    })
                    .catch(()=>{
                        if (window.adminNotifications) {
                            window.adminNotifications.error('{{ __('admin.advertisements.delete_error_message') }}');
                        } else {
                            Swal.fire('{{ __('admin.advertisements.delete_error') }}', '{{ __('admin.advertisements.delete_error_message') }}', 'error');
                        }
                        if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || '{{ __('admin.advertisements.delete') }}'; btn.removeAttribute('data-original-text'); }
                        if (rowEl) rowEl.classList.remove('soft-dim');
                        const current = location.pathname + location.search;
                        fetchData(current, false);
                    });
            });

            return false;
        };
    </script>
    <style>
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .soft-dim { opacity: 0.6; }
        .fade-out { animation: fadeOut 0.25s ease-out forwards; }
        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(-10px); }
        }
    </style>
@endpush
