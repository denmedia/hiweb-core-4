<?php

	namespace hiweb;


	use hiweb\components\console\Console;
	use hiweb\taxonomies\taxonomy;


	class taxonomies{

		/** @var taxonomy[] */
		static private $taxonomies = [];


		/**
		 * @param               $taxonomy_name
		 * @param string|array $object_type - post type / post types
		 * @return taxonomy
		 */
		static function register( $taxonomy_name, $object_type ){
			$taxonomy_name_sanitized = sanitize_file_name( strtolower( $taxonomy_name ) );
			if( !array_key_exists( $taxonomy_name_sanitized, self::$taxonomies ) ){
				self::$taxonomies[ $taxonomy_name_sanitized ] = new taxonomy( $taxonomy_name_sanitized, $object_type );
			}
			return self::$taxonomies[ $taxonomy_name_sanitized ];
		}


		static function do_register_taxonomies(){
			/**
			 * @var string  $taxonomy_name
			 * @var taxonomy $taxonomy
			 */
			foreach( self::$taxonomies as $taxonomy_name => $taxonomy ){
				if( !$taxonomy instanceof taxonomy ){
					console::debug_error( 'В массиве типов постов попался посторонний объект', [ $taxonomy_name, $taxonomy ] );
					continue;
				}
				if( taxonomy_exists( $taxonomy_name ) ){
					$wp_taxonomy = get_taxonomy( $taxonomy_name );
					foreach( $taxonomy->get_args() as $key => $value ){
						if( property_exists( $wp_taxonomy, $key ) ){
							if( $wp_taxonomy->{$key} instanceof \stdClass ){
								if( !is_array( $value ) ) $value = [ $value ];
								foreach( $value as $subkey => $subval ){
									$wp_taxonomy->{$key}->{$subkey} = $subval;
								}
							} elseif( is_array( $wp_taxonomy->{$key} ) ) {
								$wp_taxonomy->{$key} = array_merge( $wp_taxonomy->{$key}, is_array( $value ) ? $value : [ $value ] );
							} else $wp_taxonomy->{$key} = $value;
						}
					}
					$taxonomy->wp_taxonomy = $wp_taxonomy;
				} else {
					$result = register_taxonomy( $taxonomy_name, $taxonomy->object_type, $taxonomy->get_args() );
					if( !$result instanceof \WP_Error ){
						$taxonomy->wp_taxonomy = get_taxonomy( $taxonomy_name );
					} else {
						console::debug_error( 'Во время регистрации типа поста произошла ошибка', $taxonomy_name );
					}
				}
			}
		}

	}