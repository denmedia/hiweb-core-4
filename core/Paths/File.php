<?php

	namespace hiweb\core\Paths;


	use hiweb\components\Console\ConsoleFactory;
	use hiweb\core\ArrayObject\ArrayObject;
	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\Paths\PathsFactory;


	class File{

		/** @var Path */
		private $Path;
		/** @var Image */
		private $cache_Image;
		private $original_path;
		private $relative_path;
		private $absolute_path;
		/** @var array */
		private $cache_subFiles = [];


		public function __construct( Path $Path ){
			//TODO
			$this->Path = $Path;
			$this->prepare();
		}


		/**
		 * @return Path
		 */
		public function Path(){
			return $this->Path;
		}


		/**
		 * @return Url
		 */
		public function Url(){
			return $this->Path()->Url();
		}


		/**
		 * @return Image
		 */
		public function Image(){
			if( !$this->cache_Image instanceof Image ){
				$this->cache_Image = new Image( $this->Path() );
			}
			return $this->cache_Image;
		}


		private function prepare(){
			$this->original_path = $this->Path()->get_original_path();
			if( $this->Path()->is_url() ){

				if( $this->Path()->is_local() ){
					$this->original_path = $this->Url()->dirs()->join( '/' );
				} else {
					$pattern = '/^(?>[\w]+:)?(?>\/\/)?[а-яА-ЯЁёa-zA-Z0-9_\-.]+\/(?<path>[^?]+)\/?(?>\?.*)?/im';
					preg_match_all( $pattern, $this->Path()->get_original_path(), $matches );
					if( isset( $matches['path'][0] ) ){
						$this->original_path = $matches['path'][0];
					}
				}
			}
			///
			if( $this->Path()->is_relative() ){
				$this->absolute_path = PathsFactory::get_root_path() . '/' . $this->original_path;
				$this->relative_path = trim( $this->original_path, '\\/' );
			} elseif( $this->Path()->is_absolute() ) {
				$this->absolute_path = $this->original_path;
				$this->relative_path = trim( str_replace( PathsFactory::get_root_path(), '', $this->original_path ), '\\/' );
			}
		}


		/**
		 * @return string
		 */
		public function get_original_path(){
			return $this->original_path;
		}


		/**
		 * @return string
		 */
		public function get_relative_path(){
			return $this->relative_path;
		}


		/**
		 * @alias get_relative_path()
		 * @return string
		 */
		public function get_path_relative(){
			return $this->relative_path;
		}


		/**
		 * @return string
		 */
		public function get_absolute_path(){
			return $this->absolute_path;
		}


		/**
		 * @alias get_absolute_path()
		 * @return string
		 */
		public function get_path(){
			return $this->absolute_path;
		}


		/**
		 * @return bool|null
		 */
		public function is_exists(){
			if( is_string( $this->Path()->get_original_path() ) && $this->Path()->is_local() ){
				return file_exists( $this->Path()->get_original_path() );
			}
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_readable(){
			if( !$this->Path()->is_local() ) return null;
			return file_exists( $this->Path()->get_original_path() ) && is_readable( $this->get_path() );
		}


		/**
		 * @return bool
		 */
		public function is_writable(){
			if( !$this->Path()->is_local() ) return false;
			return is_writable( $this->Path()->get_original_path() );
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
					if( $file->get_next_file( '.notinclude' )->is_exists() ) continue;
					if( !$file->is_readable() ) continue;
					if( $excludeFiles_withPrefix != '' && strpos( $file->basename(), $excludeFiles_withPrefix ) === 0 ) continue;
					///
					switch( $file->extension() ){
						case 'php':
							$path = apply_filters( '\hiweb\paths\path::include_files-php', $file->get_path(), $file );
							include_once $path;
							$R[ $file->original_path ] = $file;
							break;
						case 'css':
							$path = apply_filters( '\hiweb\paths\path::include_files-css', $file->get_path(), $file );
							//css::add( $path );
							$R[ $file->original_path ] = $file;
							break;
						case 'js':
							$path = apply_filters( '\hiweb\paths\path::include_files-js', $file->get_url(), $file );
							//\hiweb\js( $path ); //TODO!
							$R[ $file->original_path ] = $file;
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
		//		public function include_files_by_name( $needle_file_names = [ 'functions.php' ] ){
		//			if( !is_array( $needle_file_names ) ) $needle_file_names = [ $needle_file_names ];
		//			$dir = $this;
		//			$R = [];
		//			if( !$dir->is_readable() || !$dir->is_dir() ){
		//				ConsoleFactory::add( 'Folder not readable', 'warn', __METHOD__, $dir, true );
		//			} else {
		//				$needle_file_names_flip = array_flip( $needle_file_names );
		//				$subFiles = $dir->get_sub_files();
		//				foreach( $subFiles as $file ){
		//					///skip folders and files
		//					if( $file->get_next_file( '.notinclude' )->is_exists() ) continue;
		//					if( !$file->is_readable() ) continue;
		//					//
		//					if( array_key_exists( $file->basename(), $needle_file_names_flip ) ){
		//						switch( $file->extension() ){
		//							case 'php':
		//								$path = apply_filters( '\hiweb\paths\path::include_files_by_name-php', $file->get_path(), $file );
		//								include_once $path;
		//								$R[ $file->original_path ] = $file;
		//								break;
		//							case 'css':
		//								$path = apply_filters( '\hiweb\paths\path::include_files_by_name-css', $file->get_path(), $file );
		//								css::add( $path );
		//								$R[ $file->original_path ] = $file;
		//								break;
		//							case 'js':
		//								$path = apply_filters( '\hiweb\paths\path::include_files_by_name-js', $file->get_url(), $file );
		//								\hiweb\js( $path ); //TODO!
		//								$R[ $file->original_path ] = $file;
		//								break;
		//						}
		//					}
		//				}
		//			}
		//
		//			return $R;
		//		}

		/**
		 * @return bool|null
		 */
		public function is_dir(){
			if( $this->Path()->is_local() ) return is_dir( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_file(){
			if( $this->Path()->is_local() ) return is_file( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_link(){
			if( $this->Path()->is_local() ) return is_link( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_uploaded_file(){
			if( $this->Path()->is_local() ) return is_uploaded_file( $this->get_path() );
			return null;
		}


		/**
		 * @return bool|null
		 */
		public function is_executable(){
			if( $this->Path()->is_local() ) return is_executable( $this->get_path() );
			return null;
		}


		/**
		 * Return mime type of file if path is file
		 * @return null|string
		 */
		public function mime(){
			if( !$this->is_file() ) return null;
			return mime_content_type( $this->get_path() );
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
			return basename( $this->original_path );
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
		 * @return ArrayObject
		 */
		public function dirs(){
			return CacheFactory::get( spl_object_id( $this->Path() ), __METHOD__ )->set_callableFunc( function(){
				return get_array( explode( '/', func_get_arg( 0 )->dirname() ) );
			}, [ $this ] )->get();
		}


		/**
		 * Return true, if file is exists and image
		 * @return bool
		 */
		public function is_image(){
			return ( $this->is_file() && strpos( $this->Image()->get_mime_type(), 'image' ) === 0 );
		}


		/**
		 * @param null|string $default
		 * @return string
		 */
		public function get_content( $default = null ){
			//TODO: сделать чтение содержимого из удаленного URL файла
			if( $this->is_readable() ) return file_get_contents( $this->get_path() ); else return $default;
		}


		/**
		 * @param          $content
		 * @param bool|int $appendPrepend - true|1 - добавить строку(и) в файл, -1 - препенд строки к файлу
		 * @return bool|int
		 * @version 1.1
		 */
		public function set_content( $content, $appendPrepend = false ){
			if( !$this->Path()->is_local() ) return - 1;
			if( $this->is_exists() && !$this->is_writable() ) return - 2;
			///
			if( $appendPrepend === true || $appendPrepend > 0 ){
				$content = (string)$this->get_content() . $content;
			} elseif( $appendPrepend < 0 ) {
				$content = $content . (string)$this->get_content();
			}
			return file_put_contents( $this->get_path(), $content );
		}


		/**
		 * @return bool|int
		 * @version 1.1
		 *          Return file or directory size in bites
		 */
		public function get_size(){ //TODO~!!!
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
			return PathsFactory::get_size_formatted( $this->get_size() );
		}


		/**
		 * Возвращает массив вложенных файлов
		 * @param array $mask - маска файлов
		 * @return File[]
		 * @version 1.2
		 */
		public function get_sub_files( $mask = [] ){
			if( !$this->Path()->is_local() ) return [];
			///
			$mask = is_array( $mask ) ? $mask : [ $mask ];
			$maskKey = json_encode( $mask );
			if( !array_key_exists( $maskKey, $this->cache_subFiles ) ){
				$this->cache_subFiles[ $maskKey ] = [];
				if( $this->is_dir() ) foreach( scandir( $this->get_absolute_path() ) as $subFileName ){
					if( $subFileName == '.' || $subFileName == '..' ) continue;
					$subFilePath = $this->get_path() . '/' . $subFileName;
					$subFile = PathsFactory::get( $subFilePath )->File();
					if( $subFile->is_dir() ){
						$this->cache_subFiles[ $maskKey ] = array_merge( $this->cache_subFiles[ $maskKey ], $subFile->get_sub_files( $mask ) );
					} else {
						if( is_array( $mask ) && count( $mask ) > 0 ){
							if( !in_array( PathsFactory::file_extension( $subFileName ), $mask ) ) continue;
						}
						$this->cache_subFiles[ $maskKey ][ $subFile->get_original_path() ] = $subFile;
					}
				}
			}
			return $this->cache_subFiles[ $maskKey ];
		}


		/**
		 * Get next file
		 * @param $file_name
		 * @return File
		 */
		public function get_next_file( $file_name ){
			return PathsFactory::get( $this->dirname() . '/' . $file_name )->File();
		}


		/**
		 * @param string $content
		 * @return bool|int
		 */
		public function make_file( $content = '' ){
			if( !$this->Path()->is_local() ){
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . '], потому что ссылка не локальная' );
				}
				return - 1;
			}
			if( $this->is_exists() ){
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . '], потому что он уже существует' );
				}
				return - 2;
			}
			if( $this->is_writable() ){
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . '], потому что нет прав на запись' );
				}
				return - 3;
			}

			if( file_put_contents( $this->get_path(), $content ) ){
				return true;
			} else {
				if( function_exists( 'console_error' ) ){
					console_error( '\hiweb\files\file::make: не удалось создать файл [' . $this->original_path . ']' );
				}
				return - 4;
			}
		}

	}