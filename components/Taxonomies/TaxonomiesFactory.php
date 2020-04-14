<?php

	namespace hiweb\components\Taxonomies;


	use hiweb\components\Console\ConsoleFactory;
	use hiweb\components\FontAwesome\FontAwesomeFactory;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Strings;


	class TaxonomiesFactory{

		use hidden_methods;


		/**
		 * Register new / set exists taxonomy
		 * @param string $taxonomy_name - Название создаваемой таксономии. Может содержать только строчные латинские символы, числа и _, т.е. a-z0-9_. Длина названия таксономии должна быть в пределах от 1 до 32 символов (ограничение базы данных).
		 * @param array  $object_type   - Название типов постов, к которым будет привязана таксономия. В этом параметре, например, можно указать 'post', тогда у обычных постов WordPress появится новая таксономия (возможность классификации).
		 * @return Taxonomy
		 */
		static function add( $taxonomy_name, $object_type = [] ){
			$taxonomy_name = Strings::sanitize_id( $taxonomy_name, '_', 32 );
			return CacheFactory::get( $taxonomy_name, __CLASS__ . '::$taxonomies', function(){
				$Taxonomy = new Taxonomy( func_get_arg( 0 ) );
				$Taxonomy->object_type( func_get_arg( 1 ) );
				return $Taxonomy;
			}, [ $taxonomy_name, $object_type ] )->get_value();
		}


		private static function _register_taxonomy(){
			foreach( CacheFactory::get_group( __CLASS__ . '::$taxonomies', true ) as $Taxonomy ){
				if( !$Taxonomy instanceof Taxonomy ){
					ConsoleFactory::add( 'This is not taxonomy!', 'warn', __METHOD__, $Taxonomy, true );
					continue;
				}
				register_taxonomy( $Taxonomy->taxonomy(), $Taxonomy->object_type(), $Taxonomy->_get_optionsCollect() );
			}
		}

	}