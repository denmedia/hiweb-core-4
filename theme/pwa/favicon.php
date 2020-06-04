<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 13:11
	 */
	
	namespace theme\pwa;
	
	
	use hiweb\components\Images\ImagesFactory;
	use theme\html_layout\tags\head;
	
	
	class favicon{
		
		static $enable = true;
		static $apple_rel = 'apple-touch-icon-precomposed'; //'apple-touch-icon';
		
		
		static function init(){
			$favicon_attach_id = get_field( 'icon', \theme\pwa::$admin_menu_slug );
			if( is_numeric( $favicon_attach_id ) ){
				head::add_html_addition( self::get() );
			}
		}
		
		
		/**
		 * @return string
		 */
		static function get(){
			$R = '';
			$favicon_attach_id = get_field( 'icon', \theme\pwa::$admin_menu_slug );
			if( is_numeric( $favicon_attach_id ) ){
				$favicon_image = ImagesFactory::get( $favicon_attach_id );
				if( $favicon_image->is_attachment_exists() ){
					$R .= "<link rel='icon' type='image/png' href='{$favicon_image->get_src( [ 16, 16, 0 ], true )}' />";
					foreach( [ 57, 72, 114, 256, 512 ] as $dimension ){
						if( $favicon_image->width() > $dimension ){
							$R .= "<link rel='" . self::$apple_rel . "' type='image/png' sizes='{$dimension}x{$dimension}' href='{$favicon_image->get_src( [ $dimension, $dimension, 0 ],  true )}'/>";
						}
					}
				}
			}
			return $R;
		}
		
		
		/**
		 * Print favicon html tag for head
		 */
		static function the(){
			echo self::get();
		}
		
	}