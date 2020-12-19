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
        if ($dimension->width < 1000 && $dimension->height < 1000 && !get_client()->is_mobile()) {
            $big_size = $this->sizes()->get([ $dimension->width * 1.75, $dimension->height * 1.75, 1 ], true);
            if ($big_size instanceof Image_Size && $big_size->is_exists_webp() && $big_size->get_name() !== $main_size->get_name() && $big_size->get_area() >= ($main_size->get_area() * 1.4)) {
                ?>
                <source srcset="<?= $main_size->get_src_webp() ?>, <?= $big_size->get_src_webp() ?> 2x" type="image/webp"><?php
            }
        } elseif ($main_size->is_exists_webp()) {
            ?>
            <source srcset="<?= $main_size->get_src_webp() ?>" type="image/webp"><?php
        }
    } ?>
    <?= $this->get_html_img($dimension, $imgAttributes) ?>
</picture>