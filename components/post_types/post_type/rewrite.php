<?php
	/**
	 * Created by PhpStorm.
	 * User: d9251
	 * Date: 20.10.2017
	 * Time: 16:25
	 */

	namespace hiweb\post_types\post_type;


	use hiweb\post_types\post_type;


	class rewrite{
		/** @var post_type */
		private $post_type;


		public function __construct( post_type $post_type ){
			$this->post_type = $post_type;
		}


		/**
		 * @param $name
		 * @param string $value
		 * @return rewrite
		 */
		private function set( $name, $value = '' ){
			$this->post_type->args_custom['rewrite'][ $name ] = $value;
			return $this;
		}


		/**
		 * @param array $set
		 * @return rewrite
		 */
		public function slug( $set = [] ){
			return $this->set( 'slug', $set );
		}


		/**
		 * @param bool $set
		 * @return rewrite
		 */
		public function with_front( $set = true ){
			return $this->set( 'with_front', $set );
		}


		/**
		 * @param $set
		 * @return rewrite
		 */
		public function feeds( $set ){
			return $this->set( 'feeds', $set );
		}


		/**
		 * @param bool $set
		 * @return rewrite
		 */
		public function pages( $set = true ){
			return $this->set( 'pages', $set );
		}


		/**
		 * @param $set
		 * @return rewrite
		 */
		public function ep_mask( $set ){
			return $this->set( 'ep_mask', $set );
		}

	}