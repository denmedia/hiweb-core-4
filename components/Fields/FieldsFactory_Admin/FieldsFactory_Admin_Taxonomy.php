<?php
	
	namespace hiweb\components\Fields\FieldsFactory_Admin;
	
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\FieldsFactory_Admin;
	use hiweb\core\Paths\PathsFactory;
	
	
	class FieldsFactory_Admin_Taxonomy{
		
		static private function get_current_query( $append_taxonomy_query = [] ){
			if( !function_exists( 'get_current_screen' ) ) return [];
			///
			$WP_Term = get_term( PathsFactory::request( 'tag_ID' ), PathsFactory::request( 'taxonomy' ), OBJECT );
			if( !$WP_Term instanceof \WP_Term ) return null;
			///
			$query = [
				'taxonomy' => [
					'term_id' => $WP_Term->term_id,
					'term_taxonomy_id' => $WP_Term->term_taxonomy_id,
					'name' => $WP_Term->name,
					'taxonomy' => $WP_Term->taxonomy,
					'slug' => $WP_Term->slug,
					'count' => $WP_Term->count,
					'parent' => $WP_Term->parent,
					'term_group' => $WP_Term->term_group
				]
			];
			if( is_array( $append_taxonomy_query ) ) $query['taxonomy'] = array_merge( $query['taxonomy'], $append_taxonomy_query );
			return $query;
		}
		
		
		static function taxonomy_add_form_fields( $taxonomy ){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query() );
		}
		
		
		static function taxonomy_edit_form( $term, $taxonomy ){
			echo FieldsFactory_Admin::get_ajax_form_html( self::get_current_query() );
		}
		
		
		static function taxonomy_edited_term( $term_id, $tt_id, $taxonomy ){
			if(!array_key_exists('hiweb-core-field-form-nonce', $_POST) || !wp_verify_nonce( $_POST['hiweb-core-field-form-nonce'], 'hiweb-core-field-form-save' ) ) return;
			$term = get_term_by( 'id', $term_id, $taxonomy );
			if( $term instanceof \WP_Term ){
				$query = FieldsFactory::get_query_from_contextObject( get_term( $term_id ) );
				$fields = FieldsFactory::get_field_by_query( $query );
				foreach( $fields as $Field ){
					$field_name = FieldsFactory_Admin::get_field_input_name( $Field );
					if( isset( $_POST[ $field_name ] ) ){
						update_term_meta( $term_id, $Field->id(), $Field->get_sanitize_admin_value( $_POST[ $field_name ], true ) );
					}
					else{
						update_term_meta( $term_id, $Field->id(), $Field->get_sanitize_admin_value( '', true ) );
					}
				}
			}
		}
	}