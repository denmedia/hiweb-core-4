<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11/12/2018
	 * Time: 00:07
	 */

	namespace hiweb\components\images;


	use hiweb\components\console\Console;


	class Size_Calculator{

		/**
		 * @var image
		 */
		private $image;


		public function __construct( Image $image ){
			$this->image = $image;
		}


		/**
		 * Calculate height by width, include original image aspect
		 * @param      $width
		 * @param bool $round
		 * @return float|int
		 */
		public function get_height_by_width( $width, $round = true ){
			if( $this->image->aspect() > 0 ){
				$R = (int)$width / $this->image->aspect();
				return $round ? round( $R ) : floor( $R );
			}
			return 0;
		}


		/**
		 * Calculate width by height, include original image aspect
		 * @param      $height
		 * @param bool $round
		 * @return float|int
		 */
		public function get_width_by_height( $height, $round = true ){
			if( $this->image->aspect() > 0 ){
				$R = (int)$height * $this->image->aspect();
				return $round ? round( $R ) : $R;
			}
			return 0;
		}


		/**
		 * @param $width
		 * @param $height
		 * @return bool
		 */
		public function is_crop( $width, $height ){
			if( $width == 0 || $height == 0 ) return false;
			$resize_width = $this->get_width_by_height( $height );
			$resize_height = $this->get_height_by_width( $resize_width );
			$resize_delta = abs( $resize_width - (int)$width ) + abs( $resize_height - (int)$height );
			return $resize_delta > 1;
		}


		/**
		 * Return dimensions size array equal / over $width, $height
		 * @param $width
		 * @param $height
		 * @return array
		 */
		public function get_dimensions_min( $width, $height ){
			if( $width == 0 || $height == 0 ) return [ 0, 0 ];
			$aspect = $width / $height;
			if( $this->image->aspect() > $aspect ){
				if( (int)$height > $this->image->height() ) $height = $this->image->height();
				return [ $this->get_width_by_height( $height ), $height ];
			} else {
				if( (int)$width > $this->image->width() ) $width = $this->image->width();
				return [ $width, $this->get_height_by_width( $width ) ];
			}
		}


		/**
		 * Return dimensions size array equal / lower $width, $height
		 * @param $width
		 * @param $height
		 * @return array
		 */
		public function get_dimensions_max( $width, $height ){
			if( $width == 0 || $height == 0 ) return [ 0, 0 ];
			$aspect = (int)$width / (int)$height;
			if( $this->image->aspect() < $aspect ){
				if( (int)$height > $this->image->height() ) $height = $this->image->height();
				return [ $this->get_width_by_height( $height ), $height ];
			} else {
				if( (int)$width > $this->image->width() ) $width = $this->image->width();
				return [ $width, $this->get_height_by_width( $width ) ];
			}
		}


		/**
		 * Convert desired crop and dimensions to current dimensions, limited by original
		 * @param      $width
		 * @param      $height
		 * @param bool $resize_mod - true - CROP, false - support aspect ration, return max, 1 - support aspect, return min
		 * @return array
		 */
		public function get_dimensions_by_resizeMod( $width, $height, $resize_mod = false ){
			//Limit check
			if( (int)$width > $this->image->width() ) $width = $this->image->width();
			if( (int)$height > $this->image->height() ) $height = $this->image->height();
			//Crop return
			if( $resize_mod === 0 || $resize_mod === true ) return [ $width, $height ];
			//Support aspect and minMax
			if( $resize_mod < 0 ) $dimensions = $this->get_dimensions_max( $width, $height ); else $dimensions = $this->get_dimensions_min( $width, $height );
			///
			return $dimensions;
		}


		public function get_dimensions_by_sizeName( $sizeName = 'thumbnail', $resize_mod = null ){
			if( array_key_exists( $sizeName, array_flip( get_intermediate_image_sizes() ) ) ){
				switch( $sizeName ){
					case 'thumbnail':
						$w = intval( get_option( 'thumbnail_size_w', 150 ) );
						$h = intval( get_option( 'thumbnail_size_h', 150 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = $this->get_dimensions_by_resizeMod( $w, $h, is_null( $resize_mod ) ? true : $resize_mod );
						break;
					case 'medium':
						$w = intval( get_option( 'medium_size_w', 300 ) );
						$h = intval( get_option( 'medium_size_h', 300 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = $this->get_dimensions_by_resizeMod( $w, $h, is_null( $resize_mod ) ? - 1 : $resize_mod );
						break;
					case 'medium_large':
						$w = intval( get_option( 'medium_large_size_w', 768 ) );
						$h = intval( get_option( 'medium_large_size_h', 768 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = $this->get_dimensions_by_resizeMod( $w, $h, is_null( $resize_mod ) ? - 1 : $resize_mod );
						break;
					case 'large':
						$w = intval( get_option( 'large_size_w', 1024 ) );
						$h = intval( get_option( 'large_size_h', 1024 ) );
						$w = $w < 8 ? 8 : $w;
						$h = $h < 8 ? $w : $h;
						$R = $this->get_dimensions_by_resizeMod( $w, $h, is_null( $resize_mod ) ? - 1 : $resize_mod );
						break;
					default:
						$size_data = wp_get_additional_image_sizes();
						$size_data = $size_data[ $sizeName ];
						$R = $this->get_dimensions_by_resizeMod( $size_data['width'], $size_data['height'], is_null( $resize_mod ) ? (bool)$size_data['crop'] : $resize_mod );
						break;
				}
			} elseif( $sizeName == $this->image->get_original_size_name() ) {
				$R = [ $this->image->width(), $this->image->height() ];
			} else {
				console::debug_warn( 'Intermediate image size not found. The maximum size of the image is established.', $sizeName );
				$R = [ $this->image->width(), $this->image->height() ];
			}

			return $R;
		}


		/**
		 * @param array $dimensionsOrSizeName
		 * @param int   $resize_mod
		 * @return array
		 */
		public function get_dimensions_by_dimensionsOrSizeName( $dimensionsOrSizeName = [ 150, 150 ], $resize_mod = 1 ){
			if( is_string( $dimensionsOrSizeName ) && trim( $dimensionsOrSizeName ) == '' ) $dimensionsOrSizeName = [ $this->image->width(), $this->image->height() ];
			if( is_array( $dimensionsOrSizeName ) && isset( $dimensionsOrSizeName[0] ) && $dimensionsOrSizeName[1] ){
				$dimensions = $this->get_dimensions_by_resizeMod( $dimensionsOrSizeName[0], $dimensionsOrSizeName[1], $resize_mod );
			} else {
				//if( $dimensionsOrSizeName == '' ) console_info( debug_backtrace() ); //todo-
				$dimensions = $this->get_dimensions_by_sizeName( $dimensionsOrSizeName, $resize_mod );
			}
			return $dimensions;
		}


		/**
		 * Convert dimension to string, return string dimensions, like 150x150c | 800x640
		 * @param $width
		 * @param $height
		 * @return string
		 */
		public function get_dimensions_size_string( $width, $height ){
			return $width . 'x' . $height . ( $this->is_crop( $width, $height ) ? 'c' : '' );
		}

	}