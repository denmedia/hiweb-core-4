<?php
	
	namespace hiweb\components\Fields\Types\Terms;
	
	
	use hiweb\components\Fields\Field;
	use WP_Taxonomy;
	use WP_Term;
	
	
	class Field_Terms extends Field{
		
		protected $options_class = '\hiweb\components\Fields\Types\Terms\Field_Terms_Options';
		
		
		public function get_css(){
			return [
				HIWEB_DIR_VENDOR . '/selectize.js/css/selectize.css',
			];
		}
		
		
		public function get_js(){
			return [
				HIWEB_DIR_VENDOR . '/selectize.js/js/standalone/selectize.min.js',
				__DIR__ . '/Field_Terms.min.js'
			];
		}
		
		
		/**
		 * @return Field_Terms_Options
		 */
		public function options(){
			return parent::options();
		}
		
		
		/**
		 * @return array
		 */
		private function get_terms_by_taxonomy(){
			$terms_by_taxonomy = [];
			$taxonomies = $this->options()->taxonomy();
			if( is_array( $taxonomies ) ){
				foreach( $taxonomies as $taxonomy ){
					if( !taxonomy_exists( $taxonomy ) ) continue;
					$args = [
						'taxonomy' => $taxonomy,
						'hide_empty' => $this->options()->hide_empty()
					];
					$terms = get_terms( $args );
					foreach( $terms as $wp_term ){
						//if( is_array( $terms ) ) $terms_by_taxonomy[ $taxonomy ][ $wp_term->term_id ] = $wp_term;
						if( is_array( $terms ) ) $terms_by_taxonomy[ $wp_term->term_id ] = $wp_term;
					}
				}
			}
			return $terms_by_taxonomy;
		}
		
		
		/**
		 * @param $wp_term
		 * @return string|null
		 */
		private function get_term_title( $wp_term ){
			$title = null;
			if( $wp_term instanceof WP_Term ){
				$title = '';
				$taxonomy = get_taxonomy( $wp_term->taxonomy );
				if( $taxonomy instanceof WP_Taxonomy ){
					$title = $taxonomy->label . '→ ';
				}
				$title .= $wp_term->name . ' (' . $wp_term->count . ')';
			}
			return $title;
		}
		
		
		/**
		 * @param            $value
		 * @param WP_Term[]  $wp_terms
		 * @param null       $terms_level
		 */
		private function get_html_options_from_terms( $value, $wp_terms, $terms_level = null ){
			$selected_ids = [];
			if( is_array( $value ) ) foreach( $value as $term_taxonomy_id ){
				if( isset( $wp_terms[ $term_taxonomy_id ] ) ){
					$wp_term = $wp_terms[ $term_taxonomy_id ];
					?>
					<option selected value="<?= $term_taxonomy_id ?>"><?= $this->get_term_title( $wp_term ) ?></option><?php
					$selected_ids[ $term_taxonomy_id ] = $term_taxonomy_id;
				}
			}
			foreach( $wp_terms as $wp_term ){
				if( isset( $selected_ids[ $wp_term->term_taxonomy_id ] ) ) continue;
				?>
				<option value="<?= $wp_term->term_taxonomy_id ?>"><?= $this->get_term_title( $wp_term ) ?></option>
				<?php
			}
		}
		
		
		public function get_admin_html( $value = null, $name = null ){
			ob_start();
			include __DIR__ . '/template.php';
			return ob_get_clean();
		}
		
		
	}