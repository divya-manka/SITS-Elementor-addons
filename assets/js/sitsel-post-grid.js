// Swiper initialization if needed
   
document.addEventListener('DOMContentLoaded', function () {
    const sliderEl = document.querySelector('.sitsel-slider');
    if (!sliderEl) return;

    const spaceBetween = parseInt(sliderEl.dataset.gap) || 20;
    const autoplayEnabled = sliderEl.dataset.autoplay === '1';
    const autoplayTimeout = parseInt(sliderEl.dataset.autoplayTimeout) || 3000;
    const loopEnabled = sliderEl.dataset.loop === '1';

    const slidesDesktop = parseInt(sliderEl.dataset.slidesDesktop) || 3;
    const slidesTablet = parseInt(sliderEl.dataset.slidesTablet) || 2;
    const slidesMobile = parseInt(sliderEl.dataset.slidesMobile) || 1;

    new Swiper('.sitsel-slider', {
        loop: loopEnabled,
        autoplay: autoplayEnabled ? { delay: autoplayTimeout } : false,
        speed: autoplayTimeout,
        slidesPerView: slidesDesktop,
        spaceBetween: spaceBetween,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: slidesMobile,
                spaceBetween: spaceBetween
            },
            768: {
                slidesPerView: slidesTablet,
                spaceBetween: spaceBetween
            },
            1024: {
                slidesPerView: slidesDesktop,
                spaceBetween: spaceBetween
            }
        }
    });
});



            
//  for testimonial slider 
                (function ($) {
            const initSwiper = function ($scope) {
                $scope.find('.sitsel-testimonial-slider').each(function () {
                    const $slider = $(this);
                    const slidesPerView = parseInt($slider.data('columns')) || 1;
                    const slidesToScroll = parseInt($slider.data('scroll')) || 1;
                    const spaceBetween = parseInt($slider.data('gutter')) || 15;
                    const loop = $slider.data('loop') === true || $slider.data('loop') === 'true';
                    const effect = $slider.data('effect') || 'slide';
                    const speed = parseInt($slider.data('speed')) || 700;
                    const pagination = $slider.data('pagination') === true || $slider.data('pagination') === 'true';
                    const navigation = $slider.data('navigation') === true || $slider.data('navigation') === 'true';

                    new Swiper($slider[0], {
                    slidesPerView: parseInt($slider.data('columns-desktop')) || 1,
                    slidesPerGroup: slidesToScroll,
                    spaceBetween: spaceBetween,
                    loop: loop,
                    effect: effect,
                    speed: speed,
                    pagination: pagination ? {
                        el: $slider.find('.swiper-pagination')[0],
                        clickable: true
                    } : false,
                    navigation: navigation ? {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    } : false,

                    // Responsive breakpoints
                    breakpoints: {
                        320: {
                            slidesPerView: parseInt($slider.data('columns-mobile')) || 1,
                        },
                        768: {
                            slidesPerView: parseInt($slider.data('columns-tablet')) || 2,
                        },
                        1025: {
                            slidesPerView: parseInt($slider.data('columns-desktop')) || 3,
                        }
                    }
                });

                });
            };

            $(window).on('elementor/frontend/init', function () {
                elementorFrontend.hooks.addAction('frontend/element_ready/sitsel_testimonial.default', initSwiper);
            });
        })(jQuery);