@if($users->hasPages())
    <div class="users-pagination">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="text-muted small">
                {{ __('admin.ui.showing_range', ['first' => $users->firstItem(), 'last' => $users->lastItem(), 'total' => $users->total()]) }}
            </div>
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif

