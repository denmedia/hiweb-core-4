<?php

	namespace hiweb\components\PostType;


	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Strings;


	class PostTypeFactory{

		use hidden_methods;


		/**
		 * @param string $post_type_name
		 * @return PostType
		 */
		static function get( $post_type_name = 'post' ){
			return CacheFactory::get( $post_type_name, __CLASS__ . '::$post_types', function(){
				global $wp_post_types;
				$post_type_name = func_get_arg( 0 );
				$PostType = new PostType( $post_type_name );
				if( array_key_exists( $post_type_name, $wp_post_types ) ){
					$PostType->_set_optionsCollect( $wp_post_types[ $post_type_name ] );
				}
				return $PostType;
			}, $post_type_name )();
		}


		////action register post types
		static protected function _register_post_types(){
			foreach( CacheFactory::get_group( __CLASS__ . '::$post_types', true ) as $post_type_name => $PostType ){
				if( !$PostType instanceof PostType ) continue;
				register_post_type( $PostType->get_post_type_name(), $PostType->_get_optionsCollect() );
			}
		}

	}