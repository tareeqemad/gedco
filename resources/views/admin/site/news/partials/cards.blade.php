@if($items->count())
    <div class="row g-3">
        @foreach($items as $row)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card card-news h-100">
                    <a class="thumb" href="{{ route('admin.news.show',$row->ID_NEWS) }}">
                        @if(method_exists($row,'coverUrl') && $row->coverUrl())
                            <img src="{{ $row->coverUrl() }}" alt="cover">
                        @else
                            <img src="{{ asset('assets/admin/images/placeholders/16x9.png') }}" alt="placeholder">
                        @endif
                    </a>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex gap-2 align-items-center">
                        <span class="badge badge-dot {{ ($row->STATUS ?? 'published')==='draft' ? 'badge-draft' : '' }}">
                            {{ $row->STATUS ?? 'published' }}
                        </span>
                                @if(($row->FEATURED ?? false))
                                    <span class="badge bg-warning-subtle text-warning border">مميّز</span>
                                @endif
                                @if($row->PDF)
                                    <span class="badge bg-danger-subtle text-danger border">PDF</span>
                                @endif
                            </div>
                            <small class="text-muted">{{ optional($row->DATE_NEWS)->format('Y-m-d') ?? '—' }}</small>
                        </div>
                        <h6 class="mb-1">
                            <a class="text-decoration-none" href="{{ route('admin.news.show',$row->ID_NEWS) }}">
                                {{ \Illuminate\Support\Str::limit($row->TITLE, 90) }}
                            </a>
                        </h6>
                        <p class="text-muted small mb-3">{{ $row->excerpt(120) }}</p>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.news.show',$row->ID_NEWS) }}" class="btn btn-sm btn-light">عرض</a>
                            @can('news.edit')
                                <a href="{{ route('admin.news.edit',$row->ID_NEWS) }}" class="btn btn-sm btn-warning">تعديل</a>
                            @endcan
                            @can('news.delete')
                                <button class="btn btn-sm btn-danger" onclick="delNews({{ $row->ID_NEWS }})">حذف</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center text-muted py-5">لا توجد أخبار.</div>
@endif

@push('scripts')
    <script>
        function delNews(id){
            if(!confirm('تأكيد حذف الخبر؟')) return;
            fetch('{{ route('admin.news.destroy', 0) }}'.replace('/0','/'+id), {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With':'XMLHttpRequest'}
            }).then(r=>r.json()).then(()=>location.reload());
        }
    </script>
@endpush
