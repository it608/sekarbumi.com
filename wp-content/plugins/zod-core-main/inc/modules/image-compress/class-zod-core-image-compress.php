<?php
if( !class_exists( 'ZOD_Core_Image_Compress' ) ){
    class ZOD_Core_Image_Compress {

        private $plugin_admin_page, $settings, $tab, $tabs;

		public function __construct(){
      require_once('image-compress-functions.php');

			add_filter( 'intermediate_image_sizes_advanced', array( $this, 'zod_core_image_compress_image_disable_image_sizes' ) );
			add_filter( 'wp_handle_upload', array( $this, 'zod_core_image_compress_image_upload_handle' ) );
			add_action( 'admin_notices', array( $this, 'zod_core_image_compress_image_server_gd_library' ) );
		}

		public function zod_core_image_compress_image_server_gd_library(){
			if( !function_exists('imagecreatefrompng') || !function_exists( 'imagecreatefromjpeg' ) ){
				$output = sprintf(
					'<div class="error"><p>%1$s</p></div>',
					__( 'ZOG Image Compress requires gd library enabled!', 'zod-core' )
				);
				echo apply_filters( 'zod_core_image_compress_image_server_gd_library', $output );
			}
		}

		public function zod_core_image_compress_image_disable_image_sizes( $sizes ){
			$settings = get_option('zod_core_settings');

			if( $settings['disable_image_size'] == 1 ){
				$imageSizes = get_intermediate_image_sizes();
				foreach( $imageSizes as $name ){
					unset( $sizes[ $name ] );
				}
			}
			return $sizes;
		}

		public function zod_core_image_compress_image_upload_handle( $params ){
			$imageType = $params['type'];
			$newParams = zod_core_image_compress_convert_upload_image( $params, $imageType );

			if( $newParams ){
				$params = $newParams;
			}

			return $params;
		}

    }

    new ZOD_Core_Image_Compress();
}