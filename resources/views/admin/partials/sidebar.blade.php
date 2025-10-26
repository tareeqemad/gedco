<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
            <img src="{{ asset('assets/admin/images/brand-logos/logo_white.webp') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('assets/admin/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('assets/admin/images/brand-logos/logo_dark.webp') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('assets/admin/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('assets/admin/images/brand-logos/logo_white.webp') }}" alt="logo" class="desktop-white">
            <img src="{{ asset('assets/admin/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">

            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>

            @php
                $usersOpen = request()->routeIs('admin.users.*');
                $footerOpen = request()->routeIs('admin.footer-links.*');
                $socialOpen = request()->routeIs('admin.social-links.*');
            @endphp

            <ul class="main-menu">

                <!-- 🏠 Dashboard -->
                <li class="slide {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item">
                        <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M3 12l9-9 9 9h-3v8h-12v-8h-3z"/>
                        </svg>
                        <span class="side-menu__label">لوحة التحكم</span>
                    </a>
                </li>

                <!-- 👥 Users -->
                <li class="slide has-sub {{ $usersOpen ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ $usersOpen ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span class="side-menu__label">المستخدمون</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1" style="{{ $usersOpen ? 'display:block' : '' }}">
                        <li class="slide side-menu__label1"><a href="javascript:void(0)">المستخدمون</a></li>

                        <li class="slide">
                            <a href="{{ route('admin.users.index') }}"
                               class="side-menu__item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                قائمة المستخدمين
                            </a>
                        </li>

                        <li class="slide">
                            <a href="{{ route('admin.users.create') }}"
                               class="side-menu__item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                إضافة مستخدم
                            </a>
                        </li>
                    </ul>
                </li>

                @can('sliders.view')
                    <li class="slide {{ request()->routeIs('admin.sliders.*') ? 'active open' : '' }}">
                        <a href="{{ route('admin.sliders.index') }}" class="side-menu__item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                            <i class="bi bi-images side-menu__icon"></i>
                            <span class="side-menu__label">السلايدر</span>
                        </a>
                    </li>
                @endcan

                <!-- 🔐 Permissions (super admin only) -->
                @role('super-admin')
                <li class="slide {{ request()->routeIs('admin.permissions.*') ? 'active open' : '' }}">
                    <a href="{{ route('admin.permissions.index') }}" class="side-menu__item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock me-2"></i>
                        <span class="side-menu__label">الصلاحيات</span>
                    </a>
                </li>
                @endrole

                <!-- 🔗 Footer Links -->
                @can('footer-links.view')
                    <li class="slide has-sub {{ $footerOpen ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ $footerOpen ? 'active' : '' }}">
                            <i class="bi bi-link-45deg side-menu__icon"></i>
                            <span class="side-menu__label">روابط الفوتر</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1" style="{{ $footerOpen ? 'display:block' : '' }}">
                            <li class="slide side-menu__label1"><a href="javascript:void(0)">روابط الفوتر</a></li>

                            <li class="slide">
                                <a href="{{ route('admin.footer-links.index') }}"
                                   class="side-menu__item {{ request()->routeIs('admin.footer-links.index') ? 'active' : '' }}">
                                    القائمة
                                </a>
                            </li>

                            <li class="slide">
                                <a href="{{ route('admin.footer-links.create') }}"
                                   class="side-menu__item {{ request()->routeIs('admin.footer-links.create') ? 'active' : '' }}">
                                    إضافة رابط
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <!-- 📱 Social Links -->
                @can('social-links.view')
                    <li class="slide has-sub {{ $socialOpen ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ $socialOpen ? 'active' : '' }}">
                            <i class="bi bi-share side-menu__icon"></i>
                            <span class="side-menu__label">روابط التواصل</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1" style="{{ $socialOpen ? 'display:block' : '' }}">
                            <li class="slide side-menu__label1"><a href="javascript:void(0)">روابط التواصل</a></li>

                            <li class="slide">
                                <a href="{{ route('admin.social-links.index') }}"
                                   class="side-menu__item {{ request()->routeIs('admin.social-links.index') ? 'active' : '' }}">
                                    القائمة
                                </a>
                            </li>

                            <li class="slide">
                                <a href="{{ route('admin.social-links.create') }}"
                                   class="side-menu__item {{ request()->routeIs('admin.social-links.create') ? 'active' : '' }}">
                                    إضافة رابط
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li class="slide">
                    <a href="{{ route('admin.site-settings.edit',1) }}"
                       class="side-menu__item {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear side-menu__icon"></i>
                        <span class="side-menu__label">إعدادات الموقع</span>
                    </a>
                </li>

            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>

        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
