@forelse($sliders as $slider)
    <tr class="slider-table-row" data-slider-id="{{ $slider->id }}">
        <td class="slider-table-number" data-label="#">
            <span class="slider-number-badge">{{ $loop->iteration + ($sliders->currentPage()-1)*$sliders->perPage() }}</span>
        </td>
        <td class="slider-table-image" data-label="{{ __('admin.slider.table_bg_image') }}">
            <div class="slider-image-wrapper">
                <img src="{{ build_image_url($slider->bg_image) }}" 
                     alt="{{ $slider->title }}"
                     loading="lazy"
                     onerror="this.src='{{ asset('assets/admin/images/placeholder.png') }}'">
            </div>
        </td>
        <td class="slider-table-content" data-label="{{ __('admin.slider.table_title') }}">
            <div class="slider-content-header">
                <h6 class="slider-content-title">{{ Str::limit($slider->title, 60) }}</h6>
            </div>
            @if($slider->subtitle)
                <p class="slider-content-subtitle">{{ Str::limit($slider->subtitle, 80) }}</p>
            @endif
        </td>
        <td class="slider-table-order text-center" data-label="{{ __('admin.slider.table_order') }}">
            <span class="slider-order-badge">#{{ $slider->sort_order }}</span>
        </td>
        <td class="slider-table-status text-center" data-label="{{ __('admin.slider.table_status') }}">
            <span class="slider-status-badge {{ $slider->is_active ? 'badge-success' : 'badge-secondary' }}">
                {{ $slider->is_active ? __('admin.slider.form_active') : __('admin.slider.form_inactive') }}
            </span>
        </td>
        <td class="slider-table-actions" data-label="{{ __('admin.slider.table_actions') }}">
            <div class="slider-actions-group">
                <a href="{{ route('admin.sliders.edit', $slider) }}" 
                   class="btn btn-sm btn-outline-primary rounded-2"
                   title="{{ __('admin.common.edit') }}">
                    <i class="bi bi-pencil"></i>
                </a>
                <button type="button"
                        class="btn btn-sm btn-outline-danger rounded-2"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal-{{ $slider->id }}"
                        title="{{ __('admin.common.delete') }}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="slider-empty-state">
            <div class="slider-empty-content">
                <i class="bi bi-images"></i>
                <h5>{{ __('admin.slider.no_results') }}</h5>
                <p class="text-muted">{{ __('admin.slider.no_slides') }}</p>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary rounded-3">
                    <i class="bi bi-plus-circle me-1"></i>
                    {{ __('admin.slider.add_first_slide') }}
                </a>
            </div>
        </td>
    </tr>
@endforelse
