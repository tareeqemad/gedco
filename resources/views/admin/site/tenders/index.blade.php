@php
    $breadcrumbTitle     = __('admin.tenders.title');
    $breadcrumbParent    = __('admin.menu.content_management');
    $breadcrumbParentUrl = route('admin.news.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.tenders.title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-file-earmark-text"
                :title="__('admin.tenders.title')"
                :create-route="route('admin.tenders.create')"
                :create-label="__('admin.tenders.add_tender')"
                create-permission="tenders.create" />

            <!-- Filters Section -->
            <div class="card-body p-3">
                <form id="filter-form" class="row g-2 g-md-3">
                    <div class="col-12 col-lg-4 col-md-6">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-search me-1"></i>{{ __('admin.tenders.search_general') }}
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="q" class="form-control border-0 bg-light rounded-3" 
                                   placeholder="{{ __('admin.tenders.search_placeholder') }}"
                                   value="{{ $q ?? '' }}">
                        </div>
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-person me-1"></i>{{ __('admin.tenders.user_label') }}
                        </label>
                        <select name="user" class="form-select border-0 bg-light rounded-3">
                            <option value="">{{ __('admin.tenders.all_users') }}</option>
                            @foreach($distinctUsers as $u)
                                <option value="{{ $u }}" @selected(($user ?? '') === $u)>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-calendar-event me-1 d-none d-sm-inline"></i>
                            <span class="d-sm-none">من</span>
                            <span class="d-none d-sm-inline">{{ __('admin.tenders.date_from') }}</span>
                        </label>
                        <input type="date" name="date_from" class="form-control border-0 bg-light rounded-3" 
                               value="{{ $dateFrom ?? '' }}">
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-calendar-check me-1 d-none d-sm-inline"></i>
                            <span class="d-sm-none">إلى</span>
                            <span class="d-none d-sm-inline">{{ __('admin.tenders.date_to') }}</span>
                        </label>
                        <input type="date" name="date_to" class="form-control border-0 bg-light rounded-3" 
                               value="{{ $dateTo ?? '' }}">
                    </div>

                    <div class="col-6 col-lg-1 col-md-2">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-list-ol me-1"></i>{{ __('admin.tenders.per_page') }}
                        </label>
                        <input type="number" min="5" max="200" name="per_page" class="form-control border-0 bg-light rounded-3"
                               value="{{ $perPage ?? 20 }}">
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-sort-down me-1"></i>{{ __('admin.tenders.sort_by') }}
                        </label>
                        <select name="sort" class="form-select border-0 bg-light rounded-3">
                            @php $sorts = ['id'=>'ID','mnews_id'=>'MNEWS_ID','the_date_1'=>'THE_DATE_1','created_at'=>'Created','updated_at'=>'Updated']; @endphp
                            @foreach($sorts as $key=>$label)
                                <option value="{{ $key }}" @selected(($sort ?? 'id') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-arrow-down-up me-1"></i>{{ __('admin.tenders.sort_direction') }}
                        </label>
                        <select name="dir" class="form-select border-0 bg-light rounded-3">
                            <option value="desc" @selected(($dir ?? 'desc')==='desc')>{{ __('admin.tenders.sort_desc') }}</option>
                            <option value="asc"  @selected(($dir ?? '')==='asc')>{{ __('admin.tenders.sort_asc') }}</option>
                        </select>
                    </div>

                    <div class="col-12 col-lg-3 d-flex align-items-end gap-2 filter-buttons-group">
                        <button class="btn btn-primary flex-fill shadow-sm rounded-3" type="submit" style="min-width: 100px;">
                            <i class="bi bi-search me-2"></i>{{ __('admin.tenders.apply') }}
                        </button>
                        <a href="{{ route('admin.tenders.index') }}" class="btn btn-outline-secondary flex-fill rounded-3" style="min-width: 100px;">
                            <i class="bi bi-arrow-clockwise me-2"></i>{{ __('admin.tenders.reset') }}
                        </a>
                    </div>
                </form>
            </div>
            {{-- Table (AJAX target) --}}
            <div id="tenders-table-wrapper">
                <div class="table-responsive">
                    @include('admin.site.tenders.partials.table', ['tenders' => $tenders])
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;" id="tenders-pagination">
                @include('admin.site.tenders.partials.pagination', ['tenders' => $tenders])
            </div>
        </x-admin.card>
    </div>
@endsection

@push('scripts')
    <script>
        (function(){
            const form = document.getElementById('filter-form');
            const wrapper = document.getElementById('tenders-table-wrapper');
            const pagination = document.getElementById('tenders-pagination');

            // Delete
            window.delTender = function(id) {
                if(!confirm('{{ __('admin.tenders.delete_confirm') }}')) return;
                fetch("{{ url('admin/tenders') }}/" + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({_method:'DELETE'})
                }).then(r => r.json())
                    .then(json => {
                        if(json.success){
                            if (window.adminNotifications) {
                                window.adminNotifications.success('{{ __('admin.tenders.delete_success') ?? 'تم الحذف بنجاح' }}');
                            }
                            submitAjax(); // Refresh list
                        } else {
                            if (window.adminNotifications) {
                                window.adminNotifications.error(json.message || '{{ __('admin.tenders.delete_failed') }}');
                            } else {
                                alert(json.message || '{{ __('admin.tenders.delete_failed') }}');
                            }
                        }
                    }).catch(() => {
                        if (window.adminNotifications) {
                            window.adminNotifications.error('{{ __('admin.tenders.delete_connection_error') }}');
                        } else {
                            alert('{{ __('admin.tenders.delete_connection_error') }}');
                        }
                    });
            };

            // Debounce
            let t;
            const debounce = (fn, d=350) => (...args) => { clearTimeout(t); t = setTimeout(()=>fn(...args), d); };

            // أي تغيير على الفورم => استعلام AJAX
            form.addEventListener('input', debounce(()=> submitAjax()));
            form.addEventListener('change', ()=> submitAjax());
            form.addEventListener('submit', function(e){
                e.preventDefault();
                submitAjax();
            });

            // تعامل مع الضغط على ترقيم الصفحات (links) بالـ AJAX
            document.addEventListener('click', function(e){
                const a = e.target.closest('#tenders-pagination a.page-link');
                if(!a) return;
                e.preventDefault();
                submitAjax(a.getAttribute('href'));
            });

            function submitAjax(url = null){
                const params = new URLSearchParams(new FormData(form));
                const fetchUrl = url ? url : ("{{ route('admin.tenders.index') }}?" + params.toString());
                fetch(fetchUrl, {
                    headers: {'X-Requested-With':'XMLHttpRequest'}
                }).then(r => r.json())
                    .then(data => {
                        if(data.html) wrapper.querySelector('.table-responsive').innerHTML = data.html;
                        if(data.pagination) pagination.innerHTML = data.pagination;
                    }).catch(()=>{ /* ignore */ });
            }
        })();
    </script>
@endpush
