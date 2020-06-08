<?php
	
	namespace hiweb\components\AdminNotices;
	
	
	class AdminNotice_Options{
		
		/** @var AdminNotice */
		private $AdmiNotice;
		private $class = [
			'notice',
			'notice-info',
			'is-dismissible'
		];
		
		
		public function __construct( $AdminNotice ){
			$this->AdmiNotice = $AdminNotice;
			$this->generate_class();
		}
		
		
		/**
		 * Set / unset standart "notice" class
		 * @param bool $set_standart_class
		 * @return AdminNotice_Options
		 */
		public function set_default_class( $set_standart_class = true ){
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
		 * @param bool $set_large
		 * @return $this
		 */
		public function large( $set_large = true ){
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
			$this->AdmiNotice->class = implode( ' ', $R );
		}
	}