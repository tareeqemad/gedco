@if($logs->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="text-muted small">
            {{ __('admin.activity_logs_ui.showing_range', ['first' => $logs->firstItem(), 'last' => $logs->lastItem(), 'total' => $logs->total()]) }}
        </div>
        {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endif
