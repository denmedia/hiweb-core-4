jQuery(document).ready(function ($) {
    $('.stellarnav').each(function () {
        let $stellar = $(this);
        $stellar.stellarNav({
            theme: 'plain',
            breakpoint: 0,
            sticky: false,
            position: 'static',
            showArrows: $(this).is('[data-arrows="1"]'),
            closeBtn: false,
            scrollbarFix: true
        });
        $stellar.find('ul[data-items-count]').each(function () {
            let $ul = $(this);
            let count = parseInt($ul.attr('data-items-count'));
            if (!isNaN(count)) {
                if (count > 20) {
                    $ul.addClass('nav-sub-cols-3');
                }
                else if (count > 10) {
                    $ul.addClass('nav-sub-cols-2');
                } else {
                    $ul.addClass('nav-sub-cols-1');
                }
            }
        });
    });
})
;