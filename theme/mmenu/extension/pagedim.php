<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:34
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class pagedim{


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
		public function white(){
			$this->data = 'pagedim-white';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function black(){
			$this->data = 'pagedim-black';
			return $this->extensions;
		}

	}