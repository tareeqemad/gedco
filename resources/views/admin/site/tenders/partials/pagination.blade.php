@if($tenders->hasPages())
    {{ $tenders->appends(request()->query())->links('pagination::bootstrap-5') }}
@endif
