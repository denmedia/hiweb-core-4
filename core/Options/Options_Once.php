<?php

	namespace hiweb\core\Options;


	use hiweb\core\Options;


	/**
	 * Используется для суб-опции, которая имеет тольуко одно значение
	 * Class Options_Once
	 * @package hiweb\core\Options
	 */
	abstract class Options_Once extends Options{

		/**
		 * @param null $option_key
		 * @param null $default
		 * @return array|mixed|null
		 */
		protected function get( $option_key = null, $default = null ){
			return $this->options_ArrayObject()->get_value_first( $default );
		}


		/**
		 * @param      $value
		 * @param null $null
		 * @return Options|mixed
		 */
		protected function set( $null, $value ){
			parent::set( 0, $value );
			return $this->getParent_OptionsObject();
		}


	}