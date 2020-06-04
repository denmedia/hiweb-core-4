<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 21/11/2018
	 * Time: 19:43
	 */

	namespace theme\migration\includes;


	use theme\includes\admin;
	use theme\migration;


	class hooks{

		static function init(){
			add_action( 'init', 'theme\\migration\\includes\\hooks::load_plugin_textdomain' );
			add_filter( 'plugin_action_links', 'theme\\migration\\includes\\hooks::plugin_action_links', 10, 4 );
			add_action( 'admin_menu', 'theme\\migration\\includes\\hooks::add_submenu_page' );
			add_action( 'wp_ajax_hiweb_migration_simple', 'theme\\migration\\includes\\hooks::ajax_do_migrate' );
			add_action( 'wp_ajax_nopriv_hiweb_migration_simple', 'theme\\migration\\includes\\hooks::ajax_do_migrate' );
		}


		static function ajax_do_migrate(){
			if( migration::is_trigger() ){
				$R = migration::do_site_migrate();
				wp_send_json_success(['queries' => $R,'old_dir' => tools::get_old_base_dir(), 'current_dir' => tools::get_base_dir()]);
			} else {
				wp_send_json_error('The site does not need to be migrated.');
			}
		}


		static function load_plugin_textdomain(){
			load_plugin_textdomain( 'hw-migration-simple', false, HW_MIGRATION_SIMPLE_DIR . '/languages' );
		}


		/**
		 * @return false|string
		 */
		static function add_submenu_page(){
			admin::fontawesome();
			return add_submenu_page( HW_MIGRATION_SIMPLE_AM_SHOWINMENU, '<i class="fas fa-walking"></i> hiWeb Migration Simple', '<i class="fas fa-walking"></i> Migration Simple', 'manage_options', HW_MIGRATION_SIMPLE_AM_SLUG, '\theme\migration\includes\hooks::add_submenu_page_echo' );
		}


		static function add_submenu_page_echo(){
			if( isset( $_POST['new_domain'] ) ){
				$old_domain = $_POST['old_domain'];
				$new_domain = $_POST['new_domain'];
				if( isset( $_POST['confirm'] ) ){
					if( trim( $new_domain ) == '' ){
						exit( 'Domain is not set...' );
					} else {
						$R = migration::do_site_migrate( $new_domain, $old_domain );
						if( is_array( $R ) ){
							include HW_MIGRATION_SIMPLE_DIR . '/template/force-re-migrate-done.php';
						} else {
							include HW_MIGRATION_SIMPLE_DIR . '/template/force-re-migrate-error.php';
						}
					}
				} else {
					if( trim( $new_domain ) == '' ){
						$new_domain = tools::get_base_url();
						$_POST['new_domain'] = $new_domain;
					}
					if( trim( $old_domain ) == '' ){
						$old_domain = tools::get_wp_old_base_url();
						$_POST['old_domain'] = $old_domain;
					}
					include HW_MIGRATION_SIMPLE_DIR . '/template/force-re-migrate-confirm.php';
				}
			} else {
				include HW_MIGRATION_SIMPLE_DIR . '/template/options.php';
			}
		}


		static function plugin_action_links( $actions = [], $plugin_file = ''/*, $plugin_data = [], $context = null*/ ){
			if( $plugin_file == 'hiweb-migration-simple/hiweb-migration-simple.php' ){
				$actions['tool_panel'] = '<a href="' . self_admin_url( 'tools.php?page=hw_migration_simple' ) . '" class="tool_panel" aria-label="Открыть панель hiWeb Migration Simple">' . __( 'Migration Panel', 'hw-migration-simple' ) . '</a>';
			}
			return $actions;
		}


	}