jQuery(document).ready(function ($) {
    // Scroll to a Specific Div
    var $scrolltop_widget = $('.hiweb-theme-widget-scrolltop');
    if ($scrolltop_widget.length) {
        var scrolltop_widget_show = function () {
            var windowpos = $(window).scrollTop();
            if (windowpos >= $scrolltop_widget.attr('data-scroll-offset')) {
                $scrolltop_widget.fadeIn($scrolltop_widget.attr('data-fade-speed'));
            } else {
                $scrolltop_widget.fadeOut($scrolltop_widget.attr('data-fade-speed'));
            }
        };
        $scrolltop_widget.on('click', function () {
            let target = $(this).attr('data-target');
            // animate
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, $scrolltop_widget.attr('data-scroll-speed'));
        });
        scrolltop_widget_show();

        $(window).on('scroll', scrolltop_widget_show);
    }
});