<?php

namespace hiweb\core\Paths;


/**
 * Class Path_Image
 * @package hiweb\core\Paths
 * @version 1.1
 */
class Path_Image {

    /**
     * @var Path
     */
    private $Path;
    /** @var int */
    private $cache_image_width;
    /** @var int */
    private $cache_image_height;


    public function __construct(Path $Path) {
        $this->Path = $Path;
    }


    /**
     * @return Path
     */
    public function path(): Path {
        return $this->Path;
    }


    /**
     * @return Path_File
     */
    public function file(): Path_File {
        return $this->path()->file();
    }


    /**
     * @return Path_Url
     */
    public function url(): Path_Url {
        return $this->path()->url();
    }


    /**
     * @return string
     */
    public function get_path(): string {
        return $this->file()->get_absolute_path();
    }


    /**
     * @return mixed|void
     */
    public function get_url() {
        return $this->path()->url()->get();
    }


    /**
     * @return int
     * @deprecated use get_width()
     */
    protected function width(): int {
        return $this->get_width();
    }


    /**
     * Return image width if file exists
     * @return int
     */
    public function get_width(): int {
        if ( !is_int($this->cache_image_width)) {
            $this->cache_image_width = 0;
            $this->cache_image_height = 0;
            if ($this->file()->is_readable()) {
                $size = getimagesize($this->file()->get_path());
                if (is_array($size)) [ $this->cache_image_width, $this->cache_image_height ] = $size;
            }
        }
        return $this->cache_image_width;
    }


    /**
     * @return int
     * @deprecated use get_height()
     */
    protected function height(): int {
        return $this->get_height();
    }


    /**
     * Return image height if file exists
     * @return int
     */
    public function get_height(): int {
        if ( !is_int($this->cache_image_height)) {
            $this->cache_image_width = 0;
            $this->cache_image_height = 0;
            if ($this->file()->is_readable()) {
                $size = getimagesize($this->file()->get_path());
                if (is_array($size)) [ $this->cache_image_width, $this->cache_image_height ] = $size;
            }
        }
        return $this->cache_image_height;
    }


    /**
     * @return int
     * @deprecated use get_aspect()
     */
    protected function aspect(): int {
        return $this->get_aspect();
    }


    /**
     * Return aspect of image if file exists
     * @return float|int
     */
    public function get_aspect() {
        if ($this->get_width() == 0 || $this->get_height() == 0) return 0;
        return $this->get_width() / $this->get_height();
    }


    /**
     * Return current image mime type
     * @return bool|mixed
     */
    public function get_mime_type() {
        if ($this->file()->get_extension() == 'jxr') return 'image/jxr';
        $mimes = [
            IMAGETYPE_GIF => "image/gif",
            IMAGETYPE_JPEG => "image/jpg",
            IMAGETYPE_PNG => "image/png",
            IMAGETYPE_SWF => "image/swf",
            IMAGETYPE_PSD => "image/psd",
            IMAGETYPE_BMP => "image/bmp",
            IMAGETYPE_TIFF_II => "image/tiff",
            IMAGETYPE_TIFF_MM => "image/tiff",
            IMAGETYPE_JPC => "image/jpc",
            IMAGETYPE_JP2 => "image/jp2",
            IMAGETYPE_JPX => "image/jpx",
            IMAGETYPE_JB2 => "image/jb2",
            IMAGETYPE_SWC => "image/swc",
            IMAGETYPE_IFF => "image/iff",
            IMAGETYPE_WBMP => "image/wbmp",
            IMAGETYPE_XBM => "image/xbm",
            IMAGETYPE_ICO => "image/ico",
        ];
        if (($this->file()->is_readable() && $image_type = exif_imagetype($this->file()->get_path())) && (array_key_exists($image_type, $mimes))) {
            return $mimes[$image_type];
        } else {
            return 'image/' . $this->file()->get_extension();
        }
    }


    /**
     * Resize/recompress current file
     * @param int  $dest_width           - destination file width or leave 0 for current file width
     * @param int  $dest_height          - destination file height or leave 0 for current file height
     * @param null $dest_file_path       - destination file or leave empty for select current file path
     * @param int  $quality_jpg_png_webp - 0...100 quality for jpg or png files
     * @param bool $tryMakeWebp
     * @return bool
     * @version 1.2
     */
    public function resize($dest_width = 0, $dest_height = 0, $dest_file_path = null, $quality_jpg_png_webp = 75, $tryMakeWebp = true) {
        if ($this->file()->is_image() && $this->get_aspect() != 0) {
            if ( !is_string($dest_file_path) || strlen($dest_file_path) < 2) $dest_file_path = $this->file()->get_path();
            if ($dest_width > $this->get_width()) $dest_width = $this->get_width();
            if ($dest_height > $this->get_height()) $dest_height = $this->get_height();
            $dest_width = (int)$dest_width == 0 ? $this->get_width() : (int)$dest_width;
            $dest_height = (int)$dest_height == 0 ? $this->get_height() : (int)$dest_height;
            $dest_aspect = $dest_width / $dest_height;
            //GD
            if (extension_loaded('gd')) {
                ///
                switch($this->get_mime_type()) {
                    case 'image/jpe':
                    case 'image/jpeg':
                    case 'image/jpg':
                        $src_image = imagecreatefromjpeg($this->file()->get_path());
                        break;
                    case 'image/png':
                        $src_image = imagecreatefrompng($this->file()->get_path());
                        break;
                    case 'image/gif':
                        $src_image = imagecreatefromgif($this->file()->get_path());
                        break;
                    default:
                        return - 1;
                }
                ///calculate dimensions
                $src_x = 0;
                $src_y = 0;
                $src_width = $this->get_width();
                $src_height = $this->get_height();
                if ($this->get_aspect() < $dest_aspect) {
                    $proportions = $this->get_width() / $dest_width;
                    $src_height = $dest_height * $proportions;
                    $src_y = ($this->get_height() - $src_height) / 2;
                } elseif ($this->get_aspect() > $dest_aspect) {
                    $proportions = $this->get_height() / $dest_height;
                    $src_width = $dest_width * $proportions;
                    $src_x = ($this->get_width() - $src_width) / 2;
                }
                ///create new source image
                $image_gd_new = imagecreatetruecolor($dest_width, $dest_height);
                ///Use alpha chanel
                imagealphablending($image_gd_new, false);
                imagesavealpha($image_gd_new, true);
                ///resize
                imagecopyresampled($image_gd_new, $src_image, 0, 0, $src_x, $src_y, $dest_width, $dest_height, $src_width, $src_height);
                $B = - 2;
                imageinterlace($image_gd_new, true);
                switch($this->get_mime_type()) {
                    case 'image/jpg':
                        $B = imagejpeg($image_gd_new, $dest_file_path, $quality_jpg_png_webp);
                        break;
                    case 'image/png':
                        $B = imagepng($image_gd_new, $dest_file_path);
                        break;
                    case 'image/gif':
                        $B = imagegif($image_gd_new, $dest_file_path);
                        break;
                }
                if ($tryMakeWebp && function_exists('imagewebp')) {
                    $dest_file = get_file($dest_file_path);
                    imagewebp($image_gd_new, $dest_file->get_dirname() . '/' . $dest_file->get_filename() . '.webp', $quality_jpg_png_webp);
                }
                imagedestroy($src_image);
                imagedestroy($image_gd_new);
                return $B;
            }
        }
        return - 3;
    }

}