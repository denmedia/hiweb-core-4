<?php
	
	namespace hiweb\components\Fields\FieldsFactory_Admin;
	
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\FieldsFactory_Admin;
	use hiweb\components\Structures\StructuresFactory;
	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;
	use WP_Post;
	
	
	class FieldsFactory_Admin_PostType{
		
		static private function get_current_query( $append_post_type_query = [] ){
			if( !function_exists( 'get_current_screen' ) ) return [];
			///
			$WP_Post = get_post( PathsFactory::request( 'post' ) );
			if( !$WP_Post instanceof WP_Post ) return null;
			///
			$query = [
				'post_type' => [
					'ID' => intval( PathsFactory::request( 'post' ) ),
					'post_type' => $WP_Post->post_type,
					'post_name' => $WP_Post->post_name,
					'post_status' => $WP_Post->post_status,
					'comment_status' => $WP_Post->comment_status,
					'post_parent' => $WP_Post->post_parent,
					'has_taxonomy' => $WP_Post->has_taxonomy,
					'front_page' => $WP_Post->ID == StructuresFactory::get_front_page_id(),
					'home_page' => $WP_Post->ID == StructuresFactory::get_blog_id(),
					'template' => get_page_template_slug( intval( PathsFactory::request( 'post' ) ) )
				]
			];
			if( is_array( $append_post_type_query ) ) $query['post_type'] = array_merge( $query['post_type'], $append_post_type_query );
			return $query;
		}
		
		
		static function _edit_form_top(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'edit_form_top' ] ) );
		}
		
		
		static function _edit_form_before_permalink(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'edit_form_before_permalink' ] ) );
		}
		
		
		static function _edit_form_after_title(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'edit_form_after_title' ] ) );
		}
		
		
		static function _edit_form_after_editor(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'edit_form_after_editor' ] ) );
		}
		
		
		static function _submitpost_box(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'submitpost_box' ] ) );
		}
		
		
		static function _edit_form_advanced(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'edit_form_advanced' ] ) );
		}
		
		
		static function _edit_page_form(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'edit_page_form' ] ) );
		}
		
		
		static function _dbx_post_sidebar(){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query( [ 'position' => 'dbx_post_sidebar' ] ) );
		}
		
		
		///POST META BOXES
		static function _add_meta_boxes(){
			$query = self::get_current_query( [ 'position' => '', 'metabox' => [] ] );
			$query_by_box = [];
			$fields = FieldsFactory::get_field_by_query( $query );
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
					echo FieldsFactory_Admin::get_ajax_form_html( func_get_arg( 1 )['args'][0] );
				}, $first_field_location->post_type(), $first_field_location->MetaBox()->Context()->_(), $first_field_location->MetaBox()->Priority()->_(), [ $query ] );
			}
		}
		
		
		/**
		 * @param int     $post_ID
		 * @param WP_Post $post
		 * @param bool    $update
		 */
		static function _save_post( $post_ID, $post, $update ){
			if( !$update || !wp_verify_nonce( $_POST['hiweb-core-field-form-nonce'], 'hiweb-core-field-form-save' ) ) return;
			$query = [
				'post_type' => [
					'post_type' => $post->post_type
				]
			];
			foreach( FieldsFactory::get_field_by_query( $query ) as $Field ){
				$field_name = FieldsFactory_Admin::get_field_input_name( $Field );
				if( $Field->get_allow_save_field( array_key_exists( $field_name, $_POST ) ? $_POST[ $field_name ] : null ) ){
					if( array_key_exists( $field_name, $_POST ) ){
						update_post_meta( $post_ID, $Field->ID(), $Field->get_sanitize_admin_value( $_POST[ $field_name ], true ) );
					}
					else{
						update_post_meta( $post_ID, $Field->ID(), $Field->get_sanitize_admin_value( '', true ) );
					}
				}
			}
		}
		
		
		static function manage_posts_columns( $posts_columns, $post_type = 'page' ){
			$query = [
				'post_type' => [
					'post_type' => $post_type,
					'columns_manager' => []
				]
			];
			$fields = FieldsFactory::get_field_by_query( $query );
			if( count( $fields ) > 0 ){
				$posts_columns = ArrayObject::get_instance( $posts_columns );
				foreach( $fields as $field_ID => $Field ){
					$ColumnsManager = $Field->Options()->Location()->PostType()->ColumnsManager();
					$posts_columns->push( $ColumnsManager->id(), $ColumnsManager->name() );
				}
				$posts_columns = $posts_columns->get();
			}
			return $posts_columns;
		}
		
		
		static function manage_posts_custom_column( $columns_name, $post_id ){
			if( function_exists( 'get_current_screen' ) && strpos( $columns_name, 'hiweb-field-' ) === 0 ){
				$field_id = substr( $columns_name, strlen( 'hiweb-field-' ) );
				$query = [
					'post_type' => [
						'post_type' => get_current_screen()->post_type
					]
				];
				$Field = FieldsFactory_Admin::get_Field( $field_id, $query );
				$callback = $Field->Options()->Location()->PostType()->ColumnsManager()->callback();
				if( !is_null( $callback ) && is_callable( $callback ) ){
					call_user_func_array( $callback, [ $post_id, $Field, $columns_name ] );
				}
				else{
					echo $Field->get_admin_columns_html( get_post( $post_id ), $post_id, $columns_name );
				}
			}
		}
		
		
		/**
		 * @param $sortable_columns
		 * @return array
		 */
		static function manage_posts_sortable_columns( $sortable_columns ){
			$fields = FieldsFactory::get_field_by_query( [
				'post_type' => [
					'post_type' => get_current_screen()->post_type
				]
			] );
			foreach( $fields as $Field ){
				if( $Field->Options()->Location()->PostType()->ColumnsManager()->sortable() ){
					$sortable_columns[ 'hiweb-field-' . $Field->ID() ] = 'hiweb-field-' . $Field->ID();
				}
			}
			return $sortable_columns;
		}
		
	}