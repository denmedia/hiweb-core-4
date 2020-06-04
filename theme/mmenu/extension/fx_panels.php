<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:13
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class fx_panels{

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
		public function none(){
			$this->data = 'fx-panels-none';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function slide_0(){
			$this->data = 'fx-panels-slide-0';
			return $this->extensions;
		}


		/**
		 * default
		 * @return extensions
		 */
		public function slide_30(){
			$this->data = 'fx-panels-slide-30';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function slide_100(){
			$this->data = 'fx-panels-slide-100';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function slide_up(){
			$this->data = 'fx-panels-slide-up';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function zoom(){
			$this->data = 'fx-panels-zoom';
			return $this->extensions;
		}

	}