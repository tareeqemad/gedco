{{-- resources/views/admin/site/tenders/index.blade.php --}}
@extends('layouts.admin')
@section('title','العطاءات')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">العطاءات</h4>
            @can('tenders.create')
                <a href="{{ route('admin.tenders.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> إضافة عطاء
                </a>
            @endcan
        </div>

        {{-- Filters --}}
        <div class="card mb-3">
            <div class="card-body">
                <form id="filter-form" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">بحث عام</label>
                        <input type="text" name="q" class="form-control" placeholder="كلمة مفتاحية"
                               value="{{ $q ?? '' }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">المستخدم (the_user_1)</label>
                        <select name="user" class="form-select">
                            <option value="">-- الجميع --</option>
                            @foreach($distinctUsers as $u)
                                <option value="{{ $u }}" @selected(($user ?? '') === $u)>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">من تاريخ (the_date_1)</label>
                        <input type="text" name="date_from" class="form-control" placeholder="YYYY-MM-DD أو جزء نصي"
                               value="{{ $dateFrom ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">إلى تاريخ (the_date_1)</label>
                        <input type="text" name="date_to" class="form-control" placeholder="YYYY-MM-DD أو جزء نصي"
                               value="{{ $dateTo ?? '' }}">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label">/صفحة</label>
                        <input type="number" min="5" max="200" name="per_page" class="form-control"
                               value="{{ $perPage ?? 20 }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">فرز حسب</label>
                        <select name="sort" class="form-select">
                            @php $sorts = ['id'=>'ID','mnews_id'=>'MNEWS_ID','the_date_1'=>'THE_DATE_1','created_at'=>'Created','updated_at'=>'Updated']; @endphp
                            @foreach($sorts as $key=>$label)
                                <option value="{{ $key }}" @selected(($sort ?? 'id') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">الاتجاه</label>
                        <select name="dir" class="form-select">
                            <option value="desc" @selected(($dir ?? 'desc')==='desc')>تنازلي</option>
                            <option value="asc"  @selected(($dir ?? '')==='asc')>تصاعدي</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i> تطبيق
                        </button>
                        <a href="{{ route('admin.tenders.index') }}" class="btn btn-outline-secondary">
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table (AJAX target) --}}
        <div id="tenders-table-wrapper" class="card">
            <div class="card-body p-0">
                @include('admin.site.tenders.partials.table', ['tenders' => $tenders])
            </div>
            <div class="card-footer" id="tenders-pagination">
                @include('admin.site.tenders.partials.pagination', ['tenders' => $tenders])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function(){
            const form = document.getElementById('filter-form');
            const wrapper = document.getElementById('tenders-table-wrapper');
            const pagination = document.getElementById('tenders-pagination');

            // حذف
            window.delTender = function(id) {
                if(!confirm('تأكيد الحذف؟')) return;
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
                            submitAjax(); // حدّث القائمة
                        } else {
                            alert(json.message || 'فشل الحذف');
                        }
                    }).catch(() => alert('خطأ في الاتصال'));
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
                        if(data.html) wrapper.querySelector('.card-body').innerHTML = data.html;
                        if(data.pagination) pagination.innerHTML = data.pagination;
                    }).catch(()=>{ /* ignore */ });
            }
        })();
    </script>
@endpush
