<?php

	namespace hiweb\components\RemoteVideos;


	use hiweb\core\Cache\CacheFactory;


	class RemoteVideosFactory{


		/**
		 * @param $url
		 * @return RemoteVideo
		 */
		static function get( $url ){
			return CacheFactory::get( $url, __CLASS__ . '::$videos', function(){
				return new RemoteVideo( func_get_arg( 0 ) );
			}, $url )();
		}

	}