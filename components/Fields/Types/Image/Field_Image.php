<?php
	
	namespace hiweb\components\Fields\Types\Image;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\components\Images\Image;
	use hiweb\components\Images\ImagesFactory;
	use hiweb\core\Paths\Path_File;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	
	
	class Field_Image extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Image\Field_Image_Options';
		
		/** @var Image */
		private $last_Image;
		
		
		/**
		 * @return Field_Image_Options
		 */
		public function options(){
			return parent::options();
		}
		
		
		public function get_js(){
			return [ __DIR__ . '/assets/image.min.js' ];
		}
		
		
		public function get_css(){
			return [ __DIR__ . '/assets/image.css' ];
		}
		
		
		function admin_init(){
			wp_enqueue_media();
		}
		
		
		/**
		 * @param mixed|null $value
		 * @param bool       $update_meta_process
		 * @return int|mixed|null
		 */
		public function get_sanitize_admin_value( $value, $update_meta_process = false ){
			$value = (int)$value;
			if( $value > 0 ){
				$Image = ImagesFactory::get( $value );
				if( !$Image->is_exists() ) $value = 0;
			}
			if($value == 0) $value = null;
			return $value;
		}
		
		
		/**
		 * @param $value
		 * @return Image
		 */
		public function Image( $value = null ){
			return ImagesFactory::get( $this->get_sanitize_admin_value( $value ) );
		}
		
		
		/**
		 * @return Image
		 */
		public function the_Image(){
			return $this->last_Image;
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			$this->last_Image = $this->Image( $value );
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}