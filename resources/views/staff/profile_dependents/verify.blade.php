<!DOCTYPE html>
<html lang="ar" dir="{{ $direction ?? 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث بيانات الموظف</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icons/icon.ico') }}">
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap' . (($direction ?? 'rtl') === 'rtl' ? '.rtl' : '') . '.min.css') }}" rel="stylesheet">

    <style>
        @font-face { font-family: 'Tajawal'; font-weight: 400; src: url('{{ asset("assets/fonts/tajawal/Tajawal-Regular.ttf") }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Tajawal'; font-weight: 500; src: url('{{ asset("assets/fonts/tajawal/Tajawal-Medium.ttf") }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Tajawal'; font-weight: 700; src: url('{{ asset("assets/fonts/tajawal/Tajawal-Bold.ttf") }}') format('truetype'); font-display: swap; }

        :root {
            --accent: #ef7c4c;
            --accent-dark: #c65a28;
            --surface: #fff7f2;
            --muted: #8c6f61;
            --text: #2f2b28;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', Arial, sans-serif;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f4f1;
            color: var(--text);
            padding: 1.5rem;
        }

        .verify-page {
            width: 100%;
            max-width: 460px;
        }

        /* ---- Logo ---- */
        .logo-area {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .logo-area img {
            height: 64px;
            margin-bottom: .75rem;
        }

        .logo-area h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent-dark);
        }

        .logo-area p {
            font-size: .85rem;
            color: var(--muted);
            margin-top: .25rem;
        }

        /* ---- Card ---- */
        .verify-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid rgba(239,124,76,.15);
            box-shadow: 0 8px 32px rgba(239,124,76,.1);
            overflow: hidden;
        }

        .card-header-strip {
            height: 5px;
            background: linear-gradient(90deg, var(--accent), #f6a67a, var(--accent));
        }

        .card-body {
            padding: 2rem 2rem 2rem;
        }

        /* ---- Alerts ---- */
        .alert {
            border-radius: 12px;
            padding: .85rem 1rem;
            font-size: .88rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }

        .alert-info {
            background: #eef6ff;
            border: 1px solid #cfe3ff;
            color: #0b4b8a;
        }

        .alert-danger {
            background: #fdeeee;
            border: 1px solid #f5b1b1;
            color: #8a1f1f;
        }

        .alert ul { margin: 0; padding-right: 1rem; }
        .alert li { line-height: 1.7; }

        /* ---- Fields ---- */
        .field-group {
            margin-bottom: 1.15rem;
        }

        .field-group label {
            display: block;
            font-size: .88rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: .4rem;
        }

        .field-group label .req { color: #e63946; }

        .field-group .input-wrap {
            position: relative;
        }

        .field-group .input-wrap .icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 14px;
            width: 20px;
            height: 20px;
            color: var(--accent);
            pointer-events: none;
            opacity: .7;
        }

        .field-group input {
            width: 100%;
            border: 1.5px solid rgba(239,124,76,.2);
            border-radius: 14px;
            padding: .85rem 1rem .85rem 2.75rem;
            font-size: .95rem;
            font-family: inherit;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            direction: ltr;
            text-align: right;
        }

        [dir="rtl"] .field-group input {
            padding: .85rem 2.75rem .85rem 1rem;
        }

        .field-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3.5px rgba(239,124,76,.12);
        }

        .field-group input.is-invalid {
            border-color: #e63946;
            box-shadow: 0 0 0 3px rgba(230,57,70,.1);
        }

        .field-group .hint {
            font-size: .78rem;
            color: #aaa;
            margin-top: .35rem;
        }

        /* ---- Button ---- */
        .btn-verify {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            width: 100%;
            border: none;
            background: linear-gradient(135deg, #ef7c4c, #f49a6a);
            color: #fff;
            padding: .95rem;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: transform .15s, box-shadow .25s;
            margin-top: .5rem;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(239,124,76,.3);
        }

        .btn-verify:active {
            transform: translateY(0);
        }

        .btn-verify svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        /* ---- Footer ---- */
        .footer-note {
            text-align: center;
            margin-top: 1.25rem;
            font-size: .78rem;
            color: #b5a59d;
        }

        /* ---- Mobile ---- */
        @media (max-width: 480px) {
            body { padding: 1rem .75rem; }
            .card-body { padding: 1.5rem 1.25rem 1.25rem; }
            .steps { padding: 1rem 1.25rem 1.5rem; }
            .logo-area img { height: 52px; }
            .logo-area h1 { font-size: 1.1rem; }
        }
    </style>
</head>
<body>

<div class="verify-page">

    {{-- Logo + Title --}}
    <div class="logo-area">
        <img src="{{ asset('assets/site/images/logos/logo-dark.webp') }}" alt="GEDCO">
        <h1>تحديث بيانات الموظفين</h1>
        <p>شركة توزيع كهرباء محافظات غزة</p>
    </div>

    {{-- Main Card --}}
    <div class="verify-card">
        <div class="card-header-strip"></div>

        <div class="card-body">

            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('staff.profile.verify') }}" class="needs-validation" novalidate>
                @csrf

                {{-- National ID --}}
                <div class="field-group">
                    <label>رقم الهوية <span class="req">*</span></label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                        <input type="text"
                               name="national_id"
                               value="{{ old('national_id', $prefill_national_id ?? '') }}"
                               required
                               maxlength="9"
                               pattern="\d{9}"
                               inputmode="numeric"
                               placeholder="أدخل رقم الهوية (9 أرقام)"
                               class="{{ $errors->has('national_id') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                {{-- Employee Number --}}
                <div class="field-group">
                    <label>الرقم الوظيفي <span class="req">*</span></label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <line x1="19" y1="8" x2="19" y2="14"/>
                            <line x1="22" y1="11" x2="16" y2="11"/>
                        </svg>
                        <input type="text"
                               name="employee_number"
                               value="{{ old('employee_number', $prefill_employee_number ?? '') }}"
                               required
                               maxlength="4"
                               pattern="\d{1,4}"
                               inputmode="numeric"
                               placeholder="أدخل الرقم الوظيفي"
                               class="{{ $errors->has('employee_number') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-verify">
                    <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    تحقق من الهوية
                </button>
            </form>
        </div>

    </div>

    <div class="footer-note">
        شركة توزيع الكهرباء &mdash; تحديث البيانات الشخصية
    </div>
</div>

<script>
(() => {
    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            } else {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = 'جارٍ التحقق...';
                }
            }
            form.classList.add('was-validated');
        }, false);
    });

    const numOnly = (el, max) => {
        if (!el) return;
        el.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, max);
        });
    };

    numOnly(document.querySelector('input[name="national_id"]'), 9);
    numOnly(document.querySelector('input[name="employee_number"]'), 4);
})();
</script>
</body>
</html>
