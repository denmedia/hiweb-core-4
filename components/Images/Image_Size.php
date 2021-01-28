<?php

namespace hiweb\components\Images;


use hiweb\components\Console\ConsoleFactory;
use hiweb\core\hidden_methods;
use hiweb\core\Paths\Path;


/**
 * Class Image_Size
 * @package hiweb\components\Images
 * @version 1.1
 */
class Image_Size {

    use hidden_methods;


    /** @var Image */
    private $Image;
    /**  @var Path|null */
    private $Path;
    /**  @var Path|null */
    private $Path_WebP;
    /** @var string */
    private $fileRelativePath;
    /** @var string */
    private $sizeName;
    /** @var null|int */
    protected $width;
    /** @var null|int */
    protected $height;
    /** @var null|bool */
    protected $crop;
    /** @var null|int */
    protected $resizeMode;


    public function __construct(Image $Image, $fileRelativePath) {
        $this->Image = $Image;
        $this->fileRelativePath = $this->Image->path()->file()->get_dirname() . '/' . $fileRelativePath;
    }


    /**
     * Return Instance of Image
     * @return Image
     */
    public function image(): Image {
        return $this->Image;
    }


    /**
     * Return Image Instance of Path
     * @return Path
     */
    public function path(): Path {
        if ( !$this->Path instanceof Path) $this->Path = get_path($this->fileRelativePath);
        return $this->Path;
    }


    /**
     * @return bool
     */
    public function is_exists(): bool {
        return $this->path()->file()->is_file();
    }


    /**
     * @return string
     */
    public function get_path_absolute(): string {
        return $this->path()->get_absolute_path();
    }


    /**
     * Return url to image file
     * @return string
     */
    public function get_src(): string {
        $url = $this->path()->get_url();
        ImagesFactory::$requested_urls[] = $url;
        return $url;
    }


    /**
     * Return WebP Instance of Path
     * @return Path
     */
    public function path_webp(): Path {
        if ( !$this->Path_WebP instanceof Path) {
            $file = $this->path()->file();
            $this->Path_WebP = get_path($file->get_dirname() . '/' . $file->get_filename() . '.webp');
        }
        return $this->Path_WebP;
    }


    /**
     * @return bool|null
     */
    public function is_exists_webp(): ?bool {
        return $this->path_webp()->file()->is_exists();
    }


    /**
     * @return string
     */
    public function get_path_absolute_webp(): string {
        return $this->path_webp()->get_absolute_path();
    }


    /**
     * Return url to image file (WebP format, if exists)
     * @return string
     */
    public function get_src_webp(): string {
        $url = $this->path_webp()->get_url();
        ImagesFactory::$requested_urls[] = $url;
        return $url;
    }


    public function set_name($sizeName) {
        $this->sizeName = $sizeName;
    }


    /**
     * @return string
     */
    public function get_name(): string {
        return (string)$this->sizeName;
    }


    /**
     * @return string
     */
    public function get_url(): string {
        return $this->path()->get_url();
    }


    /**
     * @param int           $width
     * @param int           $height
     * @param null|bool|int $cropOrResizeMode
     */
    public function set_dimension(int $width, int $height, $cropOrResizeMode = null) {
        $this->width = $width;
        $this->height = $height;
        $this->set_crop($cropOrResizeMode);
    }


    public function set_crop($cropOrResizeMode) {
        if (is_bool($cropOrResizeMode)) {
            $this->crop = $cropOrResizeMode;
            $this->resizeMode = $cropOrResizeMode ? 0 : - 1;
        } elseif (is_numeric($cropOrResizeMode)) {
            $this->crop = ($cropOrResizeMode == 0);
            if ($cropOrResizeMode < 0) {
                $this->resizeMode = - 1;
            } elseif ($cropOrResizeMode > 0) {
                $this->resizeMode = 1;
            }
        }
    }


    /**
     * @return int
     */
    public function get_width(): ?int {
        if ( !is_int($this->width)) {
            if ($this->path() instanceof Path && $this->path()->file()->is_exists() && $this->path()->is_image() && $this->path()->file()->is_exists()) {
                $this->width = $this->path()->image()->get_width();
                $this->height = $this->path()->image()->get_height();
            }
        }
        return $this->width;
    }


    /**
     * @return int
     */
    public function get_height(): ?int {
        if ( !is_int($this->height)) {
            if ($this->path() instanceof Path && $this->path()->file()->is_exists() && $this->path()->is_image() && $this->path()->file()->is_exists()) {
                $this->width = $this->path()->image()->get_width();
                $this->height = $this->path()->image()->get_height();
            }
        }
        return $this->height;
    }


    /**
     * @return float
     */
    public function get_aspect(): float {
        if ($this->get_width() == 0 || $this->get_height() == 0) return 0;
        return $this->get_width() / $this->get_height();
    }


    /**
     * @return bool|int
     */
    public function get_crop() {
        return $this->crop;
    }


    /**
     * @return array
     */
    public function get_dimension(): array {
        return [ $this->get_width(), $this->get_height(), $this->get_crop() ];
    }


    /**
     * Return image area size
     * @return int
     */
    public function get_area(): int {
        return $this->get_width() * $this->get_height();
    }


    /**
     * @param bool $force_renew
     * @param int  $quality_jpg_png_webp
     * @param null $tryMakeWebP
     * @return bool|int
     */
    public function make($force_renew = false, $quality_jpg_png_webp = 75, $tryMakeWebP = null) {
        ///skip if original file is not exists
        if ( !$this->Image->is_exists()) return 0;
        ///check try make webp file format
        if (is_null($tryMakeWebP)) $tryMakeWebP = ImagesFactory::$useWebPExtension && function_exists('imagewebp') && ($force_renew || !$this->is_exists_webp());
        ///skip make file
        if ( !$force_renew && $this->is_exists() && !$tryMakeWebP && $this->is_exists_webp()) return 0;
        ///
        $new_file_path = $this->get_path_absolute();
        if ( !$force_renew && $this->is_exists() && $tryMakeWebP) {
            $new_file_path = $this->get_path_absolute_webp();
        }
        ///Process Resize
        $R = $this->Image->path()->image()->resize($this->get_width(), $this->get_height(), $new_file_path, $quality_jpg_png_webp, $tryMakeWebP);
        if ($R === true || (is_array($R) && count($R) > 0)) {
            console_info('New image file created', __METHOD__, [ 'result' => $R, $new_file_path ]);
            $this->Image->_update_image_sizes_meta();
        } else {
            console_warn('Error while create new image file', __METHOD__, [ 'result' => $R, $new_file_path ]);
        }
        return $R;
    }


}