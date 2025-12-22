@extends('layouts.admin-auth-modern')
@section('body')
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
    <div class="login-container">
        <!-- Language Switcher -->
        <div class="language-switcher-container">
            <div class="language-switcher-dropdown">
                <button type="button" class="language-switcher-toggle" id="languageSwitcherToggle">
                    <img src="{{ $isRtl ? $palestineFlag : $usFlag }}" alt="{{ $isRtl ? 'العربية' : 'English' }}" class="flag-icon">
                    <span>{{ $isRtl ? 'عربي' : 'ENG' }}</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="language-switcher-menu" id="languageSwitcherMenu">
                    <form action="{{ route('direction.set', 'rtl') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="language-option {{ $isRtl ? 'active' : '' }}">
                            <img src="{{ $palestineFlag }}" alt="العربية" class="flag-icon-small">
                            <span>العربية</span>
                            @if($isRtl)
                                <i class="bi bi-check"></i>
                            @endif
                        </button>
                    </form>
                    <form action="{{ route('direction.set', 'ltr') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="language-option {{ !$isRtl ? 'active' : '' }}">
                            <img src="{{ $usFlag }}" alt="English" class="flag-icon-small">
                            <span>English</span>
                            @if(!$isRtl)
                                <i class="bi bi-check"></i>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="login-wrapper">
            <div class="logo-section">
                <div class="logo-container">
                    <div class="logo-circle">
                        <img src="{{ asset('assets/admin/images/brand-logos/gedco_logo.png') }}"
                             alt="GEDCO Logo"
                             class="logo-image"
                             id="logoImage"
                             onerror="this.style.display='none';document.getElementById('logoFallback').style.display='block'">
                        <i class="bi bi-lightning-charge logo-fallback" id="logoFallback" style="display:none"></i>
                    </div>
                    <div class="company-name">{{ __('admin.auth.company_name') }}</div>
                    <div class="company-description">{{ __('admin.auth.company_slogan') }}</div>
                </div>
            </div>

            <div class="form-section">
                <div class="welcome-text">
                    <h2>{{ __('admin.auth.welcome') }}</h2>
                    <p>{{ __('admin.auth.welcome_subtitle') }}</p>
                </div>

                {{-- Laravel flashes/errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-warning">{{ session('error') }}</div>
                @endif
                @if (session('status'))
                    <div class="alert alert-info">{{ session('status') }}</div>
                @endif

                <form class="login-form" id="loginForm" method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.auth.email_label') }}</label>
                        <div class="input-group">
                            <input type="email" name="email" id="email" class="form-control" 
                                   placeholder="{{ __('admin.auth.email_placeholder') }}"
                                   value="{{ old('email') }}" required autocomplete="username">
                            <i class="bi bi-person input-icon"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('admin.auth.password_label') }}</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="{{ __('admin.auth.password_placeholder') }}" 
                                   required autocomplete="current-password">
                            <i class="bi bi-lock input-icon"></i>
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="{{ __('admin.auth.password_label') }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">{{ __('admin.auth.remember_me') }}</label>
                        </div>
                        <!-- <a href="#" class="forgot-password">{{ __('admin.auth.forgot_password') }}</a> -->
                    </div>

                    <button type="submit" class="login-btn" id="submitBtn">
                        <span class="btn-text">{{ __('admin.auth.login_button') }}</span>
                        <div class="btn-loading">
                            <i class="bi bi-arrow-repeat spinner"></i>
                            <span class="ms-2">{{ __('admin.auth.login_button_loading') }}</span>
                        </div>
                    </button>
                </form>

                <div class="form-footer">
                    <p>{{ __('admin.auth.footer_copyright') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            const passwordToggle=document.getElementById('passwordToggle');
            const passwordInput=document.getElementById('password');
            const loginForm=document.getElementById('loginForm');
            const submitBtn=document.getElementById('submitBtn');

            if(passwordToggle && passwordInput){
                passwordToggle.addEventListener('click',function(){
                    const type=passwordInput.getAttribute('type')==='password'?'text':'password';
                    passwordInput.setAttribute('type',type);
                    const icon=this.querySelector('i');
                    icon.classList.toggle('bi-eye'); icon.classList.toggle('bi-eye-slash');
                });
            }

            if(loginForm && submitBtn){
                let isSubmitting = false;
                
                loginForm.addEventListener('submit',function(e){
                    // منع الإرسال المتعدد
                    if(isSubmitting || submitBtn.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // التحقق من صحة البيانات قبل الإرسال
                    const emailInput = document.getElementById('email');
                    const passwordInput = document.getElementById('password');
                    
                    if(!emailInput || !emailInput.value || !emailInput.value.trim()) {
                        e.preventDefault();
                        emailInput?.focus();
                        emailInput?.classList.add('is-invalid');
                        return false;
                    }
                    
                    if(!passwordInput || !passwordInput.value) {
                        e.preventDefault();
                        passwordInput?.focus();
                        passwordInput?.classList.add('is-invalid');
                        return false;
                    }
                    
                    // تعطيل الإرسال المتعدد
                    isSubmitting = true;
                    
                    // تعطيل الزر وإظهار حالة التحميل
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    submitBtn.setAttribute('aria-busy', 'true');
                    
                    // جعل الحقول readonly بدلاً من disabled (لإرسال القيم)
                    if(emailInput) {
                        emailInput.setAttribute('readonly', 'readonly');
                        emailInput.style.cursor = 'not-allowed';
                    }
                    if(passwordInput) {
                        passwordInput.setAttribute('readonly', 'readonly');
                        passwordInput.style.cursor = 'not-allowed';
                    }
                    
                    // إعادة تفعيل الزر في حالة فشل الإرسال (مثل خطأ في الشبكة)
                    setTimeout(function() {
                        if(isSubmitting) {
                            isSubmitting = false;
                            submitBtn.classList.remove('loading');
                            submitBtn.disabled = false;
                            submitBtn.removeAttribute('aria-busy');
                            if(emailInput) {
                                emailInput.removeAttribute('readonly');
                                emailInput.style.cursor = '';
                            }
                            if(passwordInput) {
                                passwordInput.removeAttribute('readonly');
                                passwordInput.style.cursor = '';
                            }
                        }
                    }, 30000); // 30 ثانية كحد أقصى
                });
            }

            // Language switcher toggle
            const languageToggle = document.getElementById('languageSwitcherToggle');
            const languageMenu = document.getElementById('languageSwitcherMenu');
            const languageDropdown = languageToggle?.closest('.language-switcher-dropdown');

            if(languageToggle && languageMenu && languageDropdown){
                languageToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    languageDropdown.classList.toggle('active');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!languageDropdown.contains(e.target)) {
                        languageDropdown.classList.remove('active');
                    }
                });
            }
        });
    </script>
@endpush
