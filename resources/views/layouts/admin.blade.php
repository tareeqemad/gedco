<!DOCTYPE html>
<html lang="en" dir="{{ $direction ?? 'rtl' }}" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="color" data-menu-styles="color"	 data-toggled="close">

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
    <link id="bootstrap-style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap' . (($direction ?? 'rtl') === 'rtl' ? '.rtl' : '') . '.min.css') }}" rel="stylesheet" >

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

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
    @stack('styles')
</head>

<body>



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
            @include('admin.partials.breadcrumb', [
                'title' => $breadcrumbTitle ?? '',
                'parent' => $breadcrumbParent ?? null,
                'parent_url' => $breadcrumbParentUrl ?? null
            ])

            <!-- /breadcrumb -->

            <!-- Flash Messages -->
            @include('admin.partials.flash-messages')

            <!-- Impersonation Banner -->
            @if(session()->has('impersonator_id'))
                @php
                    $impersonator = \App\Models\User::find(session('impersonator_id'));
                @endphp
                @if($impersonator)
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #000;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-check-fill fs-4"></i>
                                <div>
                                    <strong>{{ __('admin.users.impersonating_as') }}:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
                                    <br>
                                    <small>{{ __('admin.users.original_user') }}: {{ $impersonator->name }}</small>
                                </div>
                            </div>
                            <form action="{{ route('admin.users.stop-impersonating') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-dark">
                                    <i class="bi bi-arrow-left me-1"></i>{{ __('admin.users.stop_impersonating') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif

            @yield('content')

        </div>
        <!-- Container closed -->
    </div>
    <!-- main-content closed -->


    <!-- Footer Start -->
    @include('admin.partials.footer')
    <!-- Footer End -->



</div>


<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="bi bi-arrow-up fs-20"></i></span>
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

<!-- Flatpickr JS -->
<script src="{{ asset('assets/admin/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/flatpickr/l10n/ar.js') }}"></script>

<!-- Admin Date Picker -->
<script src="{{ asset('assets/admin/js/admin-datepicker.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/admin/js/custom.js') }}"></script>
<script src="{{ asset('assets/admin/js/notifications.js') }}"></script>
<script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@vite(['resources/js/app.js'])

{{-- Global: unified Swal delete confirmation for any form with data-confirm-delete --}}
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-confirm-delete]');
    if (!btn) return;
    e.preventDefault();
    const form = btn.closest('form');
    if (!form) return;
    Swal.fire({
        title: btn.dataset.confirmTitle || '{{ __('admin.ui.confirm_delete') }}',
        text: btn.dataset.confirmText || '{{ __('admin.ui.delete_irreversible') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '{{ __('admin.actions.delete') }}',
        cancelButtonText: '{{ __('admin.actions.cancel') }}',
        reverseButtons: true,
        buttonsStyling: false,
        customClass: { confirmButton: 'btn btn-danger px-4', cancelButton: 'btn btn-secondary px-4 me-2' }
    }).then(function(result) { if (result.isConfirmed) form.submit(); });
});
</script>

@stack('scripts')
</body>

</html>
