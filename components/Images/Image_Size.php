<?php
	
	namespace hiweb\components\Images;
	
	
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	use stdClass;
	
	
	class Image_Size{
		
		use hidden_methods;
		
		
		private $Image;
		/** @var stdClass */
		private $size_raw;
		private $size_name;
		protected $file_path;
		protected $width = 0;
		protected $height = 0;
		protected $crop = 1;
		
		
		public function __construct( Image $Image, $size_raw, $size_name = '' ){
			$this->Image = $Image;
			$this->size_raw = (object)$size_raw;
			$this->size_name = $size_name;
			///FILE PATH
			if( !property_exists( $this->get_size_raw(), 'file' ) || $this->get_size_raw()->file == '' ){
				if( property_exists( $this->size_raw, 'width' ) && $this->size_raw->width > 0 ) $this->width = $this->size_raw->width;
				if( property_exists( $this->size_raw, 'height' ) && $this->size_raw->height > 0 ) $this->height = $this->size_raw->height;
				$this->file_path = $this->Image->Path()->File()->dirname() . '/' . $this->Image->Path()->File()->filename() . '-' . $this->width . 'x' . $this->height . '.' . $this->Image->Path()->File()->extension();
			}
			else{
				$this->file_path = $this->Image->Path()->File()->dirname() . '/' . $this->get_size_raw()->file;
			}
			///
			if( array_key_exists( $this->size_name, wp_get_registered_image_subsizes() ) ){
				$size_data = (object)wp_get_registered_image_subsizes()[ $this->size_name ];
				if( $this->width == 0 ) $this->width = (int)$size_data->width;
				if( $this->height == 0 ) $this->height = (int)$size_data->height;
				$this->crop = (bool)$size_data->crop;
			}
			///
			if( property_exists( $this->get_size_raw(), 'crop' ) ){
				if( $this->get_size_raw()->crop === true ){
					$this->crop = 0;
				}
				elseif( $this->get_size_raw()->crop === false ){
					$this->crop = - 1;
				}
				elseif( $this->get_size_raw()->crop >= - 1 && $this->get_size_raw()->crop <= 1 ){
					$this->crop = $this->get_size_raw()->crop;
				}
			}
		}
		
		
		/**
		 * @return int
		 */
		public function width(){
			return $this->width;
		}
		
		
		/**
		 * @return int
		 */
		public function height(){
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
		 * @return bool|int
		 */
		public function get_crop_mode(){
			return $this->crop;
		}
		
		
		/**
		 * @return array
		 */
		public function dimension(){
			return [ $this->width(), $this->height(), $this->get_crop_mode() ];
		}
		
		
		/**
		 * @return string
		 */
		public function get_name(){
			return $this->size_name;
		}
		
		
		/**
		 * @return Image
		 */
		public function Image(): Image{
			return $this->Image;
		}
		
		
		/**
		 * @return stdClass
		 */
		public function get_size_raw(){
			return $this->size_raw;
		}
		
		
		/**
		 * @return string
		 */
		public function get_file_path(){
			return $this->file_path;
		}
		
		
		/**
		 * @return Path
		 */
		public function Path(): Path{
			return PathsFactory::get( $this->get_file_path() );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_exists(){
			return $this->file_path != '' && $this->Path()->File()->is_file() && $this->Path()->File()->is_exists();
		}
		
		
		/**
		 * @param bool $force_renew
		 * @param int  $quality_jpg_png
		 * @return bool|int
		 */
		public function make_file( $force_renew = false, $quality_jpg_png = 75 ){
			if( !$this->Image->is_exists() ) return 0;
			if( !$force_renew && $this->Path()->File()->is_exists() ) return 0;
			$R = $this->Image->Path()->Image()->resize( $this->width(), $this->height(), $this->get_file_path(), $quality_jpg_png );
			if( $R == true ){
				ConsoleFactory::add( 'New image file created', 'info', __METHOD__, $this->get_file_path(), true );
				$this->Image->_update_image_sizes_meta();
			}
			else{
				ConsoleFactory::add( 'Error while create new image file', 'warn', __METHOD__, $this->get_file_path(), true );
			}
			return $R;
		}
		
		
	}