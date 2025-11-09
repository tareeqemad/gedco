@if ($items->hasPages())
    <nav>
        <ul class="pagination justify-content-center my-3">
            {{-- Previous --}}
            @if ($items->onFirstPage())
                <li class="page-item disabled"><span class="page-link">«</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev">«</a></li>
            @endif

            {{-- Elements --}}
            @foreach ($items->links()->elements[0] ?? [] as $page => $url)
                @if ($page == $items->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($items->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next">»</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">»</span></li>
            @endif
        </ul>
    </nav>
@endif
