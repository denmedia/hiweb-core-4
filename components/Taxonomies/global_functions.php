<?php

	use hiweb\components\Taxonomies\TaxonomiesFactory;
	use hiweb\components\Taxonomies\Taxonomy;


	if( !function_exists( 'add_taxonomy' ) ){

		/**
		 * @param string $taxonomy_name - Название создаваемой таксономии. Может содержать только строчные латинские символы, числа и _, т.е. a-z0-9_. Длина названия таксономии должна быть в пределах от 1 до 32 символов (ограничение базы данных).
		 * @param array  $object_type   - Название типов постов, к которым будет привязана таксономия. В этом параметре, например, можно указать 'post', тогда у обычных постов WordPress появится новая таксономия (возможность классификации).
		 * @return Taxonomy
		 */
		function add_taxonomy( $taxonomy_name, $object_type = [] ){
			return TaxonomiesFactory::add( $taxonomy_name, $object_type );
		}
	}