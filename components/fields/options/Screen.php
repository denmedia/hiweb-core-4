<?php

	namespace hiweb\components\fields\options;


	use hiweb\components\fields\Field;


	class Screen extends \hiweb\core\ArrayObject\Options{

		/**
		 * Add filed to post edit screen
		 * @param string $post_types
		 * @return PostType
		 */
		public function PostType( $post_types = null ){
			if( !$this->_is_exists( 'posts' ) ){
				$PostTypeObject = new PostType( $this );
				$this->set( 'posts', $PostTypeObject );
				$PostTypeObject->post_type( $post_types );
			}
			return $this->get( 'posts' );
		}


		/**
		 * @param null $taxonomies
		 * @return Taxonomy
		 */
		public function Taxonomy( $taxonomies = null ){
			if( !$this->_is_exists( 'taxonomies' ) ){
				$TaxonomiesObject = new Taxonomy( $this );
				$this->set( 'taxonomies', $TaxonomiesObject );
				//$TaxonomiesObject->taxonomy( $post_types );
			}
			return $this->get( 'taxonomies' );
		}


		/**
		 * @param string $post_types
		 * @return PostType
		 * @deprecated use Screen()->PostType(...)->...
		 */
		public function POST_TYPES( $post_types = null ){
			return $this->PostType( $post_types );
		}


		/**
		 * @deprecated use ->Taxonomy(...)->...
		 * @param null $taxonomy
		 * @return Taxonomy
		 */
		public function TAXONOMIES( $taxonomy = null ){
			return $this->Taxonomy($taxonomy);
		}


		public function USERS( $roles = null ){
			//TODO
		}


		public function ADMIN_MENUS( $menu_slug = null ){
			//TODO
		}


		public function THEME(){
			//TODO
		}


		public function COMMENTS(){
			//TODO
		}

	}