<footer class="footer mt-auto py-3 bg-white text-center border-top">
    <div class="container">
        <span class="d-inline-flex align-items-center gap-1">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <span>
                © {{ date('Y') }}
                <a href="https://www.gedco.ps" class="text-primary fw-semibold text-decoration-underline">
                    {{ __('admin.auth.company_name') }}
                </a>
                — {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
            </span>
        </span>
    </div>
</footer>
