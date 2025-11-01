<header class="transparent scroll-light">
    <div class="container">
        <div class="row"><div class="col-md-12">
                <div class="de-flex sm-pt10">
                    <div class="de-flex-col">
                        <div id="logo">
                            {{-- خلي اللوجو يروح للهوم بدل index.html --}}
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
                                    <li><a href="http://213.244.76.228/" target="_blank">الخدمات الالكترونية</a></li>
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
                                    <li><a href="{{ route('site.jobs') }}">الوظائف</a></li>
                                    <li><a href="{{ route('site.tenders') }}">العطاءات</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="menu-item" href="#contact-footer">اتصل بنا</a>
                            </li>

                        </ul>
                    </div>

                    <div class="de-flex-col">
                        <div class="menu_side_area">
                            <a href="https://gazaappeal.gedco.ps/ar/donate" target="_blank" rel="noopener"
                               class="donate-btn d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill text-white fw-bold shadow-lg transition-all"
                               style="background: linear-gradient(135deg, #dc3545, #c82333); font-size: 1rem; letter-spacing: 0.5px; text-decoration: none; min-width: 160px;">
                                <i class="fa-solid fa-hand-holding-heart fs-5"></i>
                                <span>تبرع للشركة</span>
                            </a>
                        </div>
                        {{--
                           <div id="btn-extra">
                            <span></span><span></span>
                        </div>
                         --}}
                    </div>
                </div>
            </div></div>
    </div>
</header>
