<?php

namespace hiweb\components\Images;


use hiweb\core\Cache\CacheFactory;
use hiweb\core\hidden_methods;
use hiweb\core\Paths\Path;
use hiweb\core\Paths\PathsFactory;


/**
 * Class ImagesFactory
 * @package hiweb\components\Images
 * @version 2.0
 */
class ImagesFactory {

    use hidden_methods;


    /** @var Path */
    protected static $default_image_file;
    protected static $default_defer_file;
    public static $makeFileIfNotExists = false;
    public static $standardExtensions = [ 'jpg', 'jpeg', 'jpe', 'png', 'gif' ];
    /** @var bool If set to FALSE try make WebP file type with standards (etc. JPEG or PNG) */
    public static $useWebPExtension = false;
    public static $useImageDefer = false;
    public static $usePictureHtmlTag = false;
    public static $useWidthHeightProps = true;

    public static $requested_urls = [];


    /**
     * @param $idOrUrl
     * @return Image
     */
    static function get($idOrUrl): Image {
        $attachIdPathOrUrl = get_attachment_id_from_url($idOrUrl);
        return CacheFactory::get($attachIdPathOrUrl, __CLASS__ . '::$images', function() {
            return new Image(func_get_arg(0));
        }, [ $attachIdPathOrUrl ])->get_value();
    }


    /**
     * Set default image url/path
     * @param string|int $urlOrPathOrAttachID
     * @return bool
     */
    static public function set_default_src($urlOrPathOrAttachID): bool {
        $file = PathsFactory::get($urlOrPathOrAttachID);
        if ($file->file()->is_readable()) {
            self::$default_image_file = $file;
            return true;
        }
        return false;
    }


    /**
     * Set default defer image url/path
     * @param string|int $urlOrPathOrAttachID
     * @return bool
     */
    static public function set_defer_src($urlOrPathOrAttachID): bool {
        $file = PathsFactory::get($urlOrPathOrAttachID);
        if ($file->file()->is_readable()) {
            self::$default_defer_file = $file;
            return true;
        }
        return false;
    }


    /**
     * @param bool $force_hiweb_default
     * @return bool|string
     */
    static public function get_default_src($force_hiweb_default = false) {
        if ($force_hiweb_default || !self::$default_image_file instanceof Path) {
            return PathsFactory::get(__DIR__ . '/img/noimg.svg')->get_url();
        }
        return self::$default_image_file->get_url();
    }


    /**
     * @param bool $force_hiweb_default
     * @return bool|string
     */
    static public function get_default_defer_src($force_hiweb_default = false) {
        if ($force_hiweb_default || !self::$default_defer_file instanceof Path) {
            return PathsFactory::get(__DIR__ . '/img/image-loading.svg')->get_url();
        }
        return self::$default_defer_file->get_url();
    }

    static protected function _makeNewDimensionFile(){
        self::$makeFileIfNotExists = true;
    }

    static protected function _useImageDefer() {
        include_frontend_js(__DIR__ . '/defer.min.js', 'jquery-core');
        add_action('wp_head', function() {
            ?>
            <script>
                let hiweb_imageDefer_ajax_url = '<?=get_url(__DIR__ . '/ajax_shortinit.php')?>';
                let hiweb_imageDefer_usePictureHtml = <?= json_encode(ImagesFactory::$usePictureHtmlTag) ?>;
                let hiweb_imageDefer_useWidthHeightProps = <?= json_encode(ImagesFactory::$useWidthHeightProps) ?>;
            </script><?php
        });
        self::$useImageDefer = true;
    }


    static protected function _usePictureHtmlTag() {
        self::$usePictureHtmlTag = true;
    }


    static protected function _useWebPExtension() {
        self::$useWebPExtension = true;
    }

}