<?php
	
	namespace components\Taxonomy_Main_Select;
	
	
	use WP_Taxonomy;
	
	
	class Taxonomy_Main_Select{
		static private $init = false;
		static private $taxonomies = [];
		static $meta_key = 'hiweb-tools-taxonomy_main_select';
		
		
		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}
		
		
		static function init(){
			if( !self::$init ){
				self::$init = true;
				add_action( 'current_screen', function(){
					if( function_exists( 'get_current_screen' ) && is_object( get_current_screen() ) ){
						global $wp_taxonomies;
						if( get_current_screen()->base == 'post' ** isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
							///
							/**
							 * @var string      $taxonomy_name
							 * @var WP_Taxonomy $wp_taxonomy
							 */
							foreach( $wp_taxonomies as $taxonomy_name => $wp_taxonomy ){
								if( is_array( $wp_taxonomy->object_type ) ) foreach( $wp_taxonomy->object_type as $post_type_name ){
									if( get_current_screen()->post_type == $post_type_name ){
										include_admin_js( __DIR__ . '/tools-taxonomy_main_select.js', [ 'jquery-core' ] );
										include_admin_css( __DIR__ . '/tools-taxonomy_main_select.css' );
										static $header_taxonomies_init = false;
										if( !$header_taxonomies_init ){
											$header_taxonomies_init = true;
											add_action( 'in_admin_header', function(){
												?>
												<script>var hiweb_tools_taxonomy_main_select = <?= json_encode( self::$taxonomies ) ?>;</script>
												<?php
											} );
										}
									}
								}
							}
							///
						}
					}
				} );
				add_action( 'wp_ajax_hiweb-tools-taxonomy_main_select-get', function(){
					if( !isset( $_POST['post_id'] ) || !isset( $_POST['taxonomy'] ) ){
						return wp_send_json_error( 'не переданы taxonomy или post_id' );
					}
					preg_match_all( '/^list:(?<taxonomy>[\w\d-_]+)$/mi', $_POST['taxonomy'], $matches );
					if( !isset( $matches['taxonomy'][0] ) || $matches['taxonomy'][0] == '' ){
						return wp_send_json_error( 'не корректно переданы list:taxonomy' );
					}
					$wp_post = get_post( $_POST['post_id'] );
					if( !$wp_post instanceof \WP_Post ){
						return wp_send_json_error( 'не корректно переданы post_id' );
					}
					$post_meta = get_post_meta( $wp_post->ID, self::$meta_key . '-' . $matches['taxonomy'][0], true );
					if( !is_numeric( $post_meta ) || $post_meta == '' || is_null( $post_meta ) ){
						return wp_send_json_success( '' );
					}
					return wp_send_json_success( $matches['taxonomy'][0] . '-' . $post_meta );
				} );
				add_action( 'wp_ajax_hiweb-tools-taxonomy_main_select-set', function(){
					if( !isset( $_POST['term_id'] ) || !isset( $_POST['post_id'] ) ){
						return wp_send_json_error( 'не переданы term_id или post_id' );
					}
					$post_id = intval( $_POST['post_id'] );
					if( $post_id < 1 ) return wp_send_json_error( 'не верно передан post_id' );
					preg_match_all( '/^(?<taxonomy>[\w\d-_]+)-(?<term_id>[\d]+)$/im', $_POST['term_id'], $matches );
					if( !isset( $matches['taxonomy'][0] ) || $matches['taxonomy'][0] == '' || !isset( $matches['term_id'][0] ) || $matches['term_id'][0] == '' ){
						wp_send_json_error( 'не передан taxonomy-term_id' );
					}
					update_post_meta( $post_id, self::$meta_key . '-' . $matches['taxonomy'][0], $matches['term_id'][0] );
					wp_send_json_success( 'сохранено' );
					die;
				} );
			}
		}
		
		
		static function add( $taxonomy = 'category' ){
			self::init();
			self::$taxonomies[] = $taxonomy;
		}
	}