<?php
	
	use hiweb\components\Images\Image;
	
	
	if(!function_exists('get_image')) {
		
		/**
		 * @param $attachmentId_or_Url
		 * @return Image
		 */
		function get_image($attachmentId_or_Url) {
			return \hiweb\components\Images\ImagesFactory::get($attachmentId_or_Url);
		}
		
	}
