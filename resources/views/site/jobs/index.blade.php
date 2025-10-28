@extends('layouts.site')

@section('title', 'الاعلانات | كهرباء غزة')
@section('meta_description', 'اعلانات كهرباء غزة')

@push('styles')
    {{-- لو عندك CSS إضافي خاص بالصفحة فقط --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/site/css/certifications.css') }}"> --}}
@endpush

@section('content')
    {{-- نفس الـ sections من الصفحة الأولى لكن بدون الهيدر والفوتر --}}
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             data-bgimage="url({{ asset('assets/site/images/jobs1.webp') }}) center center / cover">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">اعلانات الوظائف</h1>

                    {{-- خلي الـ breadcrumb تحتها --}}
                    <div class="w-100 mt-2">
                        <ul class="crumb">
                            <li><a href="{{ url('/') }}">الرئيسية</a></li>
                            <li class="active">الاعلانات</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="gradient-edge-bottom color op-7 h-80"></div>
        <div class="sw-overlay op-7"></div>
    </section>
    <div class="container">
        <div class="row g-4 justify-content-center">
            @foreach($jobs as $job)
                <div class="col-md-4 col-sm-6 col-12 text-center mb-4">
                    <a href="{{ $job->link ?: ($job->image ? asset('storage/'.$job->image) : '#') }}"
                       class="image-popup d-block hover mb-3" {{ $job->link ? 'target=_blank' : '' }}>
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-2 text-center text-white z-3">
                                عرض
                            </div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>

                            @php
                                $img = $job->image ? asset('storage/'.$job->image) : asset('assets/site/images/placeholder.webp');
                                $alt = $job->title ?: 'Item';
                            @endphp
                            <img src="{{ $img }}" class="w-100 hover-scale-1-2" alt="{{ $alt }}">
                        </div>
                    </a>
                    <h4>{{ $job->title }}</h4>
                    <div>{!! nl2br(e($job->description)) !!}</div>
                </div>
            @endforeach

        </div>
    </div>
@endsection


@push('scripts')
    {{-- سكربتات إضافية خاصة بالصفحة إن وجدت --}}
@endpush
