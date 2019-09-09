<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11/12/2018
	 * Time: 00:18
	 */

	namespace hiweb\components\images;


	use hiweb\core\arrays\Arrays;
	use hiweb\core\paths\Path;


	class Size extends Path{

		/** @var Arrays */
		private $data;
		/** @var string */
		private $size_name = '';
		/** @var int */
		private $width = 0;
		/** @var int */
		private $height = 0;
		/** @var int */
		private $aspect = 0;
		/** @var image */
		private $image;


		/**
		 * source constructor.
		 * @param bool   $path
		 * @param Image  $image
		 * @param string $size_name
		 * @param int    $width
		 * @param int    $height
		 */
		public function __construct( $path, Image $image, $size_name = '', $width = 0, $height = 0 ){
			parent::__construct( $path );
			$this->image = $image;
			if( $size_name != '' ) $this->set_size_name( $size_name );
			if( intval( $width ) > 1 ){
				$this->width = $width;
			}
			if( intval( $height ) > 1 ){
				$this->height = $height;
			}
		}


		/**
		 * @return image
		 */
		public function get_image(){
			return $this->image;
		}


		/**
		 * @param image $parent_image
		 * @param null  $size_name
		 * @param array $data
		 * @return $this
		 */
		//		public function connect_file_to_image( image $parent_image, $data = [], $size_name = null ){
		//			$this->image = $parent_image;
		//			$this->data = $data instanceof arrays\array_ ? $data : Arrays::make( $data );
		//			if( $this->data->has_key( 'width' ) ) $this->width = (int)$this->data->value_by_key( 'width', 0 );
		//			if( $this->data->has_key( 'height' ) ) $this->height = (int)$this->data->value_by_key( 'height', 0 );
		//			if( $this->width > 0 && $this->height > 0 ) $this->aspect = $this->width / $this->height;
		//			$this->set_size_name( $size_name );
		//			if( !$this->is_optimized() && $this->is_readable() && $this->is_classic_file_type() ){
		//				$this->make();
		//			}
		//			return $this;
		//		}

		/**
		 * Get old pre optimize file size in bytes
		 * @return string|array
		 */
		public function _get_optimize_data(){
			return get_post_meta( $this->image->get_attachment_id(), images::$meta_key_optimized . '-' . $this->get_dimension_size_string(), true );
		}


		/**
		 * @return bool
		 */
		public function is_optimized(){
			if( $this->image->is_attachment_exists() ){
				return $this->_get_optimize_data() != '' || ( is_array( $this->_get_optimize_data() && count( $this->_get_optimize_data() ) > 0 ) );
			}
			return false;
		}


		/**
		 * Set file size in bytes
		 * @param array $optimize_data
		 * @return bool|int
		 */
		public function _set_optimized( $optimize_data = [] ){
			return update_post_meta( $this->image->get_attachment_id(), images::$meta_key_optimized . '-' . $this->get_dimension_size_string(), $optimize_data );
		}


		/**
		 * Remake optimized file
		 * @param bool $skip_if_optimized
		 * @return bool|int
		 */
		//		public function do_optimize( $skip_if_optimized = true ){
		//			$B = false;
		//			if( $this->is_readable() && $this->is_classic_file_type() ){
		//				if( !$skip_if_optimized || !$this->is_optimized() ){
		//					$time_lead = microtime( true );
		//					$tmp_path = $this->_make( 75, false );
		//					$time_lead = microtime( true ) - $time_lead;
		//					if( is_string( $tmp_path ) && !empty( $tmp_path ) ){
		//						$tmp_file = files::get( $tmp_path );
		//						///Replace old file
		//						if( $tmp_file->get_size() < $this->get_size() ){
		//							@rename( $tmp_file->get_path(), $this->get_path() );
		//						}
		//						$B = $this->_set_optimized( [
		//							'date' => microtime( true ),
		//							'time_lead' => $time_lead,
		//							'size_old' => $this->get_size(),
		//							'size_new' => $tmp_file->get_size(),
		//							'optimization' => $this->get_size() / $tmp_file->get_size()
		//						] );
		//					} else {
		//						$B = $this->_set_optimized( [
		//							'date' => microtime( true ),
		//							'time_lead' => $time_lead,
		//							'size_old' => $this->get_size(),
		//							'size_new' => 0,
		//							'optimization' => 0
		//						] );
		//					}
		//				}
		//			}
		//			return $B;
		//		}

		/**
		 * @param $name
		 */
		private function set_size_name( $name ){
			if( preg_match( '~^[\d]{1,10}x[\d]{1,10}~im', $name ) > 0 ){
				$this->size_name = $this->get_dimension_size_string();
			} else {
				$this->size_name = (string)$name;
			}
		}


		/**
		 * @return string
		 */
		public function get_size_name(){
			return $this->size_name == '' ? $this->get_dimension_size_string() : $this->size_name;
		}


		/**
		 * Return source width
		 * @return int
		 */
		public function width(){
			if( intval( $this->width ) < 1 ){
				$this->width = intval( $this->File()->width() );
			}
			return $this->width;
		}


		/**
		 * Return source height
		 * @return int
		 */
		public function height(){
			if( intval( $this->height ) < 1 ){
				$this->height = intval( $this->File()->height() );
			}
			return $this->height;
		}


		/**
		 * Return source aspect ratio
		 * @return int
		 */
		public function aspect(){
			if( $this->width() == 0 || $this->height() == 0 ) return 0;
			return $this->width() / $this->height();
		}


		/**
		 * Return true if source is cropped size
		 * @return bool
		 */
		public function is_crop(){
			return $this->get_image()->size_calculator()->is_crop( $this->width(), $this->height() );
		}


		/**
		 * Return source pixels count
		 * @return float|int
		 */
		public function get_pixels(){
			return $this->width() * $this->height();
		}


		/**
		 * Return string dimensions, like 150x150c-jpg | 800x640-png
		 * @return string
		 */
		public function get_dimension_size_string(){
			return $this->width() . 'x' . $this->height() . ( $this->is_crop() ? 'c' : '' ) . '-' . $this->extension();
		}


		/**
		 * Create file if not exists
		 * @param int $quality_if_type_support - set quyality if type is support, etc: jpg, webp...
		 * @return int - return 1 - if success, -1 - if error while make, 0 - if file already exists
		 */
		public function make_if_not_exists( $quality_if_type_support = 75 ){
			if( !$this->is_exists() ){
				return $this->_make( $quality_if_type_support ) ? 1 : - 1;
			}
			return 0;
		}


		/**
		 * Force make current image file
		 * @param int  $quality_jpg_png
		 * @param bool $replace - replace file if exists
		 * @return bool|string
		 */
		public function _make( $quality_jpg_png = 75, $replace = true ){
			if( !$this->get_image() instanceof image ) return - 1;
			///
			if( $this->get_image()->get_size_original()->is_readable() && $this->get_image()->aspect() != 0 ){
				///
				$editor = images::get_editor( $this->get_image()->get_size_original()->get_path() );
				return is_string( $editor->make_file( $this->get_path(), $this->width(), $this->height(), $quality_jpg_png ) );
			}
			///
			return - 6;
		}


		/**
		 * @return bool
		 */
		public function is_classic_file_type(){
			return array_key_exists( $this->extension(), array_flip( images::$classic_file_types ) );
		}


		/**
		 * @return bool
		 */
		public function is_progressive_file_type(){
			return array_key_exists( $this->extension(), array_flip( images::$progressive_types ) );
		}

	}