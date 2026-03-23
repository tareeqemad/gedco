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
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-images"
                :title="__('admin.slider.edit_slide')" />
        </x-admin.card>

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
