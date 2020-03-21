<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-02
	 * Time: 16:02
	 */

	namespace hiweb\core\Backtrace;


	class Point{

		/** @var Node[] */
		private $nodes;


		public function __construct( $depth = 0 ){
			$data = debug_backtrace();
			$depth = (int)$depth;
			foreach( $data as $index => $node ){
				if( $index <= $depth ) continue;
				$this->nodes[] = new Node( $node );
			}
		}


		/**
		 * @return node[]
		 */
		public function get_nodes(){
			return $this->nodes;
		}


		/**
		 * @param int $index
		 * @return node
		 */
		public function get_node( $index = 0 ){
			return $this->nodes[ $index ];
		}


		/**
		 * @return array
		 */
		public function get_chunk_functions(){
			$R = [];
			foreach( $this->get_nodes() as $index => $node ){
				$R[ $index ] = $node->get_function();
			}
			return $R;
		}


		/**
		 *
		 */
		public function the_console_nodes(){
			$group = spl_object_id( $this );
			foreach( $this->nodes as $index => $node ){
				if( $index == 0 ){
					$group = $node->get_file() . ' : ' . $node->get_line() . ' [id:' . rand( 0, 99 ) . ']';
				} else {
					$node->the_location_console( $group );
				}
			}
		}

	}