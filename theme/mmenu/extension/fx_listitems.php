<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:19
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class fx_listitems{

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


		/**
		 * @return extensions
		 */
		public function default_(){
			$this->data = '';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function drop(){
			$this->data = 'fx-listitems-drop';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function fade(){
			$this->data = 'fx-listitems-fade';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function slide(){
			$this->data = 'fx-listitems-slide';
			return $this->extensions;
		}

	}