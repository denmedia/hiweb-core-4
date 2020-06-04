<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 02:54
	 */

	namespace hiweb\components\Includes\Css;


	use hiweb\components\Includes\Css;
	use hiweb\core\Options\Options_Once;


	class Rel extends Options_Once{

		private $rel = 'stylesheet';


		//
		//		public function __construct( file $css ){
		//			$this->parent_css = $css;
		//		}

		/**
		 * @return Css
		 */
		public function stylesheet(){
			$this->set( 'rel', 'stylesheet' );
			$this->getParent_OptionsObject()->remove( 'as' );
			return $this->getParent_OptionsObject();
		}


		/**
		 * @return Css
		 */
		public function preload(){
			$this->set( 'rel', 'preload' );
			$this->getParent_OptionsObject()->set( 'as', 'style' );
			return $this->getParent_OptionsObject();
		}


		/**
		 * @return Css
		 */
		public function prefetch(){
			$this->set( 'rel', 'prefetch' );
			$this->getParent_OptionsObject()->set( 'as', 'style' );
			return $this->getParent_OptionsObject();
		}


		/**
		 * @return string
		 */
		public function __invoke(){
			return 'rel="' . $this->rel . '"';
		}

	}