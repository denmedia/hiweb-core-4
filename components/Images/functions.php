<?php

namespace hiweb\components\Images;


use hiweb\core\Paths\PathsFactory;
use stdClass;


/**
 * @param $attachIdPathOrUrl
 * @return int|mixed|string|\WP_Post
 */
function get_attachment_id_from_url($attachIdPathOrUrl) {
    if (is_string($attachIdPathOrUrl) && !is_numeric($attachIdPathOrUrl) && trim($attachIdPathOrUrl) != '') {
        $attachIdPathOrUrl = PathsFactory::get($attachIdPathOrUrl)->url()->get(false);
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $attachIdPathOrUrl));
        $attachIdPathOrUrl = $attachment[0];
    } elseif ($attachIdPathOrUrl instanceof \WP_Post && $attachIdPathOrUrl->post_type == 'attachment') {
        $attachIdPathOrUrl = $attachIdPathOrUrl->ID;
    }
    return intval($attachIdPathOrUrl);
}

/**
 * Return wp size name from dimension, or return FALSE, if is not exists
 * @param     $width
 * @param     $height
 * @param int $resize_mode
 * @return bool|string
 */
function get_wp_register_size_from_dimension($width, $height) {
    $width = absint($width);
    $height = absint($height);
    if ($width < 2) $width = 8;
    if ($height < 2) $height = 8;
    ///FIND WP SIZE NAME
    foreach (wp_get_registered_image_subsizes() as $wp_size_name => $wp_size_data) {
        $wp_size_data = (object)$wp_size_data;
        if ($wp_size_data->crop && $wp_size_data->width == $width && $wp_size_data->height == $height) {
            return $wp_size_name;
        } elseif ( !$wp_size_data->crop && (($wp_size_data->width == $width && $wp_size_data->height >= $height) || ($wp_size_data->width >= $width && $wp_size_data->height == $height))) {
            return $wp_size_name;
        }
    }
    return false;
}

/**
 * Return image dimension from wp register size name
 * @param string $wp_size_name
 * @return stdClass
 * @version 1.1
 */
function get_dimension_from_wp_register_size($wp_size_name = 'thumbnail'): stdClass {
    $dimension = new stdClass();
    $dimension->width = 8;
    $dimension->height = 8;
    $dimension->resize_mode = 0;
    $wp_size_name = strtolower($wp_size_name);
    if ( !array_key_exists($wp_size_name, wp_get_registered_image_subsizes())) return $dimension;
    $wp_size_data = (object)wp_get_registered_image_subsizes()[$wp_size_name];
    $dimension->width = $wp_size_data->width;
    $dimension->height = $wp_size_data->height;
    $dimension->resize_mode = $wp_size_data->crop ? 0 : - 1;
    return $dimension;
}

/**
 * Calculate image size by resize_mode and original image dimension
 * @param      $desiredWidth
 * @param      $desiredHeight
 * @param      $resizeMode
 * @param      $sourceWidth
 * @param      $sourceHeight
 * @return stdClass
 * @version 1.2
 */
function get_image_calculate_size_from_dimension($desiredWidth, $desiredHeight, $sourceWidth, $sourceHeight, $resizeMode): stdClass {
    $desiredWidth = absint($desiredWidth);
    $desiredHeight = absint($desiredHeight);
    $desireAspect = $desiredHeight === 0 ? 0 : ($desiredWidth / $desiredHeight);
    $sourceWidth = absint($sourceWidth);
    $sourceHeight = absint($sourceHeight);
    $sourceAspect = $sourceWidth / $sourceHeight;
    ///
    $result_size = new stdClass();
    $result_size->width = $desiredWidth;
    $result_size->height = $desiredHeight;
    ///Crop(resize mode) setup
    if ($resizeMode === true) $resizeMode = 0; elseif ($resizeMode === false) $resizeMode = - 1;
    ///Calculate result dimension
    if ($resizeMode > 0) {
        if ($sourceAspect < $desireAspect) {
            $desiredHeight = $desiredWidth / $sourceAspect;
        } else {
            $desiredWidth = $desiredHeight * $sourceAspect;
        }
    } elseif ($resizeMode < 0) {
        if ($sourceAspect > $desireAspect) {
            $desiredHeight = $desiredWidth / $sourceAspect;
        } else {
            $desiredWidth = $desiredHeight * $sourceAspect;
        }
    }
    ///Source limit setup
    if ($desiredWidth > $sourceWidth || $desiredHeight > $sourceHeight) {
        $kWidth = $sourceWidth / $desiredWidth;
        $kHeight = $sourceHeight / $desiredHeight;
        $desiredWidth = $desiredWidth * ($kWidth < $kHeight ? $kWidth : $kHeight);
        $desiredHeight = $desiredHeight * ($kWidth < $kHeight ? $kWidth : $kHeight);
    }
    $result_size->width = floor($desiredWidth);
    $result_size->height = floor($desiredHeight);
    $result_size->resize_mode = $resizeMode;
    return $result_size;
}