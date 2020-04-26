<?php
	
	namespace hiweb\components\Fields\Types\File;
	
	
	use hiweb\components\Fields\Field;
	use hiweb\core\Paths\Path_File;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	
	
	class Field_File extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\File\Field_File_Options';
		
		/** @var Path[] */
		private $Paths = [];
		/** @var null|Path */
		private $last_Path;
		
		
		/**
		 * @return Field_File_Options
		 */
		public function Options(){
			return parent::Options();
		}
		
		
		function get_css(){
			return [
				__DIR__ . '/field-file.css'
			];
		}
		
		
		function get_js(){
			return [
				__DIR__ . '/field-file.min.js'
			];
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