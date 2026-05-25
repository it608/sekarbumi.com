<?php
if( !class_exists( 'Sekarbumi_Social_Account' ) ){

	class Sekarbumi_Social_Account {

		public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_include_social_account_customizer();
			}
			add_shortcode( 'sekarbumi_widget_social_account', array( $this, 'sekarbumi_social_render' ) );
			add_action( 'widgets_init', array( $this, 'sekarbumi_social_widget' ) );
		}

		public function sekarbumi_include_social_account_customizer(){

			Kirki::add_section( 'sekarbumi_social_section', array(
				'title'		=> esc_html__( 'Social Account', 'sekarbumi' ),
				'panel'		=> 'sekarbumi_panel',
				'priority'	=> 40,
			) );

			if( is_customize_preview() ){
				include_once get_template_directory() . '/inc/modules/social-account/social-account-customizer.php';
			}

		}

		public function sekarbumi_social_render( $atts ){
			$default_value = array(
				array(
					'socmed_type'   => 'facebook'
				),
				array(
					'socmed_url'    => 'https://www.facebook.com/'
				)
			);

			$social_account = get_theme_mod( 'social_account', $default_value );
			$output = '';

			if ( is_array( $social_account ) && !empty( $social_account ) ) {

				$output = '<div class="zod-social-account"><ul>';

				foreach ( $social_account as $key => $value ) {

					$output .= sprintf(
						'
						<li>
							<a href="%1$s" rel="noopener nofollow" target="_blank">
								<i uk-icon="icon: %2$s; ratio:%3$s;"></i>
							</a>
						</li>
						',
						!empty( $value['socmed_url'] ) ? esc_url( $value['socmed_url'] ) : '#',
						!empty( $value['socmed_type'] ) ? esc_attr( $value['socmed_type'] ) : '',
            isset( $atts['icon_ratio'] ) ? esc_attr( $atts['icon_ratio'] ) : '1.3'
					);

				}

				$output .= '</ul></div>';

			}

			echo apply_filters( 'sekarbumi_social_accounts', $output );
	
		}

		public function sekarbumi_social_widget(){
      include get_template_directory() . '/inc/modules/social-account/social-account-widget.php';
			register_widget( 'Sekarbumi_Widget_Social_Account' );
		}

	}

	new Sekarbumi_Social_Account();

}