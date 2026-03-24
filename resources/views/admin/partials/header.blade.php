<header class="app-header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left align-items-center">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ route('admin.dashboard') }}" class="header-logo">
                        <img src="{{ asset('assets/admin/images/brand-logos/logo_white.webp') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('assets/admin/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('assets/admin/images/brand-logos/logo_dark.webp') }}" alt="logo" class="desktop-dark">
                        <img src="{{ asset('assets/admin/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
                        <img src="{{ asset('assets/admin/images/brand-logos/logo_white.webp') }}" alt="logo" class="desktop-white">
                        <img src="{{ asset('assets/admin/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="main-header-center ms-3 d-sm-none d-md-none d-lg-block form-group">
                <input class="form-control" placeholder="{{ __('admin.messages.search_placeholder') }}" type="search">
                <button class="btn"><i class="bi bi-search"></i></button>
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">

            <!-- Start::header-element -->
            <div class="header-element header-search d-block d-sm-none">
                <!-- Start::header-link -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                </a>

                <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                    <li>
                                <span class="dropdown-item d-flex align-items-center" >
                                    <span class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ __('admin.messages.search_placeholder') }}" aria-label="{{ __('admin.messages.search_placeholder') }}" aria-describedby="button-addon2">
                                        <button class="btn btn-primary" type="button" id="button-addon2">{{ __('admin.actions.search') }}</button>
                                        <!-- <a href="#" id="button-addon2" class="btn btn-primary">Search</a> -->
                                    </span>
                                </span>
                    </li>
                </ul>

                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            @php
                $currentDir = $direction ?? 'rtl';
                $isRtl = $currentDir === 'rtl';
                // Palestine flag - try JPG first (downloaded), then SVG, then fallback
                $palestineFlagPath = public_path('assets/admin/images/flags/palestine_flag');
                $palestineFlag = file_exists($palestineFlagPath . '.jpg') 
                    ? asset('assets/admin/images/flags/palestine_flag.jpg')
                    : (file_exists($palestineFlagPath . '.svg')
                        ? asset('assets/admin/images/flags/palestine_flag.svg')
                        : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDQwIDI0Ij48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iOCIgZmlsbD0iIzAwMCIvPjxyZWN0IHk9IjgiIHdpZHRoPSI0MCIgaGVpZ2h0PSI4IiBmaWxsPSIjRkZGIi8+PHJlY3QgeT0iMTYiIHdpZHRoPSI0MCIgaGVpZ2h0PSI4IiBmaWxsPSIjMDA3QTNEIi8+PHBhdGggZD0iTSAwIDAgTCAxMy4zMyAxMiBMIDAgMjQgWiIgZmlsbD0iI0NFMTEyNiIvPjwvc3ZnPg==');
                // Try JPG first, then SVG
                $usFlagPath = public_path('assets/admin/images/flags/us_flag');
                $usFlag = file_exists($usFlagPath . '.jpg') && filesize($usFlagPath . '.jpg') > 1000
                    ? asset('assets/admin/images/flags/us_flag.jpg')
                    : (file_exists($usFlagPath . '.svg')
                        ? asset('assets/admin/images/flags/us_flag.svg')
                        : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDQwIDI0Ij48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iMjQiIGZpbGw9IiNGRkYiLz48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iMS44NDYiIGZpbGw9IiNCMjIyMzQiLz48cmVjdCB5PSIzLjY5MiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjEuODQ2IiBmaWxsPSIjQjIyMjM0Ii8+PHJlY3QgeT0iNy4zODQiIHdpZHRoPSI0MCIgaGVpZ2h0PSIxLjg0NiIgZmlsbD0iI0IyMjIzNCIvPjxyZWN0IHk9IjExLjA3NiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjEuODQ2IiBmaWxsPSIjQjIyMjM0Ii8+PHJlY3QgeT0iMTQuNzY4IiB3aWR0aD0iNDAiIGhlaWdodD0iMS44NDYiIGZpbGw9IiNCMjIyMzQiLz48cmVjdCB5PSIxOC40NiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjEuODQ2IiBmaWxsPSIjQjIyMjM0Ii8+PHJlY3QgeT0iMjIuMTUyIiB3aWR0aD0iNDAiIGhlaWdodD0iMS44NDYiIGZpbGw9IiNCMjIyMzQiLz48cmVjdCB3aWR0aD0iMTYiIGhlaWdodD0iOS4yMyIgZmlsbD0iIzNDM0I2RSIvPjwvc3ZnPg==');
            @endphp
            <div class="header-element language-selector">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown" id="language-dropdown-toggle">
                    <img src="{{ $isRtl ? $palestineFlag : $usFlag }}" alt="{{ $isRtl ? 'العربية' : 'English' }}" class="rounded-circle" width="24" height="24" style="object-fit: cover;">
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                    <li>
                        <form action="{{ route('direction.set', 'rtl') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center w-100 {{ $isRtl ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: {{ $isRtl ? 'right' : 'left' }};">
                                <span class="avatar avatar-xs lh-1 me-2">
                                    <img src="{{ $palestineFlag }}" alt="العربية" class="rounded-circle" width="20" height="20" style="object-fit: cover;">
                                </span>
                                <span class="text-default">العربية</span>
                                @if($isRtl)
                                    <i class="bi bi-check ms-auto"></i>
                                @endif
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="{{ route('direction.set', 'ltr') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center w-100 {{ !$isRtl ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: {{ $isRtl ? 'right' : 'left' }};">
                                <span class="avatar avatar-xs lh-1 me-2">
                                    <img src="{{ $usFlag }}" alt="English" class="rounded-circle" width="20" height="20" style="object-fit: cover;">
                                </span>
                                <span class="text-default">English</span>
                                @if(!$isRtl)
                                    <i class="bi bi-check ms-auto"></i>
                                @endif
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element header-theme-mode">
                <!-- Start::header-link|layout-setting -->
                <a href="javascript:void(0);" class="header-link layout-setting">
                            <span class="light-layout">
                                <!-- Start::header-link-icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z"/></svg>

                                <!-- End::header-link-icon -->
                            </span>
                    <span class="dark-layout">
                                <!-- Start::header-link-icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z"/></svg>
                        <!-- End::header-link-icon -->
                            </span>
                </a>
                <!-- End::header-link|layout-setting -->
            </div>
            <!-- End::header-element -->


            <!-- Start::header-element -->
            <div class="header-element notifications-dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="notificationDropdown" aria-expanded="false">
                    <i class="bi bi-bell header-link-icon"></i>
                    <span class="badge bg-danger rounded-pill header-icon-badge pulse pulse-secondary" id="notification-icon-badge" style="display: none;">0</span>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <!-- Start::main-header-dropdown -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end notification-dropdown-menu" data-popper-placement="none" style="width: 350px;">
                    <div class="p-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-17 fw-semibold">{{ __('admin.notifications.title') }}</p>
                            <span class="badge bg-primary-transparent" id="notification-count">0 {{ __('admin.notifications.unread') }}</span>
                        </div>
                    </div>
                    <div class="dropdown-divider mb-0"></div>
                    <ul class="list-unstyled mb-0" id="notification-list" style="max-height: 400px; overflow-y: auto;">
                        <li class="p-4 text-center text-muted">
                            <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                            <p class="mb-0">{{ __('admin.notifications.no_notifications') }}</p>
                        </li>
                    </ul>
                    <div class="p-2 border-top text-center">
                        <a href="javascript:void(0);" class="text-primary small" id="mark-all-read" style="display: none;">
                            {{ __('admin.notifications.mark_all_read') }}
                        </a>
                    </div>
                </div>
                <!-- End::main-header-dropdown -->
            </div>
            <!-- End::header-element -->


            <!-- Start::header-element -->
            <div class="header-element header-fullscreen">
                <!-- Start::header-link -->
                <a onclick="openFullscreen();" href="#" class="header-link">
                    <i class="bi bi-fullscreen full-screen-open header-link-icon"></i>
                    <i class="bi bi-fullscreen-exit full-screen-close header-link-icon d-none"></i>
                </a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-sm-2 me-0">
                            <img src="{{ auth()->user()->avatar_url }}" alt="profile" width="32" height="32" class="rounded-circle shadow-sm">
                        </div>
                        <div class="d-xl-block d-none">
                            <p class="fw-semibold mb-0 lh-1">{{ auth()->user()->display_name }}</p>
                            <span class="op-7 fw-normal d-block fs-11">   {{ auth()->user()->getRoleNames()->first() ?? __('admin.header.no_role') }}</span>
                        </div>
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <!-- Start::main-header-dropdown -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end header-profile-dropdown" data-popper-placement="none">
                    <div class="p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img src="{{ auth()->user()->avatar_url }}" alt="profile" width="48" height="48" class="rounded-circle shadow-sm">
                            </div>
                            <div class="flex-grow-1">
                                <p class="fw-semibold mb-0">{{ auth()->user()->display_name }}</p>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </div>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                                <i class="bi bi-person me-2"></i>
                                {{ __('admin.profile.title') }}
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="bi bi-gear me-2"></i>
                                {{ __('admin.profile.settings') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    {{ __('admin.actions.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <!-- End::main-header-dropdown -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>