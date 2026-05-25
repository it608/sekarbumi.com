<?php
if( !class_exists( 'Sekarbumi_Header' ) ){

	class Sekarbumi_Header {

		public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_include_header_customizer();
			}
			add_action( 'sekarbumi_header_logo', array( $this, 'sekarbumi_get_header_logo' ), 10 );
			add_action( 'sekarbumi_mobile_logo', array( $this, 'sekarbumi_get_mobile_logo' ), 10 );
			add_filter( 'sekarbumi_default_styles', array( $this, 'sekarbumi_header_styles' ), 10 );

		}

		public function sekarbumi_include_header_customizer(){

			Kirki::add_section( 'sekarbumi_header_section', array(
				'title'		=> esc_html__( 'Header', 'sekarbumi' ),
				'panel'		=> 'sekarbumi_panel',
				'priority'	=> 20,
			) );
			
			if( is_customize_preview() ){
				include_once get_template_directory() . '/inc/modules/header/header-customizer.php';
			}

		}

		public function sekarbumi_get_mobile_logo(){
			$customizer_logo 	= get_theme_mod( 'sekarbumi_mobile_logo', '' );

			$output = sprintf(
				'
				<div class="mobile-logo">
					<a href="%1$s">
						%2$s
					</a>
				</div>
				',
				get_home_url(),
				!empty( $customizer_logo ) ? wp_get_attachment_image( $customizer_logo, 'full', false, array( 'class' => 'cf-logo' ) ) : ''
			);

			echo apply_filters( 'sekarbumi_render_mobile_logo', $output );
		}

		public function sekarbumi_header_styles( $default_styles ){

			$header_background 			= get_theme_mod( 'header_bg', array() );

			if( $header_background ){
        $header_background_color 		  = isset( $header_background['background-color'] ) ? $header_background['background-color'] : '';
        $header_background_image 		  = isset( $header_background['background-image'] ) ? $header_background['background-image'] : '';
        $header_background_repeat		  = isset( $header_background['background-repeat'] ) ? $header_background['background-repeat'] : '';
        $header_background_position	  = isset( $header_background['background-position'] ) ? $header_background['background-position'] : '';
        $header_background_size		    = isset( $header_background['background-size'] ) ? $header_background['background-size'] : '';
        $header_background_attachment	= isset( $header_background['background-attachment'] ) ? $header_background['background-attachment'] : '';
			}
			
			$header_color		  = get_theme_mod( 'header_color', '#565350' );
			$header_hover	    = get_theme_mod( 'header_color_hover', '#2D4E13' );
			$header_sub_color	= get_theme_mod( 'header_submenu_color', '#565350' );
			$header_sub_hover	= get_theme_mod( 'header_submenu_hover', '#2D4E13' );

			$default_styles .= sprintf(
				'
				#masthead {
					background-color: %1$s;
					background-image: url(%2$s);
					background-repeat: %3$s;
					background-position: %4$s;
					background-size: %5$s;
					background-attachment: %6$s;
				}
				#masthead .menu > li > a,
        .zod-tp-switcher li > a {
					color: %7$s !important;
				}
				#masthead .menu > li:hover > a,
        #masthead .menu > li.current-menu-item > a,
        #masthead .menu > li.current-menu-parent > a,
        .zod-tp-switcher li:hover > a,
        .zod-tp-switcher li.current-language-menu-item > a {
					color: %8$s !important;
				}
        #masthead a.uk-icon svg circle,
        #masthead a.uk-icon svg path,
        #masthead a.cf-mobile-toggle svg {
            stroke: %7$s;
        }
				#masthead a.uk-icon:hover svg circle,
				#masthead a.uk-icon:hover svg path,
        #masthead a.cf-mobile-toggle:hover svg {
          stroke: %8$s !important;
				}
        .uk-navbar-nav > li > a:before {
            background: %8$s;
        }
				.uk-navbar-nav a:before {
					background-color: %8$s;
				}
        #masthead .sub-menu > li > a {
          color: %9$s;
        }
        #masthead .sub-menu > li:hover > a,
        #masthead .sub-menu > li.current-menu-item > a {
          color: %10$s;
        }
				',
				!empty( $headerBgColor ) ? esc_attr( $headerBgColor ) : '#fff', // 1
				!empty( $headerBgImage ) ? esc_attr( $headerBgImage ) : '', // 2
				!empty( $headerBgRepeat ) ? esc_attr( $headerBgRepeat ) : '', // 3
				!empty( $headerBgPosition ) ? esc_attr( $headerBgPosition ) : '', // 4
				!empty( $headerBgSize ) ? esc_attr( $headerBgSize ) : '', // 5
				!empty( $headerBgAttachment ) ? esc_attr( $headerBgAttachment ) : '', // 6
				!empty( $header_color ) ? esc_attr( $header_color ) : '#565350', // 7
				!empty( $header_hover ) ? esc_attr( $header_hover ) : '#2D4E13', // 8
        !empty( $header_sub_color ) ? esc_attr( $header_sub_color ) : '#000', //9
        !empty( $header_sub_hover ) ? esc_attr( $header_sub_hover ) : '#FCBB29' //10
			);

			return $default_styles;

		}

	}

	new Sekarbumi_Header();

}