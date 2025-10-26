@extends('layouts.admin')

@section('title','لوحة التحكم')
@section('page-title','لوحة التحكم')

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item fs-15"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
        <li class="breadcrumb-item active" aria-current="page">لوحة التحكم</li>
    </ol>
@endsection

@push('styles')
    {{-- إن احتجت CSS إضافي للصفحة --}}
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-1">أهلًا {{ auth()->user()->name ?? 'أدمن' }}</h6>
                    <p class="text-muted m-0">هذه عيّنة بطاقة داخل الداشبورد.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- JS خاص بالصفحة إن لزم --}}
@endpush
