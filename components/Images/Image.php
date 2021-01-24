<?php

namespace hiweb\components\Images;


use hiweb\core\ArrayObject\ArrayObject;
use hiweb\core\Cache\CacheFactory;
use hiweb\core\hidden_methods;
use hiweb\core\Paths\Path;
use hiweb\core\Paths\PathsFactory;
use stdClass;
use WP_Post;


/**
 * Class Image
 * @package hiweb\components\Images
 * @version 1.2
 */
class Image {

    use hidden_methods;


    /**
     * @var int
     */
    protected $attachment_ID;
    protected $file;
    protected $width = 0;
    protected $height = 0;
    protected $aspect = 0;
    protected $alt;


    public function __construct($attachment_ID = 0) {
        $this->attachment_ID = intval($attachment_ID);
    }


    /**
     * Return original src
     * @return string
     */
    public function __toString(): string {
        //return $this->html('large');
        return $this->is_attachment_exists() ? $this->get_original_src() : ImagesFactory::get_default_src();
    }


    /**
     * @return stdClass|WP_Post
     * @version 1.1
     */
    public function wp_post() {
        return CacheFactory::get($this->attachment_ID, __METHOD__, function() {
            $test_wp_post = null;
            if (function_exists('get_post')) {
                $test_wp_post = get_post($this->attachment_ID);
            } else {
                global $wpdb;
                $query = $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE ID='%s' AND post_type='attachment'", $this->attachment_ID);
                $result = $wpdb->get_results($query);
                if (is_array($result) && count($result) > 0) {
                    $test_wp_post = (object)current($result);
                }
            }
            if (is_null($test_wp_post) || intval($test_wp_post->ID) === 0) {
                return (object)[ 'ID' => 0 ];
            }
            return $test_wp_post;
        })->get_value();
    }


    /**
     * @return bool
     */
    public function is_attachment_exists(): bool {
        return $this->attachment_ID > 0 && $this->wp_post()->post_type == 'attachment';
    }


    /**
     * @return int
     */
    public function get_attachment_ID(): int {
        return $this->attachment_ID;
    }


    /**
     * @return stdClass
     */
    public function get_attachment_meta(): stdClass {
        return CacheFactory::get($this->attachment_ID, __METHOD__, function() {
            $R = (object)[];
            if ($this->attachment_ID > 0) {
                if (function_exists('wp_get_attachment_metadata')) {
                    $R = (object)wp_get_attachment_metadata($this->get_attachment_ID());
                } else {
                    global $wpdb;
                    $query = $wpdb->prepare("SELECT * FROM {$wpdb->postmeta} WHERE post_id='%s' AND meta_key IN ('_wp_attachment_metadata','_wp_attachment_image_alt')", $this->get_attachment_ID());
                    $result = $wpdb->get_results($query);
                    if (is_array($result) && count($result) > 0) {
                        foreach ($result as $item) {
                            switch($item->meta_key) {
                                case '_wp_attachment_metadata':
                                    $R = (object)unserialize($item->meta_value);
                                    break;
                                case '_wp_attachment_image_alt':
                                    $this->alt = $item->meta_value;
                                    break;
                            }
                        }
                    }
                }
            }
            return $R;
        })->get_value();
    }


    public function get_image_meta() {
        return CacheFactory::get($this->attachment_ID, __METHOD__, function() {
            if (property_exists($this->get_attachment_meta(), 'image_meta')) {
                return (object)$this->get_attachment_meta()->image_meta;
            }
            return (object)[];
        })->get_value();
    }


    /**
     * @return Path
     */
    public function path(): Path {
        if ($this->file == '' && property_exists($this->get_attachment_meta(), 'file')) {
            if (property_exists($this->get_attachment_meta(), 'original_image')) {
                $file_name = dirname($this->get_attachment_meta()->file) . '/' . $this->get_attachment_meta()->original_image;
            } else {
                $file_name = $this->get_attachment_meta()->file;
            }
            $this->file = ((object)wp_upload_dir())->basedir . '/' . $file_name;
        }
        return PathsFactory::get($this->file);
    }


    /**
     * @return Image_Sizes
     */
    public function sizes(): Image_Sizes {
        return CacheFactory::get($this->attachment_ID, __METHOD__, function() {
            return new Image_Sizes($this);
        })->get_value();
    }


    /**
     * Return TRUE, if file is exists
     * @return bool
     */
    public function is_exists(): bool {
        return $this->is_attachment_exists() && $this->path()->file()->is_exists();
    }


    /**
     * @return int
     * @deprecated use get_width()
     */
    protected function width(): int {
        return $this->get_width();
    }


    /**
     * @return mixed
     */
    public function get_width(): int {
        if ($this->width == 0 && property_exists($this->get_attachment_meta(), 'width')) {
            $this->width = $this->get_attachment_meta()->width;
        }
        return $this->width;
    }


    /**
     * @return int
     * @deprecated use get_height()
     */
    protected function height(): int {
        return $this->get_height();
    }


    /**
     * @return mixed
     */
    public function get_height(): int {
        if ($this->height == 0 && property_exists($this->get_attachment_meta(), 'height')) {
            $this->height = $this->get_attachment_meta()->height;
        }
        return $this->height;
    }


    /**
     * @return float|int
     * @deprecated use get_aspect()
     */
    protected function aspect() {
        return $this->get_aspect();
    }


    /**
     * @return float|int
     */
    public function get_aspect() {
        if ($this->aspect == 0 && $this->get_height() > 0) {
            $this->aspect = $this->get_width() / $this->get_height();
        }
        return $this->aspect;
    }


    /**
     * @return bool|mixed|string
     */
    public function get_mime_type() {
        return $this->path()->image()->get_mime_type();
    }


    /**
     * @param false $esc_attr
     * @return string
     * @deprecated use get_alt(...)
     */
    protected function alt($esc_attr = false): string {
        return $this->get_alt($esc_attr);
    }


    /**
     * @param bool $esc_attr
     * @return string
     */
    public function get_alt($esc_attr = false): string {
        $this->get_attachment_meta();
        $R = (string)$this->alt;
        return $esc_attr ? esc_attr($R) : $R;
    }


    /**
     * @param false $esc_attr
     * @return string
     * @deprecated use get_title(...)
     */
    protected function title($esc_attr = false): string {
        return $this->get_title($esc_attr);
    }


    /**
     * @param bool $esc_attr
     * @return string
     */
    public function get_title($esc_attr = false): string {
        return $esc_attr ? esc_attr($this->wp_post()->post_title) : $this->wp_post()->post_title;
    }


    /**
     * @param bool $esc_attr
     * @param bool $filtered
     * @return string
     * @deprecated use get_description(...)
     */
    protected function description($esc_attr = false, $filtered = true): string {
        return $this->get_description($esc_attr);
    }


    /**
     * Return  post (attachment) content
     * @param bool $esc_attr
     * @param bool $filtered - true => use post_content_filtered / false => use post _content
     * @return string
     */
    public function get_description($esc_attr = false, $filtered = true): string {
        if ($this->is_attachment_exists()) {
            $post_content = $filtered ? $this->wp_post()->post_content_filtered : $this->wp_post()->post_content;
            return $esc_attr ? esc_attr($post_content) : $post_content;
        }
        return '';
    }


    /**
     * @deprecated use get_caption()
     */
    protected function caption(): string {
        return $this->get_caption($esc_attr = false);
    }


    /**
     * @param bool $esc_attr
     * @return string
     */
    public function get_caption($esc_attr = false): string {
        if ($this->is_attachment_exists()) {
            return $esc_attr ? esc_attr($this->wp_post()->post_excerpt) : $this->wp_post()->post_excerpt;
        }
        return '';
    }


    /**
     * @return bool|int
     */
    public function _update_image_sizes_meta() {
        $meta = (array)$this->get_attachment_meta();
        $meta['sizes'] = [];
        foreach ($this->sizes()->get_sizes() as $size_name => $Image_Size) {
            if ($size_name == 'original') continue;
            $meta['sizes'][$size_name] = [
                'file' => $Image_Size->path()->file()->get_basename(),
                'width' => $Image_Size->get_width(),
                'height' => $Image_Size->get_height(),
                'mime-type' => $Image_Size->path()->image()->get_mime_type()
            ];
        }
        if (function_exists('wp_update_attachment_metadata')) {
            return wp_update_attachment_metadata($this->get_attachment_id(), $meta);
        } else {
            global $wpdb;
            $query = $wpdb->prepare("UPDATE {$wpdb->postmeta} SET _wp_attachment_metadata='%s' WHERE post_id='%s'", serialize($meta), $this->get_attachment_ID());
            return $wpdb->query($query);
        }
    }


    /**
     * @param string|array $size
     * @param null|bool    $makeIfNotExist
     * @param null         $tryWebP
     * @return string
     * @version 1.1
     */
    public function get_src($size, $makeIfNotExist = null, $tryWebP = null): string {
        return $this->is_exists() ? (($tryWebP && get_client()->is_support_WebP()) ? $this->sizes()->get($size, $makeIfNotExist)->path_webp()->get_url() : $this->sizes()->get($size, $makeIfNotExist)->path()->get_url()) : ImagesFactory::get_default_src();
    }


    /**
     * @param     $width
     * @param     $height
     * @param int $resize_mode
     * @return string
     * @deprecated
     */
    public function get_similar_src($width, $height, $resize_mode = 1): string {
        return $this->get_src([ $width, $height, $resize_mode ], false);
    }


    /**
     * @param      $size
     * @param bool $make_new_file
     * @return string
     */
    public function get_path($size, $make_new_file = true): string {
        return $this->sizes()->get($size, $make_new_file)->get_path_absolute();
    }


    /**
     * @param      $size
     * @param bool $make_new_file
     * @return string
     */
    public function get_path_relative($size, $make_new_file = true): string {
        return $this->sizes()->get($size, $make_new_file)->path()->get_path_relative();
    }


    /**
     * Return original URL or Absolute PATH
     * @param bool $return_path
     * @return string
     */
    public function get_original_src($return_path = false): string {
        return $return_path ? $this->path()->get_absolute_path() : $this->path()->get_url();
    }


    /**
     * @param string $dimensionsOrSizeName
     * @param array  $attr_picture
     * @param array  $attr_img
     * @param bool   $make_new_file
     * @return string
     * @deprecated use get_html_picture(...)
     */
    protected function html_picture($dimensionsOrSizeName = 'thumbnail', $attr_picture = [], $attr_img = [], $make_new_file = true): string {
        return $this->get_html_picture($dimensionsOrSizeName, $attr_picture, $attr_img, $make_new_file);
    }


    /**
     * @param string $dimensionsOrSizeName
     * @param array  $pictureAttributes
     * @param array  $imgAttributes
     * @param bool   $make_new_file
     * @param bool   $tryWebP
     * @return string
     * @version 2.0
     */
    public function get_html_picture($dimensionsOrSizeName = 'thumbnail', $pictureAttributes = [], $imgAttributes = [], $make_new_file = true, $tryWebP = false): string {
        $dimension = $this->sizes()->get_calculate_size($dimensionsOrSizeName);
        ob_start();
        if ( !$this->is_exists()) {
            include __DIR__ . '/templates/noimg.php';
        } else {
            $size = $this->sizes()->get([ 50, 50 ], false);
            include __DIR__ . '/templates/picture.php';
        }
        return ob_get_clean();
    }


    /**
     * @param string $dimensionsOrSizeName
     * @param array  $attributes
     * @param bool   $make_new_file
     * @return mixed|string
     * @deprecated use get_html_img(...)
     */
    protected function html_img($dimensionsOrSizeName = 'thumbnail', $attributes = [], $make_new_file = true): string {
        return $this->get_html_img($dimensionsOrSizeName, $attributes, $make_new_file);
    }


    /**
     * @param string $dimensionsOrSizeName
     * @param array  $attributes
     * @param bool   $make_new_file
     * @param bool   $tryWebP
     * @return mixed|string
     * @version 2.0
     */
    public function get_html_img($dimensionsOrSizeName = 'thumbnail', $attributes = [], $make_new_file = true, $tryWebP = false): string {
        $dimension = $this->sizes()->get_calculate_size($dimensionsOrSizeName);
        ob_start();
        if ( !$this->is_exists()) {
            include __DIR__ . '/templates/noimg.php';
        } else {
            include __DIR__ . '/templates/img.php';
        }
        return ob_get_clean();
    }


    /**
     * @param string $dimensionsOrSizeName
     * @param array  $attributes
     * @param bool   $make_new_file
     * @param bool   $tryWepB
     * @return string
     * @deprecated use get_html(...)
     */
    protected function html($dimensionsOrSizeName = 'thumbnail', $attributes = [], $make_new_file = true, $tryWepB = false): string {
        return $this->get_html($dimensionsOrSizeName, $attributes, $make_new_file, $tryWepB);
    }


    /**
     * Return html for defer image
     * @param       $sizeOrName
     * @param array $attributes
     * @return false|string
     */
    public function get_html_defer($sizeOrName, $attributes = []) {
        ob_start();
        $dimension = $this->sizes()->get_calculate_size($sizeOrName);
        if ( !$this->is_exists()) {
            include __DIR__ . '/templates/noimg.php';
        } else {
            $size = $this->sizes()->get([ 50, 50 ], true);
            include __DIR__ . '/templates/defer.php';
        }
        return ob_get_clean();
    }


    /**
     * @param string $dimensionsOrSizeName
     * @param array  $attributes
     * @param bool   $make_new_file
     * @param null   $tryWepB
     * @return string
     */
    public function get_html($dimensionsOrSizeName = 'thumbnail', $attributes = [], $make_new_file = true, $tryWepB = false): string {
        if (ImagesFactory::$useImageDefer) {
            return $this->get_html_defer($dimensionsOrSizeName, $attributes);
        } else {
            if (ImagesFactory::$usePictureHtmlTag) {
                return $this->get_html_picture($dimensionsOrSizeName, $attributes, $attributes, $make_new_file, $tryWepB);
            } else {
                return $this->get_html_img($dimensionsOrSizeName, $attributes, $make_new_file, $tryWepB);
            }
        }
    }


}