<?php

use hiweb\components\Images\Image;

/**
 * @var Image $this
 * @var stdClass $dimension
 */


?><img src="<?=\hiweb\components\Images\ImagesFactory::get_default_src()?>" width="<?=$dimension->width?>" height="<?=$dimension->height?>" />