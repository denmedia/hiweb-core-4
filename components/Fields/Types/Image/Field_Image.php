<?php
	
	namespace hiweb\components\Fields\Types\Image;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\core\Paths\Path_File;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	
	
	class Field_Image extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Image\Field_Image_Options';
		
		/** @var Path[] */
		private $Paths = [];
		/** @var null|Path */
		private $last_Path;
		
		
		/**
		 * @return Field_Image_Options
		 */
		public function Options(){
			return parent::Options();
		}
		
		
		public function get_js(){
			return [ __DIR__ . '/App.min.js' ];
		}
		
		
		public function get_css(){
			return [ __DIR__ . '/Style.css' ];
		}
		
		
		function admin_init(){
			wp_enqueue_media();
		}
		
		
		/**
		 * @param $value
		 * @return Path
		 */
		public function Path( $value = null ){
			if( !array_key_exists( $value, $this->Paths ) ){
				$this->Paths[ $value ] = PathsFactory::get_by_id( $value );
			}
			return $this->Paths[ $value ];
		}
		
		
		/**
		 * @return mixed|Path_File
		 */
		public function the_File(){
			return $this->last_Path instanceof Path ? $this->last_Path->File() : null;
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			$this->last_Path = $this->Path( $value );
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}