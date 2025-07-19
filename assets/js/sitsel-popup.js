jQuery(document).ready(function($) {
    // Open popup
    $('.sitsel-popup-trigger').on('click', function() {
        $(this).closest('.sitsel-popup-widget-wrapper').find('.sitsel-popup-overlay').removeClass('sitsel-hidden');
    });

    // Close popup when clicking close icon or overlay
    $(document).on('click', '.sitsel-popup-close, .sitsel-popup-overlay', function(e) {
        if ($(e.target).hasClass('sitsel-popup-overlay') || $(e.target).hasClass('sitsel-popup-close')) {
            $(this).closest('.sitsel-popup-overlay').addClass('sitsel-hidden');
        }
    });

    // Prevent closing when clicking inside popup content
    $(document).on('click', '.sitsel-popup-inner', function(e) {
        e.stopPropagation();
    });
});
