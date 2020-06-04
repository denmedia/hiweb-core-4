<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 22:04
	 */

	namespace theme\widgets;


	use hiweb\arrays;
	use hiweb\images\image;
	use hiweb\strings;
	use theme\includes\frontend;
	use theme\includes\includes;


	class gallery{

		protected $id;
		/** @var image[] */
		protected $images = [];
		protected $thumbnail_size = [ 300, 200 ];
		protected $full_size = [ 1920, 1080 ];
		protected $hover_icon_class = 'fal fa-search-plus';


		public function __construct( $id = null ){
			if( !is_null( $id ) ) $this->id = $id; else $this->id = strings::rand();
		}


		/**
		 * @return string
		 */
		public function __toString(){
			return $this->get();
		}


		/**
		 * @param string|array $thumbnail_size - thumbnail|medium|large|full|[300,200]
		 * @return $this
		 */
		public function set_thumbnail_size( $thumbnail_size = 'thumbnail' ){
			$this->thumbnail_size = $thumbnail_size;
			return $this;
		}


		/**
		 * @return string|array
		 */
		public function get_thumbnail_size(){
			return $this->thumbnail_size;
		}


		/**
		 * @param string|array $full_size - thumbnail|medium|large|full|[300,200]
		 * @return $this
		 */
		public function set_full_size( $full_size = [ 1920, 1080 ] ){
			$this->full_size = $full_size;
			return $this;
		}


		/**
		 * @return string|array
		 */
		public function get_full_size(){
			return $this->full_size;
		}


		/**
		 * @param $attachmentIdOrPathOrUrl
		 * @return image
		 */
		public function add_image( $attachmentIdOrPathOrUrl ){
			$new_image = get_image( $attachmentIdOrPathOrUrl );
			if( $new_image->is_attachment_exists() ){
				$this->images[] = $new_image;
			}
			return $new_image;
		}


		/**
		 * @param $images
		 * @return $this
		 */
		public function add_images( $images ){
			if( is_array( $images ) ) foreach( $images as $image ){
				$this->add_image( $image );
			}
			return $this;
		}


		/**
		 * @return bool
		 */
		public function has_images(){
			return !arrays::is_empty( $this->images );
		}


		/**
		 * @return image[]
		 */
		public function get_images(){
			return $this->images;
		}


		/**
		 * @param string $icon_class
		 * @return $this
		 */
		public function set_hover_icon_class( $icon_class = 'fal fa-search-plus' ){
			$this->hover_icon_class = $icon_class;
			return $this;
		}


		/**
		 * @return string
		 */
		public function get_hover_icon_class(){
			return $this->hover_icon_class;
		}


		public function the(){
			includes::css( HIWEB_THEME_ASSETS_DIR . '/css/widget-gallery.min.css' );
			includes::fontawesome(false);
			if( !$this->has_images() ){
				?>
				<div class="hiweb-theme-module-gallery-empty jumbotron text-center">
					Нет ни одного изображения
				</div>
				<?php
			} else {
				frontend::fancybox();
				$wrap = bootstrap::wrap( $this->id . '-wrap' );
				$wrap->add_class( 'hiweb-theme-module-gallery' );
				$row = $wrap->add_row();
				foreach( $this->get_images() as $image ){
					$col = $row->add_col();
					$col->col_xl( 4 );
					$col->col_lg( 4 );
					$col->col_md( 6 );
					$col->col( 12 );
					$col->add_class( 'item-wrap' );
					ob_start();
					?>
					<a href="<?= $image->get_src( $this->full_size ) ?>" class="item" data-fancybox="<?= $this->id ?>">
						<div class="hover"><i class="<?= $this->hover_icon_class ?>"></i></div>
						<?= $image->html_picture( $this->thumbnail_size ) ?>
					</a>
					<?php
					$col->content( ob_get_clean() );
				}
				$wrap->the();
			}
		}


		public function get(){
			ob_start();
			$this->the();
			return ob_get_clean();
		}

	}