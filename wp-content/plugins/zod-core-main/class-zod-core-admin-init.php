<?php
if( !class_exists( 'ZOD_Core_Admin_Init' ) ){
	class ZOD_Core_Admin_Init {

		public function __construct(){
			add_action( 'init', array( $this, 'zod_core_setup_hook' ) );
		}
        
		public function zod_core_setup_hook(){

      $settings = get_option( 'zod_core_settings' );

      // Requiring Admin Menu
      if( is_admin() ){
        require ZOD_CORE_INC_DIR . '/admin/class-zod-core-admin.php';
      }

			// Requiring Metabox
			// Requiring Custom Post Type
			
			// Requiring Elementor Widgets from ZOD Core
			if( class_exists( '\Elementor\Plugin' ) ) {
				require_once ZOD_CORE_INC_DIR . '/widgets/class-zod-core-widgets.php';
			}


			// Requiring Tracking Code
      if( isset( $settings['zod_404'] ) && $settings['zod_404'] == 'on' ){
        require_once ZOD_CORE_INC_DIR . '/modules/404/class-zod-core-404.php';
      }

			// Requiring Tracking Code
      if( isset( $settings['zod_tracking_code'] ) && $settings['zod_tracking_code'] == 'on' ){
        require_once ZOD_CORE_INC_DIR . '/modules/tracking-code/class-zod-core-tracking-code.php';
      }

			// Requiring Image Compress
      if( isset( $settings['zod_image_compress'] ) && $settings['zod_image_compress'] == 'on' ){
        require_once ZOD_CORE_INC_DIR . '/modules/image-compress/class-zod-core-image-compress.php';
      }

      // Requiring Redirect Link
      if( isset( $settings['zod_redirect_link'] ) && $settings['zod_redirect_link'] == 'on' ){
        require_once ZOD_CORE_INC_DIR . '/modules/redirect-link/class-zod-core-redirect-link.php';
      }
		}
	}

	new ZOD_Core_Admin_Init();
}