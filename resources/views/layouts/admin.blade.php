
<!DOCTYPE html>
<html lang="en" dir="rtl" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','لوحة التحكم')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/admin/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Choices JS -->
    <script src="{{ asset('assets/admin/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('assets/admin/css/styles.min.css') }}" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" >

    <!-- Node Waves Css -->
    <link href="{{ asset('assets/admin/libs/node-waves/waves.min.css') }}" rel="stylesheet" >

    <!-- Simplebar Css -->
    <link href="{{ asset('assets/admin/libs/simplebar/simplebar.min.css') }}" rel="stylesheet" >

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/@simonwep/pickr/themes/nano.min.css') }}">

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/choices.js/public/assets/styles/choices.min.css') }}">

    @stack('styles')
</head>

<body>

<!-- Start Switcher -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title text-default" id="offcanvasRightLabel">Switcher</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="border-bottom border-block-end-dashed">
            <div class="nav nav-tabs nav-justified" id="switcher-main-tab" role="tablist">
                <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab" data-bs-target="#switcher-home"
                        type="button" role="tab" aria-controls="switcher-home" aria-selected="true">Theme Styles</button>
                <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab" data-bs-target="#switcher-profile"
                        type="button" role="tab" aria-controls="switcher-profile" aria-selected="false">Theme Colors</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel" aria-labelledby="switcher-home-tab"
                 tabindex="0">
                <div class="">
                    <p class="switcher-style-head">Theme Color Mode:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-light-theme">
                                    Light
                                </label>
                                <input class="form-check-input" type="radio" name="theme-style" id="switcher-light-theme"
                                       checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-dark-theme">
                                    Dark
                                </label>
                                <input class="form-check-input" type="radio" name="theme-style" id="switcher-dark-theme">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Directions:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-ltr">
                                    LTR
                                </label>
                                <input class="form-check-input" type="radio" name="direction" id="switcher-ltr" checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-rtl">
                                    RTL
                                </label>
                                <input class="form-check-input" type="radio" name="direction" id="switcher-rtl">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Navigation Styles:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-vertical">
                                    Vertical
                                </label>
                                <input class="form-check-input" type="radio" name="navigation-style" id="switcher-vertical"
                                       checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-horizontal">
                                    Horizontal
                                </label>
                                <input class="form-check-input" type="radio" name="navigation-style"
                                       id="switcher-horizontal">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navigation-menu-styles">
                    <p class="switcher-style-head">Vertical & Horizontal Menu Styles:</p>
                    <div class="row switcher-style gx-0 gy-2">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-menu-click">
                                    Menu Click
                                </label>
                                <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                       id="switcher-menu-click">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-menu-hover">
                                    Menu Hover
                                </label>
                                <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                       id="switcher-menu-hover">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-icon-click">
                                    Icon Click
                                </label>
                                <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                       id="switcher-icon-click">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-icon-hover">
                                    Icon Hover
                                </label>
                                <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                       id="switcher-icon-hover">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidemenu-layout-styles">
                    <p class="switcher-style-head">Sidemenu Layout Styles:</p>
                    <div class="row switcher-style gx-0 gy-2">
                        <div class="col-sm-6">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-default-menu">
                                    Default Menu
                                </label>
                                <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                       id="switcher-default-menu" checked>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-closed-menu">
                                    Closed Menu
                                </label>
                                <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                       id="switcher-closed-menu">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-icontext-menu">
                                    Icon Text
                                </label>
                                <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                       id="switcher-icontext-menu">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-icon-overlay">
                                    Icon Overlay
                                </label>
                                <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                       id="switcher-icon-overlay">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-detached">
                                    Detached
                                </label>
                                <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                       id="switcher-detached">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-double-menu">
                                    Double Menu
                                </label>
                                <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                       id="switcher-double-menu">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Page Styles:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-regular">
                                    Regular
                                </label>
                                <input class="form-check-input" type="radio" name="page-styles" id="switcher-regular"
                                       checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-classic">
                                    Classic
                                </label>
                                <input class="form-check-input" type="radio" name="page-styles" id="switcher-classic">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-modern">
                                    Modern
                                </label>
                                <input class="form-check-input" type="radio" name="page-styles" id="switcher-modern">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Layout Width Styles:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-full-width">
                                    Full Width
                                </label>
                                <input class="form-check-input" type="radio" name="layout-width" id="switcher-full-width"
                                       checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-boxed">
                                    Boxed
                                </label>
                                <input class="form-check-input" type="radio" name="layout-width" id="switcher-boxed">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Menu Positions:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-menu-fixed">
                                    Fixed
                                </label>
                                <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-fixed"
                                       checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-menu-scroll">
                                    Scrollable
                                </label>
                                <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-scroll">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Header Positions:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-header-fixed">
                                    Fixed
                                </label>
                                <input class="form-check-input" type="radio" name="header-positions"
                                       id="switcher-header-fixed" checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-header-scroll">
                                    Scrollable
                                </label>
                                <input class="form-check-input" type="radio" name="header-positions"
                                       id="switcher-header-scroll">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Loader:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-loader-enable">
                                    Enable
                                </label>
                                <input class="form-check-input" type="radio" name="page-loader"
                                       id="switcher-loader-enable">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-loader-disable">
                                    Disable
                                </label>
                                <input class="form-check-input" type="radio" name="page-loader"
                                       id="switcher-loader-disable" checked>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel" aria-labelledby="switcher-profile-tab" tabindex="0">
                <div>
                    <div class="theme-colors">
                        <p class="switcher-style-head">Menu Colors:</p>
                        <div class="d-flex switcher-style pb-2">
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Light Menu" type="radio" name="menu-colors"
                                       id="switcher-menu-light" checked>
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Dark Menu" type="radio" name="menu-colors"
                                       id="switcher-menu-dark">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Color Menu" type="radio" name="menu-colors"
                                       id="switcher-menu-primary">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Gradient Menu" type="radio" name="menu-colors"
                                       id="switcher-menu-gradient">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-transparent"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Transparent Menu"
                                       type="radio" name="menu-colors" id="switcher-menu-transparent">
                            </div>
                        </div>
                        <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Menu dynamically change from below Theme Primary color picker</div>
                    </div>
                    <div class="theme-colors">
                        <p class="switcher-style-head">Header Colors:</p>
                        <div class="d-flex switcher-style pb-2">
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Light Header" type="radio" name="header-colors"
                                       id="switcher-header-light" checked>
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Dark Header" type="radio" name="header-colors"
                                       id="switcher-header-dark">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Color Header" type="radio" name="header-colors"
                                       id="switcher-header-primary">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Gradient Header" type="radio" name="header-colors"
                                       id="switcher-header-gradient">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Transparent Header" type="radio" name="header-colors"
                                       id="switcher-header-transparent">
                            </div>
                        </div>
                        <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Header dynamically change from below Theme Primary color picker</div>
                    </div>
                    <div class="theme-colors">
                        <p class="switcher-style-head">Theme Primary:</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style">
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-1" type="radio"
                                       name="theme-primary" id="switcher-primary">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-2" type="radio"
                                       name="theme-primary" id="switcher-primary1">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-3" type="radio" name="theme-primary"
                                       id="switcher-primary2">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-4" type="radio" name="theme-primary"
                                       id="switcher-primary3">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-5" type="radio" name="theme-primary"
                                       id="switcher-primary4">
                            </div>
                            <div class="form-check switch-select ps-0 mt-1 color-primary-light">
                                <div class="theme-container-primary"></div>
                                <div class="pickr-container-primary"></div>
                            </div>
                        </div>
                    </div>
                    <div class="theme-colors">
                        <p class="switcher-style-head">Theme Background:</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style">
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-1" type="radio"
                                       name="theme-background" id="switcher-background">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-2" type="radio"
                                       name="theme-background" id="switcher-background1">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-3" type="radio" name="theme-background"
                                       id="switcher-background2">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-4" type="radio"
                                       name="theme-background" id="switcher-background3">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-5" type="radio"
                                       name="theme-background" id="switcher-background4">
                            </div>
                            <div class="form-check switch-select ps-0 mt-1 tooltip-static-demo color-bg-transparent">
                                <div class="theme-container-background"></div>
                                <div class="pickr-container-background"></div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-image mb-3">
                        <p class="switcher-style-head">Menu With Background Image:</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style">
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img1" type="radio"
                                       name="theme-background" id="switcher-bg-img">
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img2" type="radio"
                                       name="theme-background" id="switcher-bg-img1">
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img3" type="radio" name="theme-background"
                                       id="switcher-bg-img2">
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img4" type="radio"
                                       name="theme-background" id="switcher-bg-img3">
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img5" type="radio"
                                       name="theme-background" id="switcher-bg-img4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-grid canvas-footer">
                <a href="javascript:void(0);" id="reset-all" class="btn btn-danger btn-block m-1">Reset</a>
            </div>
        </div>
    </div>
</div>
<!-- End Switcher -->


<!-- Loader -->
<div id="loader" >
    <img src="{{ asset('assets/admin/images/media/loader.svg') }}" alt="">
</div>
<!-- Loader -->

<div class="page">
    <!-- app-header -->
    @include('admin.partials.header')
    <!-- /app-header -->
    <!-- Start::app-sidebar -->
    @include('admin.partials.sidebar')
    <!-- End::app-sidebar -->

    <!-- main-content -->
    <div class="main-content app-content">

        <!-- container -->
        <div class="main-container container-fluid">

            <!-- breadcrumb -->
            <div class="breadcrumb-header justify-content-between">
                <div class="left-content">
                    <span class="main-content-title mg-b-0 mg-b-lg-1">EMPTY PAGE</span>
                </div>
                <div class="justify-content-center mt-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item fs-15"><a href="javascript:void(0);">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Empty Page</li>
                    </ol>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- /breadcrumb -->
            @yield('content')

        </div>
        <!-- Container closed -->
    </div>
    <!-- main-content closed -->


    <!-- Footer Start -->
    <footer class="footer mt-auto py-3 bg-white text-center">
        <div class="container">
                <span> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);" class="text-primary">Nowa</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);">
                        <span class="fw-semibold text-decoration-underline">Spruko</span>
                    </a> All
                    rights
                    reserved
                </span>
        </div>
    </footer>
    <!-- Footer End -->
    <!-- Start Rightsidebar -->
    <div class="sidebar offcanvas offcanvas-end" tabindex="-1" id="sidebar-canvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header border-bottom bg-light">
            <h6 class="offcanvas-title text-default" id="offcanvasRightLabel22">NOTIFICATIONS</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
                <div class="tabs-menu p-3">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class=""><a href="#side1" class="active" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/></svg> Chat</a></li>
                        <li class=""><a href="#side2" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"  class="side-menu__icon"  height="24" viewBox="0 0 24 24" width="24"><g><path d="M0,0h24v24H0V0z" fill="none"/></g><g><path d="M12,18.5c0.83,0,1.5-0.67,1.5-1.5h-3C10.5,17.83,11.17,18.5,12,18.5z M12,2C6.48,2,2,6.48,2,12s4.48,10,10,10 c5.52,0,10-4.48,10-10S17.52,2,12,2z M12,20c-4.41,0-8-3.59-8-8s3.59-8,8-8c4.41,0,8,3.59,8,8S16.41,20,12,20z M16,11.39 c0-2.11-1.03-3.92-3-4.39V6.5c0-0.57-0.43-1-1-1s-1,0.43-1,1V7c-1.97,0.47-3,2.27-3,4.39V14H7v2h10v-2h-1V11.39z M14,14h-4v-3 c0-1.1,0.9-2,2-2s2,0.9,2,2V14z"/></g></svg> Notifications</a></li>
                        <li class=""><a href="#side3" data-bs-toggle="tab"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  class="side-menu__icon"  height="24" version="1.1" width="24"  viewBox="0 0 24 24"><path d="M12,2C6.48,2 2,6.48 2,12C2,17.52 6.48,22 12,22C17.52,22 22,17.52 22,12C22,6.48 17.52,2 12,2M7.07,18.28C7.5,17.38 10.12,16.5 12,16.5C13.88,16.5 16.5,17.38 16.93,18.28C15.57,19.36 13.86,20 12,20C10.14,20 8.43,19.36 7.07,18.28M18.36,16.83C16.93,15.09 13.46,14.5 12,14.5C10.54,14.5 7.07,15.09 5.64,16.83C4.62,15.5 4,13.82 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,13.82 19.38,15.5 18.36,16.83M12,6C10.06,6 8.5,7.56 8.5,9.5C8.5,11.44 10.06,13 12,13C13.94,13 15.5,11.44 15.5,9.5C15.5,7.56 13.94,6 12,6M12,11C11.17,11 10.5,10.33 10.5,9.5C10.5,8.67 11.17,8 12,8C12.83,8 13.5,8.67 13.5,9.5C13.5,10.33 12.83,11 12,11Z" /></svg> Friends</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active p-0 border-0" id="side1">
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-primary rounded-circle avatar-md">CH</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>New Websites is Created</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">30 mins ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-danger rounded-circle avatar-md">N</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>Prepare For the Next Project</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">2 hours ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-info rounded-circle avatar-md">S</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>Decide the live Discussion</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">3 hours ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-warning rounded-circle avatar-md">K</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>Meeting at 3:00 pm</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">4 hours ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-success rounded-circle avatar-md">R</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>Prepare for Presentation</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">1 days ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-pink rounded-circle avatar-md">MS</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>Prepare for Presentation</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">1 days ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-purple rounded-circle avatar-md">L</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>Prepare for Presentation</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">45 mintues ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="list d-flex align-items-center p-3">
                            <div class="">
                                <span class="avatar bg-secondary rounded-circle avatar-md">U</span>
                            </div>
                            <a class="wrapper w-100 ms-3" href="javascript:void(0);" >
                                <p class="mb-0 d-flex ">
                                    <b>Prepare for Presentation</b>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted me-1 fs-11"></i>
                                        <small class="text-muted ms-auto">2 days ago</small>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="tab-pane p-0 border-0 " id="side2">
                        <div class="list-group list-group-flush ">
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-3">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/12.jpg') }}" alt="img">
                                </div>
                                <div>
                                    <strong>Madeleine</strong> Hey! there I' am available....
                                    <div class="small text-muted">
                                        3 hours ago
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-3">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/1.jpg') }}" alt="img">
                                </div>
                                <div>
                                    <strong>Anthony</strong> New product Launching...
                                    <div class="small text-muted">
                                        5 hour ago
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-3">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/2.jpg') }}" alt="img">
                                </div>
                                <div>
                                    <strong>Olivia</strong> New Schedule Realease......
                                    <div class="small text-muted">
                                        45 mintues ago
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-3">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/8.jpg') }}" alt="img">
                                </div>
                                <div>
                                    <strong>Madeleine</strong> Hey! there I' am available....
                                    <div class="small text-muted">
                                        3 hours ago
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-3">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/11.jpg') }}" alt="img">
                                </div>
                                <div>
                                    <strong>Anthony</strong> New product Launching...
                                    <div class="small text-muted">
                                        5 hour ago
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-3">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/6.jpg') }}" alt="img">
                                </div>
                                <div>
                                    <strong>Olivia</strong> New Schedule Realease......
                                    <div class="small text-muted">
                                        45 mintues ago
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-3">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/9.jpg') }}" alt="img">
                                </div>
                                <div>
                                    <strong>Olivia</strong> Hey! there I' am available....
                                    <div class="small text-muted">
                                        12 mintues ago
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane p-0 border-0 " id="side3">
                        <div class="list-group list-group-flush ">
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/9.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Mozelle Belt</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/11.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Florinda Carasco</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded" ><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/10.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Alina Bernier</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/2.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Zula Mclaughin</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/13.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Isidro Heide</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/12.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Mozelle Belt</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded" ><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/4.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Florinda Carasco</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/7.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Alina Bernier</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded" ><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/2.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Zula Mclaughin</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded" ><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/14.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Isidro Heide</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded" ><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/11.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Florinda Carasco</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/9.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Alina Bernier</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/15.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Zula Mclaughin</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                            <div class="list-group-item d-flex  align-items-center border-0">
                                <div class="me-2">
                                    <img class="avatar avatar-md rounded-circle cover-image" src="{{ asset('assets/admin/images/faces/4.jpg') }}" alt="img">
                                </div>
                                <div class="">
                                    <div class="font-weight-semibold">Isidro Heide</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-light btn-rounded"><i class="fe fe-message-square fs-16"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Rightsidebar -->



</div>


<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

<!-- Popper JS -->
<script src="{{ asset('assets/admin/libs/@popperjs/core/umd/popper.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Defaultmenu JS -->
<script src="{{ asset('assets/admin/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS-->
<script src="{{ asset('assets/admin/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/admin/js/sticky.js') }}"></script>



<!-- Color Picker JS -->
<script src="{{ asset('assets/admin/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>



<!-- Custom-Switcher JS -->
<script src="{{ asset('assets/admin/js/custom-switcher.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/admin/js/custom.js') }}"></script>

@stack('styles')
</body>

</html>
