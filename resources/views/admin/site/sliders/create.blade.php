@php
    $breadcrumbTitle     = __('admin.slider.add_slide');
    $breadcrumbParent    = __('admin.slider.slider_items');
    $breadcrumbParentUrl = route('admin.sliders.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.slider.add_slide'))

@section('content')
    <div class="container-fluid p-0">
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.site.sliders._form', ['slider' => null, 'nextOrder' => $nextOrder ?? 0])
        </form>
    </div>
@endsection
