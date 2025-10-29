<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','تسجيل الدخول - كهرباء غزة')</title>

    <link id="style" href="{{ asset('assets/admin/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/admin/css/auth-modern.css') }}" rel="stylesheet">


    @stack('styles')
</head>
<body>
@yield('body')

{{-- Bootstrap --}}
<script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
    function showNotification(message, type) {
        const existing = document.querySelectorAll('.notification');
        existing.forEach(n => n.remove());
        const n = document.createElement('div');
        n.className = `notification notification-${type}`;
        const iconMap = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };
        n.innerHTML = `<div class="notification-content"><i class="fas ${iconMap[type] || 'fa-info-circle'}"></i><span>${message}</span><button class="notification-close"><i class="fas fa-times"></i></button></div>`;
        document.body.appendChild(n);
        const closeBtn = n.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            n.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => n.remove(), 300);
        });
        setTimeout(() => {
            if (n.parentNode) {
                n.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => n.remove(), 300);
            }
        }, 4000);
    }
</script>
@stack('scripts')
</body>
</html>
