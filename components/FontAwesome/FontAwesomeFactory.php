<?php

	namespace hiweb\components\FontAwesome;


	use hiweb\core\Cache\CacheFactory;


	class FontAwesomeFactory{

		static $vendor_path = HIWEB_DIR_VENDOR . '/font-awesome-5';


		/**
		 * @param string $icon_class
		 * @return FontAwesome_Icon
		 */
		static function get( $icon_class = 'fab fa-wordpress' ){
			$icon_name = fontawesome_filter_icon_name( (string)$icon_class );
			return CacheFactory::get( $icon_class, __CLASS__, function(){
				return new FontAwesome_Icon( func_get_arg( 0 ) );
			}, [ $icon_class ] )();
		}


		/**
		 * @return array
		 */
		static function get_icons_data(){
			return CacheFactory::get( 'icons_data', __CLASS__, function(){
				$icons_json_path = FontAwesomeFactory::$vendor_path . '/metadata/icons.json';
				if( file_exists( $icons_json_path ) && is_file( $icons_json_path ) && is_readable( $icons_json_path ) ){
					$json_data = json_decode( file_get_contents( $icons_json_path ), true );
					if( json_last_error() == JSON_ERROR_NONE ){
						return $json_data;
					}
				}
				return [];
			} )();
		}


		/**
		 * @param string $search
		 * @return FontAwesome_Icon[]
		 */
		static function get_search_icons( $search = 'wordpress' ){
			$ids = CacheFactory::get( $search, __METHOD__, function(){
				$R = [];
				$search = strtolower( func_get_arg( 0 ) );
				foreach( FontAwesomeFactory::get_icons_data() as $id => $icon_data ){
					if( strpos( $id, $search ) !== false ){
						$R[] = $id;
						continue;
					}
					if( strpos( $icon_data['label'], $search ) !== false ){
						$R[] = $id;
						continue;
					}
					///
					if( array_key_exists( 'search', $icon_data ) && array_key_exists( 'terms', $icon_data['search'] ) ){
						if( is_array( $icon_data['search']['terms'] ) ){
							foreach( $icon_data['search']['terms'] as $term ){
								if( strpos( $term, $search ) !== false ){
									$R[] = $id;
									continue 2;
								}
							}
						}
					}
				}
				return $R;
			}, $search )->Cache_File()->enable()->Cache()->get_value();
			///
			return CacheFactory::get( $search, __METHOD__ . '::$search_result', function(){
				$R = [];
				foreach( func_get_arg( 0 ) as $id ){
					$R[ $id ] = new FontAwesome_Icon( $id );
				}
				return $R;
			}, [ $ids ] )();
		}

	}