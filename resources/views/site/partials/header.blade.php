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
                                <a class="menu-item" href="{{ route('site.services') }}">الخدمات</a>
                                <ul>
                                    <li><a href="{{ route('site.services') }}">تبرع للشركة</a></li>
                                    <li><a href="{{ route('site.services') }}">الخدمات الالكترونية</a></li>
                                    <li><a href="{{ route('site.services') }}">المواصفات والمقايس</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="menu-item" href="#">الشركة</a>
                                <ul>
                                    <li><a href="{{ route('site.about') }}">من نحن</a></li>
                                    <li><a href="{{ route('site.team') }}">فريقنا</a></li>
                                    <li><a href="{{ route('site.careers') }}">الوظائف</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="menu-item" href="#">الاعلانات</a>
                                <ul>
                                    <li><a href="{{ route('site.jobs') }}">الوظائف</a></li>
                                    <li><a href="{{ route('site.tenders') }}">العطائات</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="menu-item" href="#contact-footer">اتصل بنا</a>
                            </li>

                        </ul>
                    </div>

                    <div class="de-flex-col">
                        {{-- زر القائمة الجانبية إن وجد --}}
                        <div id="btn-extra">
                            <span></span><span></span>
                        </div>
                    </div>
                </div>
            </div></div>
    </div>
</header>
