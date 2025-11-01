@extends('layouts.admin-auth-modern')
@section('body')
    <div class="login-container">
        <div class="login-wrapper">
            <div class="logo-section">
                <div class="logo-container">
                    <div class="logo-circle">
                        <img src="{{ asset('assets/admin/images/brand-logos/gedco_logo.png') }}"
                             alt="GEDCO Logo"
                             class="logo-image"
                             id="logoImage"
                             onerror="this.style.display='none';document.getElementById('logoFallback').style.display='block'">
                        <i class="fas fa-bolt logo-fallback" id="logoFallback" style="display:none"></i>
                    </div>
                    <div class="company-name">كهرباء غزة</div>
                    <div class="company-description">شبكة ذكية لخدمة افضل</div>
                </div>
            </div>

            <div class="form-section">
                <div class="welcome-text">
                    <h2>مرحباً بك</h2>
                    <p>سجل دخولك للوصول إلى النظام</p>
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
                        <label class="form-label">البريد الإلكتروني</label>
                        <div class="input-group">
                            <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com"
                                   value="{{ old('email') }}" required autocomplete="username">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">كلمة المرور</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="أدخل كلمة المرور" required autocomplete="current-password">
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle" id="passwordToggle"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">تذكرني</label>
                        </div>

                        <!--<a href="#" class="forgot-password">نسيت كلمة المرور؟</a>-->


                    </div>

                    <button type="submit" class="login-btn" id="submitBtn">
                        <span class="btn-text">تسجيل الدخول</span>
                        <div class="btn-loading"><i class="fas fa-spinner fa-spin"></i></div>
                    </button>
                </form>

                <div class="form-footer">
                    <p>جميع الحقوق محفوظة © كهرباء غزة</p>
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
                    icon.classList.toggle('fa-eye'); icon.classList.toggle('fa-eye-slash');
                });
            }

            if(loginForm && submitBtn){
                loginForm.addEventListener('submit',function(){
                    submitBtn.classList.add('loading');
                    submitBtn.disabled=true;
                });
            }
        });
    </script>
@endpush
