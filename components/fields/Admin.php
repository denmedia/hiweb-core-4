<?php

	namespace hiweb\components\fields;


	class Admin{

		/**
		 * @param $wp_post
		 * @return array|Field[]
		 */
		static function get_fields_by_post( $wp_post ){
			$R = [];
			foreach( Field::get_fields() as $global_id => $Field ){
				$FieldOptions = $Field->Options();
				if( $FieldOptions->_is_exists( 'screen' ) && $FieldOptions->Screen()->_is_exists( 'posts' ) ){
					$R[ $global_id ] = $Field;
				}
			}
			return $R;
		}


		///POST
		static function edit_form_top( $wp_post ){
			echo '<h1>!!!' . __FUNCTION__ . '</h1>';
		}


		static function edit_form_before_permalink( $wp_post ){
			echo '<h1>!!!' . __FUNCTION__ . '</h1>';
		}


		static function edit_form_after_title( $wp_post ){
			echo '<h1>!!!' . __FUNCTION__ . '</h1>';
		}


		static function edit_form_after_editor( $wp_post ){
			echo '<h1>!!!' . __FUNCTION__ . '</h1>';
		}


		static function submitpost_box( $wp_post ){
			echo '<h1>!!!' . __FUNCTION__ . '</h1>';
		}


		/**
		 * containing TinyMCE: 'edit_page_form', 'edit_form_advanced' and 'dbx_post_sidebar'.
		 * @param $wp_post
		 */
		static function edit_form_advanced( $wp_post ){
			echo '<h1>!!!' . __FUNCTION__ . '</h1>';
		}


		/**
		 * containing TinyMCE: 'edit_page_form', 'edit_form_advanced' and 'dbx_post_sidebar'.
		 * @param $wp_post
		 */
		static function dbx_post_sidebar( $wp_post ){
			echo '<h1>' . __FUNCTION__ . '</h1>';
		}


		static function add_meta_boxes( $post_type, $post ){
			/**
			 * @var string $global_id
			 * @var Field  $Field
			 */
			$meta_boxes = [];
			///collect fields
			foreach( self::get_fields_by_post( $post ) as $global_id => $Field ){
				$FieldOptions = $Field->Options();
				if( in_array( $post_type, $FieldOptions->Screen()->PostType()->post_type() ) && $FieldOptions->Screen()->PostType()->_is_exists( 'meta_boxes' ) ){
					$meta_box_options = $FieldOptions->Screen()->PostType()->MetaBox();
					if( !array_key_exists( $meta_box_options->id(), $meta_boxes ) ){
						$meta_boxes[ $meta_box_options->id() ] = [
							'title' => '',
							'id' => $meta_box_options->id(),
							'fields' => [],
							'context' => $meta_box_options->_( 'context' )->_( 0 ),
							'priority' => $meta_box_options->_( 'priority' )->_( 0 )
						];
					}
					if( !is_null( $meta_box_options->title() ) ){
						$meta_boxes[ $meta_box_options->id() ]['title'] = $meta_box_options->title();
					}
					$meta_boxes[ $meta_box_options->id() ]['fields'][ $global_id ] = $Field;
				}
			}
			///
			foreach( $meta_boxes as $meta_box_id => $meta_box_data ){
				add_meta_box( $meta_box_id, $meta_box_data['title'], '\hiweb\components\fields\Form::the_post_meta_box', $post_type, $meta_box_data['context'], $meta_box_data['priority'], [ $post, $meta_box_data['fields'] ] );
			}
		}


		////manage_posts_columns
		static function manage_posts_columns( $posts_columns, $post_type = 'post' ){
			/**
			 * @var string $global_id
			 * @var Field  $Field
			 */
			foreach( Field::get_fields() as $global_id => $Field ){
				if( $Field->Options()->_is_exists( 'screen' ) && $Field->Options()->Screen()->_is_exists( 'posts' ) && $Field->Options()->Screen()->PostType()->_is_exists( 'manage_columns' ) ){
					$ManageColumns = $Field->Options()->Screen()->PostType()->ManageColumns();
					$posts_columns[ 'hiweb-fields-' . $Field->get_id() ] = $ManageColumns->_( 'label' );
				}
			}
			return $posts_columns;
		}


		static function manage_posts_custom_column( $column_name, $post_id ){
			/**
			 * @var string $global_id
			 * @var Field  $Field
			 */
			foreach( Field::get_fields() as $global_id => $Field ){
				if( $Field->Options()->_is_exists( 'screen' ) && $Field->Options()->Screen()->_is_exists( 'posts' ) && $Field->Options()->Screen()->PostType()->_is_exists( 'manage_columns' ) ){
					if( $column_name == 'hiweb-fields-' . $Field->get_id() ){
						echo $Field->the_post_columns( $column_name, $post_id );
					}
				}
			}
		}


		////
		static function manage_posts_sortable_columns( $sortable_columns ){
			return $sortable_columns;
		}


		/**
		 * @param $post_ID
		 * @param $post
		 * @param $update
		 */
		static function save_post( $post_ID, $post, $update ){
			foreach( self::get_fields_by_post( $post ) as $global_id => $Field ){
				$Field->save_post( $post_ID, $post, $update );
			}
		}

	}