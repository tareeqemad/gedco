@extends('layouts.admin')
@section('title', __('admin.menu.news'))

@section('content')
    <div class="container-fluid p-0">
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0 fw-semibold">{{ __('admin.menu.news') }}</h6>
                    @if(isset($currentLanguage))
                        <span class="badge bg-info rounded-pill small">
                            {{ $currentLanguage === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
                        </span>
                    @endif
                </div>
                @can('news.create')
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> {{ __('admin.news.add_new_news') }}
                    </a>
                @endcan
            </div>

            <div class="card-body">
                <form id="filterForm" class="row g-2 mb-3" action="{{ route('admin.news.index') }}" method="get">
                    <div class="col-lg-3">
                        <input type="text" name="q" class="form-control" placeholder="{{ __('admin.messages.search_by_title_content') }}" value="{{ $q ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">{{ __('admin.news.status_all') }}</option>
                            <option value="published" @selected(($status ?? '')==='published')>{{ __('admin.news.status_published') }}</option>
                            <option value="draft"     @selected(($status ?? '')==='draft')>{{ __('admin.news.status_draft') }}</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <select name="sort" class="form-select">
                            <option value="published_at" @selected(($sort ?? '')==='published_at')>{{ __('admin.news.sort_latest') }}</option>
                            <option value="title"        @selected(($sort ?? '')==='title')>{{ __('admin.news.sort_title') }}</option>
                            <option value="views"        @selected(($sort ?? '')==='views')>{{ __('admin.news.sort_most_viewed') }}</option>
                            <option value="featured"     @selected(($sort ?? '')==='featured')>{{ __('admin.news.sort_featured') }}</option>
                        </select>
                        <select name="dir" class="form-select">
                            <option value="desc" @selected(($dir ?? '')==='desc')>{{ __('admin.news.sort_desc') }}</option>
                            <option value="asc"  @selected(($dir ?? '')==='asc')>{{ __('admin.news.sort_asc') }}</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2 align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="featured" value="1" id="featuredChk" {{ request('featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="featuredChk">{{ __('admin.news.featured_articles') }}</label>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" type="submit">{{ __('admin.news.apply') }}</button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('admin.news.clear') }}</a>
                    </div>
                </form>

                <div id="cardsWrap">
                    @include('admin.site.news.partials.cards', ['items'=>$items])
                    @include('admin.site.news.partials.pagination', ['items'=>$items])
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        (function(){
            const form = document.getElementById('filterForm');
            const wrap = document.getElementById('cardsWrap');

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
                    const body = await res.text(); // مفيد لو هتسجلّه للديبج
                    if (ct.includes('text/html') || !ct.includes('application/json')) {
                        throw new Error('HTMLResponse');
                    }
                    throw new Error('HTTP ' + res.status);
                }
                return res.json();
            };

            function refresh() {
                const url = form.action + '?' + new URLSearchParams(new FormData(form)).toString();
                ajax(url)
                    .then(({html, pagination}) => {
                        wrap.innerHTML = html + pagination;
                        window.scrollTo({top: 0, behavior: 'smooth'});
                    })
                    .catch(() => {
                        form.submit(); // فول-باك لو JSON فشل لأي سبب (CORS/سيشن/غيره)
                    });
            }

            form?.addEventListener('submit', (e) => { e.preventDefault(); refresh(); });
            form?.addEventListener('change', () => { refresh(); });

            document.addEventListener('click', function(e){
                const a = e.target.closest('.pagination a');
                if (!a) return;
                e.preventDefault();
                ajax(a.href)
                    .then(({html, pagination}) => {
                        wrap.innerHTML = html + pagination;
                        window.scrollTo({top: 0, behavior: 'smooth'});
                    })
                    .catch(() => { window.location.href = a.href; });
            });

            // حذف خبر (مع قفل زر الحذف أثناء التنفيذ)
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
