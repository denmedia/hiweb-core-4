<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:32
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class multiline{

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
		public function multiline(){
			$this->data = 'multiline';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function default_(){
			$this->data = '';
			return $this->extensions;
		}

	}