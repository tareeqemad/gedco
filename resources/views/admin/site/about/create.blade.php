@extends('layouts.admin')
@section('title','إنشاء من نحن')
@section('content')
    @php
        $route  = route('admin.about.store');
        $method = 'POST';
        $model  = null;
    @endphp

    <div class="py-4">
        <h1 class="mb-4 fw-bold">إنشاء - من نحن</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('admin.site.about._form', compact('route','method','model','col1','col2'))
    </div>
@endsection
