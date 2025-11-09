@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $ads */
    // مسار نسبي دائمًا لمنع mixed
    $basePath = request()->getPathInfo(); // مثال: /admin/advertisements
    $currentQuery = request()->except('page');

    $buildUrl = function(array $query) use ($basePath) {
        $q = http_build_query($query);
        return $basePath . ($q ? ('?' . $q) : '');
    };
@endphp

@if ($ads->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="text-muted fs-13">
            عرض <strong>{{ $ads->firstItem() }}</strong> - <strong>{{ $ads->lastItem() }}</strong> من <strong>{{ $ads->total() }}</strong>
        </div>

        <nav aria-label="Pagination">
            <ul class="pagination mb-0">
                {{-- السابق --}}
                @if ($ads->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-right-s-line"></i></span></li>
                @else
                    @php $prevQuery = array_merge($currentQuery, ['page' => $ads->currentPage() - 1]); @endphp
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $buildUrl($prevQuery) }}" data-url="{{ $buildUrl($prevQuery) }}">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                @endif

                @php
                    $start = max(1, $ads->currentPage() - 2);
                    $end   = min($ads->lastPage(), $ads->currentPage() + 2);
                @endphp

                {{-- أول صفحة + نقاط --}}
                @if($start > 1)
                    @php $firstQuery = array_merge($currentQuery, ['page' => 1]); @endphp
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $buildUrl($firstQuery) }}" data-url="{{ $buildUrl($firstQuery) }}">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endif

                {{-- الصفحات الوسط --}}
                @for($i = $start; $i <= $end; $i++)
                    @php $pageQuery = array_merge($currentQuery, ['page' => $i]); @endphp
                    <li class="page-item {{ $i == $ads->currentPage() ? 'active' : '' }}">
                        <a class="page-link pagination-link {{ $i == $ads->currentPage() ? 'bg-primary text-white' : '' }}"
                           href="{{ $buildUrl($pageQuery) }}"
                           data-url="{{ $buildUrl($pageQuery) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- نقاط + آخر صفحة --}}
                @if($end < $ads->lastPage())
                    @if($end < $ads->lastPage() - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    @php $lastQuery = array_merge($currentQuery, ['page' => $ads->lastPage()]); @endphp
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $buildUrl($lastQuery) }}" data-url="{{ $buildUrl($lastQuery) }}">{{ $ads->lastPage() }}</a>
                    </li>
                @endif

                {{-- التالي --}}
                @if ($ads->hasMorePages())
                    @php $nextQuery = array_merge($currentQuery, ['page' => $ads->currentPage() + 1]); @endphp
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $buildUrl($nextQuery) }}" data-url="{{ $buildUrl($nextQuery) }}">
                            <i class="ri-arrow-left-s-line"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-left-s-line"></i></span></li>
                @endif
            </ul>
        </nav>
    </div>
@endif
