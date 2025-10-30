<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title>@yield('title', 'كهرباء غزة')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Meta --}}
    <meta name="description" content="@yield('meta_description','كهرباء غزة')">
    <meta name="keywords" content="@yield('meta_keywords','')">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

      <link id="bootstrap" rel="stylesheet" href="{{ asset('assets/site/css/bootstrap.rtl.min.css') }}">

     <link rel="stylesheet" href="{{ asset('assets/site/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/swiper-custom-1.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/coloring.css') }}">
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" >

     <link rel="stylesheet" href="{{ asset('assets/site/css/rtl-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/rtl-fix-simple.css') }}">

     <link id="colors" rel="stylesheet" href="{{ asset('assets/site/css/colors/scheme-01.css') }}">

     <link rel="stylesheet" href="{{ asset('assets/site/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/rtl-overrides.css') }}">


    @stack('styles')
</head>

<body>
<div id="wrapper">
    <a href="#" id="back-to-top"></a>

    {{-- Loader (اختياري) --}}
    <div id="de-loader"></div>

    {{-- ===== Header ===== --}}
    @include('site.partials.header')
    {{-- ===== /Header ===== --}}

    {{-- ===== Page Content ===== --}}
    @yield('content')
    {{-- ===== /Page Content ===== --}}

    {{-- ===== Footer ===== --}}
    @include('site.partials.footer')
    {{-- ===== /Footer ===== --}}
</div>

@yield('overlay')

{{-- JS --}}
<script src="{{ asset('assets/site/js/plugins.js') }}"></script>
<script src="{{ asset('assets/site/js/designesia.js') }}"></script>
<script src="{{ asset('assets/site/js/swiper.js') }}"></script>
<script src="{{ asset('assets/site/js/custom-swiper-1.js') }}"></script>
<script src="{{ asset('assets/site/js/custom-marquee.js') }}"></script>
<script src="{{ asset('assets/site/js/custom.js') }}"></script>

@stack('scripts')
</body>
</html>
