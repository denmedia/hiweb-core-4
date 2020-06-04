<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:37
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class popup{

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
		public function popup(){
			$this->data = 'popup';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function side(){
			$this->data = '';
			return $this->extensions;
		}

	}