<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:50
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class shadow{

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
		public function page(){
			$this->data = 'shadow-page';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function panels(){
			$this->data = 'shadow-panels';
			return $this->extensions;
		}

	}