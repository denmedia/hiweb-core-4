<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 18.02.2018
	 * Time: 9:37
	 */

	namespace hiweb\admin\notices;


	use hiweb\context;


	class notice_class{

		/** @var notice */
		private $parent_notice;
		private $class = [
			'notice',
			'notice-info',
			'is-dismissible'
		];


		/**
		 * notice_class constructor.
		 */
		public function __construct( notice $parent_notice ){
			$this->parent_notice = $parent_notice;
			$this->generate_class();
		}


		/**
		 * Set / unset standart "notice" class
		 * @param bool $set_standart_class
		 * @return notice_class
		 */
		public function set_notice( $set_standart_class = true ){
			$this->class[0] = $set_standart_class ? 'notice' : '';
			$this->generate_class();
			return $this;
		}


		/**
		 * Set "notice-alt"
		 * @return $this
		 */
		public function alt(){
			$this->class[1] = 'notice-' . __FUNCTION__;
			$this->generate_class();
			return $this;
		}


		/**
		 * Set "notice-dismiss"
		 * @return $this
		 */
		public function dismiss(){
			$this->class[1] = 'notice-' . __FUNCTION__;
			$this->generate_class();
			return $this;
		}


		/**
		 * Set "notice-error"
		 * @return $this
		 */
		public function error(){
			$this->class[1] = 'notice-' . __FUNCTION__;
			$this->generate_class();
			return $this;
		}


		/**
		 * Set "notice-info"
		 * @return $this
		 */
		public function info(){
			$this->class[1] = 'notice-' . __FUNCTION__;
			$this->generate_class();
			return $this;
		}


		/**
		 * Set "notice-success"
		 * @return $this
		 */
		public function success(){
			$this->class[1] = 'notice-' . __FUNCTION__;
			$this->generate_class();
			return $this;
		}


		/**
		 * Set "notice-warning"
		 * @return $this
		 */
		public function warning(){
			$this->class[1] = 'notice-' . __FUNCTION__;
			$this->generate_class();
			return $this;
		}


		/**
		 * Set "notice-large"
		 * @return $this
		 */
		public function set_large( $set_large = true ){
			$this->class[3] = $set_large ? 'notice-large' : '';
			$this->generate_class();
			return $this;
		}


		private function generate_class(){
			$R = [];
			if( is_array( $this->class ) ) foreach( $this->class as $class ){
				if( trim( $class ) == '' ) continue;
				$R[] = $class;
			}
			$this->parent_notice->class = implode( ' ', $R );
		}

	}