<?php

	namespace hiweb\components\FontAwesome;


	class FontAwesome_Icon_Style{
/** @var FontAwesome_Icon  */
		private $Icon;
		private $data;
		private $style = 'regular';
		private $exists = false;


		public function __construct( FontAwesome_Icon $Icon, $style = 'regular' ){
			$this->Icon = $Icon;
			$this->data = [];
			$this->style = $style;
			if(is_string($style) && array_key_exists( $style, $Icon->get_svg() ) ){
				$this->data = $Icon->get_svg()[ $style ];
				$this->exists = true;
			}
		}
		
		
		/**
		 * @return string
		 */
		public function __invoke(){
			return $this->get_raw();
		}
		
		
		/**
		 * @return string
		 */
		public function __toString(){
			return $this->get_raw();
		}
		
		
		public function is_exists(){
			return $this->exists;
		}


		/**
		 * @param string $key
		 * @param null   $default
		 * @return mixed|null
		 */
		public function get_data_value( $key = '', $default = null ){
			return array_key_exists( $key, $this->data ) ? $this->data[ $key ] : $default;
		}


		/**
		 * @return int
		 */
		public function get_last_modified(){
			return $this->get_data_value( 'modified', 0 );
		}


		/**
		 * @return string
		 */
		public function get_raw(){
			return str_replace('<path ','<path fill="currentColor" ', $this->get_data_value( 'raw', '' ));
		}


		/**
		 * @return array
		 */
		public function get_viewBox(){
			return $this->get_data_value( 'viewBox', [] );
		}


		/**
		 * @return int
		 */
		public function get_width(){
			return $this->get_data_value( 'width', 0 );
		}


		/**
		 * @return int
		 */
		public function get_height(){
			return $this->get_data_value( 'height', 0 );
		}


		/**
		 * @return array
		 */
		public function get_path(){
			return $this->get_data_value( 'path', [] );
		}
		
		
		/**
		 * @return string
		 */
		public function get_class(){
			return 'fa'.$this->style[0].' fa-'.$this->Icon->get_name();
		}

	}