<?php
if( !class_exists( 'ZOD_Core_Tracking_Code' ) ){
    class ZOD_Core_Tracking_Code {
        public function __construct(){
			add_action( 'wp_head', array( $this, 'zod_core_tc_ga_render' ) );
			add_action( 'wp_head', array( $this, 'zod_core_tc_fbp_render' ) );
        }

		/**
		 * zod_core_tc_ga_render
		 * Render GA Code to wp_head
		 * @params -
		 * @hooks - wp_head
		*/
		public function zod_core_tc_ga_render(){
            $settings = get_option('zod_core_settings');
			$ga_code = isset( $settings['zod_ga_code'] ) ? $settings['zod_ga_code'] : '';
			if( !empty( $ga_code ) ){
				?>
				<!--- Google Analytics Code --->
				<script async src="https://www.googletagmanager.com/gtag/js?id=<?php esc_html_e( $ga_code ); ?>"></script>
				<script>
					window.dataLayer = window.dataLayer || [];
					function gtag(){
						dataLayer.push(arguments);
					}
					gtag('js', new Date());
					gtag('config', '<?php echo esc_attr( $gaCode );?>');
				</script>
				<!--- End Google Analytics Code --->
				<?php
			}
		}

		/**
		 * zod_core_tc_fbp_render
		 * Render Facebook Pixel Code to wp_head
		 * @params -
		 * @hooks - wp_head
		*/
		public function zod_core_tc_fbp_render(){
            $settings = get_option('zod_core_settings');
			$fbp_code = isset( $settings['zod_fbp_code'] ) ? $settings['zod_fbp_code'] : '';
			if( !empty( $fbp_code ) ){
				?>
				<!-- Facebook Pixel Code -->
				<script>
				!function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window, document,'script',
				'https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '<?php esc_attr_e( $fbp_code );?>');
				fbq('track', 'PageView');
				</script>
				<noscript>
				<img height="1" width="1" style="display:none" 
					src="https://www.facebook.com/tr?id={your-pixel-id-goes-here}&ev=PageView&noscript=1"/>
				</noscript>
				<!-- End Facebook Pixel Code -->
				<?php
			}
		}

    }

    new ZOD_Core_Tracking_Code();
}