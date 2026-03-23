@props([
    'icon' => '',
    'title' => '',
    'backRoute' => null,
    'backLabel' => null,
])

<div class="card-header card-header-form border-0">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div class="d-flex align-items-center gap-3">
            @if($icon)
                <div class="form-icon-wrap">
                    <i class="bi {{ $icon }}"></i>
                </div>
            @endif
            <div>
                <h5 class="mb-0 fw-bold" style="font-size: 1.05rem; color: #24364A;">
                    {{ $title }}
                </h5>
                @if(isset($subtitle))
                    <p class="mb-0 mt-1" style="font-size: 0.8rem; color: #6B7C93;">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            {{ $actions ?? '' }}
            @if($backRoute)
                <a href="{{ $backRoute }}" class="btn btn-sm form-btn-back">
                    <i class="bi bi-arrow-left me-1"></i>
                    <span class="d-none d-md-inline">{{ $backLabel ?? __('admin.common.back') }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
