@php
    $breadcrumbTitle     = __('admin.slider.add_slide');
    $breadcrumbParent    = __('admin.slider.slider_items');
    $breadcrumbParentUrl = route('admin.sliders.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.slider.add_slide'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-images"
                :title="__('admin.slider.add_slide')" />
        </x-admin.card>

        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.site.sliders._form', ['slider' => null, 'nextOrder' => $nextOrder ?? 0])
        </form>
    </div>
@endsection
