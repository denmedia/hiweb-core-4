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
		 * Get parsed data from array and object. Limit depth.
		 * @param     $data
		 * @param int $depth
		 * @return array|string
		 */
		private function get_variable_data( $data, $depth = 3 ){
			if( $depth < 0 ){
				if( is_array( $data ) ){
					return '[array → count=' . count( $data ) . ']';
				} elseif( is_object( $data ) ) {
					return '[object → class=' . get_class( $data ) . ']';
				} else {
					return $data;
				}
			}
			$R = [];
			if( is_array( $data ) ){
				foreach( $data as $key => $val ){
					$R[ $key ] = $this->get_variable_data( $val, $depth - 1 );
				}
				return $R;
			} elseif( is_object( $data ) ) {
				$pattern = '/^[\s\S]*' . preg_quote( get_class( $data ) ) . '/';
				foreach( (array)$data as $key => $value ){
					$key = preg_replace( $pattern, '', $key );
					$R[ $key ] = $this->get_variable_data( $value, $depth - 1 );
				}
				return $R;
			} else {
				return $data;
			}
		}


		/**
		 * @return string
		 * @version 1.4
		 */
		public function html(){
			$params = [json_encode( $this->get_variable_data( $this->content ) )];
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