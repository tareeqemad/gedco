<header class="transparent scroll-light">
    <div class="container">
        <div class="row"><div class="col-md-12">
                <div class="de-flex sm-pt10">
                    <div class="de-flex-col">
                        <div id="logo">
                            <a href="{{ route('site.home') }}">
                                <img class="logo-main"   src="{{ asset('assets/site/images/logo-white.webp') }}" alt="">
                                <img class="logo-scroll" src="{{ asset('assets/site/images/logo-dark.webp') }}"  alt="">
                                <img class="logo-mobile" src="{{ asset('assets/site/images/logo-white.webp') }}" alt="">
                            </a>
                        </div>
                    </div>

                    <div class="de-flex-col header-col-mid">
                        {{-- Main Menu --}}
                        <ul id="mainmenu">
                            <li><a class="menu-item" href="{{ route('site.home') }}">الرئيسية</a></li>

                            <li>
                                <a class="menu-item" href="#services">الخدمات</a>
                                <ul>
                                    <li><a href="https://eservices.gedco.ps/" target="_blank">الخدمات الالكترونية</a></li>
                                    <li><a href="#">المواصفات والمقايس</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="menu-item" href="#">الشركة</a>
                                <ul>
                                    <li><a href="#who-us"> من نحن</a></li>
                                    <li><a href="#section-why-choose-us">لماذا تختارنا</a></li>
                                    <li><a href="#">فريقنا</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="menu-item" href="#">الاعلانات</a>
                                <ul>
                                    <li><a href="{{ route('site.advertisements.index') }}">الإعلانات والوظائف</a></li>
                                    <li><a href="#">العطاءات</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="menu-item" href="{{ route('site.home') }}#contact-footer">اتصل بنا</a>
                            </li>
                        </ul>
                    </div>

                    <div class="de-flex-col">
                        <div class="menu_side_area">
                            <a href="https://gazaappeal.gedco.ps/ar/donate" target="_blank" rel="noopener" class="donate-btn-epic">
                                <span class="donate-btn-content">
                                    <i class="fa-solid fa-hand-holding-heart"></i>
                                    <span class="donate-text">تبرع الآن</span>
                                </span>
                                <span class="donate-btn-glow"></span>
                                <span class="donate-btn-particles"></span>
                            </a>
                            <div id="menu-btn">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div></div>
    </div>
</header>
