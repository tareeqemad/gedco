@extends('layouts.admin')
@section('title', __('admin.slider.edit_slide_title'))

@section('content')
    @php

          $breadcrumbTitle     = __('admin.slider.edit_slide');
          $breadcrumbParent    = __('admin.breadcrumbs.home');
          $breadcrumbParentUrl = route('admin.dashboard');

          $title      = __('admin.slider.edit_slide');
          $parent     = __('admin.slider.slider_items');
          $parent_url = route('admin.sliders.index');
    @endphp

    <div class="py-4">
        <div class="card border-0 shadow-sm">
            <!-- Card Header -->
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3 py-3">
                <h5 class="card-title mb-0 text-dark fw-semibold d-flex align-items-center gap-2">
                    {{ __('admin.slider.edit_slide') }}: <span class="text-primary">{{ Str::limit($slider->title, 40) }}</span>
                </h5>
                <a href="{{ route('admin.sliders.index') }}"
                   class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
                    {{ __('admin.slider.back_to_list') }}
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- فورم التعديل الرئيسي -->
                <form action="{{ route('admin.sliders.update', $slider) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="needs-validation"
                      novalidate>
                    @csrf
                    @method('PUT')


                    @include('admin.site.sliders._form', ['slider' => $slider])
                </form>

                <!-- فورم مستقل ومخفي لإخفاء الصورة (يتم استدعاؤه عبر زر X داخل _form) -->
                @if(!empty($slider->bg_image))
                    <form id="remove-image-{{ $slider->id }}"
                          action="{{ route('admin.sliders.remove-image', $slider) }}"
                          method="POST"
                          class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
