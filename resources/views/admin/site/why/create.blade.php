@extends('layouts.admin')
@section('title','إنشاء - لماذا تختارنا')

@section('content')
    @php
        $route = route('admin.why.store');
        $method = 'POST';
        $model = null;
    @endphp
    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header"><h5 class="card-title mb-0">إنشاء - لماذا تختارنا</h5></div>
            <div class="card-body">
                @include('admin.site.why._form', compact('route','method','model','items'))
            </div>
        </div>
    </div>
@endsection
