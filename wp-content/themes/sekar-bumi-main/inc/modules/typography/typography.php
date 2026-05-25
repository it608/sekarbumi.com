<?php
if( !class_exists( 'Sekarbumi_Typography' ) ){

	class Sekarbumi_Typography {

		public $main_font 				= '';
		public $secondary_font			= '';
		public $main_font_weight		= '';
		public $main_font_style			= '';
		public $secondary_font_weight	= '';
		public $secondary_font_style	= '';
		public $google_font				= '';

		public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_include_typography_customizer();
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'sekarbumi_default_fonts' ), 5 );
			add_action( 'wp_enqueue_scripts', array( $this, 'sekarbumi_enqueue_google_fonts' ), 6 );
      add_filter( 'sekarbumi_default_styles', array( $this, 'sekarbumi_typography_default_styles' ), 10 );
		}

		public function sekarbumi_include_typography_customizer(){

			Kirki::add_section( 'sekarbumi_typography_section', array(
				'title'		=> esc_html__( 'Typography', 'sekarbumi' ),
				'panel'		=> 'sekarbumi_panel',
				'priority'	=> 30,
			) );

			if( is_customize_preview() ){
				include_once get_template_directory() . '/inc/modules/typography/typography-customizer.php';
			}

		}

		public function sekarbumi_default_fonts(){

      $typographies = array();

      $is_secondary_typo = get_theme_mod( 'enable_secondary_typography', false );

      $typographies['typography_main_font'] = get_theme_mod(
        'typography_main_font',
        array(
          'font-family'    => 'Poppins',
          'variant'        => '600',
        )
      );
      
      if( $is_secondary_typo ) {
        $typographies['typography_secondary_font'] = get_theme_mod(
          'typography_secondary_font',
          array(
            'font-family'    => 'Lato',
            'variant'        => '300',
          )
        );
      }

			$font_families 	= array();

			$weights		= array();

			foreach( $typographies as $type => $typography ) {

				if ( !in_array( $typography['font-family'], $font_families ) ) {

					if ( $type === 'typography_main_font' ) {
						$this->main_font		    = isset( $typography['font-family'] ) ? $typography['font-family'] : '';
						$this->main_font_weight = isset( $typography['font-weight'] ) ? $typography['font-weight'] : '';
						$this->main_font_style	= isset( $typography['font-style'] ) ? $typography['font-style'] : '';
					} else {
						$this->secondary_font			    = isset( $typography['font-family'] ) ? $typography['font-family'] : '';
						$this->secondary_font_weight	= isset( $typography['font-weight'] ) ? $typography['font-weight'] : '';
						$this->secondary_font_style	  = isset( $typography['font-style'] ) ? $typography['font-style'] : '';
					}

					$font_families[] = sprintf(
						'
						%1$s%2$s
						',
						$typography['font-family'],
						':wght@300;400;500;600;700'
					);

				}

			}

			$font_family_request = !empty( $font_families ) ? sprintf( 'family=%s', implode( '&family=', $font_families ) ) : '';

			if ( !empty( $font_family_request ) && ( $typographies['typography_main_font']['font-family'] !== 'Classica Sans' || $typographies['typography_secondary_font']['font-family'] !== 'Basic Sans' ) ) {
				$http = ( !empty($_SERVER['HTTPS'] ) ) ? 'https' : 'http';
				$this->google_font = sprintf(
					'%1$s://fonts.googleapis.com/css2?%2$s',
					$http,
					$font_family_request
				);

			}
		}

		public function sekarbumi_enqueue_google_fonts(){
			if( empty( $this->google_font ) ){return;}

			wp_enqueue_style(
				'sekarbumi-typo-font',
				esc_url( $this->google_font ),
				array(),
				null
			);
		}

    public function sekarbumi_typography_default_styles( $default_styles ){
      
      $is_secondary_typo = get_theme_mod( 'enable_secondary_typography', false );

      $primary_font = get_theme_mod( 'typography_main_font', 
          array(
              'font-family'    => 'Poppins',
              'variant'        => '400',
          ) 
      );

      $secondary_font = get_theme_mod( 'typography_secondary_font', 
          array(
              'font-family'    => 'Lato',
              'variant'        => '400',
          ) 
      );
      
      $default_styles .= sprintf(
          '
          h1,
          h2,
          h3,
          h4,
          h5,
          h6 {
            font-family: %1$s, sans-serif;
          }
          ',
          isset( $primary_font['font-family'] ) ? esc_attr( $primary_font['font-family'] ) : '',
      );

      if( $is_secondary_typo ){
        $default_styles .= sprintf(
          '
          body,
          p,
          span {
            font-family: %1$s, sans-serif;
          }
          ',
          isset( $secondary_font['font-family'] ) ? esc_attr( $secondary_font['font-family'] ) : '',
        );
      } else {
        $default_styles .= sprintf(
          '
          body,
          p,
          span {
            font-family: %1$s, sans-serif;
          }
          ',
          isset( $primary_font['font-family'] ) ? esc_attr( $primary_font['font-family'] ) : '',
        );
      }

      return $default_styles;

    }
	}

	new Sekarbumi_Typography();

}