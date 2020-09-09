<?php
	
	namespace hiweb\components\Preloader;
	
	
	use WP_Post;
	use WP_Term;
	
	
	class Preloader_Post{
		
		private $post_ID;
		/** @var WP_Post */
		private $WP_Post;
		private $post_meta = [];
		private $term_ids_by_taxonomy = [];
		private $terms = [];
		private $term_meta = [];
		
		//woocommerce
		private $woocommerce_type = 'simple';
		
		
		public function __construct( WP_Post $wp_post ){
			$this->post_ID = $wp_post->ID;
			$this->WP_Post = $wp_post;
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
		
		
		public function set_woocommerce_type( $type = 'simple' ){
			$this->woocommerce_type = $type;
		}
		
		
		/**
		 * @return string
		 */
		public function get_woocommerce_type(){
			return $this->woocommerce_type;
		}
		
		
		/**
		 * Return current WP_Post
		 * @return WP_Post
		 */
		//		public function wp_post(){
		//			if( !$this->is_exist() ) return get_post( 0 );
		//			return $this->WP_Post;
		//		}
		
		/**
		 * @return string
		 */
		//		public function get_permalink(){
		//			if( !$this->is_exist() ) return '';
		//			$R = get_permalink( $this->wp_post() );
		//			return is_string( $R ) ? $R : '';
		//		}
		
		/**
		 * Return array of current post
		 * @return array
		 */
		//		public function get_meta_array(){
		//			return $this->post_meta;
		//		}
		
		/**
		 * Return post meta
		 * @param null|string|int $key - set NULL, then function return the array of all current post meta
		 * @param mixed           $default
		 * @return array|mixed|null
		 */
		//		public function get_meta( $key = null, $default = null ){
		//			if( !is_string( $key ) && !is_int( $key ) ) return $this->post_meta;
		//			return array_key_exists( $key, $this->post_meta ) ? $this->post_meta[ $key ] : $default;
		//		}
		
		/**
		 * @return array|mixed|null
		 */
		//		public function get_thumbnail_id(){
		//			return $this->get_meta( '_thumbnail_id' );
		//		}
		
		/**
		 * Return the array of terms variations or unique
		 * @param string $taxonomy
		 * @param bool   $return_unique
		 * @return array|WP_Term[]
		 */
		//		public function get_terms( $taxonomy = 'category', $return_unique = false ){
		//			if( array_key_exists( $taxonomy, $this->terms_by_taxonomy ) && is_array( $this->terms_by_taxonomy[ $taxonomy ] ) ){
		//				if( $return_unique ){
		//					$R = [];
		//					/** @var WP_Term $term */
		//					foreach( $this->terms_by_taxonomy[ $taxonomy ] as $term ){
		//						$R[ $term->term_id ] = $term;
		//					}
		//					return $R;
		//				}
		//				else return $this->terms_by_taxonomy[ $taxonomy ];
		//			}
		//			return [];
		//		}
		
		/**
		 * Return term meta by term_id
		 * @param int  $terms_id
		 * @param      $key
		 * @param null $default
		 * @return mixed|null
		 */
		//		public function get_term_meta( $terms_id, $key, $default = null ){
		//			if( !array_key_exists( $terms_id, $this->term_meta ) ) return $default;
		//			if( !array_key_exists( $key, $this->term_meta[ $terms_id ] ) ) return $default;
		//			return $this->term_meta[ $terms_id ][ $key ];
		//		}
		
	}