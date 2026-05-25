<?php
if( !class_exists( 'Sekarbumi_Customizer' ) ){
	class Sekarbumi_Customizer {
		
		public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_config_customizer();
			}

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'kedai_sayur_customize_controls_enqueue_scripts' ) );
		}

		public function sekarbumi_config_customizer(){
			Kirki::add_config( 'sekarbumi_settings', array(
				'capability'    => 'edit_theme_options',
				'option_type'   => 'theme_mod',
			) );
			Kirki::add_panel( 'sekarbumi_panel', array(
				'priority'    => 10,
				'title'       => esc_html__( 'Sekarbumi', 'sekarbumi' ),
				'description' => esc_html__( 'Custom Settings for sekarbumi', 'sekarbumi' ),
			) );
		}

		public function kedai_sayur_customize_controls_enqueue_scripts(){
			wp_enqueue_script( 'sekarbumi-customizer', get_template_directory_uri() . '/inc/customizer/assets/js/sekarbumi-customizer.js', array( 'jquery' ), null, true );
		}

	}

	new Sekarbumi_Customizer();
}