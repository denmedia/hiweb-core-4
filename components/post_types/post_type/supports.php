<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 20.10.2017
	 * Time: 16:08
	 */

	namespace hiweb\post_types\post_type;


	use hiweb\post_types\post_type;


	class supports{

		private $post_type;


		public function __construct( post_type $post_type ){
			$this->post_type = $post_type;
		}


		/**
		 * @param string $support_name
		 * @return supports
		 */
		private function set( $support_name = 'title' ){
			$this->post_type->args_custom['supports'][] = $support_name;
			$this->post_type->args_custom['supports'] = array_unique( $this->post_type->args_custom['supports'] );
			return $this;
		}


		/**
		 * @return supports
		 */
		public function title(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function editor(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function author(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function thumbnail(){
			add_theme_support( 'post-thumbnails' );
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function excerpt(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function trackback(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function custom_fields(){
			return $this->set( 'custom-fields' );
		}

		/**
		 * @return supports
		 */
		public function comments(){
			return $this->set( 'comments' );
		}


		/**
		 * @return supports
		 */
		public function revisions(){
			return $this->set( __FUNCTION__ );
		}


		/**
		 * @return supports
		 */
		public function page_attributes(){
			return $this->set( 'page-attributes' );
		}


		/**
		 * @return supports
		 */
		public function post_formats(){
			return $this->set( 'post-formats' );
		}

	}