<?php

	namespace hiweb\core\files;


	class Files{

		/** @var array|File[] */
		static $files = [];


		/**
		 * Возвращает объект файла
		 * @param $pathOrUrlOrAttachID
		 * @return File
		 *@version 1.0
		 */
		static function get( $pathOrUrlOrAttachID ){
			if( is_numeric( $pathOrUrlOrAttachID ) ){
				$pathOrUrlOrAttachID = get_attached_file( $pathOrUrlOrAttachID );
			}
			if( !array_key_exists( $pathOrUrlOrAttachID, self::$files ) ){
				$file = new File( $pathOrUrlOrAttachID );
				self::$files[ $pathOrUrlOrAttachID ] = $file;
				self::$files[ $file->get_path() ] = $file;
				self::$files[ $file->get_url() ] = $file;
			}
			return self::$files[ $pathOrUrlOrAttachID ];
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
			$FILE = Files::get( $filePath );
			for( $n = 1; $n < 9999; $n ++ ){
				$test_path = $FILE->dirname() . '/' . $FILE->filename() . '-' . sprintf( "%04d", $n ) . '.' . $FILE->extension();
				if( !file_exists( $test_path ) ){
					return $test_path;
				}
			}
			return $FILE->dirname() . '/' . $FILE->filename() . '-' . strings::rand( 5 ) . '.' . $FILE->extension();
		}

		/**
		 * Upload file or files
		 * @param      $_fileOrUrl - $_FILES[file_id]
		 * @param null $force_file_name
		 * @return int|\WP_Error
		 */
		static function upload( $_fileOrUrl, $force_file_name = null ){
			if( is_array( $_fileOrUrl ) ){
				if( !isset( $_fileOrUrl['tmp_name'] ) ){
					return 0;
				}
				///
				ini_set( 'upload_max_filesize', '128M' );
				ini_set( 'post_max_size', '128M' );
				ini_set( 'max_input_time', 300 );
				ini_set( 'max_execution_time', 300 );
				///
				$tmp_name = $_fileOrUrl['tmp_name'];
				$fileName = trim( $force_file_name ) == '' ? $_fileOrUrl['name'] : $force_file_name;
				if( !is_readable( $tmp_name ) ){
					return - 1;
				}
			} elseif( is_string( $_fileOrUrl ) && self::get( $_fileOrUrl )->is_url() ) {
				$fileName = trim( $force_file_name ) == '' ? self::get( $_fileOrUrl )->basename() : $force_file_name;
				$tmp_name = $_fileOrUrl;
			} elseif( is_string( $_fileOrUrl ) && file_exists( $_fileOrUrl ) && is_file( $_fileOrUrl ) && is_readable( $_fileOrUrl ) ) {
				$fileName = trim( $force_file_name ) == '' ? self::get( $_fileOrUrl )->basename() : $force_file_name;
				$tmp_name = $_fileOrUrl;
			} else {
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


	}