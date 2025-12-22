@if($users->hasPages())
    <div class="users-pagination">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="text-muted small">
                عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من {{ $users->total() }} مستخدم
            </div>
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif

