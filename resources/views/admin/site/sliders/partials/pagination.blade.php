@if($sliders->hasPages())
    <div class="slider-pagination">
        <div class="slider-pagination-info">
            {{ __('admin.slider.showing_results', [
                'from' => $sliders->firstItem(),
                'to' => $sliders->lastItem(),
                'total' => $sliders->total()
            ]) }}
        </div>
        <div class="slider-pagination-links">
            {{ $sliders->onEachSide(1)->links() }}
        </div>
    </div>
@endif

