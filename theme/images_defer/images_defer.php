<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05/12/2018
	 * Time: 19:22
	 */

	namespace theme;


	use hiweb\components\Images\ImagesFactory;
	use hiweb\core\hidden_methods;
	use hiweb\core\Paths\PathsFactory;
	use theme\html_layout\tags\head;
	use theme\includes\frontend;


	class images_defer{

		static $media_view_template_ob_start = false;
		static $media_view_template_post;

		use hidden_methods;


		static function init(){
			require_once __DIR__ . '/hooks.php';
			frontend::js( __DIR__ . '/images_defer.min.js', frontend::jquery() );
			$css_content = PathsFactory::get_file( __DIR__ . '/images_defer.min.css' )->get_content( '' );
			if( $css_content != '' ){
				$css_content = str_replace( '../../hiweb-core-3/assets', HIWEB_URL_ASSETS, $css_content );
				head::add_html_addition( '<style type="text/css" data-defer-inline-styles>' . $css_content . '</style>' );
			}
		}


		/**
		 * @param $content
		 * @return mixed
		 */
		static function _add_filter_the_content( $content ){
			if( !preg_match_all( '/<img [^>]+>/', $content, $matches ) ){
				return $content;
			}

			$selected_images = $attachment_ids = [];

			foreach( $matches[0] as $image ){
				if( false === strpos( $image, ' srcset=' ) && preg_match( '/wp-image-([0-9]+)/i', $image, $class_id ) && ( $attachment_id = absint( $class_id[1] ) ) ){

					/*
					 * If exactly the same image tag is used more than once, overwrite it.
					 * All identical tags will be replaced later with 'str_replace()'.
					 */
					$selected_images[ $image ] = $attachment_id;
					// Overwrite the ID when the same image is included more than once.
					$attachment_ids[ $attachment_id ] = true;
				}
			}

			if( count( $attachment_ids ) > 1 ){
				/*
				 * Warm the object cache with post and meta information for all found
				 * images to avoid making individual database calls.
				 */
				_prime_post_caches( array_keys( $attachment_ids ), false, true );
			}

			foreach( $selected_images as $image => $attachment_id ){
				$image_meta = wp_get_attachment_metadata( $attachment_id );
				$content = str_replace( $image, self::_wp_image_add_srcset_and_sizes( $image, $image_meta, $attachment_id ), $content );
			}

			return $content;
		}


		/**
		 * @param $image
		 * @param $image_meta
		 * @param $attachment_id
		 * @return string|string[]|null
		 */
		static function _wp_image_add_srcset_and_sizes( $image, $image_meta, $attachment_id ){
			// Ensure the image meta exists.
			if( empty( $image_meta['sizes'] ) ){
				return $image;
			}

			$image_src = preg_match( '/src="([^"]+)"/', $image, $match_src ) ? $match_src[1] : '';
			[ $image_src ] = explode( '?', $image_src );

			// Return early if we couldn't get the image source.
			if( !$image_src ){
				return $image;
			}

			// Bail early if an image has been inserted and later edited.
			if( preg_match( '/-e[0-9]{13}/', $image_meta['file'], $img_edit_hash ) && strpos( wp_basename( $image_src ), $img_edit_hash[0] ) === false ){

				return $image;
			}

			$width = preg_match( '/ width="([0-9]+)"/', $image, $match_width ) ? (int)$match_width[1] : 0;
			$height = preg_match( '/ height="([0-9]+)"/', $image, $match_height ) ? (int)$match_height[1] : 0;

			if( !$width || !$height ){
				/*
				 * If attempts to parse the size value failed, attempt to use the image meta data to match
				 * the image file name from 'src' against the available sizes for an attachment.
				 */
				$image_filename = wp_basename( $image_src );

				if( $image_filename === wp_basename( $image_meta['file'] ) ){
					$width = (int)$image_meta['width'];
					$height = (int)$image_meta['height'];
				} else {
					foreach( $image_meta['sizes'] as $image_size_data ){
						if( $image_filename === $image_size_data['file'] ){
							$width = (int)$image_size_data['width'];
							$height = (int)$image_size_data['height'];
							break;
						}
					}
				}
			}

			if( !$width || !$height ){
				return $image;
			}

			$size_array = [ $width, $height ];
			$srcset = wp_calculate_image_srcset( $size_array, $image_src, $image_meta, $attachment_id );

			if( $srcset ){
				// Check if there is already a 'sizes' attribute.
				$sizes = strpos( $image, ' sizes=' );

				if( !$sizes ){
					$sizes = wp_calculate_image_sizes( $size_array, $image_src, $image_meta, $attachment_id );
				}
			}

			if( $srcset && $sizes ){
				// Format the 'srcset' and 'sizes' string and escape attributes.
				$attr = sprintf( ' data-srcset-defer="%s"', esc_attr( $srcset ) );

				if( is_string( $sizes ) ){
					$attr .= sprintf( ' sizes="%s"', esc_attr( $sizes ) );
				}
				// Add 'srcset' and 'sizes' attributes to the image markup.
				$image = preg_replace( '/<img ([^>]+?)[\/ ]*>/', '<img $1' . $attr . ' />', $image );
				$image = preg_replace( '/src="([^"]+)"/', 'src="' . HIWEB_URL_ASSETS . '/img/image-loading.svg" data-src-defer="$1"', $image );
			}

			return $image;
		}


		/**
		 * @param $sizes
		 * @param $size
		 * @param $image_src
		 * @param $image_meta
		 * @param $attachment_id
		 * @return string
		 */
		static function _add_filter_wp_calculate_image_sizes( $sizes, $size, $image_src, $image_meta, $attachment_id ){
			if( (int)$attachment_id > 0 ){
				$image = ImagesFactory::get( $attachment_id );
				$max_width = $size[0] * 1.5;
				$size_x2 = $image->sizes()->get( [ $max_width, $max_width,1 ], false );
				$sizes = "(max-width: {$size_x2->width()}px) 100vw, {$size_x2->width()}px";
			}
			return $sizes;
		}


		/**
		 * @param $sources
		 * @param $size_array
		 * @param $image_src
		 * @param $image_meta
		 * @param $attachment_id
		 * @return mixed
		 */
		static function _add_filter_wp_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ){
			if( (int)$attachment_id > 0 ){
				$image = ImagesFactory::get( $attachment_id );
				ksort( $sources, SORT_NUMERIC );
				end( $sources );
				$max_width = key( $sources ) * 1.5;
				$size_x2 = $image->sizes()->get( [ $max_width, $max_width, 1], false );
				$sources[ $size_x2->width() ] = [
					'url' => $size_x2->path()->get_url( false ),
					'descriptor' => 'w',
					'value' => $size_x2->width()
				];
			}
			return $sources;
		}

	}