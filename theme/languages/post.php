<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/11/2018
	 * Time: 20:24
	 */

	namespace theme\languages;


	use hiweb\arrays;
	use theme\languages;


	class post{

		public $ID = 0; //инфо-переменная
		public $post_title; //инфо-переменная
		public $post_status; //инфо-переменная

		private $wp_post_id;
		private $wp_post;
		/** @var bool|string */
		private $lang_id = false;
		/** @var language */
		private $language;
		/** @var post */
		private $default_post;
		/** @var array */
		private $sibling_ids;
		/** @var post[] */
		private $sibling_posts;
		/** @var post[] */
		private $sibling_posts_any_status;


		public function __construct( $post_id ){
			$this->wp_post_id = $post_id;
			$this->wp_post = get_post( $post_id );
			$this->get_language();
			if( $this->is_exists() ){
				$this->ID = $this->wp_post->ID;
				$this->post_title = $this->wp_post->post_title;
				$this->post_status = $this->wp_post->post_status;
			}
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return intval( $this->wp_post_id ) > 0 && $this->wp_post instanceof \WP_Post;
		}


		/**
		 * Return current LANG ID
		 * @return bool|string
		 */
		public function get_lang_id(){
			if( !is_string( $this->lang_id ) ){
				$this->lang_id = get_post_meta( $this->wp_post_id, languages::$post_meta_key_lang_id, true );
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
		 * Return TRUE if current post is default lang
		 * @return bool
		 */
		public function is_default(){
			if( !$this->is_exists() ) return false;
			return $this->get_lang_id() == languages::get_default_id();
		}


		/**
		 * @return array|null|\WP_Post
		 */
		public function get_wp_post(){
			return $this->wp_post;
		}


		/**
		 * @return mixed
		 */
		public function get_post_id(){
			return $this->wp_post_id;
		}


		/**
		 * @return string
		 */
		public function get_post_status(){
			if( !$this->is_exists() ) return '';
			return $this->get_wp_post()->post_status;
		}


		/**
		 * @return int
		 */
		public function get_post_parent_id(){
			if( !$this->is_exists() ) return 0;
			return $this->get_wp_post()->post_parent;
		}


		/**
		 * @param null $lang_id - null|false - return current lang post
		 * @param bool $return_any_post_status
		 * @return post
		 */
		public function get_parent( $lang_id = null, $return_any_post_status = false ){
			if( !is_string( $lang_id ) ) $lang_id = $this->get_lang_id();
			return languages::get_post( $this->get_post_parent_id() )->get_sibling_post( $lang_id, $return_any_post_status );
		}


		/**
		 * Return default language post
		 * @return post
		 */
		public function get_default_post(){
			if( !$this->default_post instanceof post ){
				if( $this->is_default() ){
					$this->default_post = $this;
				} else {
					$this->default_post = languages::get_post( intval( get_post_meta( $this->wp_post_id, languages::$post_meta_key_default_post_id, true ) ) );
				}
			}
			return $this->default_post;
		}


		/**
		 * @return array
		 */
		public function get_sibling_post_ids(){
			if( !is_array( $this->sibling_ids ) ){
				$this->sibling_ids = [];
				if( !$this->is_exists() ) return $this->sibling_ids;
				if(detect::is_multisite()) {
					$this->sibling_ids[get_current_blog_id()] = $this->get_post_id();
					foreach(multisites::get_languages_by_site_id() as $site_id => $language){
						//$this->sibling_ids[$site_id]
						get_posts([
							'post_type' => $this->get_wp_post()->post_type,
							'post_status' => 'any',
							'posts_per_page' => 1,
							'meta_query' => [
								[
									'key' => languages::$post_meta_key_default_post_id,
									'value' => $default_post_id
								]
							]
						]);
					}
				} else {
					$this->sibling_ids[] = $this->get_post_id();
					///
					if( $this->is_default() ){
						$default_post_id = $this->get_post_id();
					} else {
						$default_post_id = $this->get_default_post()->get_post_id();
					}
					$this->sibling_ids[] = intval( $default_post_id );
					foreach(
						get_posts( [
							'post_type' => $this->get_wp_post()->post_type,
							'post_status' => 'any',
							'posts_per_page' => 99,
							'meta_query' => [
								[
									'key' => languages::$post_meta_key_default_post_id,
									'value' => $default_post_id
								]
							]
						] ) as $wp_post
					){
						$this->sibling_ids[] = $wp_post->ID;
					}
					$this->sibling_ids = array_unique( $this->sibling_ids );
				}
			}
			return $this->sibling_ids;
		}


		/**
		 * Return all sibling language posts (include current)
		 * @param bool $return_any_post_status
		 * @return post[]
		 */
		public function get_sibling_posts( $return_any_post_status = false ){
			$R = [];
			foreach( $this->get_sibling_post_ids() as $post_id ){
				$lang_post = languages::get_post( $post_id );
				if( $lang_post->is_exists() ){
					if( $return_any_post_status || $lang_post->get_post_status() == 'publish' ) $R[ $lang_post->get_lang_id() ] = $lang_post;
				}
			}
			return $R;
		}


		/**
		 * @param      $lang_id
		 * @param bool $return_any_post_status
		 * @return post
		 */
		public function get_sibling_post( $lang_id, $return_any_post_status = false ){
			$siblings = $this->get_sibling_posts( $return_any_post_status );
			return array_key_exists( $lang_id, $siblings ) ? $siblings[ $lang_id ] : languages::get_post( 0 );
		}


		/**
		 * @return mixed
		 */
		public function __toString(){
			return $this->get_post_id();
		}


		/**
		 * @param $lang_id
		 * @return bool
		 */
		public function is_sibling_lang_exists( $lang_id ){
			return array_key_exists( $lang_id, $this->get_sibling_posts( false ) );
		}


		/**
		 * @param $new_lang_id
		 * @return bool|int
		 */
		public function do_make_sibling( $new_lang_id ){
			if( $this->is_sibling_lang_exists( $new_lang_id ) ) return $this->get_sibling_post( $new_lang_id )->get_post_id();
			if( !$this->is_exists() ) return false;
			///
			$source_title = get_post_meta( $this->get_post_id(), languages::get_language( $new_lang_id )->get_field_id( 'post_title' ), true );
			$source_content = get_post_meta( $this->get_post_id(), languages::get_language( $new_lang_id )->get_field_id( 'post_content' ), true );
			$post_parent = 0;
			if( $this->get_parent( $new_lang_id )->is_exists() ){
				$post_parent = $this->get_parent( $new_lang_id )->get_post_id();
			} elseif( $this->get_parent()->is_exists() ) {
				$post_parent = $this->get_post_parent_id();
			}
			$new_post_id = wp_insert_post( [
				'post_type' => $this->get_wp_post()->post_type,
				'post_title' => $source_title == '' ? $this->get_wp_post()->post_title : $source_title,
				'post_date' => $this->get_wp_post()->post_date,
				'post_date_gmt' => $this->get_wp_post()->post_date_gmt,
				'post_parent' => $post_parent,
				'post_content' => $source_content == '' ? $this->get_wp_post()->post_content : $source_content,
				'post_author' => $this->get_wp_post()->post_author
			] );
			if( is_int( $new_post_id ) ){
				$taxonomies = get_post_taxonomies( $this->get_post_id() );
				if( is_array( $taxonomies ) ) foreach( $taxonomies as $taxonomy ){
					$terms = wp_get_post_terms( $this->get_post_id(), $taxonomy );
					wp_set_object_terms( $new_post_id, [], $taxonomy, false );
					foreach( $terms as $wp_term ){
						$lang_term = languages::get_term( $wp_term );
						wp_set_object_terms( $new_post_id, $lang_term->is_sibling_lang_exists( $new_lang_id ) ? $lang_term->get_sibling_term( $new_lang_id )->get_term_id() : $wp_term->term_id, $taxonomy, true );
					}
					//wp_set_object_terms( $new_post_id, wp_get_object_terms( $this->get_post_id(), $taxonomy, [ "fields" => "ids" ] ), $taxonomy, false );
				}
				//Set thumbnail
				set_post_thumbnail( $new_post_id, get_post_thumbnail_id( $this->get_post_id() ) );
				//Set meta values
				$meta = get_post_meta( $this->get_post_id() );
				if( is_array( $meta ) ) foreach( $meta as $key => $val ){
					update_post_meta( $new_post_id, $key, reset( $val ) );
				}
				///Set language Meta
				if( $this->is_default() ){
					update_post_meta( $new_post_id, languages::$post_meta_key_default_post_id, $this->get_post_id() );
				}
				update_post_meta( $new_post_id, languages::$post_meta_key_lang_id, $new_lang_id );
				return $new_post_id;
			}
			///
			return false;
		}

	}