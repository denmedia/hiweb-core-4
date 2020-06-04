<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-11
	 * Time: 09:42
	 */

	namespace theme\mmenu;


	use theme\mmenu;


	class extensions{

		private $mmenu;
		private $border;
		private $fullscreen;
		private $fx_listitems;
		private $fx_menu;
		private $fx_panels;
		private $listview;
		private $multiline;
		private $pagedim;
		private $popup;
		private $position;
		private $shadow;
		private $theme;


		public function __construct( mmenu $mmenu ){
			$this->mmenu = $mmenu;
		}


		public function get_data_array(){
			$R = [];
			$options = [ 'border', 'fullscreen', 'fx_listitems', 'fx_menu', 'fx_panels', 'listview', 'multiline', 'pagedim', 'popup', 'position', 'shadow', 'theme' ];
			foreach( $options as $option_name ){
				if( property_exists( $this, $option_name ) && is_object( $this->{$option_name} ) ){
					$value = $this->{$option_name}->get();
					if( !is_array( $value ) )
						$value = [ $value ];
					foreach( $value as $sub_value ){
						if( $sub_value != '' )
							$R[] = $sub_value;
					}
				}
			}
			return $R;
		}


		/**
		 * @return extension\border
		 */
		public function border(){
			if( !$this->border instanceof mmenu\extension\border ){
				$this->border = new mmenu\extension\border( $this );
			}
			return $this->border;
		}


		/**
		 * @return extension\fullscreen
		 */
		public function fullscreen(){
			if( !$this->fullscreen instanceof mmenu\extension\fullscreen ){
				$this->fullscreen = new mmenu\extension\fullscreen( $this );
			}
			return $this->fullscreen;
		}


		/**
		 * @return extension\fx_listitems
		 */
		public function fx_listitems(){
			if( !$this->fx_listitems instanceof mmenu\extension\fx_listitems ){
				$this->fx_listitems = new mmenu\extension\fx_listitems( $this );
			}
			return $this->fx_listitems;
		}


		/**
		 * @return extension\fx_menu
		 */
		public function fx_menu(){
			if( !$this->fx_menu instanceof mmenu\extension\fx_menu ){
				$this->fx_menu = new mmenu\extension\fx_menu( $this );
			}
			return $this->fx_menu;
		}


		/**
		 * @return extension\fx_panels
		 */
		public function fx_panels(){
			if( !$this->fx_panels instanceof mmenu\extension\fx_panels ){
				$this->fx_panels = new mmenu\extension\fx_panels( $this );
			}
			return $this->fx_panels;
		}


		/**
		 * @return extension\listview
		 */
		public function listview(){
			if( !$this->listview instanceof mmenu\extension\listview ){
				$this->listview = new mmenu\extension\listview( $this );
			}
			return $this->listview;
		}


		/**
		 * @return extension\multiline
		 */
		public function multiline(){
			if( !$this->multiline instanceof mmenu\extension\multiline ){
				$this->multiline = new mmenu\extension\multiline( $this );
			}
			return $this->multiline;
		}


		/**
		 * @return extension\pagedim
		 */
		public function pagedim(){
			if( !$this->pagedim instanceof mmenu\extension\pagedim ){
				$this->pagedim = new mmenu\extension\pagedim( $this );
			}
			return $this->pagedim;
		}


		/**
		 * @return extension\popup
		 */
		public function popup(){
			if( !$this->popup instanceof mmenu\extension\popup ){
				$this->popup = new mmenu\extension\popup( $this );
			}
			return $this->popup;
		}


		/**
		 * @return extension\position
		 */
		public function position(){
			if( !$this->position instanceof mmenu\extension\position ){
				$this->position = new mmenu\extension\position( $this );
			}
			return $this->position;
		}


		/**
		 * @return extension\shadow
		 */
		public function shadow(){
			if( !$this->shadow instanceof mmenu\extension\shadow ){
				$this->shadow = new mmenu\extension\shadow( $this );
			}
			return $this->shadow;
		}


		/**
		 * @return extension\theme
		 */
		public function theme(){
			if( !$this->theme instanceof mmenu\extension\theme ){
				$this->theme = new mmenu\extension\theme( $this );
			}
			return $this->theme;
		}

	}