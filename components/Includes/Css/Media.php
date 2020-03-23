<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 09:18
	 */

	namespace hiweb\components\Includes\Css;


	use hiweb\components\Includes\Css;
	use hiweb\core\Options\Options;
	use hiweb\core\Options\Options_Once;


	class Media extends Options_Once{

		//		public function __construct( file $css ){
		//			$this->parent_css = $css;
		//		}

		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
			$this->set( 'media', ['all'] );
		}


		/**
		 * Default. Used for all media type devices
		 * @return Css
		 */
		public function all(){
			$this->set( 'media', array_merge( $this->get( 'media', [] ), [ 'all' ] ) );
			return $this->getParent_OptionsObject();
		}


		/**
		 * Used for Print preview mode/printed pages
		 * @return Css
		 */
		public function print_(){
			$this->set( 'media', array_merge( $this->get( 'media', [] ), [ 'print' ] ) );
			return $this->getParent_OptionsObject();
		}


		/**
		 * Used for computer screens, tablets, smart-phones etc.
		 * @return Css
		 */
		public function screen(){
			$this->set( 'media', array_merge( $this->get( 'media', [] ), [ 'screen' ] ) );
			return $this->getParent_OptionsObject();
		}


		/**
		 * Used for screenreaders that "reads" the page out loud
		 * @return Css
		 */
		public function speech(){
			$this->set( 'media', array_merge( $this->_( 'media' ), [ 'speech' ] ) );
			return $this->getParent_OptionsObject();
		}


		/**
		 * @return string
		 */
		public function __invoke(){
			return $this->options_ArrayObject()->count() > 0 ? 'media="' . implode( ',', $this->get() ) . '"' : '';
		}

	}