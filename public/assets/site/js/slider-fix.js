/**
 * Slider Background Image Fix
 * Handles background images in swiper slides without cropping
 * Desktop: cover, Mobile: contain (full image visible)
 */

(function() {
    'use strict';

    const SliderFix = {
        /**
         * Initialize the slider fix
         */
        init: function() {
            this.applyBackgroundImages();
            window.addEventListener('load', () => this.applyBackgroundImages());
            window.addEventListener('resize', () => this.applyBackgroundImages());
            this.observeSliderChanges();
        },

        /**
         * Apply background images from data-bgimage attribute
         */
        applyBackgroundImages: function() {
            const slides = document.querySelectorAll('.swiper-inner');
            
            slides.forEach(slide => {
                const bgImageUrl = slide.getAttribute('data-bgimage');
                
                if (bgImageUrl) {
                    // Apply background image
                    slide.style.backgroundImage = bgImageUrl;
                    slide.style.backgroundPosition = 'center center';
                    slide.style.backgroundRepeat = 'no-repeat';
                    slide.style.backgroundAttachment = 'scroll';
                    slide.style.backgroundColor = '#1a1c26';
                    
                    // CSS media queries handle sizing (cover desktop, contain mobile)
                    slide.style.backgroundSize = 'cover';
                }
            });
        },

        /**
         * Observe slider for dynamic content changes
         */
        observeSliderChanges: function() {
            const sliderWrapper = document.querySelector('.swiper-wrapper');
            
            if (!sliderWrapper) return;
            
            const observer = new MutationObserver(() => {
                this.applyBackgroundImages();
            });
            
            observer.observe(sliderWrapper, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['data-bgimage']
            });
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => SliderFix.init());
    } else {
        SliderFix.init();
    }

    // Expose to window for manual control if needed
    window.SliderFix = SliderFix;
})();
