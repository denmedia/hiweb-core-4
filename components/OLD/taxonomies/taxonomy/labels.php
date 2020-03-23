<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 20.10.2017
	 * Time: 16:07
	 */

	namespace hiweb\taxonomies\taxonomy;


	use hiweb\taxonomies\taxonomy;


	class labels{

		/**
		 * @var taxonomy
		 */
		private $taxonomy;
		private $labels = [];


		public function __construct( taxonomy $taxonomy ){
			$this->taxonomy = $taxonomy;
		}


		/**
		 * @param $key
		 * @param $value
		 * @return labels
		 */
		private function set( $key, $value ){
			$this->labels[ $key ] = $value;
			$this->taxonomy->set_arg( 'labels', $this->labels );
			return $this;
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function name( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function singular_name( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function menu_name( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function all_items( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function edit_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function view_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function update_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function add_new_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function new_item_name( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function parent_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function parent_item_colon( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function search_items( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function popular_items( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function separate_items_with_commas( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function add_or_remove_items( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function choose_from_most_used( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function not_found( $set ){
			return $this->set( __FUNCTION__, $set );
		}

	}