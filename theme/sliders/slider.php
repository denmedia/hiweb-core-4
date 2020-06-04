<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 23:08
	 */

	namespace theme\sliders;


	use hiweb\core\Strings;
	use theme\sliders;


	class slider{

		public $id;
		public $slides;
		public $root_classes = [ 'hiweb-theme-widget-slider', 'owl-carousel' ];
		public $full_height = true;
		private $aspect_ratio = '16-9';
		public $slide_interval = 8000;
		public $random = false;
		public $stop_on_hover = true;
		public $options = [];


		public function __construct( $id = null ){
			sliders::init();
			if( is_null( $id ) ) $this->id = strings::rand(); else $this->id = $id;
		}


		public function set_aspect_ration_16_9(){
			$this->full_height = false;
			$this->aspect_ratio = '16-9';
		}


		public function set_aspect_ration_2_1(){
			$this->full_height = false;
			$this->aspect_ratio = '2-1';
		}


		public function set_aspect_ration_3_1(){
			$this->full_height = false;
			$this->aspect_ratio = '3-1';
		}


		public function set_aspect_ration_4_1(){
			$this->full_height = false;
			$this->aspect_ratio = '4-1';
		}


		public function set_aspect_ration_4_3(){
			$this->full_height = false;
			$this->aspect_ratio = '4-3';
		}


		/**
		 * @return slide[]
		 */
		public function get_slides(){
			$R = is_array( $this->slides ) ? array_values( $this->slides ) : [];
			if( $this->random ) shuffle( $R );
			return $R;
		}


		/**
		 * @return slide
		 */
		public function add_slide(){
			$new_index = count( $this->get_slides() );
			$new_slide = new slide( $new_index );
			$this->slides[ $new_index ] = $new_slide;
			return $new_slide;
		}


		public function the(){
			if( $this->full_height ) $this->root_classes[] = 'full-height'; else {
				$this->root_classes[] = 'aspect-' . $this->aspect_ratio;
			}
			?>
			<div id="<?= $this->id ?>" class="<?= implode( ' ', $this->root_classes ) ?>" data-slide-interval="<?= $this->slide_interval ?>" data-slider-stop-hover="<?= $this->stop_on_hover ? '1' : '0' ?>" data-slider-options="<?= htmlentities( json_encode( $this->options ) ) ?>" style="visibility: hidden;">
				<?php
					foreach( $this->get_slides() as $slide ){
						$slide->the();
					}
				?>
			</div>
			<?php
		}

	}