<?php

register_hiweb_component('\hiweb\components\Images\ImagesFactory::_makeNewDimensionFile', __('Create a new image file of the required size if it does not exist', 'hiweb-core-4'),'', true);
register_hiweb_component('\hiweb\components\Images\ImagesFactory::_useImageDefer', __('Images Defer', 'hiweb-core-4'));
register_hiweb_component('\hiweb\components\Images\ImagesFactory::_usePictureHtmlTag', __('Use <picture/> html tag', 'hiweb-core-4'), '', true);
register_hiweb_component('\hiweb\components\Images\ImagesFactory::_useWebPExtension', __('Use WebP images format if server is support them'), '', true);