<?php
	
	use hiweb\components\Context;
	use hiweb\core\Paths\PathsFactory;
	use theme\html_layout\tags\head;
	use theme\minify;


	///
	add_filter( 'template_include', function( $template = '/index.php' ){
		minify::set_template_path( $template );
		if( minify::$js_enable && minify::get_template( $template )->js()->is_exists() ){
			add_action( 'wp_footer', function(){ echo '<script defer src="' . minify::get_template()->js()->get_full_url() . '?ver=' . date( 'Y.m.d-H.i.s', filemtime( minify::get_template()->js()->get_full_path() ) ) . '"></script>'; } );
		}
		if( minify::$critical_css_enable && minify::get_template()->css()->is_critical_exists() ){
			head::add_code( '<!--Critical CSS--><style type="text/css">' . minify::get_template()->css()->get_critical_content() . '</style><!--Critical CSS End-->' );
		}
		if( minify::$css_enable || minify::$critical_css_enable ){
			ob_start();
			?>
			<script>
                var hiweb_theme_minify_template_id = "<?=minify::get_template()->cache()->get_id()?>";
					<?php if(minify::$css_enable && minify::get_template()->css()->is_exists() ){
					?>var hiweb_theme_full_css_url = "<?=minify::get_template()->css()->get_full_url()?>";<?php
				} ?>

			</script>
			<?php
			head::add_code( ob_get_clean() );
			if( !minify::$critical_css_enable || !minify::get_template()->css()->is_critical_exists() && minify::get_template()->css()->is_exists() ){
				head::add_code( '<link rel="stylesheet" type="text/css" href="' . minify::get_template()->css()->get_full_url() . '?ver=' . date( 'Y.m.d-H.i.s', filemtime( minify::get_template()->css()->get_full_path() ) ) . '" />' );
			}
		}
		return $template;
	}, 9999999 );

	add_action( 'shutdown', function(){
		if( context::is_frontend_page() ){
			if( minify::$js_enable ){
				if( function_exists( 'wp_scripts' ) && wp_scripts() instanceof WP_Scripts && is_array( wp_scripts()->done ) ){
					$B = minify::get_template()->js()->try_generate_full_file_from_wp_scripts( wp_scripts() );
					if( minify::$debug ){
						if( $B == - 1 ){
							console_info( 'обновление не требуется Full JS', '\theme\_minify\cache' );
						} elseif( $B === true ) {
							console_info( 'удачное обновление Full JS', '\theme\_minify\cache' );
						} elseif( $B === false ) {
							console_error( 'не удалось обновить Full JS', '\theme\_minify\cache' );
						}
					}
					//PAGES CACHE
					if( $B === true && class_exists( '\theme\pages_cache' ) ){
						\theme\pages_cache::get_current_page()->get_cache()->do_flush();
					}
				}
			}
			if( minify::$css_enable ){
				if( function_exists( 'wp_styles' ) && wp_styles() instanceof WP_Styles && is_array( wp_styles()->done ) ){
					$B = minify::get_template()->css()->try_generate_full_file_from_wp_styles( wp_styles() );
					if( minify::$debug ){
						if( $B == - 1 ){
							console_info( 'обновление не требуется Full CSS', '\theme\_minify\cache' );
						} elseif( $B === true ) {
							console_info( 'удачное обновление Full CSS', '\theme\_minify\cache' );
						} elseif( $B === false ) {
							console_error( 'не удалось обновить Full CSS', '\theme\_minify\cache' );
						}
					}
					//PAGES CACHE
					if( $B === true && class_exists( '\theme\pages_cache' ) ){
						\theme\pages_cache::get_current_page()->get_cache()->do_flush();
					}
				}
			}
		}
	}, 999 );

	add_action( 'wp_ajax_hiweb_theme_critical_css_generate', function(){
		if( !minify::$critical_css_enable ){
			wp_send_json_error( [ 'message' => 'Генератор Critical CSS отключен установками' ] );
		}
		if( !isset( $_POST['id'] ) ){
			wp_send_json_error( [ 'message' => 'Не передан ID шаблона' ] );
		}
		if( strlen( $_POST['chtml'] ) < 10 ){
			wp_send_json_error( [ 'message' => 'chtml слишком мал!' ] );
		}
		$template_path = minify::get_template_path_by_id( $_POST['id'] );
		if( empty( $template_path ) ){
			wp_send_json_error( [ 'message' => 'Не верно передан ID шаблона' ] );
		}
		$template = minify::get_template( $template_path );
		///
		$B = $template->critical_html()->try_generate( stripslashes( $_POST['chtml'] ) );
		if( $B == true ){
			$B = $template->critical_css()->try_generate();
			if( $B === true ){
				wp_send_json_success( [ 'message' => 'cCss создан' ] );
			} elseif( $B === false ) {
				wp_send_json_error( [ 'message' => 'Не удалось задать cCss' ] );
			} elseif( $B === - 1 ) {
				wp_send_json_error( [ 'message' => 'Полный CSS не существует' ] );
			} elseif( $B === - 2 ) {
				wp_send_json_error( [ 'message' => 'cHtml не существует' ] );
			} elseif( $B === - 3 ) {
				wp_send_json_error( [ 'message' => 'Нет необходимости обнолять cCss' ] );
			}
		} elseif( $B == - 1 ) {
			wp_send_json_error( [ 'message' => 'Нет необходимости обнолять cHtml' ] );
		} else {
			wp_send_json_error( [ 'message' => 'Не удалось задать cHtml' ] );
		}
	} );

	add_action( 'wp_ajax_nopriv_hiweb_theme_critical_css_generate', function(){
		do_action( 'wp_ajax_hiweb_theme_critical_css_generate' );
	} );

	add_filter( 'script_loader_tag', function( $tag, $handle, $src ){
		if( context::is_frontend_page() && minify::$js_enable && minify::get_template()->js()->is_exists() && PathsFactory::get( $src )->is_local() ){
			return '';
		}
		return $tag;
	}, 99, 3 );

	add_filter( 'style_loader_tag', function( $tag, $handle, $src ){
		if( context::is_frontend_page() && minify::$css_enable && minify::get_template()->css()->is_exists() && PathsFactory::get( $src )->is_local() ){
			return '';
		}
		return $tag;
	}, 99, 3 );

