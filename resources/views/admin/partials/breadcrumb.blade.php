<!-- breadcrumb -->
<div class="breadcrumb-header">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
        @if(!empty($parent))
            <a href="{{ $parent_url ?? 'javascript:void(0);' }}">{{ $parent }}</a>
            <span class="breadcrumb-sep">‹</span>
        @endif
        <span class="breadcrumb-current">{{ $title ?? '' }}</span>
    </nav>
</div>
<!-- /breadcrumb -->
