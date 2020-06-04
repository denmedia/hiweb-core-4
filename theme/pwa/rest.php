<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 07/12/2018
	 * Time: 11:13
	 */

	//add rest manifest
	
	use hiweb\core\Paths\PathsFactory;
	
	
	add_action( 'rest_api_init', function(){

		register_rest_route( 'hiweb-theme', 'pwa/manifest', [
			'methods' => 'get',
			'callback' => function(){
				///
				$icon = get_image( get_field( 'icon', self::$admin_menu_slug ) );
				$icon_splash = get_image( get_field( 'icon-splash', self::$admin_menu_slug ) );
				$name = get_field( 'name', self::$admin_menu_slug );
				$short_name = get_field( 'short_name', self::$admin_menu_slug );
				$description = get_field( 'description', self::$admin_menu_slug );
				$display = get_field( 'display', self::$admin_menu_slug );
				$orientation = get_field( 'orientation', self::$admin_menu_slug );
				$theme_color = get_field( 'theme_color', self::$admin_menu_slug );
				$background_color = get_field( 'background_color', self::$admin_menu_slug );

				///CONSTRUCT
				$manifest = \hiweb\core\ArrayObject\ArrayObject::get_instance();

				$manifest->push( 'name', $name == '' ? get_bloginfo( 'name' ) : $name );
				$manifest->push( 'short_name', $short_name == '' ? get_bloginfo( 'name' ) : $short_name );
				$manifest->push( 'description', $description == '' ? get_bloginfo( 'description' ) : $description );

				$manifest->push( 'display', $display == '' ? 'standalone' : $display );
				$manifest->push( 'orientation', $orientation == '' ? 'portrait' : $orientation );

				if( $theme_color != '' )
					$manifest->push( 'theme_color', $theme_color );
				if( $background_color != '' )
					$manifest->push( 'background_color', $background_color );

				$manifest->push( 'scope', PathsFactory::root(  )->get_path_relative() . '/' );
				$manifest->push( 'start_url', '/?pwa=1' );
				//icons
				$icons = [];
				if( !$icon->is_attachment_exists() ){
					$icon = $icon_splash;
				}
				if( $icon->is_attachment_exists() ){
					foreach( [ 192, 512 ] as $width ){
						if( $width > $icon->width() || $width > $icon->height() )
							continue;
						$icon_src = $icon->sizes()->get( [ $width, $width, 0], true );
						$icons[] = [
							'src' => $icon_src->path()->get_url(),
							'sizes' => $width . 'x' . $width,
							'type' => $icon_src->image()->get_mime_type()
						];
					}
					if( $icon_splash->is_attachment_exists() ){
						if( $icon_splash->width() >= 512 && $icon_splash->height() >= 512 ){
							$icon_splash_size = $icon_splash->sizes()->get( [ 512, 512, 0], true );
							$icons[] = [
								'src' => $icon_splash_size->path()->get_path_relative(),
								'sizes' => $icon_splash_size->width() . 'x' . $icon_splash_size->height(),
								'type' => $icon_splash_size->path()->image()->get_mime_type()
							];
						}
					}
				}
				if( count( $icons ) > 0 ){
					$manifest->push( 'icons', $icons );
				}
				return $manifest->get();
			}
		] );
	} );