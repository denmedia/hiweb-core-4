<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-02
	 * Time: 16:20
	 */

	namespace hiweb\core\Backtrace;


	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Paths\PathsFactory;


	class Node{


		private $data;
		public $function;
		public $file;
		public $line;
		public $type;
		public $location;


		public function __construct( $node_data ){
			$this->data = new ArrayObject( $node_data );
			$this->function = $this->get_function();
			$this->file = $this->get_file();
			$this->line = $this->get_line();
			$this->type = $this->data->type;
			$this->location = $this->get_location();
		}


		/**
		 * @return mixed
		 */
		public function get_function(){
			$append = '';
			if( preg_match( '/^\{.*\}$/im', $this->function ) == 0 ){
				$append = '()';
			}
			return ( (string)$this->data->class == '' ) ? $this->data->function . $append : $this->data->class . $this->data->type . $this->data->function . $append;
		}


		/**
		 * @return mixed
		 */
		public function get_file(){
			return PathsFactory::get( $this->data->file )->get_path_relative();
		}


		/**
		 * @return mixed
		 */
		public function get_line(){
			return $this->data->line;
		}


		/**
		 * @return string
		 */
		public function get_location(){
			return $this->get_file() . ' : ' . $this->get_function() . ' : ' . $this->get_line();
		}


		public function the_location_console( $groupTitle = '' ){
			console_log( '%c' . $this->get_file() . ' %c: %c' . $this->get_line() . ' %c: %c' . $this->get_function(), $groupTitle, [ 'color:#69a6cc; font-style: italic', 'color:#999999', 'color:#999999', 'color:#999999', 'color:#ffc66d' ] );
		}


	}