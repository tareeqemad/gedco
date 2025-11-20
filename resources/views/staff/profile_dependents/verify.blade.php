<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق قبل التعديل</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/site/css/staff-common.css') }}">

</head>
<body>
<div class="form-shell">
    <div class="form-header">
        <h1>تحديث بيانات الموظف</h1>
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
            <div class="section-title">تسجيل دخول</div>

            <label class="field">
                <span>رقم الهوية <span class="req-star">*</span></span>
                <input type="text"
                       name="national_id"
                       value="{{ old('national_id', $prefill_national_id ?? '') }}"
                       required
                       maxlength="9"
                       pattern="\d{9}"
                       inputmode="numeric">
                <small class="text-muted">الرجاء إدخال رقم الهوية المكوّن من 9 أرقام.</small>
            </label>
        </section>

        <section class="submit-row">
            <button type="submit">تحقّق ومتابعة</button>
        </section>
    </form>
</div>

<script>
    (() => {
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });

        const idInput = document.querySelector('input[name="national_id"]');
        if (idInput) {
            idInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 9);
            });
        }
    })();
</script>
</body>
</html>
