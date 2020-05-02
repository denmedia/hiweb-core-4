<?php
	
	namespace hiweb\components\Images;
	
	
	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	use stdClass;
	use WP_Post;
	
	
	class Image{
		
		use hidden_methods;
		
		
		/**
		 * @var int
		 */
		protected $attachment_ID;
		protected $file;
		protected $width = 0;
		protected $height = 0;
		protected $aspect = 0;
		
		
		public function __construct( $attachment_ID = 0 ){
			$this->attachment_ID = intval( $attachment_ID );
		}
		
		
		/**
		 * @return WP_Post
		 */
		public function WP_Post(){
			return CacheFactory::get( $this->attachment_ID, __METHOD__, function(){
				$test_wp_post = get_post( $this->attachment_ID );
				if( !$test_wp_post instanceof WP_Post ){
					return new WP_Post( new stdClass() );
				}
				return $test_wp_post;
			} )->get_value();
		}
		
		
		/**
		 * @return bool
		 */
		public function is_attachment_exists(){
			return $this->attachment_ID > 0 && $this->WP_Post()->post_type == 'attachment';
		}
		
		
		/**
		 * @return int
		 */
		public function get_attachment_ID(){
			return $this->attachment_ID;
		}
		
		
		/**
		 * @return stdClass
		 */
		public function get_attachment_meta(){
			return CacheFactory::get( $this->attachment_ID, __METHOD__, function(){
				$R = (object)[];
				if( $this->attachment_ID > 0 ){
					$R = (object)wp_get_attachment_metadata( $this->get_attachment_id() );
				}
				return $R;
			} )->get_value();
		}
		
		
		public function get_image_meta(){
			return CacheFactory::get( $this->attachment_ID, __METHOD__, function(){
				if( property_exists( $this->get_attachment_meta(), 'image_meta' ) ){
					return (object)$this->get_attachment_meta()->image_meta;
				}
				return (object)[];
			} )->get_value();
		}
		
		
		/**
		 * @return Path
		 */
		public function Path(){
			if( $this->file == '' && property_exists( $this->get_attachment_meta(), 'file' ) ){
				if( property_exists( $this->get_attachment_meta(), 'original_image' ) ){
					$file_name = dirname( $this->get_attachment_meta()->file ) . '/' . $this->get_attachment_meta()->original_image;
				}
				else{
					$file_name = $this->get_attachment_meta()->file;
				}
				$this->file = ( (object)wp_upload_dir() )->basedir . '/' . $file_name;
			}
			return PathsFactory::get( $this->file );
		}
		
		
		/**
		 * @return Image_Sizes
		 */
		public function Sizes(){
			return CacheFactory::get( $this->attachment_ID, __METHOD__, function(){
				return new Image_Sizes( $this );
			} )->get_value();
		}
		
		
		/**
		 * Return TRUE, if file is exists
		 * @return bool
		 */
		public function is_exists(){
			return $this->is_attachment_exists() && $this->Path()->File()->is_exists();
		}
		
		
		/**
		 * @return mixed
		 */
		public function width(){
			if( $this->width == 0 && property_exists( $this->get_attachment_meta(), 'width' ) ){
				$this->width = $this->get_attachment_meta()->width;
			}
			return $this->width;
		}
		
		
		/**
		 * @return mixed
		 */
		public function height(){
			if( $this->height == 0 && property_exists( $this->get_attachment_meta(), 'height' ) ){
				$this->height = $this->get_attachment_meta()->height;
			}
			return $this->height;
		}
		
		
		/**
		 * @return float|int
		 */
		public function aspect(){
			if( $this->aspect == 0 && $this->height() > 0 ){
				$this->aspect = $this->width() / $this->height();
			}
			return $this->aspect;
		}
		
		
		/**
		 * @return bool|mixed|string
		 */
		public function get_mime_type(){
			return $this->Path()->Image()->get_mime_type();
		}
		
		/**
		 * @return string
		 */
		public function alt(){
			return (string)get_post_meta( $this->get_attachment_id(), '_wp_attachment_image_alt', true );
		}
		
		
		/**
		 * @return mixed
		 */
		public function title(){
			return get_the_title( $this->WP_Post() );
		}
		
		
		/**
		 * @param bool $return_filtered
		 * @return string
		 */
		public function description( $return_filtered = true ){
			if( $this->is_attachment_exists() ){
				return $return_filtered ? $this->WP_Post()->post_content_filtered : $this->WP_Post()->post_content;
			}
			return '';
		}
		
		
		/**
		 * @return string
		 */
		public function caption(){
			if( $this->is_attachment_exists() ){
				return $this->WP_Post()->post_excerpt;
			}
			return '';
		}
		
		
		/**
		 * @return bool|int
		 */
		public function _update_image_sizes_meta(){
			$meta = (array)$this->get_attachment_meta();
			$meta['sizes'] = [];
			foreach( $this->Sizes()->get_sizes() as $size_name => $Image_Size ){
				$meta['sizes'][ $size_name ] = [
					'file' => $Image_Size->Path()->File()->filename(),
					'width' => $Image_Size->width(),
					'height' => $Image_Size->height(),
					'mime' => $Image_Size->Path()->Image()->get_mime_type()
				];
			}
			return wp_update_attachment_metadata( $this->get_attachment_id(), $meta );
		}
		
		
		/**
		 * @param      $size
		 * @param bool $make_new_file
		 * @return string
		 */
		public function get_src( $size, $make_new_file = true ){
			return $this->is_exists() ? $this->Sizes()->get( $size, $make_new_file )->Path()->get_url() : ImagesFactory::get_default_src();
		}
		
		
		/**
		 * @param      $size
		 * @param bool $make_new_file
		 * @return string
		 */
		public function get_path( $size, $make_new_file = true ){
			return $this->Sizes()->get( $size, $make_new_file )->get_file_path();
		}
		
		
		/**
		 * @param      $size
		 * @param bool $make_new_file
		 * @return string
		 */
		public function get_path_relative( $size, $make_new_file = true ){
			return $this->Sizes()->get( $size, $make_new_file )->Path()->get_path_relative();
		}
		
		
		/**
		 * Return original URL or Absolute PATH
		 * @param bool $return_path
		 * @return string
		 */
		public function get_original_src( $return_path = false ){
			return $return_path ? $this->Path()->get_absolute_path() : $this->Path()->get_url();
		}
		
		
		/**
		 * @param string $dimensionsOrSizeName
		 * @param array  $attr_picture
		 * @param array  $attr_img
		 * @param bool   $make_new_file
		 * @return string
		 */
		public function html_picture( $dimensionsOrSizeName = 'thumbnail', $attr_picture = [], $attr_img = [], $make_new_file = true ){
			if( !$this->is_attachment_exists() ){
				if( is_array( $dimensionsOrSizeName ) ) $dimensions = get_wp_register_size_from_dimension( $dimensionsOrSizeName[0], $dimensionsOrSizeName[1] );
				else $dimensions = $dimensionsOrSizeName;
				$size = is_array( $dimensions ) ? ' width="' . $dimensions[0] . '" height="' . $dimensions[1] . '"' : '';
				return '<img src="' . ImagesFactory::get_default_src() . '" ' . $size . '/>';
			}
			else{
				$attr_picture = new ArrayObject( $attr_picture );
				///Collect main files
				/** @var array $sizes */
				$sources = [];
				///
				foreach( $this->Sizes()->get_search( $dimensions ) as $image_Size ){
				
				}
				///
				foreach( $this->get_sizes_by_type() as $extension => $sizes ){
					$img_attributes = new ArrayObject();
					$size_current = $this->get_size_by_dimension( $dimensionsOrSizeName, $resize_mod, $make_new_file, $extension );
					if( $size_current->File()->extension() != $extension || !$size_current->File()->is_readable() || ( $this->get_size_original()->File()->extension() != $extension && $size_current->is_classic_file_type() ) ) continue;
					///
					$sizes = [];
					$srcset = [ $size_current->Url()->get() . ' ' . $size_current->width() . 'w' ];
					$sizes[] = "(max-width: {$size_current->width()}px) " . ceil( $size_current->width() * .9 ) . "px";
					///size divide 2
					$size_d2 = $this->get_size_by_dimension( [ $size_current->width() / 1.8, $size_current->height() / 1.8 ], - 1, false, $extension );
					if( $size_d2->File()->is_readable() && $size_d2->get_pixels() * ( 1.5 ^ 2 ) < $size_current->get_pixels() ){
						$srcset[] = $size_d2->Url()->get() . ' ' . $size_d2->width() . 'w';
						$sizes[] = "(max-width: {$size_d2->width()}px) " . ceil( $size_d2->width() * .9 ) . "px";
					}
					if( !wp_is_mobile() ){
						///size x 2
						$size_x2 = $this->get_size_by_dimension( [ $size_current->width() * 1.8, $size_current->height() * 1.8 ], 1, false, $extension );
						if( $size_x2->File()->is_readable() && $size_x2->get_pixels() / 1.5 > $size_current->get_pixels() && $size_x2->get_pixels() <= [ 1920 * 1920 ] ){
							$srcset[] = $size_x2->Url()->get() . ' ' . $size_x2->width() . 'w';
							$sizes[] = "(max-width: {$size_x2->width()}px) " . ceil( $size_x2->width() * .9 ) . "px";
						}
					}
					$sizes[] = $size_current->width() . 'px';
					///
					if( $this->get_size_original()->File()->extension() != $extension ){
						if( count( $srcset ) > 0 ) $img_attributes->push( 'srcset', implode( ', ', $srcset ) );
						if( count( $sizes ) > 0 ) $img_attributes->push( 'sizes', implode( ', ', $sizes ) );
						$img_attributes->push( 'type', $size_current->File()->Image()->get_mime_type() );
						$img_attributes = apply_filters( '\hiweb\images\image::html_picture-attributes', $img_attributes, $this );
						$img_attributes = apply_filters( '\hiweb\images\image::html_picture-source_attributes', $img_attributes, $this );
						$sources[] = "<source {$img_attributes->get_param_html_tags()}/>";
					}
					else{
						$img_attributes->push( 'src', $size_current->Url()->get() );
						$img_attributes->push( 'width', $size_current->width() );
						$img_attributes->push( 'height', $size_current->height() );
						if( count( $srcset ) > 0 ) $img_attributes->push( 'srcset', implode( ', ', $srcset ) );
						if( count( $sizes ) > 0 ) $img_attributes->push( 'sizes', implode( ', ', $sizes ) );
						if( $this->alt() != '' ) $img_attributes->push( 'alt', $this->alt() );
						$img_attributes->merge( $attr_img );
						$img_attributes = apply_filters( '\hiweb\images\image::html_picture-attributes', $img_attributes, $this );
						$img_attributes = apply_filters( '\hiweb\images\image::html_picture-img_attributes', $img_attributes, $this );
						$img = apply_filters( '\hiweb\images\image::html_picture-img_data', "<img {$img_attributes->get_param_html_tags()}/>", $this, $img_attributes );
					}
				}
				$sources = apply_filters( '\hiweb\images\image::html_picture-sources', $sources, $this );
				$sources = implode( '', $sources );
				$sources = $sources == '' ? '' : apply_filters( '\hiweb\images\image::html_picture-ie9escape', "<!--[if IE 9]><video style=\"display: none;\"><![endif]-->{$sources}<!--[if IE 9]></video><![endif]-->", $this );
				$R = apply_filters( '\hiweb\images\image::html_picture-return', "<picture {$attr_picture->get_param_html_tags()}>{$sources}{$img}</picture>", $this );
				return $R;
			}
		}
		
		
		/**
		 * @param string $dimensionsOrSizeName
		 * @param array  $attributes
		 * @param bool   $make_new_file
		 * @return mixed|string
		 */
		public function html_img( $dimensionsOrSizeName = 'thumbnail', $attributes = [], $make_new_file = true ){
			$size_current = $this->Sizes()->get( $dimensionsOrSizeName, $make_new_file );
			$attributes = new ArrayObject( $attributes );
			$attributes->push( 'width', $size_current->width() );
			$attributes->push( 'height', $size_current->height() );
			if( !$this->is_attachment_exists() ){
				$attributes->push( 'src', ImagesFactory::get_default_src() );
				return '<img ' . $attributes->get_param_html_tags() . '/>';
			}
			else{
				$attributes->push( 'src', $size_current->Path()->get_url() );
				$limit = 3;
				$srcset = [];
				foreach( $this->Sizes()->get_search( $dimensionsOrSizeName, 1, 0 ) as $image_Size ){
					if( $limit < 1 ) break;
					if(!$image_Size->Path()->File()->is_exists()) continue;
					$limit --;
					$srcset[] = $image_Size->Path()->get_url() . ' ' . $image_Size->width() . "w\n";
				}
				$attributes->push( 'srcset', join( ', ', $srcset ) );
				return '<img ' . $attributes->get_param_html_tags() . '/>';
			}
		}
		
		
		/**
		 * @param string $dimensionsOrSizeName
		 * @param array  $attributes
		 * @param bool   $make_new_file
		 * @return string
		 */
		public function html( $dimensionsOrSizeName = 'thumbnail', $attributes = [], $make_new_file = true ){
			return $this->html_img( $dimensionsOrSizeName,$attributes,$make_new_file );
		}
		
		
	}