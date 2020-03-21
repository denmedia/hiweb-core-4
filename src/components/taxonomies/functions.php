<?php

	use hiweb\taxonomies;


	if( !function_exists( 'add_taxonomy' ) ){
		/**
		 * @param $taxonomy_name
		 * @param string|array $object_type - post type / post types
		 * @return taxonomies\taxonomy
		 */
		function add_taxonomy( $taxonomy_name, $object_type ){
			return taxonomies::register( $taxonomy_name, $object_type );
		}
	} else {
		hiweb\console::debug_warn( 'Функция [add_taxonomy] существует!' );
	}