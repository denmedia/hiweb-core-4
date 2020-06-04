<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:53
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class theme{


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
		public function light(){
			$this->data = '';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function dark(){
			$this->data = 'theme-dark';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function white(){
			$this->data = 'theme-white';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function black(){
			$this->data = 'theme-black';
			return $this->extensions;
		}

	}