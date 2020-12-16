<?php

use hiweb\components\Images\Image;
use hiweb\components\Images\Image_Size;


/**
 * @var Image    $this
 * @var stdClass $dimension
 * @var array    $pictureAttributes
 * @var array    $imgAttributes
 * @var bool     $tryWebP
 */
$main_size = $this->sizes()->get($dimension, true);
?>
<picture>
    <?php if (\hiweb\components\Images\ImagesFactory::$useWebPExtension && \hiweb\components\Client\Client::get_instance()->is_support_WebP()) {
        $webp_path = $main_size->path_webp();
        if ($dimension->width < 1000 && $dimension->height < 1000 && !get_client()->is_mobile()) {
            $big_size = $this->sizes()->get([ $dimension->width * 1.75, $dimension->height * 1.75, 1 ], true);
            if ($big_size instanceof Image_Size && $big_size->is_exists_webp() && $big_size->get_name() !== $main_size->get_name() && $big_size->get_area() >= ($main_size->get_area() * 1.4)) {
                ?>
                <source srcset="<?= $webp_path->get_url() ?>, <?= $big_size->path_webp()->get_url() ?> 2x" type="<?= $big_size->path_webp()->image()->get_mime_type() ?>"><?php
            }
        } elseif ($main_size->is_exists_webp()) {
            ?>
            <source srcset="<?= $webp_path->get_url() ?>" type="<?= $webp_path->image()->get_mime_type() ?>"><?php
        }
    } ?>
    <?= $this->get_html_img($dimension, $imgAttributes) ?>
</picture>