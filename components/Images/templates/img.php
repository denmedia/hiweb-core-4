<?php

use hiweb\components\Images\Image;
use hiweb\components\Images\Image_Size;


/**
 * @var Image    $this
 * @var stdClass $dimension
 * @var array    $attributes
 * @var bool     $tryWebP
 */

$main_size = $this->sizes()->get($dimension);

$img_attributes = get_array($attributes);
$img_attributes->push('src', $tryWebP ? $main_size->get_src_webp() : $main_size->get_src());
$img_attributes->push('data-webp', $tryWebP ? '1' : '0');
$img_attributes->push('width', $dimension->width);
$img_attributes->push('height', $dimension->height);
$img_attributes->push('alt', $this->get_alt());
$img_attributes->push('title', $this->get_title());

if ($dimension->width < 1000 && $dimension->height < 1000) {
    $big_size = current($this->sizes()->get_similar_sizes([ $dimension->width * 1.75, $dimension->height * 1.75, 1 ]));
    if ($big_size instanceof Image_Size && $big_size->get_name() !== $main_size->get_name() && $big_size->get_area() >= ($main_size->get_area() * 1.4)) {
        if ($tryWebP && $big_size->is_exists_webp()) {
            $img_attributes->push('srcset', $big_size->get_src_webp() . ' ' . $big_size->get_width() . "w\n");
        } elseif ($big_size->is_exists()) {
            $img_attributes->push('srcset', $big_size->get_src() . ' ' . $big_size->get_width() . "w\n");
        }
    }
}

?><img <?= $img_attributes->get_as_tag_attributes() ?> />