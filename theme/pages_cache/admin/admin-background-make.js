jQuery(document).ready(function ($) {

    var hiweb_theme_pages_cache_admin = {

        init: function () {
            console.info('start background make cache...');
            setTimeout(hiweb_theme_pages_cache_admin.do_ajax_make_caches, 5000);
            setInterval(hiweb_theme_pages_cache_admin.do_ajax_make_caches, 30000);
        },

        do_ajax_make_caches: function () {
            let wpJsonUrl;
            if(typeof wpApiSettings !== 'undefined') {
                wpJsonUrl = wpApiSettings.root + 'hiweb_theme/pages_cache/background';
            } else {
                wpJsonUrl = '/wp-json/hiweb_theme/pages_cache/background';
            }
            $.ajax({
                url: wpJsonUrl,
                success: function(response){
                    //do nothing.
                }
            });
        }

    };


    hiweb_theme_pages_cache_admin.init();

});