<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:23
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class fullscreen{

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
		public function fullscreen(){
			$this->data = 'fullscreen';
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