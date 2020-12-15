<?php

use hiweb\components\Images\Image;
use hiweb\components\Images\ImagesFactory;


/**
 * @var Image    $this
 * @var stdClass $dimension
 * @var array    $attributes
 */
///
$draft_size = $this->sizes()->get([ 50, 50, 1 ], true, 20);
$defer_ajax_data = [ 'id' => $this->get_attachment_ID(), 'dimension' => $dimension, 'attributes' => $attributes ];
$source = '';
if (ImagesFactory::$usePictureHtmlTag) {
    $picture_attributes = get_array($attributes);
    $picture_attributes->push('data-image-defer', $defer_ajax_data);
    $picture_attributes->push('data-image-defer-id', 'image-defer-' . \hiweb\core\Strings::rand(4));
    $picture_attributes->push('data-image-defer-status', 'preload');
    $img_attributes = get_array();
    if (ImagesFactory::$useWebPExtension && function_exists('imagewebp')) {
        $source = '<source srcset="' . $draft_size->get_src_webp() . '" type="' . $draft_size->path_webp()->image()->get_mime_type() . '"></source>';
    }
} else {
    $img_attributes = get_array($attributes);
    $img_attributes->push('data-image-defer', $defer_ajax_data);
    $img_attributes->push('data-image-defer-id', 'image-defer-' . \hiweb\core\Strings::rand(4));
    $img_attributes->push('data-image-defer-status', 'preload');
}
$img_attributes->push('src', $draft_size->path()->get_path_relative());
$img_attributes->push('width', $dimension->width);
$img_attributes->push('height', $dimension->height);
$img_attributes->push('alt', $this->get_alt());
$img_attributes->push('title', $this->get_title());
///

if (ImagesFactory::$usePictureHtmlTag) {
    ?>
    <picture <?= $picture_attributes->get_as_tag_attributes() ?>><?= $source ?><img <?= $img_attributes->get_as_tag_attributes() ?>/></picture><?php
} else {
    ?><img <?= $img_attributes->get_as_tag_attributes() ?>/><?php
}