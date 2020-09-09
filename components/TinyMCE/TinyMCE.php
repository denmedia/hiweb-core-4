<?php
	
	namespace hiweb\components\TinyMCE;
	
	
	use hiweb\components\Includes\IncludesFactory_AdminPage;
	
	
	class TinyMCE{
		
		private static $init = false;
		
		
		static function init(){
			if( !self::$init ){
				self::$init = true;
				add_filter('mce_external_plugins', function($plugins, $editor_id){
					$plugin_paths = ['anchor','table','code']; //[HIWEB_DIR_COMPONENTS . '/TinyMCE/plugins/anchor/plugin.min.js', HIWEB_DIR_COMPONENTS . '/TinyMCE/plugins/table/plugin.min.js', HIWEB_DIR_COMPONENTS . '/TinyMCE/plugins/code/plugin.min.js'];
					foreach($plugin_paths as $plugin_name){
						$plugins[$plugin_name] = get_url( HIWEB_DIR_COMPONENTS . "/TinyMCE/plugins/{$plugin_name}/plugin.min.js" )->get();
					}
					return $plugins;
				},10,2);
				add_filter( 'tiny_mce_plugins', function( $plugins, $editor_id ){
//					$plugins[] = 'anchor';
//					$plugins[] = 'table';
//					$plugins[] = 'code';
					return $plugins;
				}, 10, 2 );
				add_filter( 'mce_buttons', function( $mce_buttons, $editor_id ){
					$search_key = array_search('alignright', $mce_buttons);
					if($search_key !== false) {
						$mce_buttons = get_array($mce_buttons)->push_value( 'alignjustify', $search_key + 1 );
					} else {
						$mce_buttons[] = 'alignjustify';
					}
					return $mce_buttons;
				}, 10, 2 );
				add_filter( 'mce_buttons_2', function( $mce_buttons_2, $editor_id ){
					$mce_buttons_2[] = 'anchor';
					$mce_buttons_2[] = 'table';
					$mce_buttons_2 = array_unique( $mce_buttons_2 );
					return $mce_buttons_2;
				}, 10, 2 );
			}
		}
		
	}