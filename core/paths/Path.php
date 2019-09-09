<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/12/2018
	 * Time: 01:46
	 */

	namespace hiweb\core\paths;


	use hiweb\components\console\Console;
	use hiweb\core\files\File;
	use hiweb\core\files\Files;
	use hiweb\core\Strings;


	class Path{

		/** @var string */
		protected $path = null;
		private $subFiles = [];


		public function __construct( $path = false ){
			if( is_string( $path ) ){
				$this->path = $path;
			}
		}


		/**
		 * Return absolute path or url
		 * @version 1.0
		 * @return string
		 */
		public function get(){
			if( $this->is_url() ){
				return $this->URL()->get();
			} elseif( $this->is_relative() ) {
				$path = strpos( $this->path, '?' ) !== false ? substr( $this->path, 0, strpos( $this->path, '?' ) - 1 ) : $this->path;
				$path = ltrim( $path, '/' );
				return Paths::root() . '/' . $path;
			}
			return $this->path;
		}


		/**
		 * Return URL from local path
		 * @param null|bool $use_noscheme
		 * @return string
		 */
		public function get_url( $use_noscheme = null ){
			if( $this->is_url() ){
				return $this->URL()->get( $use_noscheme );
			}
			return str_replace( Paths::root(), urls::root( $use_noscheme ), $this->get() );
		}


		/**
		 * @version 1.1
		 * @return string
		 */
		public function get_path(){
			if( $this->is_url() ){
				return Paths::root() . '/' . $this->URL()->dirs( false );
			}
			return $this->get();
		}


		/**
		 * @version 1.1
		 * @param bool $return_params - return params, if this is url
		 * @return string
		 */
		public function get_path_relative( $return_params = false ){
			if( $this->is_url() ){
				return '/' . $this->URL()->dirs( false ) . ( ( $return_params && $this->URL()->params( false ) != '' ) ? '?' . $this->URL()->params( false ) : '' );
			} else {
				return str_replace( Paths::root(), '', $this->get() );
			}
		}


		/**
		 * @version 1.0
		 * @return bool|string|int
		 */
		public function handle(){
			return trim( strings::sanitize_id( basename( $this->dirname() ) . '/' . $this->basename(), '-' ), '_-' );
		}


		/**
		 * @version 1.0
		 * @return bool
		 */
		public function is_relative(){
			return is_string( $this->path ) && ( strpos( $this->path, Paths::root() ) !== 0 && !$this->is_url() );
		}


		/**
		 * @version 1.0
		 * @return bool
		 */
		public function is_absolute(){
			return is_string( $this->path ) && ( strpos( $this->path, Paths::root() ) === 0 && !$this->is_url() );
		}


		/**
		 * @version 1.0
		 * @return bool
		 */
		public function is_local(){
			if( is_string( $this->path ) && $this->is_url() ){
				return $this->URL()->is_local();
			}
			return $this->is_absolute() || $this->is_relative();
		}


		/**
		 * @return bool|null
		 */
		public function is_exists(){
			if( is_string( $this->path ) && $this->is_local() ){
				return file_exists( $this->get_path() );
			}
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_readable(){
			if( !$this->is_local() )
				return null;
			return file_exists( $this->get_path() ) && is_readable( $this->get_path() );
		}


		/**
		 * Return mime type of file if path is file
		 * @return null|string
		 */
		public function mime(){
			if( !$this->is_file() )
				return null;
			return mime_content_type( $this->get_path() );
		}


		/**
		 * @return bool
		 */
		public function is_writable(){
			if( !$this->is_local() )
				return false;
			return is_writable( $this->get_path() );
		}


		/**
		 * @return mixed
		 */
		public function __toString(){
			return $this->get();
		}


		/**
		 * Возвращает TRUE, если передан URL
		 * @return mixed
		 */
		public function is_url(){
			return is_string( $this->path ) && ( strpos( $this->path, '//' ) === 0 || filter_var( $this->path, FILTER_VALIDATE_URL ) );
		}


		/**
		 * @return urls\url
		 */
		public function URL(){
			return urls::get( $this->path );
		}


		/**
		 * @return File
		 */
		public function File(){
			return Files::get( $this->path );
		}


		/**
		 * @param array  $fileExtension
		 * @param string $excludeFiles_withPrefix
		 * @return File[]
		 */
		public function include_files( $fileExtension = [ 'php', 'css', 'js' ], $excludeFiles_withPrefix = '-' ){
			$dir = $this->File();
			$R = [];
			if( !$dir->is_readable() || !$dir->is_dir() ){
				console::debug_error( __METHOD__ . ': Папка не читаема или не существует', $dir );
			} else {
				$subFiles = $dir->get_sub_files( $fileExtension );
				foreach( $subFiles as $file ){
					///skip folders and files
					if( $file->get_next_file( '.notinclude' )->is_exists() )
						continue;
					if( !$file->is_readable() )
						continue;
					if( $excludeFiles_withPrefix != '' && strpos( $file->basename(), $excludeFiles_withPrefix ) === 0 )
						continue;
					///
					switch( $file->extension() ){
						case 'php':
							$path = apply_filters( '\hiweb\paths\path::include_files-php', $file->get_path(), $file );
							include_once $path;
							$R[ $file->path ] = $file;
							break;
						case 'css':
							$path = apply_filters( '\hiweb\paths\path::include_files-css', $file->get_path(), $file );
							css::add( $path );
							$R[ $file->path ] = $file;
							break;
						case 'js':
							$path = apply_filters( '\hiweb\paths\path::include_files-js', $file->get_url(), $file );
							\hiweb\js( $path ); //TODO!
							$R[ $file->path ] = $file;
							break;
					}
				}
			}

			return $R;
		}


		/**
		 * @param array $needle_file_names
		 * @return files\file[]
		 */
		public function include_files_by_name( $needle_file_names = [ 'functions.php' ] ){
			if( !is_array( $needle_file_names ) )
				$needle_file_names = [ $needle_file_names ];
			$dir = $this->File();
			$R = [];
			if( !$dir->is_readable() || !$dir->is_dir() ){
				console::debug_error( __METHOD__ . ': Папка не читаема или не существует', $dir );
			} else {
				$needle_file_names_flip = array_flip( $needle_file_names );
				$subFiles = $dir->get_sub_files();
				foreach( $subFiles as $file ){
					///skip folders and files
					if( $file->get_next_file( '.notinclude' )->is_exists() )
						continue;
					if( !$file->is_readable() )
						continue;
					//
					if( array_key_exists( $file->basename(), $needle_file_names_flip ) ){
						switch( $file->extension() ){
							case 'php':
								$path = apply_filters( '\hiweb\paths\path::include_files_by_name-php', $file->get_path(), $file );
								include_once $path;
								$R[ $file->path ] = $file;
								break;
							case 'css':
								$path = apply_filters( '\hiweb\paths\path::include_files_by_name-css', $file->get_path(), $file );
								css::add( $path );
								$R[ $file->path ] = $file;
								break;
							case 'js':
								$path = apply_filters( '\hiweb\paths\path::include_files_by_name-js', $file->get_url(), $file );
								\hiweb\js( $path ); //TODO!
								$R[ $file->path ] = $file;
								break;
						}
					}
				}
			}

			return $R;
		}


		/**
		 * Return current image mime type
		 * @return bool|mixed
		 */
		public function get_image_mime_type(){

			if( $this->extension() == 'jxr' )
				return 'image/jxr';

			$mimes = [
				IMAGETYPE_GIF => "image/gif",
				IMAGETYPE_JPEG => "image/jpg",
				IMAGETYPE_PNG => "image/png",
				IMAGETYPE_SWF => "image/swf",
				IMAGETYPE_PSD => "image/psd",
				IMAGETYPE_BMP => "image/bmp",
				IMAGETYPE_TIFF_II => "image/tiff",
				IMAGETYPE_TIFF_MM => "image/tiff",
				IMAGETYPE_JPC => "image/jpc",
				IMAGETYPE_JP2 => "image/jp2",
				IMAGETYPE_JPX => "image/jpx",
				IMAGETYPE_JB2 => "image/jb2",
				IMAGETYPE_SWC => "image/swc",
				IMAGETYPE_IFF => "image/iff",
				IMAGETYPE_WBMP => "image/wbmp",
				IMAGETYPE_XBM => "image/xbm",
				IMAGETYPE_ICO => "image/ico",
			];
			if( ( $this->is_readable() && $image_type = exif_imagetype( $this->get_path() ) ) && ( array_key_exists( $image_type, $mimes ) ) ){
				return $mimes[ $image_type ];
			} else {
				return 'image/' . $this->extension();
			}
		}


		/**
		 * @return string
		 */
		public function extension(){
			$pathInfo = pathinfo( $this->get_path_relative() );

			return isset( $pathInfo['extension'] ) ? $pathInfo['extension'] : '';
		}


		/**
		 * Return file name which extension, like 'filename.ext'
		 * @return string
		 */
		public function basename(){
			return basename( $this->path );
		}


		/**
		 * Return file name whithout extension, like 'filename'
		 * @return string
		 */
		public function filename(){
			if( $this->extension() != '' ){
				return substr( $this->basename(), 0, strlen( '.' . $this->extension() ) * - 1 );
			}
			return $this->basename();
		}


		/**
		 * Return dir name component of path
		 * @return string
		 */
		public function dirname(){
			return dirname( $this->get_path() );
		}


		/**
		 * @return bool|null
		 */
		public function is_dir(){
			if( $this->is_local() )
				return is_dir( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_file(){
			if( $this->is_local() )
				return is_file( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_link(){
			if( $this->is_local() )
				return is_link( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_uploaded_file(){
			if( $this->is_local() )
				return is_uploaded_file( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_executable(){
			if( $this->is_local() )
				return is_executable( $this->get_path() );
			return null;
		}


		/**
		 * Return true, if file is exists and image
		 * @return bool
		 */
		public function is_image(){
			return ( $this->is_file() && strpos( $this->get_image_mime_type(), 'image' ) === 0 );
		}


		/**
		 * @param null|string $default
		 * @return string
		 */
		public function get_content( $default = null ){
			//TODO: сделать чтение содержимого из удаленного URL файла
			if( $this->is_readable() )
				return file_get_contents( $this->get_path() ); else return $default;
		}


		/**
		 * @version 1.1
		 * @param          $content
		 * @param bool|int $appendPrepend - true|1 - добавить строку(и) в файл, -1 - препенд строки к файлу
		 * @return bool|int
		 */
		public function set_content( $content, $appendPrepend = false ){
			if( !$this->is_local() )
				return - 1;
			if( !$this->is_writable() )
				return - 2;
			///
			if( $appendPrepend === true || $appendPrepend > 0 ){
				$content = (string)$this->get_content() . $content;
			} elseif( $appendPrepend < 0 ) {
				$content = $content . (string)$this->get_content();
			}
			return file_put_contents( $this->get_path(), $content );
		}


		/**
		 * @version 1.1
		 * Return file or directory size in bites
		 * @return bool|int
		 */
		public function get_size(){
			$R = false;
			if( $this->is_file() ){
				return filesize( $this->get_path() );
			} elseif( $this->is_dir() ) {
				$files = $this->get_sub_files();
				$size = 0;
				foreach( $files as $file ){
					$size += $file->get_size();
				}
				return $size;
			}
			return $R;
		}


		/**
		 * @return string
		 */
		public function get_size_formatted(){
			return Paths::get_size_formatted( $this->get_size() );
		}


		/**
		 * Возвращает массив вложенных файлов
		 * @version 1.1
		 * @param array $mask - маска файлов
		 * @return File[]
		 */
		public function get_sub_files( $mask = [] ){
			if( !$this->is_local() )
				return [];
			///
			$mask = is_array( $mask ) ? $mask : [ $mask ];
			$maskKey = json_encode( $mask );
			if( !array_key_exists( $maskKey, $this->subFiles ) ){
				$this->subFiles[ $maskKey ] = [];
				if( $this->is_dir() )
					foreach( scandir( $this->path ) as $subFileName ){
						if( $subFileName == '.' || $subFileName == '..' )
							continue;
						$subFilePath = $this->get_path() . '/' . $subFileName;
						$subFile = files::get( $subFilePath );
						if( $subFile->is_dir() ){
							$this->subFiles[ $maskKey ] = array_merge( $this->subFiles[ $maskKey ], $subFile->get_sub_files( $mask ) );
						} else {
							if( is_array( $mask ) && count( $mask ) > 0 ){
								if( !arrays::get_temp( $mask )->has_value( Paths::file_extension( $subFileName ) ) )
									continue;
							}
							$this->subFiles[ $maskKey ][ $subFile->path ] = $subFile;
						}
					}
			}
			return $this->subFiles[ $maskKey ];
		}

	}