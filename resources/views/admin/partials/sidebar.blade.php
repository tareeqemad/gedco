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
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>

            @php
                $segment = request()->segment(2);
                $isActive = fn($routes) => request()->routeIs($routes) ? 'active' : '';
                $isOpen = fn($routes) => request()->routeIs($routes) ? 'open' : '';
            @endphp

            <ul class="main-menu">

                <!-- 1. لوحة التحكم -->
                <li class="slide {{ $isActive('admin.dashboard') }}">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item">
                        <i class="bi bi-house-door side-menu__icon"></i>
                        <span class="side-menu__label">لوحة التحكم</span>
                    </a>
                </li>

                <!-- 2. المستخدمون -->
                @can('users.view')
                    <li class="slide has-sub {{ $isOpen('admin.users.*') }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ $isActive('admin.users.*') }}">
                            <i class="bi bi-people side-menu__icon"></i>
                            <span class="side-menu__label">المستخدمون</span>
                            <i class="fe fe-chevron-left side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1" style="{{ $isOpen('admin.users.*') ? 'display:block' : '' }}">
                            <li class="slide"><a href="{{ route('admin.users.index') }}" class="side-menu__item {{ $isActive('admin.users.index') }}">قائمة المستخدمين</a></li>
                            <li class="slide"><a href="{{ route('admin.users.create') }}" class="side-menu__item {{ $isActive('admin.users.create') }}">إضافة مستخدم</a></li>
                        </ul>
                    </li>
                @endcan

                <!-- 3. إدارة المحتوى -->
                <li class="slide__category"><span class="side-menu__label text-muted text-xs opacity-70">إدارة المحتوى</span></li>

                @can('sliders.view')
                    <li class="slide {{ $isActive('admin.sliders.*') }}">
                        <a href="{{ route('admin.sliders.index') }}" class="side-menu__item">
                            <i class="bi bi-images side-menu__icon"></i>
                            <span class="side-menu__label">السلايدر</span>
                        </a>
                    </li>
                @endcan

                @can('about.view')
                    <li class="slide {{ $isActive('admin.about.*') }}">
                        <a href="{{ route('admin.about.index') }}" class="side-menu__item">
                            <i class="bi bi-info-circle side-menu__icon"></i>
                            <span class="side-menu__label">من نحن</span>
                        </a>
                    </li>
                @endcan

                @can('why.view')
                    <li class="slide {{ $isActive('admin.why.*') }}">
                        <a href="{{ route('admin.why.index') }}" class="side-menu__item">
                            <i class="bi bi-lightning-charge side-menu__icon"></i>
                            <span class="side-menu__label">لماذا تختارنا</span>
                        </a>
                    </li>
                @endcan

                @can('jobs.view')
                    <li class="slide has-sub {{ $isOpen('admin.jobs.*') }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ $isActive('admin.jobs.*') }}">
                            <i class="bi bi-briefcase side-menu__icon"></i>
                            <span class="side-menu__label">الوظائف</span>
                            <i class="fe fe-chevron-left side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1" style="{{ $isOpen('admin.jobs.*') ? 'display:block' : '' }}">
                            <li class="slide"><a href="{{ route('admin.jobs.index') }}" class="side-menu__item {{ $isActive('admin.jobs.index') }}">قائمة الوظائف</a></li>
                            <li class="slide"><a href="{{ route('admin.jobs.create') }}" class="side-menu__item {{ $isActive('admin.jobs.create') }}">إضافة وظيفة</a></li>
                        </ul>
                    </li>
                @endcan

                @can('impact-stats.view')
                    <li class="slide has-sub {{ $isOpen('admin.impact-stats.*') }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ $isActive('admin.site.impact-stats.*') }}">
                            <i class="bi bi-graph-up-arrow side-menu__icon"></i>
                            <span class="side-menu__label">إحصائيات الخسائر</span>
                            <i class="fe fe-chevron-left side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1" style="{{ $isOpen('admin.impact-stats.*') ? 'display:block' : '' }}">
                            <li class="slide">
                                <a href="{{ route('admin.impact-stats.index') }}"
                                   class="side-menu__item {{ $isActive('admin.impact-stats.index') }}">
                                    القائمة
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <!-- 4. إعدادات الموقع -->
                <li class="slide__category mt-3"><span class="side-menu__label text-muted text-xs opacity-70">إعدادات الموقع</span></li>

                <li class="slide {{ $isActive('admin.site-settings.*') }}">
                    <a href="{{ route('admin.site-settings.edit', 1) }}" class="side-menu__item">
                        <i class="bi bi-gear side-menu__icon"></i>
                        <span class="side-menu__label">إعدادات عامة</span>
                    </a>
                </li>

                @role('super-admin')

                <li class="slide has-sub {{ $isOpen('admin.social-links.*') }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ $isActive('admin.social-links.*') }}">
                        <i class="bi bi-share side-menu__icon"></i>
                        <span class="side-menu__label">روابط التواصل</span>
                        <i class="fe fe-chevron-left side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1" style="{{ $isOpen('admin.social-links.*') ? 'display:block' : '' }}">
                        <li class="slide"><a href="{{ route('admin.social-links.index') }}" class="side-menu__item {{ $isActive('admin.social-links.index') }}">القائمة</a></li>
                        <li class="slide"><a href="{{ route('admin.social-links.create') }}" class="side-menu__item {{ $isActive('admin.social-links.create') }}">إضافة رابط</a></li>
                    </ul>
                </li>
                @endrole

                <!-- 5. إدارة النظام (للسوبر أدمن فقط) -->
                @role('super-admin')
                <li class="slide__category mt-3"><span class="side-menu__label text-muted text-xs opacity-70">إدارة النظام</span></li>

                <li class="slide {{ $isActive('admin.permissions.*') }}">
                    <a href="{{ route('admin.permissions.index') }}" class="side-menu__item">
                        <i class="bi bi-shield-lock side-menu__icon"></i>
                        <span class="side-menu__label">الصلاحيات</span>
                    </a>
                </li>

                <li class="slide has-sub {{ $isOpen('admin.roles.*') }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ $isActive('admin.roles.*') }}">
                        <i class="bi bi-person-badge side-menu__icon"></i>
                        <span class="side-menu__label">الأدوار</span>
                        <i class="fe fe-chevron-left side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1" style="{{ $isOpen('admin.roles.*') ? 'display:block' : '' }}">
                        <li class="slide"><a href="{{ route('admin.roles.index') }}" class="side-menu__item {{ $isActive('admin.roles.index') }}">قائمة الأدوار</a></li>
                        <li class="slide"><a href="{{ route('admin.roles.create') }}" class="side-menu__item {{ $isActive('admin.roles.create') }}">إضافة دور</a></li>
                    </ul>
                </li>
                @endrole

            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
    </div>
</aside>
