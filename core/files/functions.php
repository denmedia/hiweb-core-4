<?php

	namespace hiweb;


	/**
	 * @param $pathOrUrlOrAttachID
	 * @return files\file
	 */
	function file( $pathOrUrlOrAttachID ){
		return files::get( $pathOrUrlOrAttachID );
	}