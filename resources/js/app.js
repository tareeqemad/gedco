// resources/js/app.js
import Swal from 'sweetalert2';
import AOS from 'aos';

window.Swal = Swal;

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        easing: 'ease-out',
        once: true,
        offset: 100,
        mirror: false
    });

    // رسائل Laravel
    const success = document.querySelector('meta[name="success-message"]')?.getAttribute('content');
    const error   = document.querySelector('meta[name="error-message"]')?.getAttribute('content');

    if (success) {
        Swal.fire({
            icon: 'success',
            title: 'تم بنجاح',
            text: success,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'حدث خطأ',
            text: error,
            confirmButtonText: 'حسنًا'
        });
    }
});
