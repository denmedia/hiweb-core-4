<?php

	namespace hiweb\components\RemoteVideos;


	use hiweb\core\Cache\CacheFactory;
	use hiweb\core\Paths\PathsFactory;


    /**
     * Class RemoteVideo
     * @package hiweb\components\RemoteVideos
     * @version 1.2
     */
	class RemoteVideo{

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
					$this->id = PathsFactory::get( $this->url )->url()->params()->get_value( 'v' );
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


        /**
         * @version 1.2
         */
		private function setup_data(){
			if( $this->data_is_setuped ){
				//do nothing
			} else {
				$data = CacheFactory::get( $this->get_id(), __CLASS__, function(){
					/** @var RemoteVideo $RemoteVideo */
					$RemoteVideo = func_get_arg( 0 );
					if( !$RemoteVideo instanceof RemoteVideo ) return;
					if( $RemoteVideo->is_youtube() ){
						$youtube = "https://www.youtube.com/oembed?url=" . $RemoteVideo->url . "&format=json";
						$curl = curl_init( $youtube );
						curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
						$return = curl_exec( $curl );
						curl_close( $curl );
						return json_decode( $return, true );
					} elseif( $RemoteVideo->is_vimeo() ) {
						$vimeo = "https://vimeo.com/api/v2/video/" . $this->get_id() . ".json";
						$curl = curl_init( $vimeo );
						curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
						$return = curl_exec( $curl );
						curl_close( $curl );
						return json_decode( $return, true );
					}
					return [];
				}, [ $this ], true )->get_value();
				///SET DATA
				if( $this->is_youtube() ){
					if( isset( $data['thumbnail_url'] ) ){
						$this->thumbnail_url = $data['thumbnail_url'];
					}
					if( isset( $data['title'] ) ){
						$this->title = $data['title'];
					}
					if( isset( $data['html'] ) ){
						//$this->html = '<div class="hiweb-remote-video-html">' . $data['html'] . '</div>';
						$this->html = '<div class="hiweb-remote-video-html"><iframe src="https://www.youtube.com/embed/'.$this->get_id().'?rel=0&modestbranding=1" frameborder="0"
    allow="accelerometer; encrypted-media; gyroscope; picture-in-picture;" allowfullscreen></iframe></div>';
					}
				} elseif( $this->is_vimeo() ) {
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