<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:44
	 */
	
	namespace hiweb\core\Paths;
	
	
	use hiweb\core\strings;
	
	
	/**
	 * Class PathsFactory
	 * @varsion 1.1
	 * @package hiweb\core\Paths
	 */
	class PathsFactory{
		
		/** @var Path[] */
		static private $cache_paths = [];
		static private $cache_attachment_ids = [];
		private static $root_path;
		static private $root;
		/** @var string[] */
		private static $current_url = [];
		static $use_universal_schema_urls = true;
		
		
		/**
		 * @param string $path_or_url_handle
		 * @return Path
		 */
		static function get( $path_or_url_handle = '' ){
			$path_or_url_handle = str_replace( '\\', '/', (string)$path_or_url_handle );
			if( trim( $path_or_url_handle, '/' ) == '' ) $path_or_url_handle = self::get_current_url();
			///
			if( !array_key_exists( $path_or_url_handle, self::$cache_paths ) ){
				self::$cache_paths[ $path_or_url_handle ] = new Path( $path_or_url_handle );
			}
			///
			return self::$cache_paths[ $path_or_url_handle ];
		}
		
		
		/**
		 * @param int $attachment_id
		 * @return \hiweb\core\Paths\Path
		 */
		static function get_by_id( $attachment_id = 0 ){
			if( !array_key_exists( $attachment_id, self::$cache_attachment_ids ) ){
				self::$cache_attachment_ids[ $attachment_id ] = get_attached_file( $attachment_id );
			}
			return self::get( self::$cache_attachment_ids[ $attachment_id ] );
		}
		
		
		/**
		 * @param string $path_or_url_or_handle
		 * @return Path_File
		 */
		static function get_file( $path_or_url_or_handle = '' ){
			return self::get( $path_or_url_or_handle )->file();
		}
		
		
		/**
		 * @param string $path_or_url_or_handle
		 * @return Path_Url
		 */
		static function get_url( $path_or_url_or_handle = '' ){
			return self::get( $path_or_url_or_handle )->url();
		}
		
		
		/**
		 * @return mixed|string
		 */
		static function get_root_path(){
			if( !is_string( self::$root ) ){
				self::$root_path = ABSPATH;
				$patch = explode( '/', trim( __DIR__ ) );
				$patches = [];
				$last_path = '';
				foreach( $patch as $dir ){
					if( $dir == '' ){
						continue;
					}
					$last_path .= '/' . $dir;
					$patches[] = $last_path;
				}
				$patches = array_reverse( $patches );
				foreach( $patches as $path ){
					$check_file = $path . '/wp-config.php';
					if( file_exists( $check_file ) && is_file( $check_file ) ){
						self::$root_path = $path;
						break;
					}
				}
			}
			return self::$root_path;
		}
		
		
		/**
		 * Returns the root folder of the site. This function automatically determines the root folder of the site, based on the search for folders with the wp-config.php file
		 * Возвращает корневую папку сайта. Данная функция автоматически определяет корневую папку сайта, основанная на поиске папок с файлом wp-config.php
		 * @return Path
		 * @version 2
		 */
		static function root(){
			if( !self::$root instanceof Path ){
				self::$root = self::get( self::get_root_path() );
			}
			return self::$root;
		}
		
		
		/**
		 * Get current URl string
		 * @param bool $trimSlashes
		 * @return string
		 */
		static function get_current_url( $trimSlashes = true ){
			$key = $trimSlashes ? 'trimSlashes:true' : 'trimSlashes:false';
			if( !isset( self::$current_url[ $key ] ) ){
				self::$current_url[ $key ] = '';
				$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
				self::$current_url[ $key ] = rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'], '/' ) . ( $trimSlashes ? rtrim( $_SERVER['REQUEST_URI'], '/\\' ) : $_SERVER['REQUEST_URI'] );
			}
			return self::$current_url[ $key ];
		}
		
		
		/**
		 * Возвращает запрошенный GET или POST параметр
		 * @param       $key
		 * @param mixed $default
		 * @return mixed
		 */
		static function request( $key, $default = null ){
			$R = $default;
			if( array_key_exists( $key, $_GET ) ){
				$R = $_GET[ $key ];
			}
			if( array_key_exists( $key, $_POST ) ){
				$R = is_string( $_POST[ $key ] ) ? stripslashes( $_POST[ $key ] ) : $_POST[ $key ];
			}
			
			return $R;
		}
		
		
		/**
		 * @param int|string $size
		 * @return string
		 */
		static function get_size_formatted( $size ){
			$size = intval( $size );
			if( $size < 1024 ){
				return $size . ' ' . __( 'B' );
			}
			elseif( $size < 1048576 ){
				return round( $size / 1024, 2 ) . ' ' . __( 'KB' );
			}
			elseif( $size < 1073741824 ){
				return round( $size / 1048576, 2 ) . ' ' . __( 'MB' );
			}
			elseif( $size < 1099511627776 ){
				return round( $size / 1073741824, 2 ) . ' ' . __( 'GB' );
			}
			elseif( $size < 1125899906842624 ){
				return round( $size / 1099511627776, 2 ) . ' ' . __( 'TB' );
			}
			elseif( $size < 1152921504606846976 ){
				return round( $size / 1125899906842624, 2 ) . ' ' . __( 'PB' );
			}
			elseif( $size < 1180591620717411303424 ){
				return round( $size / 1152921504606846976, 2 ) . ' ' . __( 'EB' );
			}
			elseif( $size < 1208925819614629174706176 ){
				return round( $size / 1180591620717411303424, 2 ) . ' ' . __( 'ZB' );
			}
			else{
				return round( $size / 1208925819614629174706176, 2 ) . ' ' . __( 'YiB' );
			}
		}
		
		
		/**
		 * @param $file_name
		 * @return mixed|string
		 */
		static function file_extension( $file_name ){
			$pathinfo = pathinfo( $file_name );
			return isset( $pathinfo['extension'] ) ? $pathinfo['extension'] : '';
		}
		
		
		/**
		 * Возвращает свободное имя файла в папке
		 * @param      $filePath - желаемый путь
		 * @return string
		 */
		static function get_freeFileName( $filePath ){
			if( !file_exists( $filePath ) ){
				return $filePath;
			}
			$File = self::get( $filePath )->file();
			for( $n = 1; $n < 9999; $n ++ ){
				$test_path = $File->dirname() . '/' . $File->filename() . '-' . sprintf( "%04d", $n ) . '.' . $File->extension();
				if( !file_exists( $test_path ) ){
					return $test_path;
				}
			}
			return $File->dirname() . '/' . $File->filename() . '-' . strings::rand( 5 ) . '.' . $File->extension();
		}
		
		
		/**
		 * Upload file or files in to wo-content/uploads and create WP_Post attachment with image meta in DB
		 * @param      $fileOrUrl - $_FILES[file_id]
		 * @param null $force_file_name
		 * @return int|\WP_Error
		 */
		static function upload( $fileOrUrl, $force_file_name = null ){
			if( is_array( $fileOrUrl ) ){
				if( !isset( $fileOrUrl['tmp_name'] ) ){
					return 0;
				}
				///
				ini_set( 'upload_max_filesize', '128M' );
				ini_set( 'post_max_size', '128M' );
				ini_set( 'max_input_time', 300 );
				ini_set( 'max_execution_time', 300 );
				///
				$tmp_name = $fileOrUrl['tmp_name'];
				$fileName = trim( $force_file_name ) == '' ? $fileOrUrl['name'] : $force_file_name;
				if( !is_readable( $tmp_name ) ){
					return - 1;
				}
			}
			elseif( is_string( $fileOrUrl ) && self::get( $fileOrUrl )->is_url() ){
				$fileName = trim( $force_file_name ) == '' ? self::get( $fileOrUrl )->file()->basename() : $force_file_name;
				$tmp_name = $fileOrUrl;
			}
			elseif( is_string( $fileOrUrl ) && file_exists( $fileOrUrl ) && is_file( $fileOrUrl ) && is_readable( $fileOrUrl ) ){
				$fileName = trim( $force_file_name ) == '' ? self::get( $fileOrUrl )->file()->basename() : $force_file_name;
				$tmp_name = $fileOrUrl;
			}
			else{
				return - 2;
			}
			///File Upload
			$wp_filetype = wp_check_filetype( $fileName, null );
			$wp_upload_dir = wp_upload_dir();
			$newPath = $wp_upload_dir['path'] . '/' . sanitize_file_name( $fileName );
			$newPath = self::get_freeFileName( $newPath );
			if( !copy( $tmp_name, $newPath ) ){
				return - 2;
			}
			$attachment = [ 'guid' => $wp_upload_dir['url'] . '/' . $fileName, 'post_mime_type' => $wp_filetype['type'], 'post_title' => preg_replace( '/\.[^.]+$/', '', $fileName ), 'post_content' => '', 'post_status' => 'inherit' ];
			$attachment_id = wp_insert_attachment( $attachment, $newPath );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $newPath );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			return $attachment_id;
		}
		
		
		/**
		 * Get an attachment ID given a URL.
		 * @param string $url
		 * @return int Attachment ID on success, 0 on failure
		 */
		static function get_attachment_id_from_url( $url ){
			$attachment_id = 0;
			$dir = wp_upload_dir();
			if( false !== strpos( $url, $dir['baseurl'] . '/' ) ){ // Is URL in uploads directory?
				$file = basename( $url );
				$query_args = [
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'fields' => 'ids',
					'meta_query' => [
						[
							'value' => $file,
							'compare' => 'LIKE',
							'key' => '_wp_attachment_metadata',
						],
					]
				];
				$query = new \WP_Query( $query_args );
				if( $query->have_posts() ){
					foreach( $query->posts as $post_id ){
						$meta = wp_get_attachment_metadata( $post_id );
						$original_file = basename( $meta['file'] );
						$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
						if( $original_file === $file || in_array( $file, $cropped_image_files ) ){
							$attachment_id = $post_id;
							break;
						}
					}
				}
			}
			return $attachment_id;
		}
		
		
		/**
		 * @param array $pathsArray
		 * @return Path
		 */
		static function get_bySearch( array $pathsArray ){
			if( !is_array( $pathsArray ) ) $pathsArray = [ (string)$pathsArray ];
			foreach( $pathsArray as $path ){
				if( file_exists( $path ) ){
					return self::get( $path );
				}
			}
			return self::get( reset( $pathsArray ) );
		}
		
	}