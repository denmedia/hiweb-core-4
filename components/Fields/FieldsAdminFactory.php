<?php
	
	namespace hiweb\components\Fields;
	
	
	use hiweb\components\Console\ConsoleFactory;
	use hiweb\components\Context;
	use hiweb\components\Includes\IncludesFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;
	use WP_Post;
	
	
	class FieldsAdminFactory{
		
		use hidden_methods;
		
		private static $the_form_field;
		private static $the_form_field_value;
		private static $the_form_field_name;
		
		
		/**
		 * @param Field $field
		 * @return string
		 */
		static public function get_field_input_name( Field $field ){
			return 'hiweb-field-' . $field->ID();
		}
		
		
		/**
		 * @param field $field
		 * @return string
		 */
		static public function get_field_input_option_name( field $field ){
			$options_admin_menus = $field->Options()->Location()->_( 'admin_menus' );
			if( isset( $options_admin_menus['menu_slug'] ) ) return 'hiweb-field-option-' . $options_admin_menus['menu_slug'] . '-' . $field->ID();
			return 'hiweb-field-option-' . $field->ID();
		}
		
		
		/**
		 * @param $page_slug
		 * @return string
		 */
		static public function get_section_id( $page_slug ){
			return 'hiweb-options-section-' . $page_slug;
		}
		
		
		static public function get_option_group_id( $menu_slug ){
			return 'hiweb-option-group-' . $menu_slug;
		}
		
		
		/**
		 * @param $field_id
		 * @return string
		 */
		static function get_columns_field_id( $field_id ){
			return 'hiweb-column-' . $field_id;
		}
		
		
		/**
		 * @param field $field
		 * @return string
		 */
		static public function get_fieldset_classes( field $field ){
			$classes = [ 'hiweb-fieldset' ];
			//$classes[] = 'hiweb-fieldset-width-' . $field->FORM()->WIDTH()->get();
			$classes[] = 'hiweb-field-' . $field->ID();
			$classes[] = 'hiweb-field-' . $field->Options()->_( 'global_id' );
			return implode( ' ', $classes );
		}
		
		
		/**
		 * @return Field
		 */
		static function get_the_field(){
			return self::$the_form_field;
		}
		
		
		/**
		 * @return mixed
		 */
		static function get_the_field_value(){
			return self::$the_form_field_value;
		}
		
		
		/**
		 * @return string
		 */
		static function get_the_field_name(){
			return self::$the_form_field_name;
		}
		
		
		/**
		 * Get current admin fields query
		 * @return array
		 */
		static function get_current_fields_query(){
			$R = [
			
			];
			if( function_exists( 'get_current_screen' ) ){
				if( get_current_screen()->base == 'post' ){
					$R = [
						'post_type' => [
							'ID' => $_GET['post'],
							'post_type' => get_current_screen()->post_type
						]
					];
				}
				else{
					console_warn( get_current_screen() );
				}
			}
			return $R;
		}
		
		
		/**
		 * @param null $location_query
		 * @return array
		 */
		static function get_current_location_raw_values( $location_query = null ){
			$R = [];
			$fields = FieldsFactory::get_field_by_location( is_array( $location_query ) ? $location_query : self::get_current_fields_query() );
			foreach( $fields as $Field ){
				if( array_key_exists( 'post_type', $location_query ) && metadata_exists( 'post', $location_query['post_type']['ID'], $Field->ID() ) ){
					$R[ $Field->ID() ] = get_post_meta( $location_query['post_type']['ID'], $Field->ID(), true );
				}
				else{
					$R[ $Field->ID() ] = null;
				}
			}
			return $R;
		}
		
		
		/**
		 * @param $query - QUERY LOCATION ARRAY
		 * @return false|string
		 */
		static function get_ajax_form_html( $query ){
			if( !is_array( $query ) ) return 'no form fields query';
			IncludesFactory::Js( HIWEB_DIR_VENDOR . '/jquery.regex-selector/jquery.regex-selector.min.js' )->deeps( 'jquery-core' );
			IncludesFactory::Js( __DIR__ . '/FieldsAdmin.min.js' )->deeps( 'jquery-core' );
			IncludesFactory::Css( __DIR__ . '/css/FieldsAdmin.css' );
			if( count( FieldsFactory::get_field_by_location( $query ) ) == 0 ) return false;
			ob_start();
			self::get_wp_nonce_field();
			include __DIR__ . '/FieldsAdminFactory/templates/form-ajax.php';
			static $footer_printed = false;
			if( !$footer_printed ){
				$footer_printed = true;
				add_action( 'admin_print_footer_scripts', function(){
					?>
					<script>
                        let hiweb_components_fields_form_scripts_done = <?=json_encode( wp_scripts()->done )?>;
					</script><?php
				}, 999999 );
			}
			return ob_get_clean();
		}
		
		
		/**
		 *
		 */
		static function get_ajax_form_hock(){
			$fields = [];
			$css = [];
			$js = [];
			$query = json_decode( stripslashes( $_POST['field_query'] ), true );
			$debug = 0;
			$values = self::get_current_location_raw_values( $query );
			if( json_last_error() == JSON_ERROR_NONE && is_array( $query ) ){
				$fields = FieldsFactory::get_field_by_location( $query );
				$debug = 1;
				foreach( $fields as $Field ){
					$debug = 2;
					if( $Field->ID() != '' ){
						$field_css = $Field->get_css();
						$css = array_merge( $css, is_array( $field_css ) ? $field_css : [ $field_css ] );
						$field_js = $Field->get_js();
						$js = array_merge( $js, is_array( $field_js ) ? $field_js : [ $field_js ] );
					}
				}
			}
			///Scripts done
			$scripts_done = $_POST['scripts_done'];
			if( json_last_error() != JSON_ERROR_NONE || !is_array( $query ) ){
				$scripts_done = [];
			}
			///
			$css = array_unique( $css );
			$js = array_unique( $js );
			$css_filtered = [];
			$js_filtered = [];
			foreach( $css as $index => $file ){
				if( preg_match( '/^[\w\-_]+$/', $file ) > 0 ){
					foreach( IncludesFactory::get_srcs_from_handle( $file, false, true ) as $handle => $src ){
						$Path = PathsFactory::get( $src );
						$css_filtered[ $handle ] = $Path->Url()->get();
					}
				}
				else{
					$Path = PathsFactory::get( $file );
					$css_filtered[ $Path->handle() ] = $Path->Url()->get();
				}
			}
			foreach( $js as $index => $file ){
				if( preg_match( '/^[\w\-_]+$/', $file ) > 0 ){
					if( in_array( $file, $scripts_done ) ) continue;
					foreach( IncludesFactory::get_srcs_from_handle( $file, true, false ) as $handle => $src ){
						if( in_array( $handle, $scripts_done ) ) break;
						$Path = PathsFactory::get( $src );
						$js_filtered[ $handle ] = $Path->Url()->get();
					}
				}
				else{
					$Path = PathsFactory::get( $file );
					if( !in_array( $Path->handle(), $scripts_done ) ) $js_filtered[ $Path->handle() ] = $Path->Url()->get();
				}
			}
			///
			$form_html = self::get_form_html( $fields, $values );
			ob_start();
			ConsoleFactory::the();
			$form_html .= ob_get_clean();
			///
			wp_send_json( [
				'success' => true,
				'query' => $query,
				'debug' => $debug,
				'values' => $values,
				'scripts_done' => $scripts_done,
				'css' => $css_filtered,
				'js' => $js_filtered,
				'max_input_nesting_level' => ini_get( 'max_input_nesting_level' ),
				'max_input_vars' => ini_get( 'max_input_vars' ),
				'max_input_time' => ini_get( 'max_input_time' ),
				'max_execution_time' => ini_get( 'max_execution_time' ),
				'upload_max_filesize' => ini_get( 'upload_max_filesize' ),
				'post_max_size' => ini_get( 'post_max_size' ),
				'field_ids' => array_keys( $fields ),
				'form_html' => $form_html
			] );
		}
		
		static function get_wp_nonce_field(){
			static $nonce_printed = false;
			if(!Context::is_ajax() && !$nonce_printed ){
				$nonce_printed = true;
				wp_nonce_field( 'hiweb-core-field-form-save', 'hiweb-core-field-form-nonce', false );
			}
		}
		
		/**
		 * @param Field[]    $fields_array
		 * @param null|array $field_values - set fields value, or get values from screen context
		 * @return string|string[]|void
		 */
		static function get_form_html( $fields_array, $field_values = null ){
			if( !is_array( $fields_array ) || count( $fields_array ) == 0 ) return;
			///
			IncludesFactory::Css( __DIR__ . '/FieldsAdminFactory/style.css' );
			IncludesFactory::Js( __DIR__ . '/FieldsAdminFactory/script.min.js' );
			ob_start();
			self::get_wp_nonce_field();
			@include __DIR__ . '/FieldsAdminFactory/templates/default-form.php';
			$form_html = ob_get_clean();
			///
			$fields_html = [];
			foreach( $fields_array as $Field ){
				if( !$Field instanceof Field ){
					ConsoleFactory::add( 'its not Field instance', 'warn', __METHOD__, [ $Field ], true );
					continue;
				}
				self::$the_form_field = $Field;
				$value = null;
				if( is_array( $field_values ) ){
					if( array_key_exists( $Field->ID(), $field_values ) && !is_null( $field_values[ $Field->ID() ] ) ) $value = $field_values[ $Field->ID() ];
					elseif( !is_null( $Field->Options()->default_value() ) ) $value = $Field->Options()->default_value();
				}
				if( is_null( $value ) && !is_null( $Field->Options()->default_value() ) ){
					$value = $Field->Options()->default_value();
				}
				self::$the_form_field_value = $Field->get_sanitize_admin_value( $value );
				self::$the_form_field_name = self::get_field_input_name( $Field );
				ob_start();
				@include __DIR__ . '/FieldsAdminFactory/templates/default-field.php';
				$fields_html[] = ob_get_clean();
			}
			///
			return str_replace( '<!--fields-->', implode( chr( 13 ) . chr( 10 ), $fields_html ), $form_html );
		}
		
		
		//POST TYPE POSITIONS
		
		static function _edit_form_top(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'edit_form_top'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		static function _edit_form_before_permalink(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'edit_form_before_permalink'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		static function _edit_form_after_title(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'edit_form_after_title'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		static function _edit_form_after_editor(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'edit_form_after_editor'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		static function _submitpost_box(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'submitpost_box'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		static function _edit_form_advanced(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'edit_form_advanced'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		static function _edit_page_form(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'edit_page_form'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		static function _dbx_post_sidebar(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => 'dbx_post_sidebar'
				]
			];
			echo self::get_ajax_form_html( $query );
		}
		
		
		///POST META BOXES
		static function _add_meta_boxes(){
			$query = [
				'post_type' => [
					'ID' => $_GET['post'],
					'post_type' => get_current_screen()->post_type,
					'position' => '',
					'metabox' => []
				]
			];
			$query_by_box = [];
			$fields = FieldsFactory::get_field_by_location( $query );
			if( !is_array( $fields ) || count( $fields ) == 0 ) return;
			$first_field_location = reset( $fields )->Options()->Location()->PostType();
			foreach( $fields as $Field ){
				$box_title = $Field->Options()->Location()->PostType()->MetaBox()->title();
				$query_by_box[ $box_title ] = $query;
				$query_by_box[ $box_title ]['post_type']['metabox']['title'] = $box_title;
			}
			foreach( $query_by_box as $title => $query ){
				$box_id = 'hiweb-metabox-' . Strings::sanitize_id( $title );
				add_meta_box( $box_id, $title, function(){
					echo self::get_ajax_form_html( func_get_arg( 1 )['args'][0] );
				}, $first_field_location->post_type(), $first_field_location->MetaBox()->Context()->_(), $first_field_location->MetaBox()->Priority()->_(), [ $query ] );
			}
			/**
			 * @var string  $title
			 * @var Field[] $fields
			 */ //			foreach( $fields_by_title as $title => $fields ){
			//				$box_id = 'hiweb-metabox-' . Strings::sanitize_id( $title );
			//				$first_field_location = reset( $fields )->Options()->Location()->PostType();
			//				add_meta_box( $box_id, $title, function(){
			//					echo self::get_ajax_form_html( func_get_arg( 1 )['args'][0] );
			//				}, $first_field_location->post_type(), $first_field_location->MetaBox()->Context()->_(), $first_field_location->MetaBox()->Priority()->_(), [ $fields ] );
			//			}
		}
		
		
		/**
		 * @param int     $post_ID
		 * @param WP_Post $post
		 * @param bool    $update
		 */
		static function _save_post( $post_ID, $post, $update ){
			if( !wp_verify_nonce( $_POST['hiweb-core-field-form-nonce'], 'hiweb-core-field-form-save' ) ) return;
			$query = [
				'post_type' => [
					'ID' => $post_ID,
					'post_type' => $post->post_type
				]
			];
			foreach( FieldsFactory::get_field_by_location( $query ) as $Field ){
				$field_name = self::get_field_input_name( $Field );
				if( $Field->get_allow_save_field( array_key_exists( $field_name, $_POST ) ? $_POST[ $field_name ] : null ) ){
					if( array_key_exists( $field_name, $_POST ) ){
						update_post_meta( $post_ID, $Field->ID(), $Field->get_sanitize_admin_value( $_POST[ $field_name ], true ) );
					}
					else{
						update_post_meta( $post_ID, $Field->ID(), $Field->get_sanitize_admin_value( null, true ) );
					}
				}
			}
		}
		
	}