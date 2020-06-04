<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 06/11/2018
	 * Time: 20:35
	 */

	namespace theme\languages;


	use hiweb\arrays;
	use theme\languages;


	class term{

		public $term_id = 0; //инфо-переменная
		public $name; //инфо-переменная
		public $taxonomy = '';
		public $count = 0;

		/** @var string|int */
		private $wp_term_id;

		private $wp_term_taxonomy = '';
		/** @var array|null|\WP_Error|\WP_Term */
		private $wp_term;
		private $is_exists = false;
		/** @var bool|string */
		private $lang_id = false;
		private $language;
		private $default_term;
		private $sibling_ids;


		public function __construct( $term_id ){
			$this->wp_term_id = $term_id;
			$this->wp_term = get_term( $term_id );
			if( $this->wp_term instanceof \WP_Term ){
				$this->is_exists = true;
				$this->wp_term_taxonomy = $this->wp_term->taxonomy;
				//
				$this->term_id = $this->wp_term_id;
				$this->name = $this->wp_term->name;
				$this->taxonomy = $this->wp_term->taxonomy;
				$this->count = $this->wp_term->count;
			}
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return $this->is_exists;
		}


		/**
		 * Return current LANG ID
		 * @return bool|string
		 */
		public function get_lang_id(){
			if( !is_string( $this->lang_id ) ){
				$this->lang_id = get_term_meta( $this->wp_term_id, languages::$post_meta_key_lang_id, true );
				if( !arrays::in_array( $this->lang_id, languages::get_ids() ) ){
					$this->lang_id = languages::get_default_id();
				}
			}
			return $this->lang_id;
		}


		/**
		 * @return language
		 */
		public function get_language(){
			if( !$this->language instanceof language ){
				$this->language = languages::get_language( $this->get_lang_id() );
			}
			return $this->language;
		}


		/**
		 * Return TRUE if current term is default lang
		 * @return bool
		 */
		public function is_default(){
			if( !$this->is_exists() ) return false;
			return $this->get_lang_id() == languages::get_default_id();
		}


		/**
		 * @return array|null|\WP_Error|\WP_Term
		 */
		public function get_wp_term(){
			return $this->wp_term;
		}


		/**
		 * @return int
		 */
		public function get_term_id(){
			if( !$this->is_exists() ) return 0;
			return intval( $this->wp_term_id );
		}


		/**
		 * @return string
		 */
		public function get_taxonomy(){
			return $this->wp_term_taxonomy;
		}


		/**
		 * @return int
		 */
		public function get_parent_id(){
			if( !$this->is_exists() ) return 0;
			return $this->get_wp_term()->parent;
		}


		/**
		 * @param null $lang_id - null|false - return current lang post
		 * @return term
		 */
		public function get_parent( $lang_id = null ){
			if( !is_string( $lang_id ) ) $lang_id = $this->get_lang_id();
			return languages::get_term( $this->get_parent_id() )->get_sibling_term( $lang_id );
		}


		/**
		 * Return default language term
		 * @return term
		 */
		public function get_default_term(){
			if( !$this->default_term instanceof term ){
				if( $this->is_default() ){
					$this->default_term = $this;
				} else {
					$this->default_term = languages::get_term( intval( get_term_meta( $this->wp_term_id, languages::$post_meta_key_default_post_id, true ) ) );
				}
			}
			return $this->default_term;
		}


		/**
		 * @return array
		 */
		public function get_sibling_term_ids(){
			if( !is_array( $this->sibling_ids ) ){
				$this->sibling_ids = [];
				if( !$this->is_exists() ) return $this->sibling_ids;
				$this->sibling_ids[] = $this->get_term_id();
				///
				if( $this->is_default() ){
					$default_term_id = $this->get_term_id();
				} else {
					$default_term_id = $this->get_default_term()->get_term_id();
				}
				$this->sibling_ids[] = intval( $default_term_id );
				foreach(
					get_terms( [
						'taxonomy' => $this->get_taxonomy(),
						'hide_empty' => false,
						'get' => 'all',
						'meta_query' => [
							[
								'key' => languages::$post_meta_key_default_post_id,
								'value' => $default_term_id
							]
						]
					] ) as $wp_term
				){
					$this->sibling_ids[] = $wp_term->term_id;
				}
				$this->sibling_ids = array_unique( $this->sibling_ids );
			}
			return $this->sibling_ids;
		}


		/**
		 * Return all sibling language terms (include current)
		 * @return term[]
		 */
		public function get_sibling_terms(){
			$R = [];
			foreach( $this->get_sibling_term_ids() as $term_id ){
				$lang_term = languages::get_term( $term_id );
				if( $lang_term->is_exists() ){
					$R[ $lang_term->get_lang_id() ] = $lang_term;
				}
			}
			return $R;
		}


		/**
		 * @param $lang_id
		 * @return term
		 */
		public function get_sibling_term( $lang_id ){
			$siblings = $this->get_sibling_terms();
			return array_key_exists( $lang_id, $siblings ) ? $siblings[ $lang_id ] : languages::get_term( 0 );
		}


		/**
		 * @return mixed
		 */
		public function __toString(){
			return (string)$this->get_term_id();
		}


		/**
		 * @param $lang_id
		 * @return bool
		 */
		public function is_sibling_lang_exists( $lang_id ){
			return array_key_exists( $lang_id, $this->get_sibling_terms() );
		}


		/**
		 * @param $new_lang_id
		 * @return bool|int
		 */
		public function do_make_sibling( $new_lang_id ){
			if( $this->is_sibling_lang_exists( $new_lang_id ) ) return $this->get_sibling_term( $new_lang_id )->get_term_id();
			if( !$this->is_exists() ) return false;
			///
			$source_title = get_term_meta( $this->get_term_id(), languages::get_language( $new_lang_id )->get_field_id( 'name' ), true );
			$source_title = $source_title == '' ? $this->get_wp_term()->name : $source_title;
			if( $source_title == $this->get_wp_term()->name ) $source_title = "{$source_title} ({$new_lang_id})";
			$source_content = get_term_meta( $this->get_term_id(), languages::get_language( $new_lang_id )->get_field_id( 'description' ), true );
			$parent = 0;
			if( $this->get_parent( $new_lang_id )->is_exists() ){
				$parent = $this->get_parent( $new_lang_id )->get_term_id();
			} elseif( $this->get_parent()->is_exists() ) {
				$parent = $this->get_parent_id();
			}
			$new_term_data = wp_insert_term( $source_title, $this->get_taxonomy(), [
				'parent' => $parent,
				'description' => $source_content == '' ? $this->get_wp_term()->description : $source_content
			] );
			if( is_array( $new_term_data ) && isset( $new_term_data['term_id'] ) ){
				$new_term_id = $new_term_data['term_id'];
				$meta = get_term_meta( $this->get_term_id() );
				if( is_array( $meta ) ) foreach( $meta as $key => $val ){
					update_term_meta( $new_term_id, $key, reset( $val ) );
				}
				///Find Posts by source term
				foreach(
					get_posts( [
						'post_type' => languages::get_post_types( true ),
						'posts_per_page' => - 1,
						'post_status' => 'any',
						'meta_query' => [
							[
								'key' => languages::$post_meta_key_lang_id,
								'value' => $new_lang_id
							]
						],
						'tax_query' => [
							[
								'taxonomy' => $this->get_taxonomy(),
								'field' => 'term_id',
								'terms' => $this->get_term_id()
							]
						]
					] ) as $wp_post
				){
					wp_remove_object_terms( $wp_post->ID, $this->get_term_id(), $this->get_taxonomy() );
					wp_set_object_terms( $wp_post->ID, $new_term_id, $this->get_taxonomy(), true );
				}
				///Set language Meta
				if( $this->is_default() ){
					update_term_meta( $new_term_id, languages::$post_meta_key_default_post_id, $this->get_term_id() );
				}
				update_term_meta( $new_term_id, languages::$post_meta_key_lang_id, $new_lang_id );
				return $new_term_id;
			}
			return $new_term_data;
		}

	}