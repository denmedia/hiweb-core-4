<?php

namespace hiweb\components\Images;


use hiweb\core\Cache\CacheFactory;
use hiweb\core\hidden_methods;
use hiweb\core\Paths\PathsFactory;
use stdClass;


/**
 * Class control Image_Sizes
 * @package hiweb\components\Images
 * @version 1.1
 */
class Image_Sizes {

    use hidden_methods;


    protected $Image;
    protected $sizes;


    public function __construct(Image $Image) {
        $this->Image = $Image;
    }


    /**
     * Return calculate dimension by current image file
     * @param string|array $sizeOrName
     * @return stdClass
     */
    public function get_calculate_size($sizeOrName = 'thumbnail'): stdClass {
        if (is_string($sizeOrName)) {
            $sizeOrName = get_dimension_from_wp_register_size($sizeOrName);
        }
        if (is_array($sizeOrName) || is_object($sizeOrName)) {
            $sizeOrName = (array)$sizeOrName;
            if (isset($sizeOrName['width'])) $sizeOrName[0] = $sizeOrName['width'];
            if (isset($sizeOrName['height'])) $sizeOrName[1] = $sizeOrName['height'];
            if (isset($sizeOrName['crop'])) $sizeOrName[2] = $sizeOrName['crop'] === true ? 0 : - 1;
            if (isset($sizeOrName['resize_mode'])) $sizeOrName[2] = $sizeOrName['resize_mode'];
            if ( !isset($sizeOrName[2])) $sizeOrName[2] = - 1;
        }
        if ($this->Image->get_width() == 0 || $this->Image->get_height() == 0) {
            return (object)$sizeOrName;
        }
        return get_image_calculate_size_from_dimension($sizeOrName[0], $sizeOrName[1], $this->Image->get_width(), $this->Image->get_height(), $sizeOrName[2]);
    }


    /**
     * @return Image_Size[]
     */
    public function get_sizes(): array {
        if ( !is_array($this->sizes)) {
            $this->sizes = [
                'original' => $this->get_original_size()
            ];
            if (property_exists($this->Image->get_attachment_meta(), 'sizes') && is_array($this->Image->get_attachment_meta()->sizes)) {
                foreach ($this->Image->get_attachment_meta()->sizes as $sizeName => $sizeRawData) {
                    if(preg_match('/^[\d]{1,}x[\d]{1,}c?$/i', $sizeName) > 0) {
                        $sizeRawData['crop'] = preg_match('/c$/i', $sizeName) > 0;
                    }elseif(function_exists('wp_get_registered_image_subsizes') && array_key_exists($sizeName, wp_get_registered_image_subsizes() )) {
                        $sizeRawData['crop'] = wp_get_registered_image_subsizes()[$sizeName]['crop'];
                    }
                    $this->sizes[$sizeName] = new Image_Size($this->Image, $sizeRawData, $sizeName);
                }
            }
        }
        return $this->sizes;
    }


    /**
     * Return original image size
     * @return Image_Size
     */
    public function get_original_size(): Image_Size {
        return CacheFactory::get($this->Image->get_attachment_ID(), __METHOD__, function() {
            return new Image_Size($this->Image, (object)[ 'width' => $this->Image->get_width(), 'height' => $this->Image->get_height(), 'file' => $this->Image->path()->file()->get_basename(), 'crop' => false ], 'original');
        })->get_value();
    }


    /**
     * @param string $sizeOrName
     * @return array|Image_Size[]
     */
    public function get_similar_sizes($sizeOrName = 'medium'): array {
        $sizes_by_delta = [];
        ///
        $dimension = $this->get_calculate_size($sizeOrName);
        $src_pixel = $dimension->width * $dimension->height;
        $src_aspect = $dimension->width / $dimension->height;
        $more_that = ($dimension->resize_mode > 0);
        $less_that = ($dimension->resize_mode < 0);
        foreach ($this->get_sizes() as $sizeName => $image_Size) {
            if ($sizeName == '' || $image_Size->get_aspect() == 0) continue;
            $delta = (($image_Size->get_width() * $image_Size->get_height()) - $src_pixel) * ($src_aspect / $image_Size->get_height() && $image_Size->is_exists());
            if (($more_that && $delta >= 0) || ($less_that && $delta <= 0)) {
                $sizes_by_delta[abs($delta)] = $image_Size;
            }
        }
        ksort($sizes_by_delta);
        return $sizes_by_delta;
    }


    /**
     * @param string|array|stdClass $sizeOrName
     * @param bool                  $makeIfNotExist
     * @return Image_Size
     * @version 1.1
     */
    public function get($sizeOrName = 'medium', $makeIfNotExist = null): Image_Size {
        if ( !$this->Image->is_attachment_exists()) return $this->get_original_size();
        if (is_null($makeIfNotExist)) $makeIfNotExist = ImagesFactory::$makeFileIfNotExists;
        $dimension = $this->get_calculate_size($sizeOrName);
        ///check if dimension is exists and find in wp registered sizes...
        if ($dimension->width == $this->Image->get_width() && $dimension->height == $this->Image->get_height()) return $this->get_original_size();
        foreach ($this->get_sizes() as $image_Size) {
            if ($dimension->resize_mode == 0 && $image_Size->path()->file()->is_exists() && $image_Size->get_width() == $dimension->width && $image_Size->get_height() == $dimension->height) {
                return $image_Size;
            } elseif ($dimension->resize_mode == - 1 && $image_Size->path()->file()->is_exists() && (($dimension->width == $image_Size->get_width() && $dimension->height >= $image_Size->get_height()) || ($dimension->width >= $image_Size->get_width() && $dimension->height == $image_Size->get_height()))) {
                return $image_Size;
            } elseif ($dimension->resize_mode == 1 && $image_Size->path()->file()->is_exists() && (($dimension->width == $image_Size->get_width() && $dimension->height <= $image_Size->get_height()) || ($dimension->width <= $image_Size->get_width() && $dimension->height == $image_Size->get_height()))) {
                return $image_Size;
            }
        }
        ///find similar image size
        if ( !$makeIfNotExist) {
            foreach ($this->get_similar_sizes($sizeOrName) as $image_Size) {
                return $image_Size;
            }
        } else {
            $dimension = $this->get_calculate_size($sizeOrName);
            $newSizeName = $dimension->width . 'x' . $dimension->height . ($dimension->resize_mode == 0 ? 'c' : '');
            $Image_Size = new Image_Size($this->Image, [ 'width' => $dimension->width, 'height' => $dimension->height, 'crop' => $dimension->resize_mode ], $newSizeName);
            $this->sizes[$newSizeName] = $Image_Size;
            $Image_Size->make_file();
            return $Image_Size;
        }
        return $this->get_original_size();
    }


    /**
     * @param      $dimensionOrSizeName
     * @param bool $more_that
     * @param bool $less_that
     * @return Image_Size[]
     */
    //    public function get_search($dimensionOrSizeName, $more_that = true, $less_that = false) {
    //        $dimension = $dimensionOrSizeName;
    //        if (is_string($dimensionOrSizeName)) {
    //            $dimension = get_dimension_from_wp_register_size($dimensionOrSizeName);
    //        }
    //        $src_pixel = $dimension[0] * $dimension[1];
    //        $src_aspect = $dimension[0] / $dimension[1];
    //        $sizes_by_delta = [];
    //        foreach ($this->get_sizes() as $size_name => $image_Size) {
    //            if ($size_name == '' || $image_Size->aspect() == 0) continue;
    //            $delta = (($image_Size->width() * $image_Size->height()) - $src_pixel) * ($src_aspect / $image_Size->height());
    //            if (($more_that && $delta >= 0) || ($less_that && $delta <= 0)) {
    //                $sizes_by_delta[abs($delta)] = $image_Size;
    //            }
    //        }
    //        ksort($sizes_by_delta);
    //        return $sizes_by_delta;
    //    }

}