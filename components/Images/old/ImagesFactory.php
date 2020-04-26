<?php

	namespace hiweb\components\Images;


	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Paths\File;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;


	class ImagesFactory{

		/** @var Image[] */
		static private $images = [];
		static private $editors = [];
		/** @var null|File */
		static private $default_image_file = null;
		/** @var Path */
		static private $upload_dirs = [];

		static $original_size_name = 'original';

		static $mime_default_priority = [ 'png', 'gif', 'jpg', 'jpe', 'jpeg'/*, 'webp', 'jp2', 'jxr'*/ ];
		static $classic_file_types = [ 'png', 'jpg', 'jpeg', 'jpe', 'gif' ];
		static $progressive_types = [ 'jxr', 'webp', 'jp2' ];
		static $progressive_create_on_upload = false;
		static $meta_key_optimized = 'hiweb-optimized';
		static $extension_priority = [ 'png', 'gif', 'jpg', 'jpe', 'jpeg',/*'webp', 'jxr', 'jp2'*/ ];
		static $default_quality = 75;


		/**
		 * @param int $attachIdPathOrUrl
		 * @return image
		 * @version 1.2
		 */
		static public function get( $attachIdPathOrUrl ){
			if( is_string( $attachIdPathOrUrl ) && !is_numeric( $attachIdPathOrUrl ) ){
				$attachIdPathOrUrl = PathsFactory::get( $attachIdPathOrUrl )->Url()->get( false );
				global $wpdb;
				$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $attachIdPathOrUrl ) );
				$attachIdPathOrUrl = $attachment[0];
			} elseif( $attachIdPathOrUrl instanceof \WP_Post && $attachIdPathOrUrl->post_type == 'attachment' ) {
				$attachIdPathOrUrl = $attachIdPathOrUrl->ID;
			}
			///
			if( !isset( self::$images[ $attachIdPathOrUrl ] ) ){
				self::$images[ $attachIdPathOrUrl ] = new Image( $attachIdPathOrUrl );
			}
			return self::$images[ $attachIdPathOrUrl ];
		}


		/**
		 * @param $pathOrUrl
		 * @return Editor
		 */
		static public function get_editor( $pathOrUrl ){
			if( !array_key_exists( $pathOrUrl, self::$editors ) ){
				self::$editors[ $pathOrUrl ] = new Editor( $pathOrUrl );
			}
			return self::$editors[ $pathOrUrl ];
		}


		/**
		 * Set default image url/path
		 * @param string $urlOrPathOrAttachID
		 * @return bool
		 */
		static public function set_default_src( $urlOrPathOrAttachID ){
			if( !is_string( $urlOrPathOrAttachID ) ){
				//
			} else {
				$file = PathsFactory::get( $urlOrPathOrAttachID )->File();
				if( $file->is_readable() ){
					self::$default_image_file = $file;
					return true;
				} else {
					//
				}
			}
			return false;
		}


		/**
		 * @param bool $force_hiweb_default
		 * @return bool|string
		 */
		static public function get_default_src( $force_hiweb_default = false ){
			$default_hiweb_src = PathsFactory::get( __DIR__ ) . '/noimg.svg';
			return ( !$force_hiweb_default && self::$default_image_file instanceof file ) ? self::$default_image_file->Url()->get() : $default_hiweb_src;
		}


		/**
		 * @return path
		 */
		static function get_upload_dirs(){
			if( !isset( self::$upload_dirs[ get_current_blog_id() ] ) || !self::$upload_dirs[ get_current_blog_id() ] instanceof path ){
				if( function_exists( 'wp_get_upload_dir' ) ){
					self::$upload_dirs[ get_current_blog_id() ] = PathsFactory::get( ( new ArrayObject() )->_( 'basedir' ) );
				} else {
					self::$upload_dirs[ get_current_blog_id() ] = PathsFactory::get( WP_CONTENT_DIR . '/uploads' );
				}
			}
			return self::$upload_dirs[ get_current_blog_id() ];
		}


		/**
		 * @return path
		 */
		static function get_upload_path_dirs(){
			if( !self::$upload_dirs[ get_current_blog_id() ] instanceof path ){
				if( function_exists( 'wp_get_upload_dir' ) ){
					self::$upload_dirs[ get_current_blog_id() ] = PathsFactory::get( ( new ArrayObject() )->_( 'path' ) );
				} else {
					self::$upload_dirs[ get_current_blog_id() ] = PathsFactory::get( WP_CONTENT_DIR . '/uploads/' . date( 'Y', time() ) . '/' . date( 'm', time() ) );
				}
			}
			return self::$upload_dirs[ get_current_blog_id() ];
		}


		/**
		 * @param $extension
		 * @return bool
		 */
		static function is_extension_classic( $extension ){
			return array_key_exists( $extension, array_flip( self::$classic_file_types ) );
		}


		/**
		 * @param $extension
		 * @return bool
		 */
		static function is_extension_progressive( $extension ){
			return array_key_exists( $extension, array_flip( self::$progressive_types ) );
		}


	}