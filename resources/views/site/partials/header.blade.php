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
                                    <li><a href="">كلمة مدير عامالشركة</a></li>
                                    <li><a href="https://www.gedcoboard.com/" target="_blank" rel="noopener">مجلس الإدارة</a></li>

                                </ul>
                            </li>
                            <li><a class="menu-item" href="{{ route('site.news') }}">الاخبار</a></li>
                            <li>
                                <a class="menu-item" href="#">الاعلانات</a>
                                <ul>
                                    <li><a href="{{ route('site.advertisements.index') }}">الإعلانات والوظائف</a></li>
                                    <li><a href="{{ route('site.tenders') }}">العطاءات</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="menu-item" href="{{ route('site.home') }}#contact-footer">اتصل بنا</a>
                            </li>
                        </ul>
                    </div>

                    <div class="de-flex-col">
                        <div class="menu_side_area">
                            <div class="header-search-mini d-none d-lg-block">
                                <div class="header-search-mini__trigger" id="header-search-toggle" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true" aria-label="بحث">
                                    <span class="header-search-mini__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 3c-3.314 0-6 2.686-6 6s2.686 6 6 6c1.46 0 2.8-.518 3.833-1.382l3.775 3.774a.75.75 0 1 1-1.06 1.06l-3.775-3.774A5.977 5.977 0 0 0 15 9c0-3.314-2.686-6-6-6Zm-4.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0Z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="header-search-mini__popover" id="header-search-popover">
                                    <form
                                        id="header-search-form"
                                        class="header-search-mini__form"
                                        autocomplete="off"
                                        data-resolve-endpoint="{{ route('site.search') }}"
                                        data-suggestions-endpoint="{{ route('site.search.suggestions') }}"
                                    >
                                        <label for="header-search-input" class="header-search-mini__label">ابحث في الموقع</label>
                                        <div class="header-search-mini__input-wrap">
                                            <input
                                                id="header-search-input"
                                                name="q"
                                                type="search"
                                                minlength="2"
                                                placeholder="ابحث هنا"
                                                class="header-search-mini__input"
                                            >
                                            <button type="submit" class="header-search-mini__submit" aria-label="نفّذ البحث">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 3a6 6 0 0 1 4.708 9.708l3.792 3.792a.75.75 0 1 1-1.06 1.06l-3.792-3.792A6 6 0 1 1 9 3Zm0 1.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                        <ul class="header-search-mini__results" id="header-search-results" role="listbox"></ul>
                                        <p class="header-search-mini__message" id="header-search-message" role="status" aria-live="polite"></p>
                                    </form>
                                </div>
                            </div>
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
