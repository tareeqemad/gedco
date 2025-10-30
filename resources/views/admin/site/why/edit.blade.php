@extends('layouts.admin')
@section('title','تعديل - لماذا تختارنا')

@section('content')
    @php
         $breadcrumbTitle = 'لماذا تختارنا';
         $breadcrumbParent = 'إعدادات الموقع';
         $breadcrumbParentUrl = route('admin.why.edit', 1);

         $route = route('admin.why.update', $why);
         $method = 'PUT';
         $model = $why;
    @endphp

    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header"><h5 class="card-title mb-0">تعديل - لماذا تختارنا</h5></div>
            <div class="card-body">
                @include('admin.site.why._form', compact('route','method','model','items'))
            </div>
        </div>
    </div>
@endsection
