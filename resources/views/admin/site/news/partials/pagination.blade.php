@if($items->hasPages())
    <div class="d-flex justify-content-center mt-3">{!! $items->onEachSide(1)->links() !!}</div>
@endif
