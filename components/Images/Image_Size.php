<?php

namespace hiweb\components\Images;


use hiweb\components\Console\ConsoleFactory;
use hiweb\core\hidden_methods;
use hiweb\core\Paths\Path;
use hiweb\core\Paths\PathsFactory;
use stdClass;


class Image_Size {

    use hidden_methods;


    private $Image;
    /** @var stdClass */
    private $size_raw;
    private $size_name;
    protected $file_path;
    protected $file_path_webp;
    protected $width = 0;
    protected $height = 0;
    protected $crop = 1;


    public function __construct(Image $Image, $sizeRawData, $size_name = '') {
        $this->Image = $Image;
        $this->size_raw = (object)$sizeRawData;
        $this->size_name = $size_name;
        ///FILE PATH
        if ( !property_exists($this->get_size_raw(), 'file') || $this->get_size_raw()->file == '') {
            if (property_exists($this->size_raw, 'width') && $this->size_raw->width > 0) $this->width = $this->size_raw->width;
            if (property_exists($this->size_raw, 'height') && $this->size_raw->height > 0) $this->height = $this->size_raw->height;
            $this->file_path = $this->Image->path()->file()->get_dirname() . '/' . $this->Image->path()->file()->get_filename() . '-' . $this->width . 'x' . $this->height . '.' . $this->Image->path()->file()->get_extension();
        } else {
            if (property_exists($this->size_raw, 'width') && $this->size_raw->width > 0) $this->width = $this->size_raw->width;
            if (property_exists($this->size_raw, 'height') && $this->size_raw->height > 0) $this->height = $this->size_raw->height;
            $this->file_path = $this->Image->path()->file()->get_dirname() . '/' . $this->get_size_raw()->file;
        }
        $this->file_path_webp = $this->path()->file()->get_dirname() . '/' . $this->path()->file()->get_filename() . '.webp';
        ///
        if (property_exists($this->get_size_raw(), 'crop')) {
            if ($this->get_size_raw()->crop === true) {
                $this->crop = 0;
            } elseif ($this->get_size_raw()->crop === false) {
                $this->crop = - 1;
            } elseif ($this->get_size_raw()->crop === - 1 && $this->get_size_raw()->crop === 1) {
                $this->crop = $this->get_size_raw()->crop;
            } else {
                $this->crop = 0;
            }
        }
    }


    /**
     * @return int
     * @deprecated use get_width()
     */
    protected function width(): int {
        return $this->get_width();
    }


    /**
     * @return int
     */
    public function get_width(): int {
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
     * @return int
     */
    public function get_height(): int {
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
        if ($this->get_width() == 0 || $this->get_height() == 0) return 0;
        return $this->get_width() / $this->get_height();
    }


    /**
     * @return bool|int
     */
    public function get_crop_mode() {
        return $this->crop;
    }


    /**
     * @return array
     * @deprecated us get_dimension()
     */
    protected function dimension(): array {
        return $this->get_dimension();
    }


    /**
     * @return array
     */
    public function get_dimension(): array {
        return [ $this->get_width(), $this->get_height(), $this->get_crop_mode() ];
    }


    /**
     * Return image area size
     * @return int
     */
    public function get_area(): int {
        return $this->get_width() * $this->get_height();
    }


    /**
     * @return string
     */
    public function get_name(): string {
        return $this->size_name;
    }


    /**
     * @return Image
     */
    public function image(): Image {
        return $this->Image;
    }


    /**
     * @return stdClass
     */
    public function get_size_raw() {
        return $this->size_raw;
    }


    /**
     * @return string
     */
    public function get_file_path(): string {
        return $this->file_path;
    }


    /**
     * @return string
     */
    public function get_file_path_webp(): string {
        return $this->file_path_webp;
    }


    /**
     * @return Path
     */
    public function path(): Path {
        return PathsFactory::get($this->get_file_path());
    }


    /**
     * @return Path
     */
    public function path_webp(): Path {
        return PathsFactory::get($this->get_file_path_webp());
    }


    /**
     * Return url to image file
     * @return string
     */
    public function get_src(): string {
        return $this->path()->get_url();
    }


    /**
     * Return url to image file (WebP format, if exists)
     * @return string
     */
    public function get_src_webp(): string {
        if ( !$this->path_webp()->file()->is_exists()) {
            $this->make_file(true, 75, true);
        }
        return $this->path_webp()->get_url();
    }


    /**
     * @return bool
     */
    public function is_exists(): bool {
        return $this->file_path != '' && $this->path()->file()->is_file() && $this->path()->file()->is_exists();
    }


    public function is_webp_exists() {

    }


    /**
     * @param bool      $force_renew
     * @param int       $quality_jpg_png
     * @param null|bool $tryMakeWebP - set NULL to use default option 'ImagesFactory::$useStandardExtensions'
     * @return bool|int
     */
    public function make_file($force_renew = false, $quality_jpg_png = 75, $tryMakeWebP = null) {
        if ( !$this->Image->is_exists()) return 0;
        if ( !$force_renew && $this->path()->file()->is_exists()) return 0;
        $R = $this->Image->path()->image()->resize($this->get_width(), $this->get_height(), $this->get_file_path(), $quality_jpg_png, is_null($tryMakeWebP) ? !ImagesFactory::$useStandardExtensionsOnly : $tryMakeWebP);
        if ($R == true) {
            ConsoleFactory::add('New image file created', 'info', __METHOD__, $this->get_file_path(), true);
            $this->Image->_update_image_sizes_meta();
        } else {
            ConsoleFactory::add('Error while create new image file', 'warn', __METHOD__, $this->get_file_path(), true);
        }
        return $R;
    }


}