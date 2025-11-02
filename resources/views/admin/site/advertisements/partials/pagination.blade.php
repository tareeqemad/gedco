@php
    $baseUrl = request()->fullUrlWithoutQuery(['page']);
    $currentQuery = request()->query();
@endphp

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="text-muted fs-13">
        عرض <strong>{{ $ads->firstItem() }}</strong> - <strong>{{ $ads->lastItem() }}</strong> من <strong>{{ $ads->total() }}</strong>
    </div>

    <nav>
        <ul class="pagination mb-0">
            @if ($ads->onFirstPage())
                <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-right-s-line"></i></span></li>
            @else
                @php $prevQuery = $currentQuery; $prevQuery['page'] = $ads->currentPage() - 1; @endphp
                <li class="page-item">
                    <a class="page-link pagination-link" href="#" data-url="{{ $baseUrl . '?' . http_build_query($prevQuery) }}">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
            @endif

            @php
                $start = max(1, $ads->currentPage() - 2);
                $end = min($ads->lastPage(), $ads->currentPage() + 2);
            @endphp

            @if($start > 1)
                @php $firstQuery = $currentQuery; $firstQuery['page'] = 1; @endphp
                <li class="page-item"><a class="page-link pagination-link" href="#" data-url="{{ $baseUrl . '?' . http_build_query($firstQuery) }}">1</a></li>
                @if($start > 2)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
            @endif

            @for($i = $start; $i <= $end; $i++)
                @php $pageQuery = $currentQuery; $pageQuery['page'] = $i; @endphp
                <li class="page-item {{ $i == $ads->currentPage() ? 'active' : '' }}">
                    <a class="page-link pagination-link {{ $i == $ads->currentPage() ? 'bg-primary text-white' : '' }}"
                       href="#" data-url="{{ $baseUrl . '?' . http_build_query($pageQuery) }}">{{ $i }}</a>
                </li>
            @endfor

            @if($end < $ads->lastPage())
                @if($end < $ads->lastPage() - 1)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                @php $lastQuery = $currentQuery; $lastQuery['page'] = $ads->lastPage(); @endphp
                <li class="page-item"><a class="page-link pagination-link" href="#" data-url="{{ $baseUrl . '?' . http_build_query($lastQuery) }}">{{ $ads->lastPage() }}</a></li>
            @endif

            @if ($ads->hasMorePages())
                @php $nextQuery = $currentQuery; $nextQuery['page'] = $ads->currentPage() + 1; @endphp
                <li class="page-item">
                    <a class="page-link pagination-link" href="#" data-url="{{ $baseUrl . '?' . http_build_query($nextQuery) }}">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-left-s-line"></i></span></li>
            @endif
        </ul>
    </nav>
</div>
