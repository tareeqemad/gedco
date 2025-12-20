<!DOCTYPE html>
<html lang="ar" dir="{{ $direction ?? 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <title>لا يحق لك الدخول</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icons/icon.ico') }}">
    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap' . (($direction ?? 'rtl') === 'rtl' ? '.rtl' : '') . '.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site/css/staff-common.css') }}">
    <style>
        .error-shell {
            max-width: 600px;
            margin: 80px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            padding: 2rem 2.5rem;
            text-align: center;
        }
        .error-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .error-code {
            font-size: 3rem;
            font-weight: 800;
            color: #e63946;
            margin-bottom: .5rem;
        }
        .error-text {
            color: #555;
            margin-bottom: 1.5rem;
        }
        .error-actions a {
            display: inline-block;
            margin: 0 .25rem;
            padding: .55rem 1.4rem;
            border-radius: 999px;
            text-decoration: none;
            font-size: .95rem;
        }
        .btn-main {
            background: #ef7c4c;
            color: #fff;
        }
        .btn-secondary {
            background: #f3f3f3;
            color: #333;
        }
    </style>
</head>
<body>

<div class="error-shell">
    <div class="error-code">403</div>
    <div class="error-title">لا يحق لك الدخول إلى هذه الصفحة</div>
    <p class="error-text">
        لا تملك صلاحية عرض هذا السجل.<br>
        في حال كنت تعتقد أن هذا خطأ، الرجاء مراجعة الجهة المسؤولة.
    </p>

    <div class="error-actions">
        <a href="{{ route('staff.profile.verify.form') }}" class="btn-main">التحقق برقم الهوية</a>
        <a href="{{ url('/') }}" class="btn-secondary">العودة للصفحة الرئيسية</a>
    </div>
</div>

</body>
</html>
