<?php

	namespace hiweb\post_types;


	use hiweb\post_types;
	use hiweb\post_types\post_type\labels;
	use hiweb\post_types\post_type\rewrite;
	use hiweb\post_types\post_type\supports;


	class post_type{

		private $_type;
		/** @var \WP_Error|\WP_Post_Type */
		public $wp_post_type;
		public $args = [
			'label' => null,
			'labels' => [],
			'description' => '',
			'public' => true,
			'hierarchical' => false,
			'exclude_from_search' => null,
			'publicly_queryable' => null,
			'show_ui' => true,
			'show_in_menu' => null,
			'show_in_nav_menus' => null,
			'show_in_admin_bar' => null,
			'menu_position' => null,
			'menu_icon' => 'dashicons-sticky',
			'capability_type' => 'post',
			'capabilities' => [],
			'map_meta_cap' => null,
			'supports' => [],
			'register_meta_box_cb' => null,
			'taxonomies' => [],
			'has_archive' => false,
			'rewrite' => true,
			'query_var' => true,
			'can_export' => true,
			'delete_with_user' => null,
			'_builtin' => false,
			'_edit_link' => 'post.php?post=%d',
		];
		public $args_custom = [

		];

		private $labels;
		private $rewrite;
		private $supports;


		/**
		 * post_type constructor.
		 * @param string $post_type
		 */
		public function __construct( $post_type ){
			$this->_type = sanitize_file_name( strtolower( $post_type ) );
			$this->labels = new labels( $this );
			$this->rewrite = new rewrite( $this );
			$this->supports = new supports( $this );
		}


		/**
		 * @return array
		 */
		public function get_args_custom(){
			return is_array( $this->args_custom ) ? $this->args_custom : [];
		}


		/**
		 * Set argument value
		 * @param string $arg_name
		 * @param mixed $value
		 * @return post_type|mixed|null
		 */
		public function set_arg( $arg_name, $value = null ){
			if( is_null( $value ) ){
				return array_key_exists( $arg_name, $this->args ) ? $this->args[ $arg_name ] : null;
			} else {
				$this->args[ $arg_name ] = $value;
				$this->args_custom[ $arg_name ] = $value;
				return $this;
			}
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function description( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function public_( $set = null ){
			return $this->set_arg( 'public', $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function hierarchical( $set = null ){
			if($set === true) {
				$this->supports()->page_attributes();
			}
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function exclude_from_search( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return $this
		 */
		public function publicly_queryable( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_ui( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_in_menu( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_in_nav_menus( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function show_in_admin_bar( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function menu_position( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function menu_icon( $set = null ){
			if(is_string($set)) $set = post_types::filter_fontawesome_menu_icon($set);
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function capability_type( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function map_meta_cap( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @return supports
		 */
		public function supports(){
			return $this->supports;
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function register_meta_box_cb( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function taxonomies( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function has_archive( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @return rewrite
		 */
		public function rewrite(){
			return $this->rewrite;
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function query_var( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function can_export( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function _edit_link( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function _builtin( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function delete_with_user( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}

		///////


		/**
		 * @param null $set
		 * @return post_type|mixed|null
		 */
		public function label( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @return labels
		 */
		public function labels(){
			return $this->labels;
		}


		/**
		 * @return string
		 */
		public function type(){
			return $this->_type;
		}


		/**
		 * Возвращает массив установок
		 * @return array
		 */
		public function props(){
			return $this->args;
		}


		/**
		 * @return \WP_Error|\WP_Post_Type
		 */
		public function wp_post_type(){
			return $this->wp_post_type;
		}


		/**
		 * @param $callback ($columns)
		 * @return $this
		 */
		public function manage_posts_columns( $callback ){
			add_filter( 'manage_' . $this->_type . '_posts_columns', $callback, 10, 1 );
			return $this;
		}


		/**
		 * @param $callback ($column, $post_id)
		 * @return post_type
		 */
		public function manage_posts_custom_column($callback){
			add_action( 'manage_'.$this->_type.'_posts_custom_column' , $callback, 10, 2 );
			return $this;
		}


	}
