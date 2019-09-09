<?php

	namespace hiweb\components\console;


	class Message{

		public $content = '';
		public $type = 'info';
		public $debugMod = '';
		public $addition_data = [];


		public function __construct( $content = '', $type = 'info', $addition_data = [] ){
			$this->content = $content;
			$this->type = $type;
			$this->addition_data = $addition_data;
		}


		public function set_content( $content = null ){
			$this->content = $content;
		}


		public function set_addition_data( $addition_data ){
			$this->addition_data = $addition_data;
		}


		/**
		 * @return string
		 */
		public function type(){
			$allow_types = [ 'info', 'log', 'warn', 'error' ];
			return array_search( $this->type, $allow_types ) === false ? 'info' : $this->type;
		}


		/**
		 * @return string
		 * @version 1.2
		 */
		public function html(){
			if( is_object( $this->content ) ){
				$R = [];
				$pattern = '/^[\s\S]*'.preg_quote( get_class( $this->content ) ).'/';
				foreach( (array)$this->content as $key => $value ){
					$key = preg_replace( $pattern, '', $key );
					$R[ $key ] = $value;
				}
				$params = [ json_encode( $R ) ];
			} else $params = [ json_encode( $this->content ) ];
			if( is_array( $this->addition_data ) ) foreach( $this->addition_data as $data ){
				$params[] = json_encode( $data );
			}
			$params = implode( ', ', $params );
			return "<script>console.{$this->type()}({$params});</script>";
		}


		/**
		 * Print html
		 */
		public function the(){
			$R = $this->html();
			echo $R;
		}


		public function set_groupTitle( $set = true ){
			$this->debugMod = $set;
		}

	}