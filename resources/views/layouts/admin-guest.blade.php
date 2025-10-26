<!DOCTYPE html>
<html lang="ar" dir="rtl" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','تسجيل الدخول - لوحة التحكم')</title>

    {{-- فافيكون --}}
    <link rel="icon" href="{{ asset('assets/admin/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap --}}
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- Styles & Icons --}}
    <link href="{{ asset('assets/admin/css/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet">

    {{-- أي ملفات CSS إضافية للتمبلت ضعها هنا --}}
    @stack('styles')
</head>
<body class="error-page1 bg-primary">
@yield('body')

{{-- Scripts --}}
<script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/show-password.js') }}"></script>
<script src="{{ asset('assets/admin/js/custom-switcher.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/authentication-main.js') }}"></script>
@stack('scripts')
</body>
</html>
