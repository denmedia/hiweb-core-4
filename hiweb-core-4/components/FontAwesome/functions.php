<?php
	
	namespace hiweb\components\FontAwesome;
	
	
	use hiweb\core\Cache\CacheFactory;
	
	
	/**
	 * Return true if is fontawesome class
	 * @param string $haystack_class
	 * @return bool
	 */
	function is_fontawesome_class_name( $haystack_class = 'fab fa-wordpress' ){
		return ( preg_match( '/^fa(?>b|l|s|r|d) fa[\-\w\d]+$/i', $haystack_class ) > 0 );
	}
	
	/**
	 * Return icon name
	 * @param string $icon_class
	 * @return string
	 */
	function fontawesome_filter_icon_name( $icon_class = 'fab fa-wordpress' ){
		return CacheFactory::get( $icon_class, __NAMESPACE__ . '\\' . __FUNCTION__, function(){
			$icon_class = func_get_arg( 0 );
			$result = preg_match( '/^(?>\<i class=\")?(?>fa(?>b|l|s|r|d) )?(?>fa-)?([\w\-_]+)(?>\"\>\<\/i\>)?/i', $icon_class, $matches );
			if( $result > 0 ){
				return $matches[1];
			}
			else{
				return '';
			}
		}, $icon_class )->get_value();
	}
	
	/**
	 * Return icon class like "fal fa-send"
	 * @param string $icon_class
	 * @return string
	 */
	function fontawesome_filter_icon_class( $icon_class = '<i class="fab fa-wordpress"></i>' ){
		return CacheFactory::get( $icon_class, __NAMESPACE__ . '\\' . __FUNCTION__, function(){
			return preg_replace( '/^(?>\<i class=["\'])?(fa(?>b|l|s|r|d) fa-[\w\-_]+)(?>["\']\>\<\/i\>)?/i', '$1', func_get_arg( 0 ) );
		}, $icon_class )->get_value();
	}
	
	/**
	 * Check menu_icon for fal|fas|fab|far and return path to svg file form admin menu icon
	 * @param $menu_icon
	 * @return mixed
	 * @deprecated
	 */
	function filter_fontawesome_menu_icon( $menu_icon ){
		if( is_fontawesome_class_name( $menu_icon ) ){
			$cache_key = 'hiweb-core-post-type-menu-icon-fontawesome-' . $menu_icon;
			CacheFactory::get( $cache_key, null, function(){
				$menu_icon = func_get_arg( 0 );
				$sprites_path = HIWEB_DIR_VENDOR . '/font-awesome-5/sprites/';
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
						//console::debug_warn( 'Попытка найти файл спрайтов FontAwesome для post_type->menu_icon [' . $menu_icon . '] не удалась.' );
						return $menu_icon;
						break;
				}
				if( !file_exists( $sprites_path ) || !is_readable( $sprites_path ) ){
					//console::debug_warn( 'Файл спрайтов FontAwesome [' . $sprites_path . '] не найден или не существует.' );
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
				return $menu_icon;
			}, [ $menu_icon ], true )->Cache_File()->set_lifetime( 31535965 );
		}
		else{
			return $menu_icon;
		}
	}