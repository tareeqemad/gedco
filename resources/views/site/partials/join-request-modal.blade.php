{{--
    Reusable Join Request Modal
    Usage: @include('site.partials.join-request-modal', ['isRtl' => $isRtl])

    Trigger any element with: data-jr-open
    Optional id override: @include('site.partials.join-request-modal', ['modalId' => 'jrModalCustom'])
--}}
@php
    $jrModalId = $modalId ?? 'jrModal';
    $jrIsRtl = $isRtl ?? (session('direction', 'rtl') === 'rtl');
@endphp

@once
@push('styles')
<style>
    /* ============ Join Request Modal ============ */
    .jr-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .jr-modal-backdrop.is-open {
        display: flex;
        opacity: 1;
    }

    .jr-modal {
        width: 100%;
        max-width: 520px;
        background: #ffffff;
        border-radius: 1.25rem;
        box-shadow: 0 25px 80px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        transform: translateY(24px) scale(0.96);
        opacity: 0;
        transition: transform 0.35s cubic-bezier(0.2, 0.9, 0.3, 1.2), opacity 0.25s ease;
        max-height: calc(100vh - 2rem);
        max-height: calc(100dvh - 2rem);
        display: grid;
        grid-template-rows: auto minmax(0, 1fr) auto;
        grid-template-columns: 100%;
    }
    /* Body height bounded — guarantees overflow → scroll. */
    .jr-modal-body {
        max-height: min(420px, calc(100dvh - 220px));
    }
    @media (max-height: 720px) {
        .jr-modal-body { max-height: calc(100dvh - 180px); }
    }
    /* When success state is on, hide everything and only show success */
    .jr-modal.is-success {
        grid-template-rows: 1fr;
    }
    .jr-modal.is-success > .jr-modal-header,
    .jr-modal.is-success > .jr-modal-body,
    .jr-modal.is-success > .jr-modal-footer { display: none !important; }
    .jr-modal:not(.is-success) > .jr-success { display: none !important; }
    .jr-modal-backdrop.is-open .jr-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .jr-modal-header {
        position: relative;
        padding: 1.6rem 1.75rem 1.4rem;
        background: linear-gradient(135deg, #F85C00 0%, #ff8f3b 100%);
        color: #ffffff !important;
        flex-shrink: 0;
    }
    .jr-modal-header h3,
    .jr-modal-header h3 span,
    .jr-modal-header p {
        color: #ffffff !important;
    }
    .jr-modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: 0.2px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .jr-modal-header h3 i {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff !important;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        line-height: 1;
        flex-shrink: 0;
    }
    .jr-modal-header p {
        margin: 0.4rem 0 0;
        font-size: 0.85rem;
        opacity: 0.95;
        line-height: 1.6;
    }
    .jr-modal-close {
        position: absolute;
        top: 0.85rem;
        background: rgba(255, 255, 255, 0.18);
        border: none;
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s ease, transform 0.2s ease;
    }
    .jr-modal-close:hover { background: rgba(255, 255, 255, 0.32); transform: rotate(90deg); }
    html[dir="rtl"] .jr-modal-close { left: 0.9rem; }
    html[dir="ltr"] .jr-modal-close { right: 0.9rem; }

    .jr-modal-body {
        padding: 1.5rem 1.75rem 1.25rem;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: 0;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        touch-action: pan-y;
    }
    .jr-modal-body::-webkit-scrollbar { width: 8px; }
    .jr-modal-body::-webkit-scrollbar-track { background: transparent; }
    .jr-modal-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
    .jr-modal-body::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    .jr-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        margin: 0.25rem 0 0.85rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .jr-section-title i { color: #F85C00; font-size: 0.85rem; }
    .jr-section-title:not(:first-child) { margin-top: 1.1rem; }

    .jr-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1.1rem;
    }
    @media (max-width: 520px) { .jr-row { grid-template-columns: 1fr; } }

    .jr-field { margin-bottom: 1.1rem; }
    .jr-row .jr-field { margin-bottom: 0; }
    .jr-field label {
        display: block;
        font-size: 0.88rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.55rem;
    }
    .jr-field label .req { color: #F85C00; margin: 0 0.15rem; }

    /* Custom select with icons */
    .jr-select-wrap { position: relative; }
    .jr-select {
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        padding: 0.85rem 2.6rem 0.85rem 1rem;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }
    html[dir="rtl"] .jr-select { padding: 0.85rem 1rem 0.85rem 2.6rem; }
    .jr-select:focus {
        outline: none;
        border-color: #F85C00;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(248, 92, 0, 0.12);
    }
    .jr-select-caret {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        font-size: 0.8rem;
    }
    html[dir="rtl"] .jr-select-caret { left: 1rem; }
    html[dir="ltr"] .jr-select-caret { right: 1rem; }

    .jr-input {
        width: 100%;
        padding: 0.85rem 1rem;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        color: #1f2937;
        transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }
    .jr-input:focus {
        outline: none;
        border-color: #F85C00;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(248, 92, 0, 0.12);
    }

    /* Conditional referrer field — animated reveal */
    .jr-referrer-field {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transform: translateY(-6px);
        transition: max-height 0.35s ease, opacity 0.25s ease, transform 0.3s ease, margin 0.25s ease;
        margin-bottom: 0;
    }
    .jr-referrer-field.is-shown {
        max-height: 200px;
        opacity: 1;
        transform: translateY(0);
        margin-bottom: 1.1rem;
    }

    .jr-error {
        display: none;
        margin-top: 0.4rem;
        font-size: 0.78rem;
        color: #dc2626;
        font-weight: 600;
    }
    .jr-field.has-error .jr-error { display: block; }
    .jr-field.has-error .jr-select,
    .jr-field.has-error .jr-input {
        border-color: #fecaca;
        background: #fef2f2;
    }

    /* Source option preview chips (visual hint) */
    .jr-source-hint {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-top: 0.55rem;
    }
    .jr-source-hint span {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.55rem;
        background: #fff7ed;
        color: #9a3412;
        border: 1px solid #fed7aa;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .jr-source-hint span i { font-size: 0.72rem; }

    .jr-modal-footer {
        display: flex;
        gap: 0.65rem;
        padding: 1rem 1.75rem 1.4rem;
        border-top: 1px solid #f1f5f9;
        background: #fcfcfd;
        flex-shrink: 0;
    }
    .jr-btn {
        flex: 1;
        padding: 0.85rem 1.2rem;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
    }
    .jr-btn-cancel {
        background: #f1f5f9;
        color: #475569;
    }
    .jr-btn-cancel:hover { background: #e2e8f0; color: #1e293b; }

    .jr-btn-primary {
        background: linear-gradient(135deg, #F85C00, #ff8f3b);
        color: #fff;
        box-shadow: 0 6px 18px rgba(248, 92, 0, 0.32);
    }
    .jr-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(248, 92, 0, 0.42);
    }
    .jr-btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .jr-spinner {
        display: none;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: jr-spin 0.7s linear infinite;
    }
    .jr-btn.is-loading .jr-spinner { display: inline-block; }
    .jr-btn.is-loading .jr-btn-label { opacity: 0.85; }

    @keyframes jr-spin {
        to { transform: rotate(360deg); }
    }

    /* Success state — visibility controlled by .jr-modal.is-success */
    .jr-success {
        text-align: center;
        padding: 2.2rem 1.75rem 2.4rem;
    }
    .jr-success .check {
        width: 78px;
        height: 78px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff;
        font-size: 2.2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 12px 28px rgba(16, 185, 129, 0.35);
        animation: jr-pop 0.45s cubic-bezier(0.2, 0.9, 0.3, 1.4);
    }
    .jr-success h4 {
        font-size: 1.2rem;
        color: #0f172a;
        font-weight: 800;
        margin: 0 0 0.4rem;
    }
    .jr-success p {
        color: #475569;
        font-size: 0.92rem;
        margin: 0;
        line-height: 1.65;
    }

    @keyframes jr-pop {
        0% { transform: scale(0.4); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    @media (max-width: 480px) {
        .jr-modal-header { padding: 1.3rem 1.25rem 1.1rem; }
        .jr-modal-body { padding: 1.2rem 1.25rem 0.4rem; }
        .jr-modal-footer { padding: 0.85rem 1.25rem 1.2rem; }
        .jr-modal-header h3 { font-size: 1.1rem; }
    }

    /* Lock the page scroll while modal is open */
    html.jr-no-scroll {
        overflow: hidden !important;
        overscroll-behavior: contain !important;
    }
    html.jr-no-scroll body {
        overflow: hidden !important;
    }
</style>
@endpush
@endonce

<div class="jr-modal-backdrop" id="{{ $jrModalId }}"
     role="dialog" aria-modal="true" aria-labelledby="{{ $jrModalId }}-title" aria-hidden="true">
    <div class="jr-modal" dir="{{ $jrIsRtl ? 'rtl' : 'ltr' }}">
        <div class="jr-modal-header">
            <h3 id="{{ $jrModalId }}-title">
                <i class="fas fa-handshake"></i>
                <span>{{ $jrIsRtl ? 'كيف تعرفت علينا؟' : 'How did you hear about us?' }}</span>
            </h3>
            <p>
                {{ $jrIsRtl
                    ? 'ساعدنا في تحسين خدماتنا — اختر الطريقة التي تعرفت بها على المركز.'
                    : 'Help us improve — let us know how you discovered the center.' }}
            </p>
            <button type="button" class="jr-modal-close" data-jr-close
                    aria-label="{{ $jrIsRtl ? 'إغلاق' : 'Close' }}">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="jr-modal-body" data-lenis-prevent data-scroll-lock-scrollable>
                <div class="jr-section-title">
                    <i class="fas fa-user-circle"></i>
                    {{ $jrIsRtl ? 'بيانات مقدّم الطلب' : 'Applicant information' }}
                </div>

                <div class="jr-row">
                    <div class="jr-field" data-jr-field="applicant_name">
                        <label for="{{ $jrModalId }}-applicant-name">
                            {{ $jrIsRtl ? 'الاسم الكامل' : 'Full name' }}
                            <span class="req">*</span>
                        </label>
                        <input type="text"
                               id="{{ $jrModalId }}-applicant-name"
                               name="applicant_name"
                               class="jr-input"
                               maxlength="150"
                               autocomplete="name"
                               placeholder="{{ $jrIsRtl ? 'اكتب اسمك الكامل' : 'Your full name' }}">
                        <div class="jr-error" data-jr-error="applicant_name"></div>
                    </div>
                    <div class="jr-field" data-jr-field="applicant_phone">
                        <label for="{{ $jrModalId }}-applicant-phone">
                            {{ $jrIsRtl ? 'رقم الهاتف' : 'Phone number' }}
                            <span class="req">*</span>
                        </label>
                        <input type="tel"
                               id="{{ $jrModalId }}-applicant-phone"
                               name="applicant_phone"
                               class="jr-input"
                               maxlength="30"
                               autocomplete="tel"
                               placeholder="{{ $jrIsRtl ? 'مثال: 0599xxxxxx' : 'e.g. +970...' }}"
                               dir="ltr"
                               style="text-align: {{ $jrIsRtl ? 'right' : 'left' }};">
                        <div class="jr-error" data-jr-error="applicant_phone"></div>
                    </div>
                </div>

                <div class="jr-row">
                    <div class="jr-field" data-jr-field="applicant_email">
                        <label for="{{ $jrModalId }}-applicant-email">
                            {{ $jrIsRtl ? 'البريد الإلكتروني' : 'Email' }}
                        </label>
                        <input type="email"
                               id="{{ $jrModalId }}-applicant-email"
                               name="applicant_email"
                               class="jr-input"
                               maxlength="150"
                               autocomplete="email"
                               placeholder="example@domain.com"
                               dir="ltr"
                               style="text-align: {{ $jrIsRtl ? 'right' : 'left' }};">
                        <div class="jr-error" data-jr-error="applicant_email"></div>
                    </div>
                    <div class="jr-field" data-jr-field="company_name">
                        <label for="{{ $jrModalId }}-company-name">
                            {{ $jrIsRtl ? 'اسم الشركة (اختياري)' : 'Company (optional)' }}
                        </label>
                        <input type="text"
                               id="{{ $jrModalId }}-company-name"
                               name="company_name"
                               class="jr-input"
                               maxlength="200"
                               autocomplete="organization"
                               placeholder="{{ $jrIsRtl ? 'اسم الشركة أو المؤسسة' : 'Company / organization' }}">
                        <div class="jr-error" data-jr-error="company_name"></div>
                    </div>
                </div>

                <div class="jr-section-title">
                    <i class="fas fa-question-circle"></i>
                    {{ $jrIsRtl ? 'كيف تعرفت علينا' : 'How you found us' }}
                </div>

                <div class="jr-field" data-jr-field="source">
                    <label for="{{ $jrModalId }}-source">
                        {{ $jrIsRtl ? 'كيف تعرفت علينا' : 'How did you find us' }}
                        <span class="req">*</span>
                    </label>
                    <div class="jr-select-wrap">
                        <select id="{{ $jrModalId }}-source" name="source" class="jr-select" required>
                            <option value="" disabled selected>
                                {{ $jrIsRtl ? '— اختر خيارًا —' : '— Select an option —' }}
                            </option>
                            <option value="friend_employee" data-icon="fa-user-friends">
                                {{ $jrIsRtl ? '👥  صديق / موظف' : '👥  Friend / Employee' }}
                            </option>
                            <option value="social_media" data-icon="fa-hashtag">
                                {{ $jrIsRtl ? '📱  وسائل التواصل الاجتماعي' : '📱  Social Media' }}
                            </option>
                            <option value="website" data-icon="fa-globe">
                                {{ $jrIsRtl ? '🌐  الموقع الإلكتروني' : '🌐  Website' }}
                            </option>
                            <option value="advertisement" data-icon="fa-bullhorn">
                                {{ $jrIsRtl ? '📢  إعلان' : '📢  Advertisement' }}
                            </option>
                            <option value="other" data-icon="fa-ellipsis-h">
                                {{ $jrIsRtl ? '✨  أخرى' : '✨  Other' }}
                            </option>
                        </select>
                        <span class="jr-select-caret"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="jr-error" data-jr-error="source"></div>
                </div>

                <div class="jr-referrer-field" data-jr-field="referrer_name">
                    <label for="{{ $jrModalId }}-referrer">
                        {{ $jrIsRtl ? 'من هو الشخص؟' : 'Who is the person?' }}
                        <span class="req">*</span>
                    </label>
                    <input type="text"
                           id="{{ $jrModalId }}-referrer"
                           name="referrer_name"
                           class="jr-input"
                           maxlength="150"
                           placeholder="{{ $jrIsRtl ? 'اسم الموظف أو الصديق' : 'Name of employee or friend' }}">
                    <div class="jr-error" data-jr-error="referrer_name"></div>
                </div>
            </div>

            <div class="jr-modal-footer">
                <button type="button" class="jr-btn jr-btn-cancel" data-jr-close>
                    {{ $jrIsRtl ? 'إلغاء' : 'Cancel' }}
                </button>
                <button type="button" class="jr-btn jr-btn-primary" data-jr-submit>
                    <span class="jr-spinner"></span>
                    <span class="jr-btn-label">
                        <i class="fas fa-paper-plane"></i>
                        {{ $jrIsRtl ? 'إرسال' : 'Send' }}
                    </span>
                </button>
            </div>

        <div class="jr-success">
            <div class="check"><i class="fas fa-check"></i></div>
            <h4>{{ $jrIsRtl ? 'تم الإرسال بنجاح!' : 'Submitted successfully!' }}</h4>
            <p>
                {{ $jrIsRtl
                    ? 'شكرًا لك. سنتواصل معك قريبًا لاستكمال طلب الانضمام.'
                    : 'Thank you. We will get in touch shortly to follow up on your request.' }}
            </p>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
(function () {
    const ENDPOINT = @json(route('site.gca.join-request'));
    const REDIRECT_URL = 'https://gedco.ps/electricalCertification/admin/login';

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    let openCount = 0;
    let savedScrollbarWidth = 0;

    // Try to find a Lenis instance the site might have created.
    // Common patterns: window.lenis, window.Lenis instance, or events to stop it.
    function getLenis() {
        return window.lenis || window.Lenis && window.Lenis.instance || null;
    }
    function stopSmoothScroll() {
        const lenis = getLenis();
        if (lenis && typeof lenis.stop === 'function') {
            try { lenis.stop(); } catch (e) {}
        }
        // Skrollr fallback
        if (window.s && typeof window.s.stopAnimateTo === 'function') {
            try { window.s.stopAnimateTo(); } catch (e) {}
        }
    }
    function startSmoothScroll() {
        const lenis = getLenis();
        if (lenis && typeof lenis.start === 'function') {
            try { lenis.start(); } catch (e) {}
        }
    }

    function lockBodyScroll() {
        if (openCount === 0) {
            savedScrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            if (savedScrollbarWidth > 0) {
                document.body.style.paddingInlineEnd = savedScrollbarWidth + 'px';
            }
            document.documentElement.classList.add('jr-no-scroll');
            stopSmoothScroll();
        }
        openCount++;
    }
    function unlockBodyScroll() {
        openCount = Math.max(0, openCount - 1);
        if (openCount === 0) {
            document.documentElement.classList.remove('jr-no-scroll');
            document.body.style.paddingInlineEnd = '';
            startSmoothScroll();
        }
    }

    document.querySelectorAll('.jr-modal-backdrop').forEach(initModal);

    function initModal(backdrop) {
        const modal       = backdrop.querySelector('.jr-modal');
        const body        = backdrop.querySelector('.jr-modal-body');
        const sourceSel   = backdrop.querySelector('select[name="source"]');
        const refField    = backdrop.querySelector('[data-jr-field="referrer_name"]');
        const refInput    = refField.querySelector('input[name="referrer_name"]');
        const nameInput   = backdrop.querySelector('input[name="applicant_name"]');
        const phoneInput  = backdrop.querySelector('input[name="applicant_phone"]');
        const emailInput  = backdrop.querySelector('input[name="applicant_email"]');
        const companyInput= backdrop.querySelector('input[name="company_name"]');
        const submitBtn   = backdrop.querySelector('[data-jr-submit]');
        const closeEls    = backdrop.querySelectorAll('[data-jr-close]');
        const allInputs   = [nameInput, phoneInput, emailInput, companyInput, refInput];

        let isOpen = false;

        const triggers = document.querySelectorAll('[data-jr-open="' + backdrop.id + '"], [data-jr-open=""]');
        triggers.forEach(t => t.addEventListener('click', function (e) {
            e.preventDefault();
            open();
        }));

        closeEls.forEach(el => el.addEventListener('click', close));
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) close();
        });

        // Bullet-proof scroll handling: capture wheel/touch events and manually
        // scroll the modal body. This bypasses any smooth-scroll library (Lenis,
        // Skrollr) that may be intercepting wheel events at the document level.
        backdrop.addEventListener('wheel', function (e) {
            // Stop the event so Lenis/Skrollr never see it.
            e.stopPropagation();
            e.preventDefault();
            // If the cursor is over the body, scroll the body manually.
            const targetEl = e.target.nodeType === 1 ? e.target : e.target.parentElement;
            const inBody = targetEl && targetEl.closest && targetEl.closest('.jr-modal-body');
            if (inBody) {
                body.scrollTop += e.deltaY;
            }
        }, { passive: false, capture: true });

        // Touch scroll for mobile — replicate the same logic with touchmove deltas.
        let touchStartY = 0;
        backdrop.addEventListener('touchstart', function (e) {
            if (e.touches.length === 1) touchStartY = e.touches[0].clientY;
        }, { passive: true });

        backdrop.addEventListener('touchmove', function (e) {
            const targetEl = e.target.nodeType === 1 ? e.target : e.target.parentElement;
            const inBody = targetEl && targetEl.closest && targetEl.closest('.jr-modal-body');
            if (inBody) {
                // Allow native touch scroll inside body — it works because data-lenis-prevent
                // is set, but we also stop propagation as defence-in-depth.
                e.stopPropagation();
                return;
            }
            // Outside body — block scroll entirely.
            e.preventDefault();
            e.stopPropagation();
        }, { passive: false, capture: true });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isOpen) close();
            if (e.key === 'Enter' && isOpen && e.target && e.target.tagName === 'INPUT') {
                e.preventDefault();
                onSubmit(e);
            }
        });

        sourceSel.addEventListener('change', function () {
            toggleReferrer(sourceSel.value === 'friend_employee');
            clearError('source');
        });
        refInput.addEventListener('input',   () => clearError('referrer_name'));
        nameInput.addEventListener('input',  () => clearError('applicant_name'));
        phoneInput.addEventListener('input', () => clearError('applicant_phone'));
        emailInput.addEventListener('input', () => clearError('applicant_email'));

        submitBtn.addEventListener('click', onSubmit);

        function open() {
            if (isOpen) return;
            resetForm();
            isOpen = true;
            backdrop.classList.add('is-open');
            backdrop.setAttribute('aria-hidden', 'false');
            lockBodyScroll();
            setTimeout(() => {
                nameInput.focus();
                runDiagnostics();
            }, 350);
        }

        function runDiagnostics() {
            const body = backdrop.querySelector('.jr-modal-body');
            const html = document.documentElement;
            const docBody = document.body;
            const wrapper = document.getElementById('wrapper');
            const cs = (el) => el ? getComputedStyle(el) : null;

            const data = {
                viewport: {
                    innerWidth:  window.innerWidth,
                    innerHeight: window.innerHeight,
                    scrollY:     window.scrollY,
                },
                html: {
                    classList:        Array.from(html.classList),
                    clientHeight:     html.clientHeight,
                    scrollHeight:     html.scrollHeight,
                    overflow:         cs(html).overflow,
                    overflowY:        cs(html).overflowY,
                    height:           cs(html).height,
                    canScroll:        html.scrollHeight > html.clientHeight,
                },
                body: {
                    classList:        Array.from(docBody.classList),
                    clientHeight:     docBody.clientHeight,
                    scrollHeight:     docBody.scrollHeight,
                    overflow:         cs(docBody).overflow,
                    overflowY:        cs(docBody).overflowY,
                    height:           cs(docBody).height,
                    display:          cs(docBody).display,
                    canScroll:        docBody.scrollHeight > docBody.clientHeight,
                },
                wrapper: wrapper ? {
                    clientHeight:     wrapper.clientHeight,
                    scrollHeight:     wrapper.scrollHeight,
                    overflow:         cs(wrapper).overflow,
                    overflowY:        cs(wrapper).overflowY,
                    canScroll:        wrapper.scrollHeight > wrapper.clientHeight,
                } : 'no #wrapper',
                modal: {
                    clientHeight: modal.clientHeight,
                    scrollHeight: modal.scrollHeight,
                    maxHeight:    cs(modal).maxHeight,
                    display:      cs(modal).display,
                    gridRows:     cs(modal).gridTemplateRows,
                },
                modalBody: {
                    clientHeight:  body.clientHeight,
                    scrollHeight:  body.scrollHeight,
                    offsetHeight:  body.offsetHeight,
                    maxHeight:     cs(body).maxHeight,
                    overflow:      cs(body).overflow,
                    overflowY:     cs(body).overflowY,
                    height:        cs(body).height,
                    overflowing:   body.scrollHeight > body.clientHeight,
                    canScrollDown: body.scrollHeight - body.scrollTop - body.clientHeight,
                },
                openCount: openCount,
            };

            console.log('%c[JR-MODAL DIAGNOSTICS]', 'background:#F85C00;color:#fff;padding:2px 6px;border-radius:3px;font-weight:bold;');
            console.log(JSON.stringify(data, null, 2));

            // Live test: try to scroll the body programmatically
            const before = body.scrollTop;
            body.scrollTop = 50;
            const after = body.scrollTop;
            console.log('%c[SCROLL TEST]', 'background:#0EA5E9;color:#fff;padding:2px 6px;border-radius:3px;font-weight:bold;',
                'before=' + before + ' after-set-50=' + after,
                after > 0 ? '✅ body IS scrollable' : '❌ body is NOT scrollable (content fits)');
            body.scrollTop = 0;

            // Test page scroll lock
            const pageBefore = window.scrollY;
            window.scrollBy(0, 100);
            const pageAfter = window.scrollY;
            console.log('%c[PAGE LOCK TEST]', 'background:#10B981;color:#fff;padding:2px 6px;border-radius:3px;font-weight:bold;',
                'before=' + pageBefore + ' after-scroll-100=' + pageAfter,
                pageAfter === pageBefore ? '✅ page IS locked' : '❌ page is NOT locked');
            window.scrollTo(0, pageBefore);
        }

        function close() {
            if (!isOpen) return;
            isOpen = false;
            backdrop.classList.remove('is-open');
            backdrop.setAttribute('aria-hidden', 'true');
            unlockBodyScroll();
            setTimeout(resetForm, 300);
        }

        function resetForm() {
            modal.classList.remove('is-success');
            allInputs.forEach(i => { if (i) i.value = ''; });
            sourceSel.selectedIndex = 0;
            if (body) body.scrollTop = 0;
            toggleReferrer(false);
            ['applicant_name','applicant_phone','applicant_email','company_name','source','referrer_name']
                .forEach(clearError);
            setLoading(false);
        }

        function toggleReferrer(show) {
            const body = backdrop.querySelector('.jr-modal-body');
            if (show) {
                refField.classList.add('is-shown');
                // Wait for the reveal animation, then scroll the field into view
                // inside the modal body (not the page).
                setTimeout(function () {
                    if (typeof refField.scrollIntoView === 'function') {
                        refField.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else if (body) {
                        body.scrollTop = body.scrollHeight;
                    }
                    refInput.focus({ preventScroll: true });
                }, 380);
            } else {
                refField.classList.remove('is-shown');
                refInput.value = '';
                clearError('referrer_name');
            }
        }

        function setError(field, message) {
            const wrap = backdrop.querySelector('[data-jr-field="' + field + '"]');
            const err  = backdrop.querySelector('[data-jr-error="' + field + '"]');
            if (wrap) wrap.classList.add('has-error');
            if (err)  err.textContent = message;
        }
        function clearError(field) {
            const wrap = backdrop.querySelector('[data-jr-field="' + field + '"]');
            const err  = backdrop.querySelector('[data-jr-error="' + field + '"]');
            if (wrap) wrap.classList.remove('has-error');
            if (err)  err.textContent = '';
        }
        function setLoading(on) {
            submitBtn.classList.toggle('is-loading', on);
            submitBtn.disabled = on;
        }

        function validate() {
            let ok = true;
            ['applicant_name','applicant_phone','applicant_email','company_name','source','referrer_name']
                .forEach(clearError);

            if (!nameInput.value.trim()) {
                setError('applicant_name', @json($jrIsRtl ? 'الرجاء كتابة الاسم الكامل.' : 'Please enter your full name.'));
                ok = false;
            }
            const phoneVal = phoneInput.value.trim();
            if (!phoneVal) {
                setError('applicant_phone', @json($jrIsRtl ? 'الرجاء كتابة رقم الهاتف.' : 'Please enter your phone number.'));
                ok = false;
            } else if (!/^[\d\s\+\-\(\)]+$/.test(phoneVal) || phoneVal.replace(/\D/g, '').length < 7) {
                setError('applicant_phone', @json($jrIsRtl ? 'رقم الهاتف غير صحيح.' : 'Phone number is invalid.'));
                ok = false;
            }
            const emailVal = emailInput.value.trim();
            if (emailVal && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
                setError('applicant_email', @json($jrIsRtl ? 'البريد الإلكتروني غير صحيح.' : 'Email is invalid.'));
                ok = false;
            }
            if (!sourceSel.value) {
                setError('source', @json($jrIsRtl ? 'الرجاء اختيار خيار من القائمة.' : 'Please select an option.'));
                ok = false;
            }
            if (sourceSel.value === 'friend_employee' && !refInput.value.trim()) {
                setError('referrer_name', @json($jrIsRtl ? 'الرجاء كتابة اسم الشخص.' : 'Please enter the person\'s name.'));
                ok = false;
            }
            return ok;
        }

        function onSubmit(e) {
            if (e && e.preventDefault) e.preventDefault();
            if (!validate()) {
                // Scroll to first error inside modal body
                const firstErr = backdrop.querySelector('.jr-field.has-error');
                if (firstErr && firstErr.scrollIntoView) {
                    firstErr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
                return;
            }

            setLoading(true);

            const payload = {
                applicant_name:  nameInput.value.trim(),
                applicant_phone: phoneInput.value.trim(),
                applicant_email: emailInput.value.trim() || null,
                company_name:    companyInput.value.trim() || null,
                source:          sourceSel.value,
                referrer_name:   refInput.value.trim() || null,
            };

            fetch(ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    if (res.status === 422 && data.errors) {
                        Object.keys(data.errors).forEach(k => setError(k, data.errors[k][0]));
                    } else {
                        setError('source', @json($jrIsRtl ? 'حدث خطأ. حاول مجددًا.' : 'Something went wrong. Try again.'));
                    }
                    setLoading(false);
                    return;
                }
                modal.classList.add('is-success');
                setLoading(false);
                setTimeout(() => {
                    close();
                    if (REDIRECT_URL) {
                        window.open(REDIRECT_URL, '_blank', 'noopener');
                    }
                }, 1800);
            })
            .catch(() => {
                setError('source', @json($jrIsRtl ? 'تعذّر الاتصال بالخادم.' : 'Network error.'));
                setLoading(false);
            });
        }
    }
}());
</script>
@endpush
@endonce
