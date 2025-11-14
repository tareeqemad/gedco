<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق قبل التعديل</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

    <style>
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 400; src: url('{{ asset('assets/fonts/cairo/Cairo-Regular.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 500; src: url('{{ asset('assets/fonts/cairo/Cairo-Medium.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 600; src: url('{{ asset('assets/fonts/cairo/Cairo-SemiBold.ttf') }}') format('truetype'); font-display: swap; }
        @font-face { font-family: 'Cairo'; font-style: normal; font-weight: 700; src: url('{{ asset('assets/fonts/cairo/Cairo-Bold.ttf') }}') format('truetype'); font-display: swap; }

        :root{ --surface:#fff7f2; --surface-alt:#fff1e6; --border:#f1b08d; --accent:#ef7c4c; --accent-dark:#c65a28; --text:#2f2b28; --muted:#8c6f61; }
        body{ margin:0; font-family:"Cairo",Arial,sans-serif; background:#ffffff; color:var(--text); padding:2rem 0; }
        .form-shell{ width:min(720px,100%); margin:0 auto; background:#fff; border-radius:24px; border:1px solid rgba(239,124,76,.2); box-shadow:0 20px 50px rgba(239,124,76,.15); overflow:hidden; }
        .form-header{ padding:2rem 1rem; background:linear-gradient(135deg, rgba(239,124,76,.15), rgba(239,124,76,.05)); border-bottom:1px solid rgba(239,124,76,.15); text-align:center; }
        .form-header h1{ margin:0; color:var(--accent-dark); font-weight:700; }
        .form-section{ padding:2rem; border-bottom:1px solid rgba(239,124,76,.08); }
        .form-section:last-of-type{ border-bottom:none; }
        .section-title{ display:inline-flex; align-items:center; gap:.5rem; background:rgba(239,124,76,.1); color:var(--accent-dark); padding:.4rem 1.1rem; border-radius:999px; font-weight:700; margin-bottom:1.25rem; }
        label.field{ display:flex; flex-direction:column; gap:.35rem; font-weight:600; color:var(--muted); }
        label.field span{ font-size:.9rem; }
        input,select{ border:1px solid rgba(239,124,76,.2); border-radius:14px; padding:.8rem 1rem; font-size:.95rem; background:#fff; transition:border-color .2s, box-shadow .2s; }
        input:focus,select:focus{ outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(239,124,76,.18); }
        .submit-row{ padding:2rem; text-align:center; }
        .submit-row button{ background:linear-gradient(135deg, #ef7c4c, #f49a6a); border:none; color:#fff; padding:.9rem 2.75rem; border-radius:18px; font-size:1rem; font-weight:700; }
        .submit-row button:hover{ transform:translateY(-2px); box-shadow:0 12px 20px rgba(239,124,76,.25); }
        .alert{ border-radius:14px; padding:1rem 1.1rem; margin:1rem 2rem 0; font-weight:600; }
        .alert-info{ background:#eef6ff; border:1px solid #b6d5ff; color:#1b4d91; }
        .alert-danger{ background:#fdeeee; border:1px solid #f5b1b1; color:#8a1f1f; }
        .grid{ display:grid; gap:1rem 1.5rem; }
        .grid-2{ grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); }
        .req-star{ color:#d9534f; margin-inline-start:.2rem; }

    </style>
</head>
<body>
<div class="form-shell">
    <div class="form-header">
        <h1>التحقّق قبل التعديل</h1>
    </div>

    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="lh-lg">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('staff.profile.verify') }}" class="needs-validation" novalidate>
        @csrf

        <section class="form-section">
            <div class="section-title">بيانات التحقق</div>

            <div class="grid grid-2">
                <label class="field">
                    <span>طريقة التعريف <span class="req-star">*</span></span>
                    <select name="by" required>
                        @php $by = old('by', $prefill_by ?? 'national_id'); @endphp
                        <option value="national_id" {{ $by==='national_id'?'selected':'' }}>رقم الهوية</option>
                        <option value="employee_number" {{ $by==='employee_number'?'selected':'' }}>الرقم الوظيفي</option>
                    </select>
                </label>

                <label class="field">
                    <span>القيمة <span class="req-star">*</span></span>
                    <input type="text" name="value" value="{{ old('value', $prefill_value ?? '') }}" required>
                    <small class="text-muted">رقم الهوية (9 أرقام) أو الرقم الوظيفي (<= 1999).</small>
                </label>

                <label class="field">
                    <span>كلمة المرور <span class="req-star">*</span></span>
                    <input type="password" name="password" id="password" minlength="6" required autocomplete="current-password">
                    <div style="margin-top:.5rem;">
                        <label style="display:inline-flex;align-items:center;gap:.4rem;cursor:pointer;">
                            <input type="checkbox" id="toggle-password"> إظهار كلمة المرور
                        </label>
                    </div>
                </label>
            </div>
        </section>

        <section class="submit-row">
            <button type="submit">تحقّق ومتابعة</button>

            <div class="mt-3">
                <a href="{{ route('staff.profile.create') }}"
                   class="btn btn-light"
                   style="border:1px solid rgba(239,124,76,.4);
                  color:var(--accent-dark);
                  padding:.75rem 2.5rem;
                  border-radius:18px;
                  font-weight:700;
                  background:rgba(239,124,76,.1);
                  transition:all .2s;">
                    العودة لصفحة الإدخال
                </a>
            </div>
        </section>
    </form>
</div>

<script>
    (() => {
        const toggle = document.getElementById('toggle-password');
        const pass = document.getElementById('password');
        toggle?.addEventListener('change', () => {
            pass.type = toggle.checked ? 'text' : 'password';
        });

        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
</body>
</html>
