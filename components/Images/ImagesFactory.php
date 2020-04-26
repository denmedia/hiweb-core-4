<?php
	
	namespace hiweb\components\Images;
	
	
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\Paths\Path;
	use hiweb\core\Paths\PathsFactory;
	
	
	class ImagesFactory{
		
		/** @var Path */
		protected static $default_image_file;
		
		
		/**
		 * @param $idOrUrl
		 * @return Image
		 */
		static function get( $idOrUrl ){
			$attachIdPathOrUrl = get_attachment_id_from_url( $idOrUrl );
			return CacheFactory::get( $attachIdPathOrUrl, __CLASS__ . '::$images', function(){
				return new Image( func_get_arg( 0 ) );
			}, [ $attachIdPathOrUrl ] )->get_value();
		}
		
		
		/**
		 * Set default image url/path
		 * @param string $urlOrPathOrAttachID
		 * @return bool
		 */
		static public function set_default_src( $urlOrPathOrAttachID ){
			$file = PathsFactory::get( $urlOrPathOrAttachID );
			if( $file->File()->is_readable() ){
				self::$default_image_file = $file;
				return true;
			}
			return false;
		}
		
		
		/**
		 * @param bool $force_hiweb_default
		 * @return bool|string
		 */
		static public function get_default_src( $force_hiweb_default = false ){
			if( $force_hiweb_default || !self::$default_image_file instanceof Path ){
				return PathsFactory::get( __DIR__ . '/noimg.svg' )->get_url();
			}
			return self::$default_image_file->get_url();
		}
		
	}