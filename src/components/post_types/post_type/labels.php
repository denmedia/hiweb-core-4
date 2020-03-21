<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 20.10.2017
	 * Time: 16:07
	 */

	namespace hiweb\post_types\post_type;


	use hiweb\post_types\post_type;


	class labels{

		private $post_type;


		public function __construct( post_type $post_type ){
			$this->post_type = $post_type;
		}


		/**
		 * @param $key
		 * @param $value
		 * @return labels
		 */
		private function set( $key, $value ){
			$this->post_type->args_custom['labels'][ $key ] = $value;
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
		public function add_new( $set ){
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
		public function edit_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function new_item( $set ){
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
		public function view_items( $set ){
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
		public function not_found( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function not_found_in_trash( $set ){
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
		public function all_items( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function archives( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function attributes( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function insert_into_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function uploaded_to_this_item( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function featured_image( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function set_featured_image( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function remove_featured_image( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function use_featured_image( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function filter_items_list( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function items_list_navigation( $set ){
			return $this->set( __FUNCTION__, $set );
		}


		/**
		 * @param $set
		 * @return labels
		 */
		public function items_list( $set ){
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
		public function name_admin_bar( $set ){
			return $this->set( __FUNCTION__, $set );
		}

	}