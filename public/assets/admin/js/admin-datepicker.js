/**
 * Admin Date Picker - Arabic Support
 * تطبيق date picker عربي على جميع حقول التاريخ في لوحة الإدارة
 */
(function() {
    'use strict';

    // انتظار تحميل flatpickr
    function waitForFlatpickr(callback) {
        if (typeof flatpickr !== 'undefined') {
            callback();
        } else {
            setTimeout(function() {
                waitForFlatpickr(callback);
            }, 100);
        }
    }

    // تحديد اللغة حسب اتجاه لوحة الإدارة
    function getLocale() {
        const direction = document.documentElement.getAttribute('dir') || 'rtl';
        return direction === 'rtl' ? 'ar' : 'en';
    }

    // تهيئة date picker على جميع حقول التاريخ
    function initDatePickers() {
        if (typeof flatpickr === 'undefined') {
            console.warn('Flatpickr not loaded yet');
            return;
        }

        // البحث عن جميع inputs من نوع date
        const dateInputs = document.querySelectorAll('input[type="date"]:not([data-flatpickr-initialized])');
        
        if (dateInputs.length === 0) {
            return;
        }

        const locale = getLocale();
        const isRTL = locale === 'ar';

        dateInputs.forEach(function(input) {
            // تجنب إعادة التهيئة
            if (input.hasAttribute('data-flatpickr-initialized')) {
                return;
            }

            // إعدادات flatpickr للعربية
            const config = {
                locale: locale,
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                altInputClass: 'form-control rounded-3 admin-date-input',
                allowInput: false,
                clickOpens: true,
                rtl: isRTL,
                animate: true,
                disableMobile: false,
                defaultDate: input.value || null,
                onChange: function(selectedDates, dateStr, instance) {
                    input.value = dateStr;
                    var event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                },
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.style.cursor = 'pointer';
                        instance.altInput.setAttribute('readonly', 'readonly');
                    }
                    if (input) {
                        input.style.position = 'absolute';
                        input.style.opacity = '0';
                        input.style.width = '1px';
                        input.style.height = '1px';
                    }
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    // Force calendar to open below the input
                    setTimeout(function() {
                        var cal = instance.calendarContainer;
                        var inputEl = instance.altInput || instance.input;
                        var rect = inputEl.getBoundingClientRect();
                        cal.style.top = (rect.bottom + window.scrollY + 2) + 'px';
                        cal.classList.remove('arrowBottom');
                        cal.classList.add('arrowTop');
                    }, 0);
                }
            };

            // تهيئة flatpickr
            try {
                const fp = flatpickr(input, config);
                input.setAttribute('data-flatpickr-initialized', 'true');
            } catch (e) {
                console.warn('Failed to initialize flatpickr on', input, e);
            }
        });
    }

    // تهيئة عند تحميل flatpickr
    waitForFlatpickr(function() {
        // تهيئة عند تحميل الصفحة
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initDatePickers, 200);
            });
        } else {
            setTimeout(initDatePickers, 200);
        }

        // إعادة التهيئة عند تحديث المحتوى (للمحتوى الديناميكي)
        const observer = new MutationObserver(function(mutations) {
            let shouldReinit = false;
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            if (node.tagName === 'INPUT' && node.type === 'date') {
                                shouldReinit = true;
                            } else if (node.querySelector && node.querySelector('input[type="date"]:not([data-flatpickr-initialized])')) {
                                shouldReinit = true;
                            }
                        }
                    });
                }
            });
            if (shouldReinit) {
                setTimeout(initDatePickers, 100);
            }
        });

        // مراقبة التغييرات في DOM
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });

    // تصدير للاستخدام العام
    window.initAdminDatePickers = initDatePickers;
})();


