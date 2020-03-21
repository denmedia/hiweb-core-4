<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 30.05.2018
	 * Time: 16:27
	 */

	namespace hiweb\remote_videos;


	use hiweb\cache;
	use hiweb\Urls;


	class video{

		public $url;
		public $data;
		public $id = null;
		public $title = false;
		public $thumbnail_url = false;
		public $html = false;
		private $data_is_setuped = false;


		public function __construct( $url ){
			$this->url = $url;
			$this->id = $this->get_id();
			$this->setup_data();
		}


		/**
		 * @return bool|string
		 */
		public function get_id(){
			if( is_null( $this->id ) ){
				if( $this->is_youtube() ){
					$this->id = Urls::get( $this->url )->param( 'v' );
				} elseif( $this->is_vimeo() ) {
					$this->id = trim( parse_url( $this->url )['path'], '/' );
				} else {
					$this->id = false;
				}
			}

			return $this->id;
		}


		/**
		 * @return bool
		 */
		public function is_youtube(){
			return strpos( $this->url, 'youtube' ) !== false;
		}


		/**
		 * @return bool
		 */
		public function is_vimeo(){
			return strpos( $this->url, 'vimeo' ) !== false;
		}


		private function setup_data(){
			if( $this->data_is_setuped ){
				//do nothing
			} else {
				if( $this->is_youtube() ){
					$youtube = "http://www.youtube.com/oembed?url=" . $this->url . "&format=json";
					if( !cache::is_exists( $youtube ) ){
						$curl = curl_init( $youtube );
						curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
						$return = curl_exec( $curl );
						curl_close( $curl );
						$data = json_decode( $return, true );
						cache::set( $youtube, $data );
					} else {
						$data = cache::get( $youtube );
					}
					if( isset( $data['thumbnail_url'] ) ){
						$this->thumbnail_url = $data['thumbnail_url'];
					}
					if( isset( $data['title'] ) ){
						$this->title = $data['title'];
					}
					if( isset( $data['html'] ) ){
						$this->html = '<div class="hiweb-remote-video-html">' . $data['html'] . '</div>';
					}
				} elseif( $this->is_vimeo() ) {
					$vimeo = "http://vimeo.com/api/v2/video/" . $this->get_id() . ".json";
					if( !cache::is_exists( $vimeo ) ){
						$curl = curl_init( $vimeo );
						curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
						$return = curl_exec( $curl );
						curl_close( $curl );
						$data = json_decode( $return, true );
						cache::set( $vimeo, $data );
					} else {
						$data = cache::get( $vimeo );
					}
					if( isset( $data[0] ) ){
						$this->thumbnail_url = $data[0]['thumbnail_large'];
						$this->title = $data[0]['title'];
						$this->html = '<div class="hiweb-remote-video-html"><iframe src="https://player.vimeo.com/video/' . $data[0]['id'] . '?api=1&amp;color=#FFFFFF&amp;portrait=0&amp;title=0&amp;byline=0" frameborder="0" wmode="opaque" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" ></iframe></div>';
					}
				}
				$this->data_is_setuped = true;
			}
		}


		/**
		 * @return string|false
		 */
		public function get_thumbnail_url(){
			$this->setup_data();

			return $this->thumbnail_url;
		}


		public function html(){
			if( $this->is_exist() ){
				return $this->html;
			} else {
				console_warn( 'Не удалось вывести шаблон видео файла: он не существует [' . $this->url . ']' );

				return '<!--VIDEO HTML-->';
			}
		}


		public function is_exist(){
			$this->setup_data();

			return $this->html != false;
		}


	}