jQuery(document).ready(function ($) {

    let $source_images_data = $('[data-image-defer-id]');
    if ($source_images_data.length > 0) {


        let hiweb_imagesDefer = {
            ///true, если в текущий момент идет ajax получение изображений
            is_receive_data: 0,

            ///Найденные defer изображения до загрузки данных
            source_images_data: {},
            ///Загруженные html скрипты
            receive_images_html: {},
            ///Загруженные url
            receive_images_src: {},
            //Очередь изображений
            images_ids_queue: [],
            images_ids_processed: [],

            ///
            scroll_event_interval: 200,
            scroll_event_handler: null,
            scroll_event_now: false,

            ///
            queue_started: false,
            queue_parallel_limit: 5, //set option
            queue_parallel_count: 5, //set option

            init: function () {
                hiweb_imagesDefer.make_events();
                let everythingLoaded = setInterval(function () {
                    if (/loaded|complete/.test(document.readyState)) {
                        clearInterval(everythingLoaded);
                        hiweb_imagesDefer.receive_images_data(hiweb_imagesDefer.find_images_on_windows);
                        ///ajax events
                        $(document).on('ajaxComplete', function () {
                            if (hiweb_imagesDefer.is_receive_data === 2) {
                                hiweb_imagesDefer.is_receive_data = 0;
                            } else if (hiweb_imagesDefer.is_receive_data === 0) {
                                hiweb_imagesDefer.receive_images_data(hiweb_imagesDefer.find_images_on_windows)
                            }
                        });
                        //setInterval(()=>{ hiweb_imagesDefer.receive_images_data(hiweb_imagesDefer.find_images_on_windows) }, 5000);
                    }
                }, 100);
            },

            receive_images_data: function (successCallBack) {
                hiweb_imagesDefer.is_receive_data = 1;
                let $source_images_data = $('[data-image-defer-id]');
                if ($source_images_data.length === 0) return;
                $source_images_data.each(function () {
                    let $img = $(this);
                    hiweb_imagesDefer.source_images_data[$img.attr('data-image-defer-id')] = JSON.parse($img.attr('data-image-defer'));
                });
                $.ajax({
                    url: hiweb_imageDefer_ajax_url,
                    //url: '/wp-json/hiweb/components/images/defer',
                    type: 'post',
                    dataType: 'json',
                    data: {images: hiweb_imagesDefer.source_images_data, usePictureHtml: hiweb_imageDefer_usePictureHtml, useWidthHeightProps: hiweb_imageDefer_useWidthHeightProps},
                    success: function (response) {
                        if (response.success) {
                            for (let defer_id in response.images) {
                                if (!hiweb_imagesDefer.receive_images_html.hasOwnProperty(defer_id)) {
                                    hiweb_imagesDefer.receive_images_html[defer_id] = response.images[defer_id];
                                    hiweb_imagesDefer.receive_images_src[defer_id] = response.images_src[defer_id];
                                }
                            }
                            if (typeof successCallBack === 'function') successCallBack();
                        }
                        $('body').append(response.js);
                    },
                    complete: function () {
                        hiweb_imagesDefer.is_receive_data = 2;
                    }
                })
            },

            make_events: function () {
                $(window).scroll(function (event) {
                    if (!hiweb_imagesDefer.scroll_event_now) {
                        hiweb_imagesDefer.scroll_event_now = true;
                        clearTimeout(hiweb_imagesDefer.scroll_event_handler);
                        hiweb_imagesDefer.scroll_event_handler = setTimeout(function () {
                            hiweb_imagesDefer.find_images_on_windows();
                            hiweb_imagesDefer.queue_start();
                            hiweb_imagesDefer.scroll_event_now = false;
                        }, hiweb_imagesDefer.scroll_event_interval);
                    }
                });
            },

            find_images_on_windows: function () {
                let minHeight = $(window).scrollTop();
                let maxHeight = minHeight + window.innerHeight;
                for (let defer_id in hiweb_imagesDefer.receive_images_html) {
                    ///skip already in queue
                    if (hiweb_imagesDefer.images_ids_queue.indexOf(defer_id) > -1 || hiweb_imagesDefer.images_ids_processed.indexOf(defer_id) > -1) continue;
                    ///find images in browser window
                    let $img = $('[data-image-defer-id="' + defer_id + '"][data-image-defer-status="preload"]');
                    let offset_top = $img.find('img').length > 0 ? $img.find('img').offset().top : $img.offset().top;
                    if ($img.length > 0 && offset_top < maxHeight + $img.height() - 100) {
                        hiweb_imagesDefer.images_ids_queue.push(defer_id);
                        hiweb_imagesDefer.queue_start();
                    }
                }
            },

            queue_start: function () {
                if (hiweb_imagesDefer.queue_started) return;
                hiweb_imagesDefer.queue_started = true;
                for (let n = 0; n < hiweb_imagesDefer.queue_parallel_limit; n++) {
                    hiweb_imagesDefer.queue_next();
                    hiweb_imagesDefer.queue_parallel_count++;
                }
            },

            queue_next: function () {
                if (!hiweb_imagesDefer.queue_started && hiweb_imagesDefer.queue_parallel_count >= hiweb_imagesDefer.queue_parallel_limit) {
                    return;
                } else if (hiweb_imagesDefer.images_ids_queue.length === 0) {
                    hiweb_imagesDefer.queue_started = false;
                    hiweb_imagesDefer.queue_parallel_count = 0;
                } else {
                    let defer_id = hiweb_imagesDefer.images_ids_queue.shift();
                    hiweb_imagesDefer.images_ids_processed.push(defer_id);
                    hiweb_imagesDefer.load_image(defer_id, hiweb_imagesDefer.queue_next);
                }
            },

            load_image: function (defer_id, successCallback) {
                if (!hiweb_imagesDefer.receive_images_html.hasOwnProperty(defer_id)) return;
                let $img = $('[data-image-defer-id="' + defer_id + '"]');
                if ($img.length > 0) {
                    $img.attr('data-image-defer-status', 'loading');
                    let urls = hiweb_imagesDefer.receive_images_src[defer_id];
                    let urls_count = urls.length;
                    if (urls_count === 0 && typeof successCallback === 'function') {
                        return successCallback();
                    }
                    ///
                    for (let i in urls) {
                        let loading_image = new Image();
                        loading_image.onload = function () {
                            urls_count--;
                            if (urls_count < 1 && typeof successCallback === 'function') {
                                $img.replaceWith($(hiweb_imagesDefer.receive_images_html[defer_id]).attr('data-image-defer-status', 'loaded'));
                                successCallback();
                            }
                        }
                        loading_image.onerror = function () {
                            urls_count--;
                            if (urls_count < 1 && typeof successCallback === 'function') {
                                successCallback();
                            }
                        }
                        loading_image.src = urls[i];
                    }
                    ///
                    // let loading_image = new Image();
                    // loading_image.onload = function () {
                    //     $img.replaceWith($(hiweb_imagesDefer.receive_images_html[defer_id]).attr('data-image-defer-status', 'loaded'));
                    //     if (typeof successCallback === 'function') successCallback();
                    // };
                    // loading_image.onerror = function () {
                    //$img[0].outerHTML = $(hiweb_imagesDefer.receive_images_html[defer_id]).attr('data-image-defer-status', 'loaded')[0].outerHTML;
                    //     if (typeof successCallback === 'function') successCallback();
                    // }
                    // loading_image.src = hiweb_imagesDefer.receive_images_src[defer_id];
                }
            }


        };

        hiweb_imagesDefer.init();
    }
});