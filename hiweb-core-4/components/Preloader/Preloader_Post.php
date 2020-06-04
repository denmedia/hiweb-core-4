<?php

	namespace hiweb\components\Preloader;


	use hiweb\core\Cache\CacheFactory;
	use stdClass;
	use WP_Post;
	use WP_Term;


	class Preloader_Post{

		private $post_ID;
		/** @var WP_Post */
		private $WP_Post;
		private $post_meta = [];
		private $parent = 0;
		private $children = [];

		private $terms_by_taxonomy = [];
		private $term_meta = [];


		public function __construct( $post_data = null ){
			if( $post_data instanceof stdClass ){
				///WP_Post setup
				if( $post_data->post instanceof stdClass ){
					$this->WP_Post = get_post( $post_data->post );
					$this->post_ID = $this->WP_Post->ID;
				}
				///Post meta setup
				if( is_array( $post_data->meta ) ){
					$this->post_meta = $post_data->meta;
				}
				///Parent setup
				$this->parent = intval( $post_data->parent );
				///Children setup
				if( is_array( $post_data->children ) ) $this->children = $post_data->children;
				if( is_array( $post_data->terms ) ) foreach( $post_data->terms as $taxonomy => $terms_data ){
					wp_cache_add( $this->post_ID, array_keys( $terms_data ), $taxonomy . '_relationships' );
					if( is_array( $terms_data ) ) foreach( $terms_data as $term_id => $term_data ){
						$new_term_data = (object)[
							'term_id' => $term_id,
							'term_taxonomy_id' => $term_id,
							'taxonomy' => $taxonomy,
							'parent' => intval( $term_data['parent'] ),
							'name' => $term_data['name'],
							'slug' => $term_data['slug'],
							'description' => (string)$term_data['description'],
							'count' => intval( $term_data['count'] )
						];
						$WP_Term = new WP_Term( $new_term_data );
						$this->terms_by_taxonomy[ $taxonomy ][ $WP_Term->name ] = $WP_Term;
						$this->terms_by_taxonomy[ $taxonomy ][ $WP_Term->slug ] = $WP_Term;
						$this->terms_by_taxonomy[ $taxonomy ][ $term_id ] = $WP_Term;
						if( array_key_exists( 'meta', $term_data ) && is_array( $term_data['meta'] ) ){
							$this->term_meta[ $term_id ] = $term_data['meta'];
						}
					}
				}
			}
		}


		/**
		 * @return bool
		 */
		public function is_exist(){
			return $this->WP_Post instanceof WP_Post;
		}


		/**
		 * Return current Post ID
		 * @return int
		 */
		public function get_ID(){
			return $this->post_ID;
		}


		/**
		 * Return current WP_Post
		 * @return WP_Post
		 */
		public function WP_Post(){
			if( !$this->is_exist() ) return get_post( 0 );
			return $this->WP_Post;
		}


		/**
		 * Return array of current post
		 * @return array
		 */
		public function get_meta_array(){
			return $this->post_meta;
		}


		/**
		 * Return post meta
		 * @param null|string|int $key - set NULL, then function return the array of all current post meta
		 * @param mixed $default
		 * @return array|mixed|null
		 */
		public function get_meta( $key = null, $default = null ){
			if(!is_string($key) && !is_int($key)) return $this->post_meta;
			return array_key_exists( $key, $this->post_meta ) ? $this->post_meta[ $key ] : $default;
		}


		/**
		 * Return the array of terms
		 * @param string $taxonomy
		 * @return array|WP_Term[]
		 */
		public function get_terms( $taxonomy = 'category' ){
			if( array_key_exists( $taxonomy, $this->terms_by_taxonomy ) && is_array( $this->terms_by_taxonomy[ $taxonomy ] ) ){
				return $this->terms_by_taxonomy[ $taxonomy ];
			}
			return [];
		}


		/**
		 * Return term meta by term_id
		 * @param int  $terms_id
		 * @param      $key
		 * @param null $default
		 * @return mixed|null
		 */
		public function get_term_meta( $terms_id, $key, $default = null ){
			if( !array_key_exists( $terms_id, $this->term_meta ) ) return $default;
			if( !array_key_exists( $key, $this->term_meta[ $terms_id ] ) ) return $default;
			return $this->term_meta[ $terms_id ][ $key ];
		}

	}