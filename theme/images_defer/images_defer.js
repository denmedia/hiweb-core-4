/**
 * Images Defer
 */

jQuery(document).ready(function ($) {

    var hiweb_theme_imagesDefer = {

        $images_on_page: [],
        images_queue: [],
        images_done: [],
        images_error: [],
        scroll_event_interval: 200,
        scroll_event_handler: null,
        scroll_event_now: false,

        init: function () {
            hiweb_theme_imagesDefer.make_events();
            var everythingLoaded = setInterval(function() {
                if (/loaded|complete/.test(document.readyState)) {
                    clearInterval(everythingLoaded);
                    hiweb_theme_imagesDefer.find_images_on_page();
                    hiweb_theme_imagesDefer.find_images_on_windows();
                    ///ajax events
                    $(document).on('ajaxComplete', hiweb_theme_imagesDefer.find_images_on_page);
                    $(document).on('ajaxComplete', hiweb_theme_imagesDefer.find_images_on_windows);
                }
            }, 400);
        },

        make_events: function () {
            $(window).scroll(function (event) {
                if (!hiweb_theme_imagesDefer.scroll_event_now) {
                    hiweb_theme_imagesDefer.scroll_event_now = true;
                    clearTimeout(hiweb_theme_imagesDefer.scroll_event_handler);
                    hiweb_theme_imagesDefer.scroll_event_handler = setTimeout(function () {
                        hiweb_theme_imagesDefer.find_images_on_windows();
                        hiweb_theme_imagesDefer.scroll_event_now = false;
                    }, hiweb_theme_imagesDefer.scroll_event_interval);
                }
            });
        },

        find_images_on_page: function () {
            hiweb_theme_imagesDefer.$images_on_page = [];
            $('img[data-src-defer]').each(function () {
                hiweb_theme_imagesDefer.$images_on_page.push($(this));
                hiweb_theme_imagesDefer.set_image_aspect($(this));
            });
        },

        find_images_on_windows: function () {
            let minHeight = $(window).scrollTop();
            let maxHeight = minHeight + window.innerHeight;
            for (let index in hiweb_theme_imagesDefer.$images_on_page) {
                let $img = hiweb_theme_imagesDefer.$images_on_page[index];
                if (/*$img.offset().top >= -1 && */$img.offset().top < maxHeight + $img.height() + 100) {
                    hiweb_theme_imagesDefer.images_queue.push(hiweb_theme_imagesDefer.$images_on_page[index]);
                    hiweb_theme_imagesDefer.load_image(hiweb_theme_imagesDefer.$images_on_page[index]);
                    delete hiweb_theme_imagesDefer.$images_on_page[index];
                }
            }
        },

        load_image: function ($img) {
            $img.addClass('hiweb-theme-imagesDefer-image');
            let loading_image = new Image();
            loading_image.onload = function () {
                $img.attr('src', $img.attr('data-src-defer'));
                $img.removeAttr('data-src-defer');
                $img.attr('srcset', $img.attr('data-srcset-defer'));
                $img.removeAttr('data-srcset-defer');
                $img.css('width', '').css('height', '');
                $img.removeClass('hiweb-theme-imagesDefer-image');
            };
            loading_image.src = $img.attr('data-src-defer');
        },


        set_image_aspect: function ($img) {
            if ($img.css('position') === 'absolute') return;
            ///
            let aspect_current = $img.width() / $img.height();
            let aspect_data = parseFloat($img.attr('data-aspect'));
            if (aspect_data > aspect_current) {
                $img.height($img.width() / aspect_data);
            } else {
                $img.width($img.height() * aspect_data);
            }
        }


    };


    hiweb_theme_imagesDefer.init();


});