<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:09
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class fx_menu{

		private $data = '';
		private $extensions;

		public function __construct( extensions $extensions ){
			$this->extensions = $extensions;
		}


		/**
		 * @return string|array
		 */
		public function get(){
			return $this->data;
		}


		public function default_(){
			$this->data = '';
			return $this->extensions;
		}

		public function fade(){
			$this->data = 'fx-menu-fade';
			return $this->extensions;
		}

		public function slide(){
			$this->data = 'fx-menu-slide';
			return $this->extensions;
		}

		public function zoom(){
			$this->data = 'fx-menu-zoom';
			return $this->extensions;
		}

	}