<?php

	///CONTENT IMAGES REPLACE
	use hiweb\core\ArrayObject\ArrayObject;

	remove_filter( 'the_content', 'wp_make_content_images_responsive', 10 );
	add_filter( 'the_content', '\\theme\\images_defer::_add_filter_the_content' );

	add_filter( 'wp_calculate_image_sizes', '\\theme\\images_defer::_add_filter_wp_calculate_image_sizes', 10, 5 );

	add_filter( 'wp_calculate_image_srcset', '\\theme\\images_defer::_add_filter_wp_calculate_image_srcset', 20, 5 );

	add_filter( '\hiweb\images\image::html-attributes', function( ArrayObject $attributes ){
		$attributes->key_rename( 'src', 'data-src-defer' );
		$attributes->push( 'src', HIWEB_URL_ASSETS . '/img/image-loading.svg' );
		$attributes->key_rename( 'srcset', 'data-srcset-defer' );
		return $attributes;
	} );
	add_filter( '\hiweb\images\image::html_picture-img_attributes', function( ArrayObject $attributes ){
		$attributes->key_rename( 'src', 'data-src-defer' );
		$attributes->push( 'src', HIWEB_URL_ASSETS . '/img/image-loading.svg' );
		$attributes->key_rename( 'srcset', 'data-srcset-defer' );
		return $attributes;
	} );
	add_filter( '\hiweb\images\image::html_picture-source_attributes', function( ArrayObject $attributes ){
		$attributes->key_rename( 'srcset', 'data-srcset-defer' );
		return $attributes;
	} );