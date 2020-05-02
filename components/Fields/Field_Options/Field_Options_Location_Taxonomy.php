<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location_Taxonomy extends Options{
		
		/**
		 * @param $term_id
		 * @return $this
		 */
		public function term_id( $term_id ){
			return $this->_( __FUNCTION__, $term_id );
		}
		
		
		/**
		 * @param $term_taxonomy_id
		 * @return $this
		 */
		public function term_taxonomy_id( $term_taxonomy_id ){
			return $this->_( __FUNCTION__, $term_taxonomy_id );
		}
		
		
		/**
		 * @param $name
		 * @return $this
		 */
		public function name( $name ){
			return $this->_( __FUNCTION__, $name );
		}
		
		
		/**
		 * @param $taxonomy
		 * @return $this
		 */
		public function taxonomy( $taxonomy ){
			return $this->_( __FUNCTION__, $taxonomy );
		}
		
		
		/**
		 * @param $slug
		 * @return $this
		 */
		public function slug( $slug ){
			return $this->_( __FUNCTION__, $slug );
		}
		
		
		/**
		 * @param $count
		 * @return $this
		 */
		public function count( $count ){
			return $this->_( __FUNCTION__, $count );
		}
		
		
		/**
		 * @param $parent
		 * @return $this
		 */
		public function parent( $parent ){
			return $this->_( __FUNCTION__, $parent );
		}
		
		
		/**
		 * @param $term_group
		 * @return $this
		 */
		public function term_group( $term_group ){
			return $this->_( __FUNCTION__, $term_group );
		}
	
	}