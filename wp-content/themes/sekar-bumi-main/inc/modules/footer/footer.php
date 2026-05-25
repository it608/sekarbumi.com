<?php
if( !class_exists( 'Sekarbumi_Footer' ) ){

    class Sekarbumi_Footer {

        public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_include_footer_customizer();
			}
			add_action( 'widgets_init', array( $this, 'sekarbumi_footer_register_sidebar' ) );
			add_filter( 'dynamic_sidebar_params' , array( $this, 'sekarbumi_widget_title_tag' ) );
			add_action( 'sekarbumi_copyright', array( $this, 'sekarbumi_footer_copyright' ) );
			add_action( 'sekarbumi_footer_text', array( $this, 'sekarbumi_get_footer_text' ) );
			add_action( 'sekarbumi_footer_mail', array( $this, 'sekarbumi_get_footer_mail' ) );
			add_filter( 'sekarbumi_default_styles', array( $this, 'sekarbumi_footer_styles' ), 10 );

        }

		public function sekarbumi_include_footer_customizer(){

			Kirki::add_section( 'sekarbumi_footer_section', array(
				'title'		=> esc_html__( 'Footer', 'sekarbumi' ),
				'panel'		=> 'sekarbumi_panel',
				'priority'	=> 60,
			) );

			if( is_customize_preview() ){
				include_once get_template_directory() . '/inc/modules/footer/footer-customizer.php';
			}

		}

    public function sekarbumi_footer_register_sidebar(){
			for( $i = 1; $i <= 5; $i++ ){
				register_sidebar(
					array(
						'name'          => __( 'Footer Section 1 Widget ' . $i, 'sekarbumi' ),
						'id'            => 'footer-' . $i,
						'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'sekarbumi' ),
						'before_widget' => '<li id="%1$s" class="widget %2$s">',
						'after_widget'  => '</li>',
						'before_title'  => '<h2 class="widgettitle">',
						'after_title'   => '</h2>',
					)
				);
			}
			for( $i = 1; $i <= 2; $i++ ){
				register_sidebar(
					array(
						'name'          => __( 'Footer Section 2 Widget ' . $i, 'sekarbumi' ),
						'id'            => 'footer-2-' . $i,
						'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'sekarbumi' ),
						'before_widget' => '<li id="%1$s" class="widget %2$s">',
						'after_widget'  => '</li>',
						'before_title'  => '<h2 class="widgettitle">',
						'after_title'   => '</h2>',
					)
				);
			}
    }

		public function sekarbumi_widget_title_tag( $params ){
			$params[0]['before_title'] =  '<h4 class="widget-title">' ;
			$params[0]['after_title'] =  '</h4>' ;
			return $params;
		}

		public function sekarbumi_footer_copyright(){
			
      $get_copyright_text = get_theme_mod( 'footer_copyright_text', 'Copyright ©' );

			$copyright_text = sprintf(
				'%1$s',
        esc_html__( $get_copyright_text, 'sekarbumi' ),
			);

			$copyright = sprintf(
				'
				<div class="zod-copyright-text">
					<p class="uk-margin-remove">%1$s</p>
				</div>
				',
				!empty( $copyright_text ) ? esc_html__( $copyright_text, 'sekarbumi' ) : ''
			);

			echo apply_filters( 'sekarbumi_copyrights', $copyright );
    }

		public function sekarbumi_footer_styles( $default_styles ){

			$footer_bg				= get_theme_mod( 'footer_bg', array() );
			$footer_color			= get_theme_mod( 'footer_color', '#fff' );
			$footer_link_color		= get_theme_mod( 'footer_link_color', '#fff' );
			$footer_hover_color		= get_theme_mod( 'footer_hover_color', '#000' );
			$footer_widget_title	= get_theme_mod( 'footer_widget_title_color', '#040000' );

			if( $footer_bg ){
				$footer_bg_color 		  = isset( $footer_bg['background-color'] ) ? $footer_bg['background-color'] : '';
				$footer_bg_image 		  = isset( $footer_bg['background-image'] ) ? $footer_bg['background-image'] : '';
				$footer_bg_repeat		  = isset( $footer_bg['background-repeat'] ) ? $footer_bg['background-repeat'] : '';
				$footer_bg_position	  = isset( $footer_bg['background-position'] ) ? $footer_bg['background-position'] : '';
				$footer_bg_size			  = isset( $footer_bg['background-size'] ) ? $footer_bg['background-size'] : '';
				$footer_bg_attachment	= isset( $footer_bg['background-attachment'] ) ? $footer_bg['background-attachment'] : '';
			}
			
			$default_styles .= sprintf(
				'
				#footer {
					background-color: %1$s;
					background-image: url(%2$s);
					background-repeat: %3$s;
					background-position: %4$s;
					background-size: %5$s;
					background-attachment: %6$s;
				}
				#footer *:not(.widget-title):not(.sekarbumi-social-account a):not(.wpcf7-submit):not(.wpcf7-not-valid-tip) {
					color: %7$s;
				}
				#footer a {
					color: %8$s !important;
				}
				.bt-social-account svg {
					fill: %8$s;
				}
				#footer a:hover {
					color: %9$s !important;
				}
				.bt-social-account a:hover svg {
					fill: %9$s;
				}
				#footer .widget-title {
					color: %10$s;
				}
				',
				!empty( $footer_bg_color ) ? esc_attr( $footer_bg_color ) : '', //1
				!empty( $footer_bg_image ) ? esc_attr( $footer_bg_image ) : '', //2
				!empty( $footer_bg_repeat ) ? esc_attr( $footer_bg_repeat ) : '', //3
				!empty( $footer_bg_position ) ? esc_attr( $footer_bg_position ) : '', //4
				!empty( $footer_bg_size ) ? esc_attr( $footer_bg_size ) : '', //5
				!empty( $footer_bg_attachment ) ? esc_attr( $footer_bg_attachment ) : '', //6
				!empty( $footer_color ) ? esc_attr( $footer_color ) : '', //7
				!empty( $footer_link_color ) ? esc_attr( $footer_link_color ) : '', //8
				!empty( $footer_hover_color ) ? esc_attr( $footer_hover_color ) : '', //9
				!empty( $footer_widget_title ) ? esc_attr( $footer_widget_title ) : '#040000' //10
			);

			return $default_styles;

		}

    }

	new Sekarbumi_Footer();
} 