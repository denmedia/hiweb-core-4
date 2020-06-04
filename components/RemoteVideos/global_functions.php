<?php

	use hiweb\components\RemoteVideos\RemoteVideo;
	use hiweb\components\RemoteVideos\RemoteVideosFactory;


	if( !function_exists( 'get_remote_video' ) ){

		/**
		 * Return RemoteVideo instance
		 * @param string $url
		 * @return RemoteVideo
		 */
		function get_remote_video( $url ){
			return RemoteVideosFactory::get( $url );
		}
	}