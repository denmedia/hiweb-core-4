<?php

use hiweb\components\Images\Image;


/**
 * @var Image    $this
 * @var stdClass $dimension
 * @var array    $attributes
 */

$img_attributes = get_array($attributes);
///
$defer_ajax_data = [ 'id' => $this->get_attachment_ID(), 'dimension' => $dimension, 'attributes' => $attributes ];
$img_attributes->push('data-image-defer', $defer_ajax_data);
$img_attributes->push('data-image-defer-id', 'image-defer-' . \hiweb\core\Strings::rand(4));
$img_attributes->push('data-image-defer-status', 'preload');
$img_attributes->push('src', $this->sizes()->get([ 50, 50, 1 ], true)->path()->get_path_relative());
$img_attributes->push('width', $dimension->width);
$img_attributes->push('height', $dimension->height);
$img_attributes->push('alt', $this->get_alt());
$img_attributes->push('title', $this->get_title());
///

?><img <?= $img_attributes->get_as_tag_attributes() ?>/>