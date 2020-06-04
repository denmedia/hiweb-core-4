jQuery(document).ready(function ($) {

    /**
     * Critical CSS Generator
     * @type {{}}
     */
    var hiweb_theme_critical_css = {

        cHeight: 1400,
        //cElements: 'html, body, div, a, span, p, b, strong, nav, ul, ol, li, img, form, input, textarea, select, button, main, side, header, footer, [class], [id], h1, h2, h3, h4, h5, h6, i',
        cElements: 'header, footer, main, section, side, .nav, .nav-bar, .mm-menu, nav, body',
        cElementsDisallow: 'head, link, meta, script',

        init: function () {
            var everythingLoaded = setInterval(function () {
                if (/loaded|complete/.test(document.readyState)) {
                    clearInterval(everythingLoaded);
                    ///
                    if (typeof hiweb_theme_minify_template_id === 'string') {
                        let $cElements = hiweb_theme_critical_css.extract_cHTML('html');
                        $.ajax({
                            url: '/wp-admin/admin-ajax.php?action=hiweb_theme_critical_css_generate',//'/wp-json/hiweb_theme/critical_css/generate',
                            dataType: 'json',
                            type: 'post',
                            data: {id: hiweb_theme_minify_template_id, chtml: $cElements[0][0].outerHTML, referer: window.location.href},
                            success: function (response) {
                                if(response.hasOwnProperty('success')){
                                    if(response.success){
                                        console.info('hiWeb Theme: critical CSS created!');
                                    }
                                }
                            }
                        });
                        ///
                        if (typeof hiweb_theme_full_css_url == 'string') {
                            ///INCLUDE FULL CSS
                            let $head = $('head');
                            if ($head.length > 0) {
                                hiweb_theme_critical_css.loadStyleSheet(hiweb_theme_full_css_url);
                            }
                        }
                    }
                }
                ///
            }, 500);
        },

        /**
         *
         * @param $element
         * @param onlyCriticalHeight
         * @returns {*}
         */
        extract_cHTML: function ($element, onlyCriticalHeight = true) {
            $element = $($element);
            if ($element.length === 0) return '';
            let R = [];
            $($element).each(function () {
                let $source = $(this);
                if (!onlyCriticalHeight || $source.offset().top <= hiweb_theme_critical_css.cHeight || $source.has(hiweb_theme_critical_css.cElements) || $source.is($(hiweb_theme_critical_css.cElements))) {
                    let $current = $source.first().clone().empty();
                    if ($current.is('[style]')) {
                        $current.attr('style', '');
                    }
                    let $children = hiweb_theme_critical_css.extract_cHTML($source.children().not(hiweb_theme_critical_css.cElementsDisallow), onlyCriticalHeight);
                    for (let index in $children) {
                        $current.append($children[index]);
                    }
                    R.push($current);
                }
            });
            return R;
        },

        loadStyleSheet: function (src, userPreload = false) {
            if (document.createStyleSheet) document.createStyleSheet(src);
            else {
                var stylesheet = document.createElement('link');
                stylesheet.href = src;
                stylesheet.rel = userPreload ? 'preload' : 'stylesheet';
                if (!userPreload) stylesheet.type = 'text/css';
                if (userPreload) stylesheet.as = 'style';
                document.getElementsByTagName('head')[0].appendChild(stylesheet);
            }
        }

    };
    hiweb_theme_critical_css.init();

});