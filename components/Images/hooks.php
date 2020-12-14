<?php

use hiweb\components\Images\ImagesFactory;


if (ImagesFactory::$useImageDefer) {
    if (function_exists('add_action')) {
        add_action('rest_api_init', function() {
            register_rest_route('hiweb/components', '/images/defer', [
                'methods' => 'post',
                'callback' => function() {
                    if ( !isset($_POST['images']) || !is_array($_POST['images'])) return [ 'success' => false, 'message' => 'Не переданы данные изображений для загрузки в $_POST[images]' ];
                    ImagesFactory::$useImageDefer = false;
                    $images = [];
                    $images_src = [];
                    $images_error = [];
                    foreach ($_POST['images'] as $defer_id => $defer_data) {
                        if ( !isset($defer_data['id']) || !isset($defer_data['dimension'])) {
                            if (WP_DEBUG) $images_error[$defer_id] = $defer_data;
                            continue;
                        }
                        $image = get_image($defer_data['id']);
                        $images_src[$defer_id] = $image->get_src($defer_data['dimension'], null, true);
                        ///is webp support
                        $images[$defer_id] = $image->get_html($defer_data['dimension'], isset($defer_data['attributes']) ? $defer_data['attributes'] : [], null, get_client()->is_support_WebP());
                    }
                    ob_start();
                    \hiweb\components\Console\ConsoleFactory::the();
                    $js = ob_get_clean();
                    return [ 'success' => true, 'images' => $images, 'images_src' => $images_src, 'images_error' => $images_error, 'js' => $js ];
                }
            ]);
        });
        add_action('wp_head', function() {
            ?>
            <script>let hiweb_imageDefer_ajax_url = '<?=get_url(__DIR__ . '/ajax_shortinit.php')?>';</script><?php
        });

        add_action('init', function() {
            include_frontend_js(__DIR__ . '/defer.min.js', 'jquery-core');
        });
    }
}