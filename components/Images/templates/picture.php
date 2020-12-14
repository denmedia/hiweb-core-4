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

?>
<picture>
    <?php if ( !\hiweb\components\Images\ImagesFactory::$useStandardExtensionsOnly && \hiweb\components\Client\Client::get_instance()->is_support_WebP()) {
        $webp_path = $this->sizes()->get($dimension, true)->path_webp();
        if ($webp_path->file()->is_exists()) {
            ?>
            <source srcset="<?= $webp_path->get_url() ?>" type="<?=$webp_path->image()->get_mime_type()?>"><?php
        }
    } ?>
    <?= $this->get_html_img($dimensionsOrSizeName, $imgAttributes, $make_new_file, false) ?>
</picture>