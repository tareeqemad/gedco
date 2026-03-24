@if($permissions->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="text-muted small">
            {{ __('admin.ui.showing_range', ['first' => $permissions->firstItem(), 'last' => $permissions->lastItem(), 'total' => $permissions->total()]) }}
        </div>
        {{ $permissions->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endif
