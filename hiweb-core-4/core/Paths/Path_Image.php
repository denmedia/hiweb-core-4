<?php
	
	namespace hiweb\core\Paths;
	
	
	
	class Path_Image{
		
		/**
		 * @var Path
		 */
		private $Path;
		/** @var int */
		private $cache_image_width;
		/** @var int */
		private $cache_image_height;
		
		private $Intervention_Image;
		
		
		public function __construct( Path $Path ){
			$this->Path = $Path;
		}
		
		
		/**
		 * @return Path
		 */
		public function Path(){
			return $this->Path;
		}
		
		
		/**
		 * @return Path_File
		 */
		public function File(){
			return $this->Path()->file();
		}
		
		
		/**
		 * @return string
		 */
		public function get_path(){
			return $this->File()->get_absolute_path();
		}
		
		
		/**
		 * @return mixed|void
		 */
		public function get_url(){
			return $this->Path()->url()->get();
		}
		
		
		/**
		 * Return image width if file exists
		 * @return int
		 */
		public function width(){
			if( !is_int( $this->cache_image_width ) ){
				$this->cache_image_width = 0;
				$this->cache_image_height = 0;
				if( $this->File()->is_readable() ){
					$size = getimagesize( $this->File()->get_path() );
					if( is_array( $size ) ) list( $this->cache_image_width, $this->cache_image_height ) = $size;
				}
			}
			return $this->cache_image_width;
		}
		
		
		/**
		 * Return image height if file exists
		 * @return int
		 */
		public function height(){
			if( !is_int( $this->cache_image_height ) ){
				$this->cache_image_width = 0;
				$this->cache_image_height = 0;
				if( $this->File()->is_readable() ){
					$size = getimagesize( $this->File()->get_path() );
					if( is_array( $size ) ) list( $this->cache_image_width, $this->cache_image_height ) = $size;
				}
			}
			return $this->cache_image_height;
		}
		
		
		/**
		 * Return aspect of image if file exists
		 * @return float|int
		 */
		public function aspect(){
			if( $this->width() == 0 || $this->height() == 0 ) return 0;
			return $this->width() / $this->height();
		}
		
		
		/**
		 * Return current image mime type
		 * @return bool|mixed
		 */
		public function get_mime_type(){
			if( $this->File()->extension() == 'jxr' ) return 'image/jxr';
			$mimes = [
				IMAGETYPE_GIF => "image/gif",
				IMAGETYPE_JPEG => "image/jpg",
				IMAGETYPE_PNG => "image/png",
				IMAGETYPE_SWF => "image/swf",
				IMAGETYPE_PSD => "image/psd",
				IMAGETYPE_BMP => "image/bmp",
				IMAGETYPE_TIFF_II => "image/tiff",
				IMAGETYPE_TIFF_MM => "image/tiff",
				IMAGETYPE_JPC => "image/jpc",
				IMAGETYPE_JP2 => "image/jp2",
				IMAGETYPE_JPX => "image/jpx",
				IMAGETYPE_JB2 => "image/jb2",
				IMAGETYPE_SWC => "image/swc",
				IMAGETYPE_IFF => "image/iff",
				IMAGETYPE_WBMP => "image/wbmp",
				IMAGETYPE_XBM => "image/xbm",
				IMAGETYPE_ICO => "image/ico",
			];
			if( ( $this->File()->is_readable() && $image_type = exif_imagetype( $this->File()->get_path() ) ) && ( array_key_exists( $image_type, $mimes ) ) ){
				return $mimes[ $image_type ];
			}
			else{
				return 'image/' . $this->File()->extension();
			}
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
			if( $this->File()->is_image() && $this->aspect() != 0 ){
				if( !is_string( $dest_file_path ) || strlen( $dest_file_path ) < 2 ) $dest_file_path = $this->File()->get_path();
				if( $dest_width > $this->width() ) $dest_width = $this->width();
				if( $dest_height > $this->height() ) $dest_height = $this->height();
				$dest_width = (int)$dest_width == 0 ? $this->width() : (int)$dest_width;
				$dest_height = (int)$dest_height == 0 ? $this->height() : (int)$dest_height;
				$dest_aspect = $dest_width / $dest_height;
				//GD
				if( extension_loaded( 'gd' ) ){
					///
					switch( $this->get_mime_type() ){
						case 'image/jpg':
							$src_image = imagecreatefromjpeg( $this->File()->get_path() );
							break;
						case 'image/png':
							$src_image = imagecreatefrompng( $this->File()->get_path() );
							break;
						case 'image/gif':
							$src_image = imagecreatefromgif( $this->File()->get_path() );
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
					}
					elseif( $this->aspect() > $dest_aspect ){
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
					switch( $this->get_mime_type() ){
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