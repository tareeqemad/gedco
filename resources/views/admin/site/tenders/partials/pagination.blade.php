{{-- resources/views/admin/site/tenders/partials/pagination.blade.php --}}
@if($tenders->hasPages())
    <nav class="mt-3">
        <ul class="pagination justify-content-center mb-0">
            {{-- Previous Page Link --}}
            @if ($tenders->onFirstPage())
                <li class="page-item disabled"><span class="page-link">السابق</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $tenders->previousPageUrl() }}" rel="prev">السابق</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($tenders->links()->elements[0] ?? [] as $page => $url)
                @if ($page == $tenders->currentPage())
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($tenders->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $tenders->nextPageUrl() }}" rel="next">التالي</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">التالي</span></li>
            @endif
        </ul>
    </nav>
@endif
