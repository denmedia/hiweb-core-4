<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 09:41
	 */

	namespace theme\mmenu\extension;


	use hiweb\core\hidden_methods;
	use theme\mmenu\extensions;


	class border{

		private $data = 'indent';
		private $extensions;


		use hidden_methods;


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
		public function indent(){
			$this->data = 'indent';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function full(){
			$this->data = 'border-full';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function offset(){
			$this->data = 'border-offset';
			return $this->extensions;
		}


		/**
		 * @return extensions
		 */
		public function none(){
			$this->data = 'border-none';
			return $this->extensions;
		}

	}