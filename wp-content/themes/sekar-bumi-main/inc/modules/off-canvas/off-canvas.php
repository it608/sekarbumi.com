<?php
if( !class_exists( 'Sekarbumi_Off_Canvas' ) ){
    class Sekarbumi_Off_Canvas {
      public function __construct(){
        if( class_exists( 'Kirki' ) ){
          $this->sekarbumi_include_off_canvas_customizer();
        }
        add_filter( 'sekarbumi_default_styles', array( $this, 'sekarbumi_off_canvas_styles' ), 10 );
      }

      public function sekarbumi_include_off_canvas_customizer(){
        Kirki::add_section( 'sekarbumi_off_canvas', array(
          'title'		=> esc_html__( 'Off Canvas/Mobile Menu', 'sekarbumi' ),
          'panel'		=> 'sekarbumi_panel',
          'priority'	=> 20,
        ) );

        if( is_customize_preview() ){
          include_once get_template_directory() . '/inc/modules/off-canvas/off-canvas-customizer.php';
        }

      }

      public function sekarbumi_off_canvas_styles( $default_styles ){

        $off_canvas				              = get_theme_mod( 'off_canvas_bg', array() );
        $off_canvas_color			          = get_theme_mod( 'off_canvas_color', '#fff' );
        $off_canvas_hover_color		      = get_theme_mod( 'off_canvas_hover_color', '#000' );
        $off_canvas_toggle_color	      = get_theme_mod( 'off_canvas_toggle_color', '#000' );
        $off_canvas_toggle_hover_color	= get_theme_mod( 'off_canvas_toggle_hover_color', '#000' );

        if( $off_canvas ){
          $off_canvas_bg_color 		  = isset( $off_canvas['background-color'] ) ? $off_canvas['background-color'] : '';
          $off_canvas_image 		  = isset( $off_canvas['background-image'] ) ? $off_canvas['background-image'] : '';
          $off_canvas_repeat		  = isset( $off_canvas['background-repeat'] ) ? $off_canvas['background-repeat'] : '';
          $off_canvas_position	  = isset( $off_canvas['background-position'] ) ? $off_canvas['background-position'] : '';
          $off_canvas_size			  = isset( $off_canvas['background-size'] ) ? $off_canvas['background-size'] : '';
          $off_canvas_attachment	= isset( $off_canvas['background-attachment'] ) ? $off_canvas['background-attachment'] : '';
        }
        
        $default_styles .= sprintf(
          '
          .off-canvas-wrapper > div {
            background-color: %1$s;
            background-image: url(%2$s);
            background-repeat: %3$s;
            background-position: %4$s;
            background-size: %5$s;
            background-attachment: %6$s;
          }
          .off-canvas-wrapper * {
            color: %7$s !important;
          }
          .off-canvas-wrapper *:hover {
            color: %8$s !important;
          }
          .off-canvas-wrapper a:hover svg {
            fill: %8$s;
          }
          .mobile-nav a svg {
            fill: %9$s;
          }
          .mobile-nav a:hover svg {
            fill: %10$s;
          }
          ',
          !empty( $off_canvas_bg_color ) ? esc_attr( $off_canvas_bg_color ) : '', //1
          !empty( $off_canvas_image ) ? esc_attr( $off_canvas_image ) : '', //2
          !empty( $off_canvas_repeat ) ? esc_attr( $off_canvas_repeat ) : '', //3
          !empty( $off_canvas_position ) ? esc_attr( $off_canvas_position ) : '', //4
          !empty( $off_canvas_size ) ? esc_attr( $off_canvas_size ) : '', //5
          !empty( $off_canvas_attachment ) ? esc_attr( $off_canvas_attachment ) : '', //6
          !empty( $off_canvas_color ) ? esc_attr( $off_canvas_color ) : '', //7
          !empty( $off_canvas_hover_color ) ? esc_attr( $off_canvas_hover_color ) : '', //8
          !empty( $off_canvas_toggle_color ) ? esc_attr( $off_canvas_toggle_color ) : '', //9
          !empty( $off_canvas_toggle_hover_color ) ? esc_attr( $off_canvas_toggle_hover_color ) : '' //10
        );

        return $default_styles;

      }
    }

	new Sekarbumi_Off_Canvas();
} 