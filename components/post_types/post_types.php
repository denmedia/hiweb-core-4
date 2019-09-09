<?php

	namespace hiweb;


	use hiweb\components\console\Console;
	use hiweb\core\cache\Cache;
	use hiweb\post_types\post_type;
	use SimpleXMLElement;


	class post_types{

		/** @var array */
		static $post_types = [];


		/**
		 * Возвращает корневой CPT класс для работы с кастомным типом поста
		 * @param $post_type
		 * @return post_types\post_type
		 */
		static function register( $post_type ){
			$post_type_sanitized = sanitize_file_name( strtolower( $post_type ) );
			if( !array_key_exists( $post_type_sanitized, self::$post_types ) ){
				self::$post_types[ $post_type_sanitized ] = new post_types\post_type( $post_type );
			}
			return self::$post_types[ $post_type_sanitized ];
		}


		static function do_register_post_types(){
			/**
			 * @var string    $post_type_name
			 * @var post_type $post_type
			 */
			foreach( self::$post_types as $post_type_name => $post_type ){
				if( !$post_type instanceof post_type ){
					console::debug_error( 'В массиве типов постов попался посторонний объект', [ $post_type_name, $post_type ] );
					continue;
				}
				if( post_type_exists( $post_type_name ) ){
					$wp_post_type = get_post_type_object( $post_type_name );
					foreach( $post_type->get_args_custom() as $key => $value ){
						if( property_exists( $wp_post_type, $key ) ){
							if( is_array( $wp_post_type->{$key} ) ){
								$wp_post_type->{$key} = array_merge( $wp_post_type->{$key}, is_array( $value ) ? $value : [ $value ] );
							} else $wp_post_type->{$key} = $value;
						}
					}
					$post_type->wp_post_type = $wp_post_type;
				} else {
					$wp_post_type = register_post_type( $post_type_name, $post_type->get_args_custom() );
					if( $wp_post_type instanceof \WP_Post_Type ){
						$post_type->wp_post_type = $wp_post_type;
					} else {
						console::debug_error( 'Во время регистрации типа поста произошла ошибка', $wp_post_type );
					}
				}
			}
		}


		/**
		 * Check menu_icon for fal|fas|fab|far and return path to svg file form admin menu icon
		 * @param $menu_icon
		 * @return mixed
		 */
		static function filter_fontawesome_menu_icon( $menu_icon ){
			if( preg_match( '/^fa(?>b|l|s|r) fa[\-\w\d]+$/i', $menu_icon ) > 0 ){
				$cache_key = 'hiweb-core-post-type-menu-icon-fontawesome-' . $menu_icon;
				if( Cache::is_exists( $cache_key ) ) return cache::get( $cache_key );
				$sprites_path = HIWEB_DIR_VENDORS . '/font-awesome-5/sprites/';
				switch( $menu_icon[2] ){
					case 'b':
						$sprites_path .= 'brands.svg';
						break;
					case 'l':
						$sprites_path .= 'light.svg';
						break;
					case 'r':
						$sprites_path .= 'regular.svg';
						break;
					case 's':
						$sprites_path .= 'solid.svg';
						break;
					default:
						console::debug_warn( 'Попытка найти файл спрайтов FontAwesome для post_type->menu_icon [' . $menu_icon . '] не удалась.' );
						return $menu_icon;
						break;
				}
				if( !file_exists( $sprites_path ) || !is_readable( $sprites_path ) ){
					console::debug_warn( 'Файл спрайтов FontAwesome [' . $sprites_path . '] не найден или не существует.' );
					return $menu_icon;
				}
				$svgs_xml = simplexml_load_file( $sprites_path );
				$icon_id = substr( $menu_icon, 7 );
				foreach( $svgs_xml->symbol as $symbol ){
					if( (string)$symbol->attributes()->id == $icon_id ){
						$svg_path = $symbol;
						$menu_icon = 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="' . $symbol->attributes()->viewBox . '" style="fill: none;" height="24px" width="24px">' . $symbol->path->asXML() . '</svg>' );
						break;
					}
				}
				Cache::set( $cache_key, $menu_icon );
				return $menu_icon;
			} else {
				return $menu_icon;
			}
		}

	}
