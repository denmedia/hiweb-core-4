<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 09:18
	 */

	namespace hiweb\css;


	use hiweb\hidden_methods;


	class media{

		private $screen = [];
		private $parent_css;


		use hidden_methods;


		public function __construct( file $css ){
			$this->parent_css = $css;
		}


		/**
		 * Default. Used for all media type devices
		 * @return file
		 */
		public function all(){
			$this->screen[] = 'all';
			return $this->parent_css;
		}


		/**
		 * Used for Print preview mode/printed pages
		 * @return file
		 */
		public function print_(){
			$this->screen[] = 'print';
			return $this->parent_css;
		}


		/**
		 * Used for computer screens, tablets, smart-phones etc.
		 * @return file
		 */
		public function screen(){
			$this->screen[] = 'screen';
			return $this->parent_css;
		}


		/**
		 * Used for screenreaders that "reads" the page out loud
		 * @return file
		 */
		public function speech(){
			$this->screen[] = 'speech';
			return $this->parent_css;
		}


		/**
		 * @return string
		 */
		public function get(){
			return $this->screen != '' ? 'media="' . implode( ',', $this->screen ) . '"' : '';
		}

	}