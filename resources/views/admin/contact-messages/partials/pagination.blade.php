<div class="d-flex justify-content-between align-items-center flex-wrap">
    <div class="text-muted small">
        عرض {{ $messages->firstItem() ?? 0 }} إلى {{ $messages->lastItem() ?? 0 }} من {{ $messages->total() }} رسالة
    </div>
    <div>
        {{ $messages->links() }}
    </div>
</div>
