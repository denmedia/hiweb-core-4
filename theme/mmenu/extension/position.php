<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 10:40
	 */

	namespace theme\mmenu\extension;


	use theme\mmenu\extensions;


	class position{


		private $data = [];
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
		public function left(){
			$this->data[0] = '';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function right(){
			$this->data[0] = 'position-right';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function top(){
			$this->data[0] = 'position-top';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function bottom(){
			$this->data[0] = 'position-bottom';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function back(){
			$this->data[1] = 'position-back';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function front(){
			$this->data[1] = 'position-front';
			return $this->extensions;
		}


	}