<?php

if( !function_exists( 'zod_core_image_compress_image_has_alpha' ) ){
	function zod_core_image_compress_image_has_alpha($imgdata) {
		$w = imagesx($imgdata);
		$h = imagesy($imgdata);
	
		if($w>50 || $h>50){ //resize the image to save processing if larger than 50px:
			$thumb = imagecreatetruecolor(10, 10);
			imagealphablending($thumb, FALSE);
			imagecopyresized( $thumb, $imgdata, 0, 0, 0, 0, 10, 10, $w, $h );
			$imgdata = $thumb;
			$w = imagesx($imgdata);
			$h = imagesy($imgdata);
		}
		//run through pixels until transparent pixel is found:
		for($i = 0; $i<$w; $i++) {
			for($j = 0; $j < $h; $j++) {
				$rgba = imagecolorat($imgdata, $i, $j);
				if(($rgba & 0x7F000000) >> 24) return true;
			}
		}
		return false;
	}
}

if( !function_exists( 'zod_core_image_compress_convert_upload_image' ) ){
	function zod_core_image_compress_convert_upload_image( $params, $image_type ){
		$settings = get_option( 'zod_core_settings' );
		$imageQuality = !empty( $settings ) ? $settings['image_quality'] : 90;
		$uploadConvertJPG = !empty( $settings ) ? $settings['upload_convert_jpg'] : 0;
		
		$newPath = substr( $params['file'], 0, -4 ) . '.jpg';
		$newUrl = substr( $params['url'], 0, -4 ) . '.jpg';
		$i = 1;
		while( file_exists( $newPath ) ){
			$newPath = substr( $params['file'], 0, -4 ) . '-' . $i . '.jpg';
			$newUrl = substr( $params['url'], 0, -4 ) . '-' . $i . '.jpg';
			++$i;
		}

		switch( $image_type ){
			case 'image/png':
				$img = imagecreatefrompng( $params['file'] );
				if( $uploadConvertJPG == 1 ){

					$bg = imagecreatetruecolor( imagesx( $img ), imagesy( $img ) );
					imagefill( $bg, 0, 0, imagecolorallocate( $bg, 255, 255, 255 ) );
					imagealphablending( $bg, 1 );
					imagecopy( $bg, $img, 0, 0, 0, 0, imagesx( $img ), imagesy( $img ) );

					if( imagejpeg( $bg, $newPath, $imageQuality  ) ){ 

						// Delete the Original File
						unlink( $params['file'] );

						$params['file'] = $newPath;
						$params['url'] = $newUrl;
						$params['type'] = 'image/jpeg';
						return $params;
					}
					imagedestroy($img);

				} elseif( $uploadConvertJPG == 2 ) {
					if( zod_core_image_compress_image_has_alpha( $img ) == false ){

						$bg = imagecreatetruecolor( imagesx( $img ), imagesy( $img ) );
						imagefill( $bg, 0, 0, imagecolorallocate( $bg, 255, 255, 255 ) );
						imagealphablending( $bg, 1 );
						imagecopy( $bg, $img, 0, 0, 0, 0, imagesx( $img ), imagesy( $img ) );
						
						if( imagejpeg( $bg, $newPath, $imageQuality  ) ){
							// Delete the Original File
							unlink( $params['file'] );

							$params['file'] = $newPath;
							$params['url'] = $newUrl;
							$params['type'] = 'image/jpeg';
							return $params;
						}
						imagedestroy($img);

					}
				}
				break;
			case 'image/jpg':
				$img = imagecreatefromjpeg( $params['file'] );

				// Delete the Original File
				unlink( $params['file'] );
				if( imagejpeg( $img, $newPath, $imageQuality ) ){
					$params['file'] = $newPath;
					$params['url'] = $newUrl;
					$params['type'] = 'image/jpeg';
					return $params;
				}

				imagedestroy( $img );
				break;
			case 'image/jpeg':
				$i = 1;
				$img = imagecreatefromjpeg( $params['file'] );

				// Delete the Original File
				unlink( $params['file'] );

				$newPath = substr( $params['file'], 0, -5 ) . '.jpeg';
				$newUrl = substr( $params['url'], 0, -5 ) . '.jpeg';
				while( file_exists( $newPath ) ){
					$newPath = substr( $params['file'], 0, -5 ) . '-' . $i . '.jpeg';
					$newUrl = substr( $params['url'], 0, -5 ) . '-' . $i . '.jpeg';
					++$i;
				}

				if( imagejpeg( $img, $newPath, $imageQuality ) ){
					$params['file'] = $newPath;
					$params['url'] = $newUrl;
					$params['type'] = 'image/jpeg';
					return $params;
				}

				imagedestroy( $img );
				break;
		}
	}
}