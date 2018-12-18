/**
 * Custom js for theme
 */

(function ($) {
    $(document).ready(function () {
        $(".twp-slider-3").owlCarousel({
            loop: ($('.twp-slider-3').children().length) == 1 ? false : true,
            margin: 3,
            autoplay: 5000,
            nav: true,
            navText: ["<i class='ion-ios-arrow-left'></i>", "<i class='ion-ios-arrow-right'></i>"],
            items: 3,
            responsive: {
                0: {
                    items: 1
                },
                640: {
                    items: 2
                },
                768: {
                    items: 3
                }
            }
        });
    });
})(jQuery);