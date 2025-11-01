import AOS from 'aos';
import 'aos/dist/aos.css'; // استيراد الـ CSS

// تهيئة AOS بعد تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,     // مدة الحركة
        easing: 'ease-out', // نوع التسارع
        once: true,        // الحركة مرة واحدة فقط
        offset: 100,       // بدء الحركة قبل 100px
        delay: 0
    });
});
