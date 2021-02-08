<?php
	
	namespace hiweb\components\FontAwesome;
	
	
	use hiweb\core\Cache\CacheFactory;
	
	
	class FontAwesome_Icon{
		
		private $original_icon_class = '';
		
		private $icon_name = '';
		/** @var string //fab|far|fas|fad|fal */
		private $default_type;
		private $default_style;
		private $style_convert = [
			'fab' => 'brands',
			'fal' => 'light',
			'far' => 'regular',
			'fas' => 'solid',
			'fad' => 'duotone'
		];
		
		private $data_loaded = false;
		private $exists = false;
		
		private $changes;
		private $ligatures;
		private $search;
		private $styles;
		private $unicode;
		private $label;
		private $voted;
		private $svg;
		private $free;
		
		
		public function __construct( $icon_name = 'fab fa-wordpress' ){
			$this->original_icon_class = $icon_name;
			if( preg_match( '/^fa(?>b|l|s|r|d) /i', $icon_name, $matches ) > 0 ){
				if( array_key_exists( trim( $matches[0] ), $this->style_convert ) ){
					$this->default_type = trim( $matches[0] );
					$this->default_style = $this->style_convert[ trim( $matches[0] ) ];
				}
			}
			$this->icon_name = fontawesome_filter_icon_name( $icon_name );
			///LOAD DATA
			$this->load_data();
		}
		
		
		/**
		 * @return string
		 */
		public function __invoke(){
			return $this->get_style()->get_raw();
		}
		
		
		/**
		 * Return the svg code of the icon, like '<svg><path>...</path></svg>'
		 * @return string
		 */
		public function __toString(){
			return $this->get_style()->get_raw();
		}
		
		
		/**
		 * load the icon data from icons.json file
		 * @version 1.1
		 */
		private function load_data(){
			$icon_data = CacheFactory::get( $this->icon_name, __METHOD__, function(){
				if( !$this->data_loaded ){
					$this->data_loaded = true;
					$icons = FontAwesomeFactory::get_icons_data();
					if( array_key_exists( $this->icon_name, $icons ) && is_array( $icons[ $this->icon_name ] ) ){
						return $icons[ $this->icon_name ];
					}
				}
				return false;
			}, [], true )->file()->set_lifetime( 2.628e+6 )->cache()->get_value();
			if( is_array( $icon_data ) && count( $icon_data ) > 0 ){
				if( isset( $icon_data['changes'] ) ) $this->changes = $icon_data['changes'];
				if( isset( $icon_data['ligatures'] ) ) $this->ligatures = $icon_data['ligatures'];
				if( isset( $icon_data['search'] ) ) $this->search = $icon_data['search'];
				if( isset( $icon_data['styles'] ) ) $this->styles = $icon_data['styles'];
				if( isset( $icon_data['unicode'] ) ) $this->unicode = $icon_data['unicode'];
				if( isset( $icon_data['label'] ) ) $this->label = $icon_data['label'];
				if( isset( $icon_data['voted'] ) ) $this->voted = $icon_data['voted'];
				if( isset( $icon_data['svg'] ) ) $this->svg = $icon_data['svg'];
				if( isset( $icon_data['free'] ) ) $this->free = $icon_data['free'];
				if( is_array( $this->styles ) && count( $this->styles ) > 0 ){
					if((string)$this->default_style != '') {
						//do nothing
					}
					///Priority duotone
					elseif( in_array( 'duotone', $this->styles ) ){
						$this->default_style = 'duotone';
					}
					elseif( isset( $this->styles[0] ) ){
						$this->default_style = $this->styles[0];
					}
					else{
						$this->default_style = '';
					}
					if( $this->default_style != '' ){
						$this->default_type = 'fa' . substr( $this->default_style, 0, 1 );
					}
				}
				$this->exists = true;
			}
		}
		
		
		public function is_exists(){
			$this->load_data();
			return $this->exists;
		}
		
		
		/**
		 * @return string
		 */
		public function get_name(){
			return $this->icon_name;
		}
		
		
		/**
		 * @return string
		 */
		public function get_type(){
			if( !$this->is_exists() ) return '';
			return $this->default_type;
		}
		
		
		/**
		 * @return array
		 */
		public function get_changes(){
			if( !$this->is_exists() ) return [];
			return $this->changes;
		}
		
		
		/**
		 * @return array
		 */
		public function get_ligatures(){
			if( !$this->is_exists() ) return [];
			return $this->ligatures;
		}
		
		
		/**
		 * @return array
		 */
		public function get_search(){
			if( !$this->is_exists() ) return [];
			return $this->search;
		}
		
		
		/**
		 * @return array
		 */
		public function get_styles(){
			if( !$this->is_exists() ) return [];
			return $this->styles;
		}
		
		
		/**
		 * @return string
		 */
		public function get_unicode(){
			if( !$this->is_exists() ) return '';
			return $this->unicode;
		}
		
		
		/**
		 * @return string
		 */
		public function get_label(){
			if( !$this->is_exists() ) return '';
			return $this->label;
		}
		
		
		/**
		 * @return string
		 */
		public function get_voted(){
			if( !$this->is_exists() ) return '';
			return $this->voted;
		}
		
		
		/**
		 * @return array
		 */
		public function get_svg(){
			if( !$this->is_exists() ) return [];
			return $this->svg;
		}
		
		
		/**
		 * @return array
		 */
		public function get_free(){
			if( !$this->is_exists() ) return [];
			return $this->free;
		}
		
		
		/**
		 * Return class of the icon, like 'fab fa-wordpress'
		 * @return string
		 */
		public function get_class(){
			return CacheFactory::get( $this->icon_name, __METHOD__, function(){
				if( $this->is_exists() ){
					return $this->default_type . ' fa-' . $this->icon_name;
				}
				else{
					return $this->original_icon_class;
				}
			} )();
		}
		
		
		///STYLES
		
		
		/**
		 * Return true if style exists
		 * @param string $style
		 * @return bool
		 */
		public function is_style_exists( $style = 'solid' ){
			if( !$this->is_exists() ) return false;
			return in_array( $style, $this->get_styles() );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_solid(){
			return $this->is_style_exists( 'solid' );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_regular(){
			return $this->is_style_exists( 'regular' );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_light(){
			return $this->is_style_exists( 'light' );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_duotone(){
			return $this->is_style_exists( 'duotone' );
		}
		
		
		/**
		 * @return bool
		 */
		public function is_brands(){
			return $this->is_style_exists( 'brands' );
		}
		
		
		public function get_src(){
			return get_rest_url( null, 'hiweb/components/fontawesome/svg/' . $this->get_class() );
		}
		
		
		/**
		 * @param string $style
		 * @return FontAwesome_Icon_Style
		 */
		public function get_style( $style = null ){
			if( $this->is_brands() ){
				$style = 'brands';
			}
			elseif( (string)$style == '' ){
				if( $this->is_style_exists( $this->default_style ) ){
					$style = $this->default_style;
				}
				elseif(count($this->get_styles()) > 0){
					$style = current( $this->get_styles() );
				} else {
					$style = $this->default_style;
				}
			}
			return CacheFactory::get( (string)$style . '-' . $this->icon_name, __METHOD__, function(){
				return new FontAwesome_Icon_Style( $this, func_get_arg( 0 ) );
			}, [ $style ] )();
		}
		
		
		/**
		 * @return FontAwesome_Icon_Style
		 */
		public function get_style_solid(){
			return $this->get_style( 'solid' );
		}
		
		
		/**
		 * @return FontAwesome_Icon_Style
		 */
		public function get_style_regular(){
			return $this->get_style( 'regular' );
		}
		
		
		/**
		 * @return FontAwesome_Icon_Style
		 */
		public function get_style_light(){
			return $this->get_style( 'light' );
		}
		
		
		/**
		 * @return FontAwesome_Icon_Style
		 */
		public function get_style_duotone(){
			return $this->get_style( 'duotone' );
		}
		
		
		/**
		 * @return FontAwesome_Icon_Style
		 */
		public function get_style_brands(){
			return $this->get_style( 'brands' );
		}
		
		
	}