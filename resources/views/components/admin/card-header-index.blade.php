@props([
    'icon' => '',
    'title' => '',
    'createRoute' => null,
    'createLabel' => null,
    'createPermission' => null,
])

<div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 0.75rem;">
        <div class="d-flex align-items-center gap-2 flex-wrap" style="flex: 1 1 auto;">
            @if($icon)
                <i class="bi {{ $icon }} fs-5"></i>
            @endif
            <h5 class="mb-0 fw-bold text-white" style="font-size: 1.1rem; line-height: 1.2;">
                {{ $title }}
            </h5>
            {{-- Slot for badges, counts, etc --}}
            {{ $badge ?? '' }}
        </div>
        <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
            {{-- Slot for extra buttons/elements --}}
            {{ $actions ?? '' }}
            @if($createRoute)
                @if($createPermission)
                    @can($createPermission)
                        <a href="{{ $createRoute }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            <span class="d-none d-md-inline">{{ $createLabel ?? __('admin.common.add') }}</span>
                        </a>
                    @endcan
                @else
                    <a href="{{ $createRoute }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        <span class="d-none d-md-inline">{{ $createLabel ?? __('admin.common.add') }}</span>
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>
