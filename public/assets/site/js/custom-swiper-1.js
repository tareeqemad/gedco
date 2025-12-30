/**
 * Initialize Swiper slider with RTL/LTR direction support.
 * Detects page direction from HTML dir attribute and configures slider accordingly.
 */
(function() {
    // Get direction from HTML element or default to 'rtl'
    const htmlDir = document.documentElement.getAttribute('dir') || 'rtl';
    const isRtl = htmlDir === 'rtl';

    // === Swiper base config ===
    const swiper = new Swiper('.swiper', {
        rtl: isRtl, // Enable RTL mode when direction is RTL
        autoplay: {
            delay: 4000,
            disableOnInteraction: false
        },
        loop: true,
        spaceBetween: 0,
        effect: "creative",
        speed: 1500, // transition speed

        creativeEffect: {
            prev: {
                // Zoom out (shrink + fade)
                scale: 1.1,
                opacity: 0,
                translate: [0, 0, 0], // stay centered
            },
            next: {
                // Zoom in (grow + fade in)
                scale: 1.3,
                opacity: 0,
                translate: [0, 0, 0], // stay centered
            },
        },

        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: false,
            clickable: false,
        },
    });

    // Expose for debugging if needed
    window.gedcoMainSwiper = swiper;

    // === Pin / Unpin Logic ===
    const PIN_KEY = 'gedco:pinned-slider';
    // Duration to respect pin (in milliseconds) – can be tuned as needed
    const PIN_DURATION_MS = 10 * 60 * 1000; // 10 minutes

    function loadPinned() {
        try {
            const raw = window.localStorage.getItem(PIN_KEY);
            if (!raw) return null;
            const data = JSON.parse(raw);
            if (!data || typeof data !== 'object') return null;

            const now = Date.now();
            if (!data.expiresAt || now > data.expiresAt) {
                window.localStorage.removeItem(PIN_KEY);
                return null;
            }
            return data;
        } catch (e) {
            return null;
        }
    }

    function savePinned(sliderId) {
        const now = Date.now();
        const payload = {
            sliderId: String(sliderId),
            pinnedAt: now,
            expiresAt: now + PIN_DURATION_MS,
        };
        try {
            window.localStorage.setItem(PIN_KEY, JSON.stringify(payload));
        } catch (e) {
            // ignore storage errors
        }
        return payload;
    }

    function clearPinned() {
        try {
            window.localStorage.removeItem(PIN_KEY);
        } catch (e) {
            // ignore
        }
    }

    function findSlideIndexById(sliderId) {
        const slides = Array.prototype.slice.call(swiper.slides || []);
        const match = slides.find(function (el) {
            return el.getAttribute('data-slider-id') === String(sliderId);
        });
        if (!match) return -1;
        return slides.indexOf(match);
    }

    function updateButtonsUI(activeSliderId) {
        var buttons = document.querySelectorAll('.slider-pin-toggle');
        Array.prototype.forEach.call(buttons, function (btn) {
            const id = btn.getAttribute('data-slider-id');
            const isActive = activeSliderId && String(id) === String(activeSliderId);
            btn.classList.toggle('is-pinned', !!isActive);
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            btn.setAttribute('title', isActive ? 'Unpin this slide' : 'Pin this slide');
        });
    }

    function applyInitialPinState() {
        const pinned = loadPinned();
        if (!pinned || !pinned.sliderId) {
            updateButtonsUI(null);
            return;
        }

        const targetIndex = findSlideIndexById(pinned.sliderId);
        if (targetIndex >= 0) {
            // slideToLoop handles looped swiper correctly
            if (typeof swiper.slideToLoop === 'function') {
                swiper.slideToLoop(targetIndex);
            } else {
                swiper.slideTo(targetIndex);
            }
            // stop autoplay while pinned
            if (swiper.autoplay && typeof swiper.autoplay.stop === 'function') {
                swiper.autoplay.stop();
            }
            updateButtonsUI(pinned.sliderId);
        } else {
            // no matching slide anymore
            clearPinned();
            updateButtonsUI(null);
        }
    }

    function handlePinClick(event) {
        const btn = event.currentTarget;
        const sliderId = btn.getAttribute('data-slider-id');
        if (!sliderId) return;

        const current = loadPinned();
        const alreadyPinned = current && String(current.sliderId) === String(sliderId);

        if (alreadyPinned) {
            // Unpin -> resume normal behaviour
            clearPinned();
            updateButtonsUI(null);
            if (swiper.autoplay && typeof swiper.autoplay.start === 'function') {
                swiper.autoplay.start();
            }
            return;
        }

        // Pin this slide
        const stored = savePinned(sliderId);
        updateButtonsUI(stored.sliderId);

        const targetIndex = findSlideIndexById(sliderId);
        if (targetIndex >= 0) {
            if (typeof swiper.slideToLoop === 'function') {
                swiper.slideToLoop(targetIndex);
            } else {
                swiper.slideTo(targetIndex);
            }
        }

        // Stop autoplay while pinned
        if (swiper.autoplay && typeof swiper.autoplay.stop === 'function') {
            swiper.autoplay.stop();
        }
    }

    function bindPinButtons() {
        const buttons = document.querySelectorAll('.slider-pin-toggle');
        Array.prototype.forEach.call(buttons, function (btn) {
            btn.addEventListener('click', handlePinClick);
        });
    }

    // Initialize pin state after Swiper is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            bindPinButtons();
            applyInitialPinState();
        });
    } else {
        bindPinButtons();
        applyInitialPinState();
    }
})();
