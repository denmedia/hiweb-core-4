<?php

	namespace hiweb\core\files;


	use hiweb\core\paths\Path;


	class File extends Path{


		protected $width;
		protected $height;


		/**
		 * @param $file_name
		 * @return File
		 */
		public function get_next_file( $file_name ){
			return files::get( $this->dirname() . '/' . $file_name );
		}


		public function make_file( $content = '' ){
			if( !$this->is_local() ){
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->path . '], потому что ссылка не локальная' );
				}
				return - 1;
			}
			if( $this->is_exists() ){
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->path . '], потому что он уже существует' );
				}
				return - 2;
			}
			if( $this->is_writable() ){
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->path . '], потому что нет прав на запись' );
				}
				return - 3;
			}

			if( file_put_contents( $this->get_path(), $content ) ){
				return true;
			} else {
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->path . ']' );
				}
				return - 4;
			}
		}


		/**
		 * @return int
		 */
		public function width(){
			if( !is_int( $this->width ) ){
				$this->width = 0;
				if( $this->is_readable() ){
					$size = getimagesize( $this->get_path() );
					if( is_array( $size ) ) list( $this->width, $this->height ) = $size;
				}
			}
			return $this->width;
		}


		/**
		 * @return int
		 */
		public function height(){
			if( !is_int( $this->height ) ){
				$this->height = 0;
				if( $this->is_readable() ){
					$size = getimagesize( $this->get_path() );
					if( is_array( $size ) ) list( $this->width, $this->height ) = $size;
				}
			}
			return $this->height;
		}


		/**
		 * @return float|int
		 */
		public function aspect(){
			if( $this->width() == 0 || $this->height() == 0 ) return 0;
			return $this->width() / $this->height();
		}


		/**
		 * Resize/recompress current file
		 * @param int  $dest_width      - destination file width or leave 0 for current file width
		 * @param int  $dest_height     - destination file height or leave 0 for current file height
		 * @param null $dest_file_path  - destination file or leave empty for select current file path
		 * @param int  $quality_jpg_png - 0...100 quality for jpg or png files
		 * @return bool
		 */
		public function resize( $dest_width = 0, $dest_height = 0, $dest_file_path = null, $quality_jpg_png = 75 ){
			if( $this->is_image() && $this->aspect() != 0 ){
				if( !is_string( $dest_file_path ) || strlen( $dest_file_path ) < 2 ) $dest_file_path = $this->get_path();
				if( $dest_width > $this->width() ) $dest_width = $this->width();
				if( $dest_height > $this->height() ) $dest_height = $this->height();
				$dest_width = (int)$dest_width == 0 ? $this->width() : (int)$dest_width;
				$dest_height = (int)$dest_height == 0 ? $this->height() : (int)$dest_height;
				$dest_aspect = $dest_width / $dest_height;
				//GD
				if( extension_loaded( 'gd' ) ){
					///
					switch( $this->get_image_mime_type() ){
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
							return - 1;
					}
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
					///create new source image
					$image_gd_new = imagecreatetruecolor( $dest_width, $dest_height );
					///Use alpha chanel
					imagealphablending( $image_gd_new, false );
					imagesavealpha( $image_gd_new, true );
					///resize
					imagecopyresampled( $image_gd_new, $src_image, 0, 0, $src_x, $src_y, $dest_width, $dest_height, $src_width, $src_height );
					$B = - 2;
					switch( $this->get_image_mime_type() ){
						case 'image/jpg':
							imageinterlace( $image_gd_new, true );
							$B = imagejpeg( $image_gd_new, $dest_file_path, $quality_jpg_png );
							break;
						case 'image/png':
							$B = imagepng( $image_gd_new, $dest_file_path );
							break;
						case 'image/gif':
							$B = imagegif( $image_gd_new, $dest_file_path );
							break;
					}
					imagedestroy( $src_image );
					imagedestroy( $image_gd_new );
					return $B;
				}
			}
			return - 3;
		}


	}