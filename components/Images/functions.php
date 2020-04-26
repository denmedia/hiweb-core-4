<?php
	
	namespace hiweb\components\Images;
	
	
	use hiweb\core\Paths\PathsFactory;
	
	
	/**
	 * @param $attachIdPathOrUrl
	 * @return int|mixed|string|\WP_Post
	 */
	function get_attachment_id_from_url( $attachIdPathOrUrl ){
		if( is_string( $attachIdPathOrUrl ) && !is_numeric( $attachIdPathOrUrl ) ){
			$attachIdPathOrUrl = PathsFactory::get( $attachIdPathOrUrl )->Url()->get( false );
			global $wpdb;
			$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $attachIdPathOrUrl ) );
			$attachIdPathOrUrl = $attachment[0];
		}
		elseif( $attachIdPathOrUrl instanceof \WP_Post && $attachIdPathOrUrl->post_type == 'attachment' ){
			$attachIdPathOrUrl = $attachIdPathOrUrl->ID;
		}
		return $attachIdPathOrUrl;
	}
	
	/**
	 * Return wp size name from dimension, or return FALSE, if is not exists
	 * @param     $width
	 * @param     $height
	 * @param int $crop_mod
	 * @return bool|string
	 */
	function get_wp_register_size_from_dimension( $width, $height, $crop_mod = - 1 ){
		$width = (int)$width;
		$height = (int)$height;
		$crop_mod = (int)$crop_mod;
		if( $width < 2 ) $width = 8;
		if( $height < 2 ) $height = 8;
		if( $crop_mod < - 1 || $crop_mod > 1 ) $crop_mod = 1;
		///FIND WP SIZE NAME
		if( $crop_mod < 1 ){
			foreach( wp_get_registered_image_subsizes() as $wp_size_name => $wp_size_data ){
				$wp_size_data = (object)$wp_size_data;
				if( $wp_size_data->width == $width && $wp_size_data->height == $height && ( $wp_size_data->crop === false && $crop_mod == - 1 || $wp_size_data->crop === true && $crop_mod == 0 ) ){
					return $wp_size_name;
				}
			}
		}
		return false;
	}
	
	/**
	 * Return image dimension from wp register size name
	 * @param string $wp_size_name
	 * @return array|bool
	 */
	function get_wp_register_dimension_from_register_size( $wp_size_name = 'thumbnail' ){
		$wp_size_name = strtolower( $wp_size_name );
		if( !array_key_exists( $wp_size_name, wp_get_registered_image_subsizes() ) ) return false;
		$wp_size_data = (object)wp_get_registered_image_subsizes()[ $wp_size_name ];
		return [ $wp_size_data->width, $wp_size_data->height, $wp_size_data->crop ? 0 : - 1 ];
	}
	
	function get_image_size_calculate_from_dimension( $dest_width, $dest_height, $crop_mode, $original_width, $original_height, $round = true ){
		$R = [ 0, 0 ];
		$dest_width = (int)$dest_width;
		$dest_height = (int)$dest_height;
		$dest_aspect = $dest_width / $dest_height;
		$original_width = (int)$original_width;
		$original_height = (int)$original_height;
		$original_aspect = $original_width / $original_height;
		///Crop(resize mode) setup
		if( $crop_mode === true ) $crop_mode = 0;
		elseif( $crop_mode === false ) $crop_mode = - 1;
		if( $crop_mode > 0 ){
			if( $original_aspect < $dest_aspect ){
				$dest_height = $dest_width / $original_aspect;
			}
			else{
				$dest_width = $dest_height * $original_aspect;
			}
		}
		elseif( $crop_mode < 0 ){
			if( $original_aspect > $dest_aspect ){
				$dest_height = $dest_width / $original_aspect;
			}
			else{
				$dest_width = $dest_height * $original_aspect;
			}
		}
		///Original limit setup
		if( $original_width < $dest_width ){
			$k = $original_width / $dest_width;
			$dest_height = $dest_height * $k;
		}
		if( $original_height < $dest_height ){
			$k = $original_height / $dest_height;
			$dest_width = $dest_width * $k;
		}
		///
		if( $round ){
			$dest_width = round( $dest_width );
			$dest_height = round( $dest_height );
		}
		///
		return [ $dest_width, $dest_height ];
	}