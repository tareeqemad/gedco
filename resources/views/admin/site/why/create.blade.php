@extends('layouts.admin')
@section('title', __('admin.why_choose_us.create_title'))

@section('content')
    @php
        $route = route('admin.why.store');
        $method = 'POST';
        $model = null;
    @endphp
    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header"><h5 class="card-title mb-0">{{ __('admin.why_choose_us.create_title') }}</h5></div>
            <div class="card-body">
                @include('admin.site.why._form', compact('route','method','model','items'))
            </div>
        </div>
    </div>
@endsection
