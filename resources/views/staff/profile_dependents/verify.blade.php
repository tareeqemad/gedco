<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث بيانات الموظف</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site/css/staff-common.css') }}">

    <style>
        .verify-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .verify-note-card {
            background: #fff7f2;
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            border: 1px solid #ffe0cc;
            font-size: 0.95rem;
            line-height: 1.9;
        }

        .verify-note-card h2 {
            font-size: 1.05rem;
            margin-bottom: .75rem;
            font-weight: 700;
        }

        .verify-note-card ul {
            padding-right: 1.2rem;
            margin-bottom: 0;
        }

        .verify-note-card li {
            margin-bottom: .35rem;
        }

        .bullet-green::before,
        .bullet-purple::before,
        .bullet-orange::before {
            content: "• ";
            font-size: 1.2rem;
        }
        .bullet-green::before { color: #2a9d8f; }
        .bullet-purple::before { color: #9b5de5; }
        .bullet-orange::before { color: #f77f00; }

        .sub-note {
            font-size: 0.86rem;
            color: #666;
            margin-top: .9rem;
        }

        .req-star {
            color: #e63946;
        }
    </style>
</head>
<body>
<div class="form-shell">
    <div class="form-header">
        <h1>تحديث بيانات الموظف</h1>
        <p class="text-muted mb-0" style="font-size:.9rem;">
            قبل عرض نموذج التعديل، نحتاج أولاً للتحقق من هويتك عن طريق رقم الهوية.
        </p>
    </div>

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

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

        <div class="verify-wrapper">

            {{-- 1) منطقة تسجيل الدخول للتحقق --}}
            <section class="form-section">
                <div class="section-title">تسجيل دخول للتحقّق</div>

                <label class="field">
                    <span>رقم الهوية <span class="req-star">*</span></span>
                    <input type="text"
                           name="national_id"
                           value="{{ old('national_id', $prefill_national_id ?? '') }}"
                           required
                           maxlength="9"
                           pattern="\d{9}"
                           inputmode="numeric">
                    <small class="text-muted">الرجاء إدخال رقم الهوية المكون من 9 أرقام بشكل صحيح.</small>
                </label>

                <div class="submit-row" style="margin-top:1.5rem;">
                    <button type="submit">تسجيل الدخول للتحقّق</button>
                </div>
            </section>

            {{-- 2) شرح بسيط لما سيحدث بعد إدخال رقم الهوية --}}
            <section class="verify-note-card">
                <h2>ماذا سيحدث بعد إدخال رقم الهوية؟</h2>
                <ul>
                    <li class="bullet-green">
                        نقوم بالتحقق من رقم الهوية في قاعدة البيانات.
                    </li>
                    <li class="bullet-purple">
                        إذا وُجد ملف مطابق، سيتم فتح صفحة تحتوي على جميع بياناتك الحالية لتتمكن من مراجعتها.
                    </li>
                    <li class="bullet-purple">
                        يمكنك تعديل البيانات المسموح بتعديلها فقط، ثم حفظ التغييرات.
                    </li>
                    <li class="bullet-orange">
                        إذا لم يتم العثور على أي بيانات، فسيظهر لك إشعار يوضح ذلك.
                    </li>
                </ul>

                <p class="sub-note">
                    يرجى التأكد من كتابة رقم الهوية بدقة.
                </p>
            </section>
        </div>
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
