<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-04
	 * Time: 19:54
	 */

	namespace hiweb\components\Images;


	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\Paths\File;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;


	class Editor extends File{

		private $width = null;
		private $height = null;


		/**
		 * @return int
		 */
		public function width(){
			if( is_null( $this->width ) || $this->width == 0 ){
				$this->width = 0;
				if( $this->is_readable() ){
					list( $this->width, $this->height ) = getimagesize( $this->get_path() );
					$this->width = (int)$this->width;
					$this->height = (int)$this->height;
				}
			}
			return $this->width;
		}


		/**
		 * @return int
		 */
		public function height(){
			if( is_null( $this->height ) || $this->height == 0 ){
				$this->height = 0;
				if( $this->is_readable() ){
					list( $this->width, $this->height ) = getimagesize( $this->get_path() );
					$this->width = (int)$this->width;
					$this->height = (int)$this->height;
				}
			}
			return $this->height;
		}


		public function aspect(){
			return $this->width() / $this->height();
		}


		/**
		 * Calculate height by width, include original image aspect
		 * @param      $width
		 * @param bool $round
		 * @return float|int
		 */
		public function get_resize_height_by_width( $width, $round = true ){
			if( $this->aspect() > 0 ){
				$R = (int)$width / $this->aspect();
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
		public function get_resize_width_by_height( $height, $round = true ){
			if( $this->aspect() > 0 ){
				$R = (int)$height * $this->aspect();
				return $round ? round( $R ) : $R;
			}
			return 0;
		}


		/**
		 * @param $width
		 * @param $height
		 * @return bool
		 */
		public function is_crop( $width = 0, $height = '' ){
			if( $width == 0 || $height == 0 ){
				$width = $this->width();
				$height = $this->height();
			}
			if( $width == 0 || $height == 0 ) return false;
			$resize_width = $this->get_resize_width_by_height( $height );
			$resize_height = $this->get_resize_height_by_width( $resize_width );
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
			if( $this->aspect() > $aspect ){
				if( (int)$height > $this->height() ) $height = $this->height();
				return [ $this->get_resize_width_by_height( $height ), $height ];
			} else {
				if( (int)$width > $this->width() ) $width = $this->width();
				return [ $width, $this->get_resize_height_by_width( $width ) ];
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
			if( $this->aspect() < $aspect ){
				if( (int)$height > $this->height() ) $height = $this->height();
				return [ $this->get_resize_width_by_height( $height ), $height ];
			} else {
				if( (int)$width > $this->width() ) $width = $this->width();
				return [ $width, $this->get_resize_height_by_width( $width ) ];
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
			if( (int)$width > $this->width() ) $width = $this->width();
			if( (int)$height > $this->height() ) $height = $this->height();
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
			} elseif( $sizeName == ImagesFactory::$original_size_name ) {
				$R = [ $this->width(), $this->height() ];
			} else {
				ConsoleFactory::add('Intermediate image size not found. The maximum size of the image is established.','warn',__CLASS__,$sizeName, true);
				$R = [ $this->width(), $this->height() ];
			}

			return $R;
		}


		/**
		 * @param array $dimensionsOrSizeName
		 * @param int   $resize_mod
		 * @return array
		 */
		public function get_dimensions_by_dimensionsOrSizeName( $dimensionsOrSizeName = [ 150, 150 ], $resize_mod = 1 ){
			if( is_string( $dimensionsOrSizeName ) && trim( $dimensionsOrSizeName ) == '' ) $dimensionsOrSizeName = [ $this->width(), $this->height() ];
			if( is_array( $dimensionsOrSizeName ) && isset( $dimensionsOrSizeName[0] ) && $dimensionsOrSizeName[1] ){
				$dimensions = $this->get_dimensions_by_resizeMod( $dimensionsOrSizeName[0], $dimensionsOrSizeName[1], $resize_mod );
			} else {
				if( $dimensionsOrSizeName == '' ){
					console_info( '//TODO!' );
					console_debug_backtrace();
				}
				$dimensions = $this->get_dimensions_by_sizeName( $dimensionsOrSizeName, $resize_mod );
			}
			return $dimensions;
		}


		/**
		 * Convert dimension to string, return string dimensions, like 150x150c | 800x640
		 * @return string
		 */
		public function get_dimensions_size_string(){
			return $this->width() . 'x' . $this->height() . ( $this->is_crop() ? 'c' : '' );
		}


		/**
		 * @return bool
		 */
		public function is_classic_file_type(){
			return array_key_exists( $this->extension(), array_flip( ImagesFactory::$classic_file_types ) );
		}


		/**
		 * @return bool
		 */
		public function is_progressive_file_type(){
			return array_key_exists( $this->extension(), array_flip( ImagesFactory::$progressive_types ) );
		}


		/**
		 * Create file from current, or remake current file, if not set destination path
		 * @param string $destination_file
		 * @param int    $dest_width
		 * @param int    $dest_height
		 * @param int    $quality_jpg_png_webp
		 * @return Editor|int|string
		 */
		public function make_file( $destination_file = '', $dest_width = 0, $dest_height = 0, $quality_jpg_png_webp = 75 ){
			if( !$this->is_readable() ) return - 1;
			if( is_string( $destination_file ) && trim( $destination_file ) != '' ){
				$destination_file = ImagesFactory::get_editor( $destination_file );
			} else {
				$destination_file = $this;
			}
			///QualityProgress
			if( $this->width() > 2000 || $this->height() > 2000 ) $quality_jpg_png_webp = round( $quality_jpg_png_webp * .85 );
			if( $this->width() > 3000 || $this->height() > 3000 ) $quality_jpg_png_webp = round( $quality_jpg_png_webp * .85 );
			///
			if( $this->aspect() != 0 ){
				///
				if( $dest_width > $this->width() ) $dest_width = $this->width();
				if( $dest_height > $this->height() ) $dest_height = $this->height();
				$dest_width = (int)$dest_width == 0 ? $this->width() : (int)$dest_width;
				$dest_height = (int)$dest_height == 0 ? $this->height() : (int)$dest_height;
				$dest_aspect = $dest_width / $dest_height;
				///calculate dimensions
				$src_x = 0;
				$src_y = 0;
				$src_width = $this->width();
				$src_height = $this->height();
				if( $this->aspect() < $dest_aspect ){
					$proportions = $this->width() / $dest_width;
					$src_height = $dest_height * $proportions;
					$src_y = ( $this->height() - $src_height ) / 2;
				} elseif( $this->aspect() > $dest_aspect ) {
					$proportions = $this->height() / $dest_height;
					$src_width = $dest_width * $proportions;
					$src_x = ( $this->width() - $src_width ) / 2;
				}
				///
				if( $destination_file->is_classic_file_type() ){

					//GD
					if( extension_loaded( 'gd' ) ){
						///
						//console::debug_info( __METHOD__ . ': make new classic file by GD [' . $this->get_path_relative() . ']' );
						///
						switch( $this->Image()->get_mime_type() ){
							case 'image/jpg':
								$src_image = imagecreatefromjpeg( $this->get_path() );
								break;
							case 'image/png':
								$src_image = imagecreatefrompng( $this->get_path() );
								break;
							case 'image/gif':
								$src_image = imagecreatefromgif( $this->get_path() );
								break;
							default:
								return - 2;
						}
						///create new source image
						$image_gd_new = imagecreatetruecolor( $dest_width, $dest_height );
						if( $this->Image()->get_mime_type() != 'image/jpg' ){
							///Use alpha chanel
							imagealphablending( $image_gd_new, false );
							imagesavealpha( $image_gd_new, true );
						}
						///resize
						imagecopyresampled( $image_gd_new, $src_image, 0, 0, $src_x, $src_y, $dest_width, $dest_height, $src_width, $src_height );
						$B = - 3;
						$temp_file = PathsFactory::get( WP_CONTENT_DIR . '/hiweb-image-temp-' . Strings::rand() . '.' . $destination_file->extension() )->File();
						switch( $destination_file->Image()->get_mime_type() ){
							case 'image/jpg':
								imageinterlace( $image_gd_new, true );
								$B = imagejpeg( $image_gd_new, $temp_file->get_path(), $quality_jpg_png_webp );
								break;
							case 'image/png':
								$B = imagepng( $image_gd_new, $temp_file->get_path(), round( $quality_jpg_png_webp / 10 ) - 1 );
								break;
							case 'image/gif':
								$B = imagegif( $image_gd_new, $temp_file->get_path() );
								break;
						}
						//Console::debug_info( [ __METHOD__ . ': done [' . $this->get_path_relative() . ']', $this->get_path() ] );
						imagedestroy( $src_image );
						imagedestroy( $image_gd_new );
						if( $temp_file->is_exists() && $temp_file->get_size() > 0 ){
							if( $destination_file->is_exists() ) unlink( $destination_file->get_path() );
							@rename( $temp_file->get_path(), $destination_file->get_path() );
							return $destination_file;
						} else {
							return false;
						}
					}
				} elseif( $destination_file->is_progressive_file_type() ) {
					if( extension_loaded( 'imagick' ) ){
						//Console::debug_info( __METHOD__ . ': make new progressive file [' . $destination_file->get_path_relative() . ']' );
						$temp_file = PathsFactory::get( WP_CONTENT_DIR . '/hiweb-image-temp-' . Strings::rand() . '.' . $destination_file->extension() )->File();
						///
						$converter = 'convert';
						$addition_options = [];
						if( array_key_exists( $destination_file->extension(), array_flip( [ 'webp' ] ) ) ){
							$converter = HIWEB_DIR_VENDOR . '/image-optimize/cwebp-linux';
							if( $destination_file->is_crop() ){
								$addition_options[] = '-crop ' . $src_x . ' ' . $src_y . ' ' . $src_width . ' ' . $src_height;
							}
							$addition_options[] = '-q ' . floatval( $quality_jpg_png_webp * .8 );
							$addition_options[] = '-resize ' . $dest_width . ' ' . $dest_height;
							$addition_options[] = $this->get_path();
							$addition_options[] = '-o';
							$addition_options[] = $temp_file->get_path();
							$shell_command_str = "{$converter} " . implode( ' ', $addition_options );
						} elseif( $destination_file->extension() == 'jp2' ) {
							if( $destination_file->is_crop() ){
								$addition_options[] = '-crop ' . $src_width . 'x' . $src_height . '+' . $src_x . '+' . $src_y . '\!';
							}
							$addition_options[] = '-quality ' . $quality_jpg_png_webp;
							$addition_options[] = '-resize ' . $dest_width . 'x' . $dest_height;
							$shell_command_str = "{$converter} {$this->get_path()} " . implode( ' ', $addition_options ) . " {$temp_file->get_path()}";
						} else {
							return - 3;
						}
						///

						///
						$R = shell_exec( $shell_command_str );
						if( $temp_file->is_readable() && $temp_file->get_size() > 0 ){
							@rename( $temp_file->get_path(), $destination_file->get_path() );
							//console::debug_info( [ __METHOD__ . ': done [' . $this->get_path_relative() . ']', $this->get_path() ] );
							return $destination_file;
						}
						return - 4;
					}
					return - 5;
				}
			}
			///
			return - 6;
		}


	}