<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','تسجيل الدخول - كهرباء غزة')</title>

{{-- Bootstrap + Font Awesome + Cairo Font من الـ CDN زي التصميم الجديد --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('assets/admin/css/auth-modern.css') }}" rel="stylesheet">


{{-- مساحة لستايلات إضافية لو احتجتها لاحقاً --}}
@stack('styles')
</head>
<body>
@yield('body')

{{-- Bootstrap --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Utilities مشتركة (نوتيفيكيشن جاهز للاستخدام) --}}
<script>
    function showNotification(message,type){
        const existing=document.querySelectorAll('.notification');
        existing.forEach(n=>n.remove());
        const n=document.createElement('div');
        n.className=`notification notification-${type}`;
        const iconMap={success:'fa-check-circle',error:'fa-exclamation-circle',info:'fa-info-circle',warning:'fa-exclamation-triangle'};
        n.innerHTML=`<div class="notification-content"><i class="fas ${iconMap[type]||'fa-info-circle'}"></i><span>${message}</span><button class="notification-close"><i class="fas fa-times"></i></button></div>`;
        document.body.appendChild(n);
        const closeBtn=n.querySelector('.notification-close');
        closeBtn.addEventListener('click',()=>{n.style.animation='slideOutRight 0.3s ease-out';setTimeout(()=>n.remove(),300);});
        setTimeout(()=>{if(n.parentNode){n.style.animation='slideOutRight 0.3s ease-out';setTimeout(()=>n.remove(),300);}},4000);
    }
</script>
{{-- سكربتات الصفحات الفرعية --}}
@stack('scripts')
</body>
</html>
