<?php
if( !class_exists( 'Sekarbumi_Lazyload' ) ){

	class Sekarbumi_Lazyload {
		public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_include_lazyload_customizer();
			}
			add_filter( 'wp_get_attachment_image_attributes', array( $this, 'sekarbumi_lazy_load' ), 9999, 3 );
			add_filter( 'wp_lazy_loading_enabled', '__return_false' );
		}

		public function sekarbumi_include_lazyload_customizer(){

			Kirki::add_section( 'sekarbumi_lazyload_section', array(
				'title'		=> esc_html__( 'Lazyload', 'sekarbumi' ),
				'panel'		=> 'sekarbumi_panel',
				'priority'	=> 50,
			) );
			
			if( is_customize_preview() ){
				include_once get_template_directory() . '/inc/modules/lazyload/lazyload-customizer.php';
			}

		}

		public function sekarbumi_is_lazy_load(){
			$lazyload = get_theme_mod( 'lazy_image', true );
			return !empty( $lazyload ) && $lazyload == 'on' ? true : false;
		}

		public function sekarbumi_lazy_load( $attr, $a, $size ){
			
			if( $this->sekarbumi_is_lazy_load() ){
					
				if( isset( $attr['data-is-ignore-lazyload'] ) ){
					return $attr;
				}
				
				$attr['class'] = $attr['class'];
				
				if( isset( $attr['src'] ) ){
					$attr['data-src'] = $attr['src'];
					unset( $attr['src'] );		
				}
				

				if ( isset( $attr['srcset'] ) ) {
					$attr['data-srcset'] = $attr['srcset'];
					unset( $attr['srcset'] );
				}
				

				if ( isset( $attr['loading'] ) ) {
					unset( $attr['loading'] );
				}

				if( !isset( $attr['data-uk-img'] ) ){
					$attr['data-uk-img'] = '';
				}

			}

			return $attr;

		}
	}

	new Sekarbumi_Lazyload();

}