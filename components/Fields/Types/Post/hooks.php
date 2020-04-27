<?php
	
	use hiweb\components\Fields\Types\Post\Field_Post;
	
	
	add_action( 'wp_ajax_hiweb-components-fields-type-post', function(){
		/** @var Field_Post $Field */
		$Field = \hiweb\components\Fields\FieldsFactory::get_field( $_POST['global_id'] );
		$post_types = '';
		if( $Field instanceof Field_Post ){
			$post_types = $Field->Options()->post_type();
		}
		elseif( isset( $_POST['post_type'] ) ){
			$post_types = $_POST['post_type'];
		}
		if( $post_types == '' ){
			$post_types = [ 'post', 'page' ];
		}
		$query = [
			'post_type' => $post_types,
			//'wpse18703_title' => $_POST['search'],
			'posts_per_page' => 99,
			'post_status' => 'any',
			's' => $_POST['search'],
			'orderby' => 'title',
			'order' => 'ASC'
		];
		$wp_query = new WP_Query( $query );
		$R = [];
		//					$post_types_names = [];
		//					if( is_array( $post_types ) ) foreach( $post_types as $post_type ){
		//						if( post_type_exists( $post_type ) ){
		//							$post_types_names[ $post_type ] = get_post_type_object( $post_type )->label;
		//						} else {
		//							$post_types_names[ $post_type ] = 'неизвестный тип записи';
		//						}
		//					}
		/** @var WP_Post $wp_post */
		foreach( $wp_query->get_posts() as $wp_post ){
			$R[] = [
				'value' => $wp_post->ID,
				'title' => $wp_post->post_title == '' ? '--без названия: ' . $wp_post->ID . '--' : $wp_post->post_title,
				//'name' => '<img src="' . get_image( get_post_thumbnail_id( $wp_post ) )->get_src( 'thumbnail' ) . '">' . $wp_post->post_title
			];
		}
		
		//wp_send_json_success( $R );
		echo json_encode( [
			'success' => true,
			'items' => $R
		] );
		die;
	} );