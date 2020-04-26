<?php
	
	namespace hiweb\components\Images;
	
	
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\hidden_methods;
	
	
	class Image_Sizes{
		
		use hidden_methods;
		
		
		protected $Image;
		protected $sizes;
		
		
		public function __construct( Image $Image ){
			$this->Image = $Image;
		}
		
		
		/**
		 * @return Image_Size[]
		 */
		public function get_sizes(){
			if( !is_array( $this->sizes ) ){
				$this->sizes = [];
				if( property_exists( $this->Image->get_attachment_meta(), 'sizes' ) && is_array( $this->Image->get_attachment_meta()->sizes ) ){
					foreach( $this->Image->get_attachment_meta()->sizes as $size_name => $size_raw ){
						$this->sizes[ $size_name ] = new Image_Size( $this->Image, $size_raw, $size_name );
					}
				}
			}
			return $this->sizes;
		}
		
		
		/**
		 * @param string $size_name
		 * @param array  $size_raw
		 * @return Image_Size
		 */
		private function get_dummy_size( $size_name = '', $size_raw = [] ){
			return CacheFactory::get( $this->Image->get_attachment_ID(), __METHOD__, function(){
				return new Image_Size( $this->Image, (object)func_get_arg(1), func_get_arg( 0 ) );
			}, [ $size_name, $size_raw ] )->get_value();
		}
		
		
		/**
		 * @param      $size
		 * @param bool $make_if_not_exists
		 * @return Image_Size
		 */
		public function get( $size, $make_if_not_exists = true ){
			$R = false;
			$size_name = '';
			///PREPARE SIZE NAME
			if( is_array( $size ) ){
				$size = [ $size[0], isset( $size[1] ) ? $size[1] : $size[0], isset( $size[2] ) ? $size[2] : 1 ] + $size;
				$size[0] = (int)$size[0];
				$size[1] = (int)$size[1];
				$size[2] = (int)$size[2];
				if( $size[0] < 2 ) $size[0] = 8;
				if( $size[1] < 2 ) $size[1] = 8;
				if( $size[2] < - 1 || $size[2] > 1 ) $size[2] = 1;
				//make new size name
				$size_name = $size[0] . 'x' . $size[1];
			}
			else if( is_string( $size ) || ( is_array( $size ) && !is_numeric( reset( $size ) ) && is_string( reset( $size ) ) ) ){
				if( is_array( $size ) ) $size_name = reset( $size );
				else $size_name = $size;
			}
			///
			if( $this->Image->is_exists() ){
				list( $size[0], $size[1] ) = get_image_size_calculate_from_dimension( $size[0], $size[1], $size[2], $this->Image->width(), $this->Image->height() );
				///return by WP SIZE NAME
				if( array_key_exists( $size_name, $this->get_sizes() ) ){
					$R = $this->get_sizes()[ $size_name ];
				}
				else{
					$dimension = get_wp_register_dimension_from_register_size( $size_name );
					if( is_array( $dimension ) ){
						$this->sizes[ $size_name ] = new Image_Size( $this->Image, [ 'width' => $dimension[0], 'height' => $dimension[1], 'crop' => $dimension[2] ], $size_name );
						$R = $this->sizes[ $size_name ];
					}
				}
				///return by DIMENSION
				if( is_numeric( $size ) ){
					$size = [ intval( $size ), intval( $size ), 1 ];
				}
				if( is_array( $size ) ){
					$R = $this->sizes[ $size_name ] = new Image_Size( $this->Image, (object)[ 'file' => '', 'width' => $size[0], 'height' => $size[1], 'crop' => $size[2] ], $size_name );
				}
				if( $make_if_not_exists && $R instanceof Image_Size && !$R->is_exists() ) $R->make_file();
			}
			if( !$R instanceof Image_Size ){
				///return dummy Image_Size
				return $this->get_dummy_size( $size_name, ['width' => $size[0], 'height' => $size[1], 'crop' => $size[2]] );
			}
			return $R;
		}
		
		
		/**
		 * @param      $dimensionOrSizeName
		 * @param bool $more_that
		 * @param bool $less_that
		 * @return Image_Size[]
		 */
		public function get_search( $dimensionOrSizeName, $more_that = true, $less_that = false ){
			$dimension = $dimensionOrSizeName;
			if( is_string( $dimensionOrSizeName ) ){
				$dimension = get_wp_register_dimension_from_register_size( $dimensionOrSizeName );
			}
			$src_pixel = $dimension[0] * $dimension[1];
			$src_aspect = $dimension[0] / $dimension[1];
			$sizes_by_delta = [];
			foreach( $this->get_sizes() as $size_name => $image_Size ){
				if( $size_name == '' || $image_Size->aspect() == 0 ) continue;
				$delta = ( ( $image_Size->width() * $image_Size->height() ) - $src_pixel ) * ( $src_aspect / $image_Size->height() );
				if( ( $more_that && $delta >= 0 ) || ( $less_that && $delta <= 0 ) ){
					$sizes_by_delta[ abs( $delta ) ] = $image_Size;
				}
			}
			ksort( $sizes_by_delta );
			return $sizes_by_delta;
		}
		
		
	}