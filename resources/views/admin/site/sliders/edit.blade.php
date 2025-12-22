@php
    $breadcrumbTitle     = __('admin.slider.edit_slide');
    $breadcrumbParent    = __('admin.slider.slider_items');
    $breadcrumbParentUrl = route('admin.sliders.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.slider.edit_slide_title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 0.75rem;">
                    <div class="d-flex align-items-center gap-2 flex-wrap" style="flex: 1 1 auto;">
                        <i class="bi bi-images fs-5"></i>
                        <h5 class="mb-0 fw-bold text-white" style="font-size: 1.1rem; line-height: 1.2;">
                            {{ __('admin.slider.edit_slide') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.site.sliders._form', ['slider' => $slider])
        </form>

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
@endsection
