<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 13/12/2018
	 * Time: 15:25
	 */

	///UPLOAD FILTER IMAGES
	use hiweb\ArrayObject;
	use hiweb\files;


	add_filter( 'wp_generate_attachment_metadata', function( $metadata, $attachment_id ){
		$image_file = files::get( $attachment_id );
		if( $image_file->get_image_mime_type() == 'image/jpg' || $image_file->get_image_mime_type() == 'image/png' ){
			$metadata_array = ArrayObject::make( $metadata );
			if( is_array( $metadata_array->value_by_key( 'sizes' ) ) ){
				$editor = \hiweb\Images::get_editor( $image_file->get_path() );
				foreach( $metadata_array->value_by_key( 'sizes' ) as $size_name => $size_data ){
					$size_data = ArrayObject::make( $size_data );
					$sub_file = files::get( $image_file->dirname() . '/' . $size_data->value_by_key( 'file' ) );
					if( $sub_file->is_readable() ){
						$sub_file->resize();
						if( \hiweb\Images::$progressive_create_on_upload ){
							foreach( \hiweb\Images::$progressive_types as $extension ){
								$sub_editor = $editor->make_file( $image_file->dirname() . '/' . $image_file->filename() . '-' . $sub_file->width() . 'x' . $sub_file->height() . '.' . $extension, $sub_file->width(), $sub_file->height() );
								if( $sub_editor instanceof \hiweb\images\editor ){
									if( $sub_editor->is_readable() && $sub_editor->aspect() > 0 && $sub_editor->get_size() > 1024 && $sub_editor->get_size() < $sub_file->get_size() ){
										$metadata['sizes'][ $sub_editor->get_dimensions_size_string() . '-' . $extension ] = [
											'file' => $sub_editor->basename(),
											'width' => $sub_editor->width(),
											'height' => $sub_editor->height(),
											'mime-type' => $sub_editor->get_image_mime_type()
										];
									} else {
										//remove if file greater that current similar image size file
										unlink( $sub_editor->get_path() );
									}
								}
							}
						}
					}
				}
				///Original Make Progressive file types
				if( \hiweb\Images::$progressive_create_on_upload ){
					foreach( \hiweb\Images::$progressive_types as $extension ){
						$sub_editor = $editor->make_file( $image_file->dirname() . '/' . $image_file->filename() . '.' . $extension, $editor->width(), $editor->height() );
						if( $sub_editor instanceof \hiweb\images\editor ){

							if( $sub_editor->is_readable() && $sub_editor->aspect() > 0 && $sub_editor->get_size() > 1024 && $sub_editor->get_size() < $editor->get_size() ){
								$metadata['sizes'][ $sub_editor->get_dimensions_size_string() . '-' . $extension ] = [
									'file' => $sub_editor->basename(),
									'width' => $sub_editor->width(),
									'height' => $sub_editor->height(),
									'mime-type' => $sub_editor->get_image_mime_type()
								];
							} else {
								//remove if file greater that original file
								unlink( $sub_editor->get_path() );
							}
						}
					}
				}
				$editor->make_file();
				$metadata['hiweb-optimize'] = microtime( true );
			}
		}
		return $metadata;
	}, 999, 2 );