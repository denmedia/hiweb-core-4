<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/11/2018
	 * Time: 18:48
	 */

	namespace theme\languages;


	use hiweb\core\ArrayObject\ArrayObject;
	use theme\languages;


	class language{

		protected $id;
		protected $locale;
		protected $name;
		protected $title;
		protected $site_id = 0;


		public function __construct( $data ){
			$data_array = new ArrayObject($data);
			$this->id = mb_strtolower( $data_array->_('id', 'ru' ) );
			$this->locale = mb_strtolower( $data_array->_( 'locale', 'ru_RU' ) );
			$this->name = mb_strtolower(  $data_array->_('name', 'стандартный язык' ) );
			$this->title = mb_strtolower(  $data_array->_( 'title', 'по-умолчанию' ) );
			if( detect::is_multisite() )
				$this->site_id = intval(  $data_array->_('site_id', 0 ) );
		}


		public function get_id(){
			return $this->id;
		}


		/**
		 * @return bool
		 */
		public function is_default(){
			return $this->id == languages::get_default_id();
		}


		/**
		 * @param      $field_id
		 * @param bool $ignore_multisite
		 * @return string
		 */
		public function get_field_id( $field_id, $ignore_multisite = false ){
			if( !$ignore_multisite && detect::is_multisite() ){
				return $field_id;
			} elseif( !$ignore_multisite && $this->is_default() ) {
				return $field_id;
			} else {
				return $field_id . '-lang-' . $this->id;
			}
		}


		/**
		 * @param      $field_id
		 * @param null $contentObject
		 * @return mixed
		 */
		public function get_field( $field_id, $contentObject = null ){
			return get_field( languages::get_field_id( $field_id ), $contentObject );
		}


		/**
		 * @param      $fieldId
		 * @param null $contextObject
		 * @return mixed
		 */
		public function have_rows( $fieldId, $contextObject = null ){
			return $this->have_rows( languages::get_field_id( $fieldId ), $contextObject );
		}


		/**
		 * @param int $case
		 * @return string
		 */
		public function get_name( $case = MB_CASE_TITLE ){
			return mb_convert_case( $this->name, $case );
		}


		/**
		 * @return string
		 */
		public function get_locale(){
			return $this->locale;
		}


		/**
		 * @param int $case
		 * @return string
		 */
		public function get_title( $case = MB_CASE_TITLE ){
			return mb_convert_case( $this->title, $case );
		}


		public function get_post( $post_id ){
			$request_post = languages::get_post( $post_id );
			if( $request_post->is_default() ){
				//TODO
			}
			dump_var( 'TODO: get_post [' . $post_id . ']' );
		}


		/**
		 * @return int
		 */
		public function get_site_id(){
			return $this->site_id;
		}


		/**
		 * Return current page url by language
		 * @return string
		 */
		public function get_url(){
			if( detect::is_multisite() ){
				return get_home_url( $this->get_site_id() );
			} else {
				if( function_exists( 'get_queried_object' ) && get_queried_object_id() != get_option( 'page_on_front' ) ){
					if( get_queried_object() instanceof \WP_Post && languages::is_post_type_allowed( get_queried_object()->post_type ) ){
						$lang_post = languages::get_post( get_queried_object_id() );
						if( $lang_post->is_sibling_lang_exists( $this->get_id() ) ){
							return get_permalink( $lang_post->get_sibling_post( $this->get_id() )->get_post_id() );
						}
					}
					if( get_queried_object() instanceof \WP_Term && languages::is_taxonomy_allowed( get_queried_object()->taxonomy ) ){
						$lang_term = languages::get_term( get_queried_object_id() );
						if( $lang_term->is_sibling_lang_exists( $this->get_id() ) ){
							return get_term_link( $lang_term->get_sibling_term( $this->get_id() )->get_term_id() );
						}
					}
				}
				return PathsFactory::root() . '/' . $this->get_id();
			}
		}


		/**
		 * @param      $post_id
		 * @param bool $apply_filters
		 * @return mixed|string
		 */
		public function get_the_content( $post_id, $apply_filters = false ){
			$wp_post = get_post( $post_id );
			if( !$wp_post instanceof \WP_Post )
				return '';
			///
			if( $this->is_default() || detect::is_multisite() ){
				$R = $apply_filters ? apply_filters( 'the_content', $wp_post->post_content ) : $wp_post->post_content;
			} else {
				$R = $apply_filters ? apply_filters( 'the_content', $this->get_field( 'post_content', $wp_post ) ) : $this->get_field( 'post_content', $wp_post );
				if( trim( $R ) == '' ){
					$R = $apply_filters ? apply_filters( 'the_content', $wp_post->post_content ) : $wp_post->post_content;
				}
			}
			return $R;
		}


		/**
		 * @param \WP_Query $wp_query
		 * @return \WP_Query
		 */
		public function filter_wp_query( \WP_Query &$wp_query ){
			if( !$this->is_default() ){
				if( is_array( $wp_query->query ) && count( $wp_query->query ) > 0 ){
					$wp_query->set( 'meta_query', [
						[
							'key' => languages::$post_meta_key_lang_id,
							'value' => $this->get_id()
						]
					] );
				} else {
					//
				}
			} else {
				$wp_query->set( 'meta_query', [
					[
						'relation' => 'OR',
						[
							'key' => languages::$post_meta_key_lang_id,
							'compare' => 'NOT EXISTS'
						],
						[
							'key' => languages::$post_meta_key_lang_id,
							'value' => $this->get_id()
						]
					]
				] );
			}
			return $wp_query;
		}

	}