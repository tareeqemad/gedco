<header class="transparent scroll-light">
    <div class="container">
        <div class="row"><div class="col-md-12">
                <div class="de-flex sm-pt10">
                    <div class="de-flex-col">
                        <div id="logo">
                            @php
                                $currentDir = session('direction', 'rtl');
                                $isRtl = $currentDir === 'rtl';
                                
                                // Determine logo paths based on direction
                                if ($isRtl) {
                                    $logoMain = asset('assets/site/images/logos/logo-white.webp');
                                    $logoScroll = asset('assets/site/images/logos/logo-dark.webp');
                                    $logoMobile = asset('assets/site/images/logos/logo-white.webp');
                                } else {
                                    // LTR: use logo-ltr.png for all states
                                    $logoMain = asset('assets/site/images/logos/logo-ltr.png');
                                    $logoScroll = asset('assets/site/images/logos/logo-ltr.png');
                                    $logoMobile = asset('assets/site/images/logos/logo-ltr.png');
                                }
                            @endphp
                            <a href="{{ route('site.home') }}">
                                <img class="logo-main"   src="{{ $logoMain }}" alt="">
                                <img class="logo-scroll" src="{{ $logoScroll }}" alt="">
                                <img class="logo-mobile" src="{{ $logoMobile }}" alt="">
                            </a>
                        </div>
                    </div>

                    <div class="de-flex-col header-col-mid">
                        @php
                            
                            // Flags paths
                            $palestineFlagPath = public_path('assets/admin/images/flags/palestine_flag');
                            $palestineFlag = file_exists($palestineFlagPath . '.jpg') 
                                ? asset('assets/admin/images/flags/palestine_flag.jpg')
                                : (file_exists($palestineFlagPath . '.svg')
                                    ? asset('assets/admin/images/flags/palestine_flag.svg')
                                    : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDQwIDI0Ij48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iOCIgZmlsbD0iIzAwMCIvPjxyZWN0IHk9IjgiIHdpZHRoPSI0MCIgaGVpZ2h0PSI4IiBmaWxsPSIjRkZGIi8+PHJlY3QgeT0iMTYiIHdpZHRoPSI0MCIgaGVpZ2h0PSI4IiBmaWxsPSIjMDA3QTNEIi8+PHBhdGggZD0iTSAwIDAgTCAxMy4zMyAxMiBMIDAgMjQgWiIgZmlsbD0iI0NFMTEyNiIvPjwvc3ZnPg==');
                            
                            $usFlagPath = public_path('assets/admin/images/flags/us_flag');
                            $usFlag = file_exists($usFlagPath . '.jpg') && filesize($usFlagPath . '.jpg') > 1000
                                ? asset('assets/admin/images/flags/us_flag.jpg')
                                : (file_exists($usFlagPath . '.svg')
                                    ? asset('assets/admin/images/flags/us_flag.svg')
                                    : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDQwIDI0Ij48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iMjQiIGZpbGw9IiNGRkYiLz48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iMS44NDYiIGZpbGw9IiNCMjIyMzQiLz48cmVjdCB5PSIzLjY5MiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjEuODQ2IiBmaWxsPSIjQjIyMjM0Ii8+PHJlY3QgeT0iNy4zODQiIHdpZHRoPSI0MCIgaGVpZ2h0PSIxLjg0NiIgZmlsbD0iI0IyMjIzNCIvPjxyZWN0IHk9IjExLjA3NiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjEuODQ2IiBmaWxsPSIjQjIyMjM0Ii8+PHJlY3QgeT0iMTQuNzY4IiB3aWR0aD0iNDAiIGhlaWdodD0iMS44NDYiIGZpbGw9IiNCMjIyMzQiLz48cmVjdCB5PSIxOC40NiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjEuODQ2IiBmaWxsPSIjQjIyMjM0Ii8+PHJlY3QgeT0iMjIuMTUyIiB3aWR0aD0iNDAiIGhlaWdodD0iMS44NDYiIGZpbGw9IiNCMjIyMzQiLz48cmVjdCB3aWR0aD0iMTYiIGhlaWdodD0iOS4yMyIgZmlsbD0iIzNDM0I2RSIvPjwvc3ZnPg==');
                        @endphp
                        {{-- Main Menu --}}
                        <ul id="mainmenu">
                            <li><a class="menu-item" href="{{ route('site.home') }}">{{ __('common.home') }}</a></li>

                            <li>
                                <a class="menu-item" href="#services">{{ __('common.services') }}</a>
                                <ul>
                                    <li><a href="https://eservices.gedco.ps/" target="_blank">{{ __('common.e_services') }}</a></li>
                                    <li><a href="#">{{ __('common.specs') }}</a></li>
                                </ul>
                            </li>


                            <li>
                                <a class="menu-item" href="#">{{ __('common.company') }}</a>
                                <ul>
                                    <li><a href="#who-us">{{ __('common.about_us') }}</a></li>
                                    <li><a href="#section-why-choose-us">{{ __('common.why_choose') }}</a></li>
                                    <li><a href="{{route('company.general-manager-message')}}">{{ __('common.gm_message') }}</a></li>
                                    <li><a href="https://www.gedcoboard.com/" target="_blank" rel="noopener">{{ __('common.board') }}</a></li>

                                </ul>
                            </li>
                            <li><a class="menu-item" href="{{ route('site.news') }}">{{ __('common.news') }}</a></li>
                            <li>
                                <a class="menu-item" href="#">{{ __('common.advertisements') }}</a>
                                <ul>
                                    <li><a href="{{ route('site.advertisements.index') }}">{{ __('common.jobs') }}</a></li>
                                    <li><a href="{{ route('site.tenders') }}">{{ __('common.tenders') }}</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="menu-item" href="{{ route('site.home') }}#contact-footer">{{ __('common.contact') }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="de-flex-col">
                        <div class="menu_side_area">
                            @php
                                $currentDir = session('direction', 'rtl');
                            @endphp
                            {{-- Donate Button (CTA) - أول شيء في أقصى اليسار --}}
                            <a href="https://gazaappeal.gedco.ps/ar/donate" target="_blank" rel="noopener" class="donate-btn-epic">
                                <span class="donate-btn-content">
                                    <i class="fa-solid fa-hand-holding-heart"></i>
                                    <span class="donate-text">{{ __('common.donate_now') }}</span>
                                </span>
                                <span class="donate-btn-glow"></span>
                                <span class="donate-btn-particles"></span>
                            </a>
                            
                            {{-- Search Button - في المنتصف --}}
                            <div class="header-search-mini d-none d-lg-block">
                                <div class="header-search-mini__trigger" id="header-search-toggle" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true" aria-label="{{ $isRtl ? 'بحث' : 'Search' }}">
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
                                        <label for="header-search-input" class="header-search-mini__label">{{ __('common.search_site') }}</label>
                                        <div class="header-search-mini__input-wrap">
                                            <input
                                                id="header-search-input"
                                                name="q"
                                                type="search"
                                                minlength="2"
                                                placeholder="{{ __('common.search_here') }}"
                                                class="header-search-mini__input"
                                            >
                                            <button type="submit" class="header-search-mini__submit" aria-label="{{ __('common.search') }}">
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
                            
                            {{-- Language Dropdown - في أقصى اليمين --}}
                            <div class="language-dropdown-wrapper d-none d-lg-inline-block">
                                <div class="language-dropdown">
                                    <button 
                                        type="button"
                                        class="language-dropdown-toggle" 
                                        id="language-dropdown-toggle"
                                    >
                                        <img src="{{ $isRtl ? $palestineFlag : $usFlag }}" alt="{{ $isRtl ? 'العربية' : 'English' }}" class="language-flag-icon">
                                        <span>{{ $isRtl ? __('common.arabic') : __('common.english') }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>
                                    <div class="language-dropdown-menu" id="language-dropdown-menu">
                                        <form action="{{ route('direction.set', 'rtl') }}" method="POST">
                                            @csrf
                                            <button 
                                                type="submit"
                                                class="language-option {{ $currentDir === 'rtl' ? 'active' : '' }}"
                                            >
                                                <img src="{{ $palestineFlag }}" alt="العربية" class="language-flag-icon-small">
                                                <span>{{ __('common.arabic') }}</span>
                                                @if($currentDir === 'rtl')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="language-check-icon">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('direction.set', 'ltr') }}" method="POST">
                                            @csrf
                                            <button 
                                                type="submit"
                                                class="language-option {{ $currentDir === 'ltr' ? 'active' : '' }}"
                                            >
                                                <img src="{{ $usFlag }}" alt="English" class="language-flag-icon-small">
                                                <span>{{ __('common.english') }}</span>
                                                @if($currentDir === 'ltr')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="language-check-icon">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const toggle = document.getElementById('language-dropdown-toggle');
                                    const menu = document.getElementById('language-dropdown-menu');
                                    const dropdown = document.querySelector('.language-dropdown');
                                    
                                    if (toggle && menu) {
                                        toggle.addEventListener('click', function(e) {
                                            e.stopPropagation();
                                            dropdown.classList.toggle('open');
                                        });
                                        
                                        document.addEventListener('click', function(e) {
                                            if (!dropdown.contains(e.target)) {
                                                dropdown.classList.remove('open');
                                            }
                                        });
                                    }
                                });
                            </script>
                            {{-- Mobile Language Dropdown تم نقله إلى داخل mobile menu --}}
                            
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
