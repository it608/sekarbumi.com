<?php
if( !class_exists( 'Sekarbumi_Site_Identity' ) ){

	class Sekarbumi_Site_Identity {

		public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_include_site_identity_customizer();
			}
			add_action( 'sekarbumi_logo', array( $this, 'sekarbumi_get_site_logo' ) );
			add_action( 'sekarbumi_footer_logo', array( $this, 'sekarbumi_get_footer_logo' ) );
			add_action( 'sekarbumi_default_styles', array( $this, 'sekarbumi_site_identity_styles' ), 20 );
		}

		public function sekarbumi_include_site_identity_customizer(){

			Kirki::add_section( 'sekarbumi_site_identity_section', array(
				'title'		=> esc_html__( 'Site Identity', 'sekarbumi' ),
				'panel'		=> 'sekarbumi_panel',
				'priority'	=> 10,
			) );
			
			if( is_customize_preview() ){
				include_once get_template_directory() . '/inc/modules/site-identity/site-identity-customizer.php';
			}
		}

		public function sekarbumi_get_site_logo(){
			$get_customizer_logo 	= get_theme_mod( 'sekarbumi_site_logo', '' );
			$get_metabox_logo		= get_post_meta( get_the_ID(), 'custom_logo', true );

			$customizer_logo = !empty( $get_customizer_logo ) ? wp_get_attachment_image( $get_customizer_logo, 'full', false, array( 'class' => 'zod-client-logo' ) ) : '';
			$metabox_logo = !empty( $get_metabox_logo ) ? wp_get_attachment_image( $get_metabox_logo, 'full', false, array( 'class' => 'zod-client-logo' ) ) : '';

			$output = sprintf(
				'
				<div class="site-logo">
					<a href="%1$s">
						%2$s
					</a>
				</div>
				',
				get_home_url(),
				!empty( $metabox_logo ) ? $metabox_logo : $customizer_logo
			);

			echo apply_filters( 'sekarbumi_logos', $output );
		}

		public function sekarbumi_get_footer_logo(){
			$get_footer_logo 	= get_theme_mod( 'sekarbumi_footer_logo', '' );
			$footer_logo = !empty( $get_footer_logo ) ? wp_get_attachment_image( $get_footer_logo, 'full', false, array( 'class' => 'zod-client-logo' ) ) : '';

			$output = sprintf(
				'
				<div class="footer-logo">
					<a href="%1$s">
						%2$s
					</a>
				</div>
				',
				get_home_url(),
				!empty( $footer_logo ) ? $footer_logo : ''
			);

			echo apply_filters( 'sekarbumi_footer_logos', $output );
		}

		public function sekarbumi_site_identity_styles( $default_styles ){

			$logo_sizes_customizer 		  	= get_theme_mod('sekarbumi_logo_sizes', 200 );
			$logo_sizes_mobile_customizer = get_theme_mod('sekarbumi_logo_sizes_mobile', 200 );
			$logo_sizes_metabox				    = get_post_meta( get_the_ID(), 'logo_sizes', true );
			$logo_sizes_mobile_metabox		= get_post_meta( get_the_ID(), 'logo_sizes_mobile', true );
			
			$default_styles .= sprintf(
				'
				.zod-client-logo {
					max-width: %1$spx;
				}
				@media only screen and (max-width: 1024px){
					.zod-client-logo {
						max-width: %2$spx;
					}
				}
				',
				!empty( $logo_sizes_metabox ) ? esc_attr( $logo_sizes_metabox ) : esc_attr( $logo_sizes_customizer ),
				!empty( $logo_sizes_mobile_metabox ) ? esc_attr( $logo_sizes_mobile_metabox ) : esc_attr( $logo_sizes_mobile_customizer )
			);

			return $default_styles;
		}


	}

	new Sekarbumi_Site_Identity();

}