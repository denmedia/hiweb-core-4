<?php
	
	namespace hiweb\components\Fields\Types\Post;
	
	
	use hiweb\components\Fields\Field;
	
	
	class Field_Post extends Field{
		
		
		protected $options_class = '\hiweb\components\Fields\Types\Post\Field_Post_Options';
		
		
		public function get_css(){
			return [
				HIWEB_DIR_VENDOR . '/selectize.js/css/selectize.css',
				__DIR__ . '/Field_Post.css'
			];
		}
		
		
		public function get_js(){
			return [
				HIWEB_DIR_VENDOR . '/selectize.js/js/standalone/selectize.min.js',
				__DIR__ . '/Field_Post.min.js'
			];
		}
		
		
		/**
		 * @return Field_Post_Options
		 */
		public function options(){
			return parent::options();
		}
		
		
		/**
		 * @param mixed|null $value
		 * @param bool       $update_meta_process
		 * @return array|mixed|null
		 */
		public function get_sanitize_admin_value( $value, $update_meta_process = false ){
			if( !is_array( $value ) && $this->options()->multiple() ) $value = [ $value ];
			elseif(is_array($value) && !$this->options()->multiple()) {
				$value = reset($value);
			}
			return $value;
		}
		
		
		/**
		 * @return bool
		 */
		protected function is_many_post_types(){
			return ( is_array( $this->options()->post_type() ) && count( $this->options()->post_type() ) > 1 );
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			$value = $this->get_sanitize_admin_value( $value );
			$post_types = $this->options()->post_type();
			if( is_string( $post_types ) ) $post_types = [ $post_types ];
			$post_types_names = [];
			if( is_array( $post_types ) ) foreach( $post_types as $post_type ){
				if( post_type_exists( $post_type ) ){
					$post_types_names[ $post_type ] = get_post_type_object( $post_type )->label;
				}
				else{
					$post_types_names[ $post_type ] = 'неизвестный тип записи';
				}
			}
			
			$selected = [];
			$is_many_post_types = $this->is_many_post_types();
			$post_types_labels = [];
			if( $is_many_post_types ){
				foreach( get_post_types([], OBJECT) as $WP_Post_Type ){
					$post_types_labels[ $WP_Post_Type->name ] = $WP_Post_Type->label;
				}
			}
			if( is_array( $value ) && count( $value ) > 0 ){
				///
				$wp_query_args = [
					'post_type' => $post_types,
					'posts_per_page' => 20,
					'post_status' => 'any',
					'post__in' => $value
				];
				$wp_query = new \WP_Query( $wp_query_args );
				foreach( $wp_query->get_posts() as $post ){
					$selected[ $post->ID ] = ( ( $is_many_post_types && array_key_exists( $post->post_type, $post_types_labels ) ) ? $post_types_labels[ $post->post_type ] . ': ' : '-' ) . $post->post_title;
				}
			}
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
	}