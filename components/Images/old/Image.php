<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11/12/2018
	 * Time: 00:16
	 */
	
	namespace hiweb\components\Images;
	
	
	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\hidden_methods;
	use WP_Post;
	
	
	/**
	 * Class image
	 * @package hiweb\images
	 */
	class Image{
		
		private $attach_id = 0;
		private $size_calculator;
		
		/** @var null|WP_Post */
		protected $wp_post = null;
		
		/** @var array */
		protected $attachment_meta_raw = null;
		/** @var array */
		protected $image_meta_raw = null;
		protected $attachment_sizes_raw = null;
		
		protected $path_attachment_by_upload_dir;
		
		/** @var string */
		protected $original_size_name = 'original';
		protected $size_original;
		
		/** @var Size[] */
		protected $sizes;
		/** @var Size[] */
		protected $sizes_by_pixels;
		/** @var array */
		protected $sizes_by_type;
		/** @var array */
		protected $sizes_by_dimension;
		/** @var array */
		protected $sizes_by_crop;
		protected $html_tags = [];
		
		
		use hidden_methods;
		
		
		public function __construct( $attachment_id ){
			$this->original_size_name = ImagesFactory::$original_size_name;
			$this->html_tags = new ArrayObject();
			$this->path_attachment_by_upload_dir = ltrim( str_replace( ImagesFactory::get_upload_dirs()->File()->get_path(), '', $this->get_original_src( true ) ), '/' );
			if( is_numeric( $attachment_id ) ){
				$this->attach_id = intval( $attachment_id );
				$this->_load_sizes_from_meta();
			}
		}
		
		
		/**
		 * @return string
		 */
		public function get_original_size_name(){
			return $this->original_size_name;
		}
		
		
		/**
		 * @return Size_Calculator
		 */
		public function size_calculator(){
			if( !$this->size_calculator instanceof Size_Calculator ){
				$this->size_calculator = new Size_Calculator( $this );
			}
			return $this->size_calculator;
		}
		
		
		/**
		 * @return array|bool|null|WP_Post
		 */
		public function get_wp_post(){
			if( !$this->wp_post instanceof WP_Post ){
				$this->wp_post = get_post( $this->attach_id );
			}
			return $this->wp_post;
		}
		
		
		/**
		 * @return bool
		 */
		public function is_attachment_exists(){
			return $this->get_wp_post() instanceof WP_Post;
		}
		
		
		/**
		 * @return array
		 */
		public function get_attachment_meta(){
			if( is_null( $this->attachment_meta_raw ) ){
				$this->attachment_meta_raw = [];
				if( $this->is_attachment_exists() ){
					$this->attachment_meta_raw = wp_get_attachment_metadata( $this->get_attachment_id() );
					if( !is_array( $this->attachment_meta_raw ) ) $this->attachment_meta_raw = [];
				}
			}
			return $this->attachment_meta_raw;
		}
		
		
		/**
		 * @return array
		 */
		public function get_image_meta_raw(){
			if( is_null( $this->image_meta_raw ) ){
				$this->image_meta_raw = [];
				if( array_key_exists( 'image_meta', $this->get_attachment_meta() ) ){
					$this->image_meta_raw = $this->attachment_meta_raw['image_meta'];
					if( !is_array( $this->image_meta_raw ) ) $this->image_meta_raw = [];
				}
			}
			return $this->image_meta_raw;
		}
		
		
		/**
		 * @return bool|Size
		 */
		public function get_size_original(){
			if( !$this->size_original instanceof Size ){
				$this->size_original = false;
				$attach_meta = $this->get_attachment_meta();
				if( array_key_exists( 'file', $attach_meta ) ){
					$file_original_path = ImagesFactory::get_upload_dirs()->File()->get_path() . '/' . $attach_meta['file'];
					if( strlen( $attach_meta['file'] ) > 3 ){
						$this->size_original = new Size( $file_original_path, $this );
					}
					else{
						//console::debug_error( __METHOD__ . ': файл не верный', $file_original_path );
					}
				}
			}
			return $this->size_original;
		}
		
		
		/**
		 * Возвращает папку с именем файла, относительно папки UPLOAD
		 * @return string
		 */
		public function get_path_attachment_by_upload_dir(){
			return $this->path_attachment_by_upload_dir;
		}
		
		
		/**
		 * load meta data and sizes from attachment data
		 */
		private function _load_sizes_from_meta( $check_for_optimize = true ){
			$this->sizes = [];
			if( $this->is_attachment_exists() ){
				$meta = $this->get_attachment_meta();
				if( $this->get_size_original() instanceof Size ){
					if( array_key_exists( 'sizes', $meta ) ){
						$this->attachment_sizes_raw = $meta['sizes'];
						if( is_array( $this->attachment_sizes_raw ) ) foreach( $this->attachment_sizes_raw as $size_name => $size_data ){
							if( array_key_exists( 'file', $size_data ) ){
								if( strlen( $size_data['file'] ) > 3 ){
									$this->_connect_size( $this->get_size_original()->File()->dirname() . '/' . $size_data['file'], $size_name );
								}
							}
						}
					}
				}
			}
		}
		
		
		/**
		 * @return bool|int
		 */
		private function update_image_sizes_meta(){
			$meta = $this->get_attachment_meta();
			$meta['sizes'] = [];
			foreach( $this->get_sizes() as $file_sizeName => $image_file ){
				if( $file_sizeName === $this->original_size_name ) continue;
				$meta['sizes'][ $file_sizeName ] = [
					'file' => $image_file->basename(),
					'width' => $image_file->width(),
					'height' => $image_file->height(),
					'mime' => $image_file->get_image_mime_type()
				];
			}
			return wp_update_attachment_metadata( $this->get_attachment_id(), $meta );
		}
		
		
		/**
		 * @param string|Size $pathOrSize
		 * @param string      $size_name
		 * @return Size
		 */
		private function _connect_size( $pathOrSize, $size_name = '' ){
			if( $pathOrSize instanceof Size ){
				$new_size = $pathOrSize;
			}
			elseif( is_string( $pathOrSize ) && trim( $pathOrSize ) != '' ){
				$new_size = new Size( $pathOrSize, $this, $size_name );
			}
			/** @var Size $new_size */
			$this->sizes[ $new_size->get_size_name() ] = $new_size;
			$this->sizes_by_crop[ $new_size->is_crop() ][ $new_size->get_dimension_size_string() ] = $new_size;
			$this->sizes_by_pixels[ $new_size->get_pixels() ][ $new_size->File()->extension() ] = $new_size;
			$this->sizes_by_dimension[ $new_size->width() . 'x' . $new_size->height() ][ $new_size->File()->extension() ] = $new_size;
			$this->sizes_by_type[ $new_size->File()->extension() ][ $new_size->width() . 'x' . $new_size->height() ] = $new_size;
			ksort( $this->sizes_by_pixels );
			return $new_size;
		}
		
		
		/**
		 * @return int|string
		 */
		public function get_attachment_id(){
			return $this->attach_id;
		}
		
		
		/**
		 * @return array|Size[]
		 */
		public function get_sizes(){
			return $this->sizes;
		}
		
		
		/**
		 * @return array
		 */
		public function get_sizes_by_crop(){
			$this->get_sizes();
			return $this->sizes_by_crop;
		}
		
		
		/**
		 * @return array
		 */
		public function get_sizes_by_pixels(){
			return $this->sizes_by_pixels;
		}
		
		
		/**
		 * @return array
		 */
		public function get_sizes_by_dimension(){
			return $this->sizes_by_dimension;
		}
		
		
		/**
		 * @return Size[]
		 */
		public function get_sizes_by_type(){
			return $this->sizes_by_type;
		}
		
		
		/**
		 * @return ArrayObject
		 */
		public function html_tags(){
			return $this->html_tags;
		}
		
		
		/**
		 * @return int
		 */
		public function width(){
			return $this->get_size_original() instanceof Size ? $this->get_size_original()->width() : 0;
		}
		
		
		/**
		 * @return int
		 */
		public function height(){
			return $this->get_size_original() instanceof Size ? $this->get_size_original()->height() : 0;
		}
		
		
		/**
		 * @return int
		 */
		public function aspect(){
			return $this->get_size_original() instanceof Size ? $this->get_size_original()->aspect() : 0;
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
			return get_the_title( $this->get_wp_post() );
		}
		
		
		/**
		 * @param bool $return_filtered
		 * @return string
		 */
		public function description( $return_filtered = true ){
			if( $this->is_attachment_exists() ){
				return $return_filtered ? $this->get_wp_post()->post_content_filtered : $this->get_wp_post()->post_content;
			}
			return '';
		}
		
		
		/**
		 * @return string
		 */
		public function caption(){
			if( $this->is_attachment_exists() ){
				return $this->get_wp_post()->post_excerpt;
			}
			return '';
		}
		
		
		/**
		 * @param string $dimensionsOrSizeName
		 * @param int    $resize_mod
		 * @return int
		 */
		public function is_size_dimension_exists( $dimensionsOrSizeName = 'thumbnail', $resize_mod = 1 ){
			///size to dimension
			if( is_string( $dimensionsOrSizeName ) && array_key_exists( $dimensionsOrSizeName, $this->get_sizes() ) ) return true;
			///
			$dimensions = $this->size_calculator()->get_dimensions_by_dimensionsOrSizeName( $dimensionsOrSizeName, $resize_mod );
			foreach( $this->get_sizes_by_dimension() as $size_str => $files ){
				if( $size_str == $this->size_calculator()->get_dimensions_size_string( $dimensions[0], $dimensions[1] ) ){
					return true;
				}
			}
			return false;
		}
		
		
		/**
		 * @param string $dimensionOrSizeName
		 * @param int    $resize_mod
		 * @param string $extension
		 * @return bool
		 */
		public function is_size_dimension_type_exists( $dimensionOrSizeName = 'thumbnail', $resize_mod = 1, $extension = 'jpg' ){
			if( !$this->is_attachment_exists() ) return false;
			$dimensions = $this->size_calculator()->get_dimensions_by_dimensionsOrSizeName( $dimensionOrSizeName, $resize_mod );
			$dimension_str = $dimensions[0] . 'x' . $dimensions[1];
			if( array_key_exists( $dimension_str, $this->get_sizes_by_dimension() ) && array_key_exists( $extension, $this->get_sizes_by_dimension()[ $dimension_str ] ) ){
				/** @var Size $size */
				$size = $this->get_sizes_by_dimension()[ $dimension_str ][ $extension ];
				if( $size instanceof Size ){
					return $size->File()->is_readable();
				}
			}
			return false;
		}
		
		
		/**
		 * @param string|array $dimensionOrSizeName
		 * @param int          $resize_mod
		 * @param bool         $make_size_file_if_not_exists
		 * @param array        $extension_priority
		 * @return bool|Size
		 */
		public function get_size_by_dimension( $dimensionOrSizeName = 'thumbnail', $resize_mod = 1, $make_size_file_if_not_exists = true, $extension_priority = [] ){
			if( !$this->is_attachment_exists() ) return false;
			///
			if( is_string( $extension_priority ) && strlen( $extension_priority ) > 0 ) $extension_priority = [ $extension_priority ];
			if( !is_array( $extension_priority ) || count( $extension_priority ) == 0 ) $extension_priority = ImagesFactory::$extension_priority;
			///
			$dimensions = $this->size_calculator()->get_dimensions_by_dimensionsOrSizeName( $dimensionOrSizeName, $resize_mod );
			$dimension_str = $dimensions[0] . 'x' . $dimensions[1];
			///
			$extension_first = reset( $extension_priority );
			///
			$R = false;
			///
			if( !$this->is_size_dimension_type_exists( $dimensionOrSizeName, $resize_mod, $extension_first ) && $make_size_file_if_not_exists ){
				///MAKE NEW FILE
				$new_path = $this->get_size_original()->File()->dirname() . '/' . $this->get_size_original()->File()->filename() . '-' . $dimension_str . '.' . $this->get_size_original()->File()->extension();
				$new_size = new Size( $new_path, $this, '', $dimensions[0], $dimensions[1] );
				$this->_connect_size( $new_size );
				$R = $new_size;
			}
			elseif( $this->is_size_dimension_exists( $dimensionOrSizeName, $resize_mod ) ){
				///GET EXISTS
				foreach( $extension_priority as $extension ){
					if( array_key_exists( $extension, $this->sizes_by_dimension[ $dimension_str ] ) ){
						$R = $this->sizes_by_dimension[ $dimension_str ][ $extension ];
						break;
					}
				}
			}
			else{
				///FIND SIMILAR FILE
				$sizes_by_pixel = $this->get_sizes_by_pixels();
				$sizes_by_pixel[ $this->get_size_original()->get_pixels() ][ $this->get_size_original()->File()->extension() ] = $this->get_size_original();
				if( $resize_mod >= 0 ){
					ksort( $sizes_by_pixel );
				}
				elseif( $resize_mod < 0 ){
					krsort( $sizes_by_pixel );
				}
				/** @var Size[] $sizes */
				$dimensions_pixels = $dimensions[0] * $dimensions[1];
				
				foreach( $sizes_by_pixel as $pixels => $sizes ){
					if( ( $resize_mod >= 0 && $pixels >= $dimensions_pixels ) || ( $resize_mod < 0 && $pixels <= $dimensions_pixels ) ){
						
						foreach( $extension_priority as $extension ){
							if( array_key_exists( $extension, $sizes ) ){
								if( ( $resize_mod >= 0 && $sizes[ $extension ]->width() >= $dimensions[0] && $sizes[ $extension ]->height() >= $dimensions[1] ) || ( $resize_mod <= 0 && $sizes[ $extension ]->width() <= $dimensions[0] && $sizes[ $extension ]->height() <= $dimensions[1] ) ){
									$R = $sizes[ $extension ];
									break 2;
								}
							}
						}
						$R = reset( $sizes );
					}
				}
			}
			///
			if( !$R instanceof Size ) $R = $this->get_size_original();
			///Make image file if not exists
			if( $R instanceof Size && $make_size_file_if_not_exists ){
				if( !$R->File()->is_exists() || abs( $R->File()->Image()->width() - $R->width() ) > 2 ){
					$B = $R->_make( ImagesFactory::$default_quality );
					///
					if( $R->File()->is_exists() ) $this->update_image_sizes_meta();
					else{
						//Console::debug_error( __METHOD__ . ': error while create new image file for size [' . $R->get_size_name() . ']', $B );
					}
				}
			}
			///
			return $R;
		}
		
		///////////////
		
		
		/**
		 * @param string   $dimensionOrSizeName
		 * @param int|bool $resize_mod
		 * @param array    $extension_priority
		 * @param bool     $make_new_file
		 * @return string|false
		 */
		public function get_src( $dimensionOrSizeName = 'thumbnail', $resize_mod = 1, $extension_priority = [], $make_new_file = true ){
			$R = $this->get_size_by_dimension( $dimensionOrSizeName, $resize_mod, $make_new_file, $extension_priority );
			return ( $R instanceof Size ) ? $R->Url()->get() : false;
		}
		
		
		/**
		 * @param string $dimensionOrSizeName
		 * @param int    $resize_mod
		 * @param array  $extension_priority
		 * @param bool   $make_new_file
		 * @return string
		 */
		public function get_path( $dimensionOrSizeName = '', $resize_mod = 1, $extension_priority = [ 'png', 'jpg', 'gif', 'webp', 'jp2' ], $make_new_file = true ){
			$R = $this->get_size_by_dimension( $dimensionOrSizeName, $resize_mod, $make_new_file, $extension_priority );
			return ( $R instanceof Size ) ? $R->File()->get_path() : false;
		}
		
		
		/**
		 * @param string $dimensionOrSizeName
		 * @param int    $resize_mod
		 * @param array  $extension_priority
		 * @param bool   $make_new_file
		 * @return string
		 */
		public function get_path_relative( $dimensionOrSizeName = '', $resize_mod = 1, $extension_priority = [ 'png', 'jpg', 'gif', 'webp', 'jp2' ], $make_new_file = true ){
			$R = $this->get_size_by_dimension( $dimensionOrSizeName, $resize_mod, $make_new_file, $extension_priority );
			return ( $R instanceof Size ) ? $R->get_path_relative() : false;
		}
		
		
		/**
		 * @param $return_path
		 * @return string | false
		 */
		public function get_original_src( $return_path = false ){
			return $this->get_size_original() instanceof Size ? ( $return_path ? $this->get_size_original()->File()->get_path() : $this->get_size_original()->Url()->get() ) : false;
		}
		
		
		/**
		 * @param string $dimensionsOrSizeName
		 * @param int    $resize_mod
		 * @param array  $attr_picture
		 * @param array  $attr_img
		 * @param bool   $make_new_file
		 * @return string
		 */
		public function html_picture( $dimensionsOrSizeName = 'thumbnail', $resize_mod = 1, $attr_picture = [], $attr_img = [], $make_new_file = true ){
			$dimensions = $this->size_calculator()->get_dimensions_by_dimensionsOrSizeName( $dimensionsOrSizeName, $resize_mod );
			if( !$this->is_attachment_exists() ){
				$size = is_array( $dimensions ) ? ' width="' . $this->width() . '" height="' . $this->height() . '"' : '';
				return '<img src="' . ImagesFactory::get_default_src() . '" ' . $size . '/>';
			}
			else{
				$attr_picture = new ArrayObject( $attr_picture );
				///Collect main files
				/** @var array $sizes */
				$sources = [];
				$img = '';
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
		 * @param int    $resize_mod
		 * @param array  $attr
		 * @param array  $extension_priority
		 * @param bool   $make_new_file
		 * @return mixed|string
		 */
		public function html_img( $dimensionsOrSizeName = 'thumbnail', $resize_mod = 1, $attr = [], $extension_priority = [], $make_new_file = true ){
			$dimensions = $this->size_calculator()->get_dimensions_by_dimensionsOrSizeName( $dimensionsOrSizeName, $resize_mod );
			if( !$this->is_attachment_exists() ){
				$size = ( is_array( $dimensions ) && $this->width() > 0 && $this->height() > 0 ) ? ' width="' . $this->width() . '" height="' . $this->height() . '"' : '';
				return '<img src="' . ImagesFactory::get_default_src() . '" ' . $size . '/>';
			}
			else{
				$attributes = new ArrayObject();
				$size_current = $this->get_size_by_dimension( $dimensionsOrSizeName, $resize_mod, $make_new_file, $extension_priority );
				$attributes->push( 'src', $size_current->Url()->get() );
				if( $size_current->width() > 0 ) $attributes->push( 'width', $size_current->width() );
				if( $size_current->height() > 0 ) $attributes->push( 'height', $size_current->height() );
				///
				$sizes = [];
				$srcset = [ $size_current->Url()->get() . ' ' . $size_current->width() . 'w' ];
				$sizes[] = "(max-width: {$size_current->width()}px) " . ceil( $size_current->width() * .9 ) . "px";
				///size divide 2
				$size_d2 = $this->get_size_by_dimension( [ $size_current->width() / 1.8, $size_current->height() / 1.8 ], - 1, false, $extension_priority );
				if( $size_d2->File()->is_readable() && $size_d2->get_pixels() * ( 1.5 ^ 2 ) < $size_current->get_pixels() ){
					$srcset[] = $size_d2->Url()->get() . ' ' . $size_d2->width() . 'w';
					$sizes[] = "(max-width: {$size_d2->width()}px) " . ceil( $size_d2->width() * .9 ) . "px";
				}
				///size x 2
				$size_x2 = $this->get_size_by_dimension( [ $size_current->width() * 1.8, $size_current->height() * 1.8 ], 1, false, $extension_priority );
				if( $size_x2->File()->is_readable() && $size_x2->get_pixels() / 1.5 > $size_current->get_pixels() ){
					$srcset[] = $size_x2->Url()->get() . ' ' . $size_x2->width() . 'w';
					$sizes[] = "(max-width: {$size_x2->width()}px) " . ceil( $size_x2->width() * .9 ) . "px";
				}
				$sizes[] = $size_current->width() . 'px';
				if( count( $srcset ) > 0 ) $attributes->push( 'srcset', implode( ', ', $srcset ) );
				if( count( $sizes ) > 0 ) $attributes->push( 'sizes', implode( ', ', $sizes ) );
				///
				if( $this->alt() != '' ) $attributes->push( 'alt', $this->alt() );
				$attributes->merge( $attr );
				///
				$attributes = apply_filters( '\hiweb\images\image::html-attributes', $attributes, $this );
				return apply_filters( '\hiweb\images\image::html-return', "<img {$attributes->get_param_html_tags()}/>", $this );
			}
		}
		
		
		/**
		 * @param string $dimensionsOrSizeName
		 * @param int    $resize_mod
		 * @param array  $attr
		 * @param array  $extension_priority
		 * @param bool   $make_new_file
		 * @return string
		 */
		public function html( $dimensionsOrSizeName = 'thumbnail', $resize_mod = 1, $attr = [], $extension_priority = [], $make_new_file = true ){
			return $this->html_img( $dimensionsOrSizeName, $resize_mod, $attr, $extension_priority, $make_new_file );
		}
		
	}