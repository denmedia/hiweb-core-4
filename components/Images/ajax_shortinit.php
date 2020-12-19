<?php

use hiweb\components\Console\ConsoleFactory;
use hiweb\components\Images\ImagesFactory;


require_once dirname(dirname(__DIR__)) . '/hiweb-core-4.php';
define('SHORTINIT', true);
require_once \hiweb\core\Paths\PathsFactory::get_root_path() . '/wp-config.php';
///

if ( !isset($_POST['images']) || !is_array($_POST['images'])) exit(json_encode([ 'success' => false, 'message' => 'Не переданы данные изображений для загрузки в $_POST[images]' ]));
ImagesFactory::$useImageDefer = false;
$images = [];
$images_urls = [];
$images_error = [];
$request_urls = ImagesFactory::$requested_urls;
foreach ($_POST['images'] as $defer_id => $defer_data) {
    if ( !isset($defer_data['id']) || !isset($defer_data['dimension'])) {
        if (WP_DEBUG) $images_error[$defer_id] = $defer_data;
        continue;
    }
    $image = get_image($defer_data['id']);
    ///is webp support
    $images[$defer_id] = \hiweb\components\HTML_CSS_JS_Minifier::minify_html($image->get_html($defer_data['dimension'], isset($defer_data['attributes']) ? $defer_data['attributes'] : [], null, get_client()->is_support_WebP()));
    $images_urls[$defer_id] = array_values( array_diff(ImagesFactory::$requested_urls, $request_urls) );
    $request_urls = ImagesFactory::$requested_urls;
}
ob_start();
ConsoleFactory::the();
$js = ob_get_clean();
exit(json_encode([ 'success' => true, 'images' => $images, 'images_src' => $images_urls, 'images_error' => $images_error, 'js' => $js ]));
