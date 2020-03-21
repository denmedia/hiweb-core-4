<?php

	namespace hiweb\components\Includes;


	function get_search_paths( $fileNameOrPath, $extension = 'css' ){
		return [
			$fileNameOrPath,
			get_stylesheet_directory() . '/' . $fileNameOrPath,
			get_stylesheet_directory() . '/' . $fileNameOrPath . '.min.' . $extension,
			get_stylesheet_directory() . '/' . $fileNameOrPath . '.' . $extension,
			get_template_directory() . '/' . $fileNameOrPath,
			get_template_directory() . '/' . $fileNameOrPath . '.min.' . $extension,
			get_template_directory() . '/' . $fileNameOrPath . '.' . $extension,
			//				HIWEB_THEME_VENDORS_DIR . '/' . $fileNameOrPath,
			//				HIWEB_THEME_VENDORS_DIR . '/' . $fileNameOrPath . '.min.' . $extension,
			//				HIWEB_THEME_VENDORS_DIR . '/' . $fileNameOrPath . '.' . $extension,
			//				HIWEB_DIR_VENDORS . '/' . $fileNameOrPath,
			//				HIWEB_DIR_VENDORS . '/' . $fileNameOrPath . '.min.' . $extension,
			//				HIWEB_DIR_VENDORS . '/' . $fileNameOrPath . '.' . $extension
		];
	}