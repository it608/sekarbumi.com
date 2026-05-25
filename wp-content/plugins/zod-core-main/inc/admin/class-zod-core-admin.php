<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'ZOD_Core_Admin' ) ){
	class ZOD_Core_Admin {
		public function __construct(){
			add_action( 'admin_enqueue_scripts', array( $this, 'zod_core_admin_script' ) );
			add_action( 'admin_menu', array( $this, 'zod_core_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'zod_core_admin_register_settings' ) );
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'zod_core_filter_plugin_actions' ), 10, 2 );
		}

		public function zod_core_admin_script(){
            wp_enqueue_style( 'zod-core-admin', ZOD_CORE_INC_URI . '/admin/assets/css/zod-core-admin.css', array(), ZOD_CORE_VERSION );
            wp_enqueue_script( 'zod-core-admin', ZOD_CORE_INC_URI . '/admin/assets/js/zod-core-admin.js', array(), ZOD_CORE_VERSION, true );
		}

        public function zod_core_filter_plugin_actions( $links, $file ){
			$settings_link = '<a href="options-general.php?page=zog-ga-page">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		public function zod_core_admin_menu(){
			add_menu_page( 
				esc_html__( 'ZOD Core Settings', 'zod-core' ),
				esc_html__( 'ZOD Core', 'zod-core' ) ,
				'manage_options',
				'zod_core_settings',
				array( $this, 'zod_core_admin_page' ),
				ZOD_CORE_INC_URI . '/admin/assets/images/zod-icon.png',
				2
			);
		}

		public function zod_core_admin_register_settings(){
			register_setting( 'zod_core_settings', 'zod_core_settings', array( $this, 'zod_core_admin_sanitize' ) );
		}

		public function zod_core_admin_sanitize( $options ){
			if( $options ) {
                // Checkbox
                if ( ! empty( $options['zod_404'] ) ) {
					$options['zod_404'] = 'on';
				} else {
					unset( $options['zod_404'] ); // Remove from options if not checked
				}
                if ( ! empty( $options['zod_redirect_link'] ) ) {
					$options['zod_redirect_link'] = 'on';
				} else {
					unset( $options['zod_redirect_link'] ); // Remove from options if not checked
				}
                if ( ! empty( $options['zod_image_compress'] ) ) {
					$options['zod_image_compress'] = 'on';
				} else {
					unset( $options['zod_image_compress'] ); // Remove from options if not checked
				}
                if ( ! empty( $options['tracking_code'] ) ) {
					$options['tracking_code'] = 'on';
				} else {
					unset( $options['tracking_code'] ); // Remove from options if not checked
				}

				// Input
				if ( ! empty( $options['image_quality'] ) ) {
					$options['image_quality'] = sanitize_text_field( $options['image_quality'] );
				} else {
					unset( $options['image_quality'] ); // Remove from options if empty
				}
				if ( ! empty( $options['zod_ga_code'] ) ) {
					$options['zod_ga_code'] = sanitize_text_field( $options['zod_ga_code'] );
				} else {
					unset( $options['zod_ga_code'] ); // Remove from options if empty
				}
				if ( ! empty( $options['zod_fbp_code'] ) ) {
					$options['zod_fbp_code'] = sanitize_text_field( $options['zod_fbp_code'] );
				} else {
					unset( $options['zod_fbp_code'] ); // Remove from options if empty
				}

				// Select Convert JPG
				if ( ! empty( $options['upload_convert_jpg'] ) ) {
					$options['upload_convert_jpg'] = sanitize_text_field( $options['upload_convert_jpg'] );
				}
				if ( ! empty( $options['404_page'] ) ) {
					$options['404_page'] = $options['404_page'];
				}
			}
			return $options;
		}

		public function zod_core_admin_page(){
            $settings = get_option( 'zod_core_settings' );
			?>
            <form method="post" action="options.php">
                
                <?php settings_fields( 'zod_core_settings' ); ?>

                <div class="zod-core-admin">
                    <div class="zod-core-admin-header">
                        <img src="<?php echo esc_url( ZOD_CORE_INC_URI . '/admin/assets/images/zod-logo.png' ); ?>" />
                    </div>
                    <div class="zod-core-admin-container">
                        <div class="zod-core-admin-menu">
                            <ul class="zod-core-list zod-core-admin-tabs">
                                <li><a href="#modules"><?php esc_html_e( 'Modules', 'zod-core' ); ?></a></li>
                                <li><a href="#404"><?php esc_html_e( '404', 'zod-core' ); ?></a></li>
                                <li><a href="#redirect_link"><?php esc_html_e( 'Redirect Link', 'zod-core' ); ?></a></li>
                                <li><a href="#image_compress"><?php esc_html_e( 'Image Compress', 'zod-core' ); ?></a></li>
                                <li><a href="#tracking_code"><?php esc_html_e( 'Tracking Code', 'zod-core' ); ?></a></li>
                            </ul>
                        </div>
                        <div class="zod-core-admin-content">
                            <div id="modules" class="tabs-content"><?php include ZOD_CORE_INC_DIR . '/admin/template/modules.php'; ?></div>
                            <div id="404" class="tabs-content"><?php include ZOD_CORE_INC_DIR . '/admin/template/404.php'; ?></div>
                            <div id="redirect_link" class="tabs-content"><?php include ZOD_CORE_INC_DIR . '/admin/template/redirect-link.php'; ?></div>
                            <div id="image_compress" class="tabs-content"><?php include ZOD_CORE_INC_DIR . '/admin/template/image-compress.php'; ?></div>
                            <div id="tracking_code" class="tabs-content"><?php include ZOD_CORE_INC_DIR . '/admin/template/tracking-code.php'; ?></div>
                        </div>
                    </div>
                    <?php submit_button(); ?>
                </div>
            </form>
			<?php
		}
	}

	new ZOD_Core_Admin();
}