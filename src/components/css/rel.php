<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 02:54
	 */

	namespace hiweb\css;


	use hiweb\hidden_methods;


	class rel{

		/** @var file */
		private $parent_css;
		private $rel = 'stylesheet';


		use hidden_methods;


		public function __construct( file $css ){
			$this->parent_css = $css;
		}


		/**
		 * @return file
		 */
		public function stylesheet(){
			$this->rel = 'stylesheet';
			$this->parent_css->add_tag( 'as', null );
			return $this->parent_css;
		}


		/**
		 * @return file
		 */
		public function preload(){
			$this->rel = 'preload';
			$this->parent_css->add_tag( 'as', 'style' );
			return $this->parent_css;
		}


		/**
		 * @return file
		 */
		public function prefetch(){
			$this->rel = 'prefetch';
			$this->parent_css->add_tag( 'as', 'style' );
			return $this->parent_css;
		}


		/**
		 * @return string
		 */
		public function get(){
			return 'rel="' . $this->rel . '"';
		}

	}