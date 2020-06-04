<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:26
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class listview{

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
		public function justify(){
			$this->data = 'listview-justify';
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