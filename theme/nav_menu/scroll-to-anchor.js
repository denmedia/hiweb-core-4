jQuery(document).ready(function ($) {
    $(document).on('click tap', 'a[href^="#"]', function (e) {
        let $a = $(this);
        if($a.closest('.nav, nav, li').length > 0) {
            let $target = $($a.attr('href'));
            let offset = $('.mh-head.mh-sticky').height();
            if ($target.length > 0) {
                e.preventDefault();
                $('.mh-head.mh-sticky.mh-scrolledout').removeClass('mh-scrolledout');
                $('html, body').animate({
                    scrollTop: $target.offset().top - offset
                }, 1000, function(){
                    $('.mh-head.mh-sticky.mh-scrolledout').removeClass('mh-scrolledout');
                });
            }
        }
    });
});