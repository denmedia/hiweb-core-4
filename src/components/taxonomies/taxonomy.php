<?php

	namespace hiweb\taxonomies;


	use hiweb\taxonomies\taxonomy\labels;


	class taxonomy{

		private $name;
		public $wp_taxonomy;
		/** @var array */
		private $args = [];
		/** @var labels */
		private $labels;
		public $object_type = [];


		/**
		 * taxonomy constructor.
		 * @param string $taxonomy
		 * @param string|array $object_type
		 */
		public function __construct( $taxonomy, $object_type ){
			$this->name = sanitize_file_name( strtolower( $taxonomy ) );
			$this->object_type = $object_type;
			$this->labels = new labels( $this );
		}


		/**
		 * @return array
		 */
		public function get_args(){
			return is_array( $this->args ) ? $this->args : [];
		}


		/**
		 * Set argument value
		 * @param string $arg_name
		 * @param mixed $value
		 * @return taxonomy|mixed|null
		 */
		public function set_arg( $arg_name, $value = null ){
			if( is_null( $value ) ){
				return array_key_exists( $arg_name, $this->args ) ? $this->args[ $arg_name ] : null;
			} else {
				$this->args[ $arg_name ] = $value;
				return $this;
			}
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
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
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function description( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function public_( $set = null ){
			return $this->set_arg( 'public', $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function publicly_queryable( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function hierarchical( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function show_ui( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function show_in_menu( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function show_in_nav_menus( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function show_tagcloud( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function show_in_quick_edit( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function show_admin_column( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function meta_box_cb( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function capabilities( $set = null ){
		//TODO!
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function rewrite( $set = null ){
		//TODO!
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function query_var( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function update_count_callback( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function show_in_rest( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function rest_base( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}


		/**
		 * @param null $set
		 * @return taxonomy|mixed|null
		 */
		public function rest_controller_class( $set = null ){
			return $this->set_arg( __FUNCTION__, $set );
		}

	}