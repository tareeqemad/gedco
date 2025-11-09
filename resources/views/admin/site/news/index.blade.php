@extends('layouts.admin')
@section('title','الأخبار')

@section('content')
    <div class="container-fluid p-0">
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2 px-3">
                <h6 class="mb-0 fw-semibold">الأخبار</h6>
                @can('news.create')
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-circle-line"></i> خبر جديد
                    </a>
                @endcan
            </div>

            <div class="card-body">
                <form id="filterForm" class="row g-2 mb-3" action="{{ route('admin.news.index') }}" method="get">
                    <div class="col-lg-3">
                        <input type="text" name="q" class="form-control" placeholder="ابحث بالعنوان/المحتوى" value="{{ $q ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">الحالة</option>
                            <option value="published" @selected(($status ?? '')==='published')>منشور</option>
                            <option value="draft"     @selected(($status ?? '')==='draft')>مسودّة</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <select name="sort" class="form-select">
                            <option value="published_at" @selected(($sort ?? '')==='published_at')>الأحدث</option>
                            <option value="title"        @selected(($sort ?? '')==='title')>العنوان</option>
                            <option value="views"        @selected(($sort ?? '')==='views')>الأكثر مشاهدة</option>
                            <option value="featured"     @selected(($sort ?? '')==='featured')>المميّزة</option>
                        </select>
                        <select name="dir" class="form-select">
                            <option value="desc" @selected(($dir ?? '')==='desc')>تنازلي</option>
                            <option value="asc"  @selected(($dir ?? '')==='asc')>تصاعدي</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2 align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="featured" value="1" id="featuredChk" {{ request('featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="featuredChk">مقالات مميّزة</label>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" type="submit">تطبيق</button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">تفريغ</a>
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

@push('styles')
    <style>
        .card-news{border:1px solid #eef1f5;border-radius:12px;overflow:hidden;transition:transform .15s ease;}
        .card-news:hover{transform:translateY(-2px);}
        .card-news .thumb{aspect-ratio:16/9;background:#f5f7fb;display:block;overflow:hidden;}
        .card-news .thumb img{width:100%;height:100%;object-fit:cover;display:block;}
        .badge-dot{position:relative;padding-right:.85rem;}
        .badge-dot::before{content:"";width:6px;height:6px;border-radius:50%;background:#22c55e;position:absolute;right:.4rem;top:50%;transform:translateY(-50%);}
        .badge-dot.badge-draft::before{background:#eab308;}
        .btn[disabled], .btn.disabled{pointer-events:none; opacity:.65;}
    </style>
@endpush

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
                    title: 'تأكيد الحذف',
                    text: 'هل أنت متأكد من حذف هذا الخبر؟ لا يمكن التراجع!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء',
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
                                Swal.fire('تم!', data.message, 'success');
                            } else {
                                Swal.fire('خطأ', data.message || 'فشل الحذف', 'error');
                                btn.disabled = false;
                                btn.classList.remove('disabled');
                                btn.removeAttribute('aria-busy');
                                btn.innerHTML = originalHtml;
                            }
                        })
                        .catch(() => {
                            Swal.fire('خطأ', 'حدث خطأ في الاتصال', 'error');
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
