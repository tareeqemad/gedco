@if ($ads->hasPages())
    {{ $ads->appends(request()->query())->links('pagination::bootstrap-5') }}
@endif
