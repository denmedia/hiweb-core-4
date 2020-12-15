<?php

namespace hiweb\components\Images;


use hiweb\core\Cache\CacheFactory;
use hiweb\core\Paths\Path;
use hiweb\core\Paths\PathsFactory;


class ImagesFactory {

    /** @var Path */
    protected static $default_image_file;
    protected static $default_defer_file;
    public static $makeFileIfNotExists = true;
    public static $standardExtensions = [ 'jpg', 'jpeg', 'jpe', 'png', 'gif' ];
    /** @var bool If set to FALSE try make WebP file type with standards (etc. JPEG or PNG) */
    public static $useWebPExtension = true;
    public static $useImageDefer = true;
    public static $usePictureHtmlTag = true;

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

}