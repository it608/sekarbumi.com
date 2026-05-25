<?php
if( !class_exists( 'SKBM_Page_Title' ) ){

	class SKBM_Page_Title {

		public $registered_page_title = array();

		public function __construct(){
			if( class_exists( 'Kirki' ) ){
				$this->sekarbumi_page_title_customizer();
			}
			add_filter( 'get_the_archive_title', array( $this, 'sekarbumi_remove_label_title' ), 10 );
			add_filter( 'sekarbumi_register_page_title', array( $this, 'sekarbumi_kisah_sukses_page_title' ), 10 );
			add_filter( 'sekarbumi_register_page_title', array( $this, 'sekarbumi_berita_page_title' ), 10 );
			add_filter( 'sekarbumi_register_page_title', array( $this, 'sekarbumi_siaran_pers_page_title' ), 10 );
			add_filter( 'sekarbumi_register_page_title', array( $this, 'sekarbumi_laporan_page_title' ), 10 );
			add_filter( 'sekarbumi_register_page_title', array( $this, 'sekarbumi_newsletter_page_title' ), 10 );
			add_action( 'sekarbumi_default_styles', array( $this, 'sekarbumi_set_page_title' ), 10 );
			add_action( 'page_title', array( $this, 'sekarbumi_render_page_title' ), 20 );
		}

		public function sekarbumi_page_title_customizer(){
			Kirki::add_section( 'sekarbumi_page_title_section', array(
				'title'		=> esc_html__( 'Page Title', 'sekarbumi' ),
				'panel'		=> 'sekarbumi_panel',
				'priority'	=> 30,
			) );
			
			if( is_customize_preview() ){
				include_once get_template_directory() . '/inc/modules/page-title/page-title-customizer.php';
			}
		}

		public function sekarbumi_remove_label_title( $title ) {

			if ( is_category() ) {

				$title = single_cat_title( '', false );

			} elseif ( is_tag() ) {

				$title = single_tag_title( '', false );

			} elseif ( is_author() ) {

				$title = '<span class="vcard">' . get_the_author() . '</span>';

			} elseif ( is_post_type_archive() ) {

				$title = post_type_archive_title( '', false );

			} elseif ( is_tax() ) {

				$title = single_term_title( '', false );

			} elseif ( is_year() ) {

				$title = get_the_date( _x( 'Y', 'yearly archives date format', 'sekarbumi' ) );

			} elseif ( is_month() ) {

				$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'sekarbumi' ) );

			} elseif ( is_day() ) {

				$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'sekarbumi' ) );

			}

			return $title;

		}

		public function sekarbumi_kisah_sukses_page_title( $page_title ){
			if ( is_post_type_archive( 'kisah-sukses' ) ) {
				$page_title_background_color	= get_theme_mod( 'page_title_background_color', '#fff' );
				$page_title_background			= get_theme_mod( 'page_title_background', array() );
				$page_title_text_color			= get_theme_mod( 'page_title_text_color', '#000' );
	
				$page_title = array(
					'title'			=> __( 'Kisah Sukses' ),
					'background'	=> $this->sekarbumi_page_title_background(
						array(
							'background_color'		=> !empty( $page_title_background_color ) ? $page_title_background_color : '',
							'background_image'		=> isset( $page_title_background['background-image'] ) ? $page_title_background['background-image'] : '',
							'background_repeat'		=> isset( $page_title_background['background-repeat'] ) ? $page_title_background['background-repeat'] : '',
							'background_position'	=> isset( $page_title_background['background-position'] ) ? $page_title_background['background-position'] : '',
							'background_size'		=> isset( $page_title_background['background-size'] ) ? $page_title_background['background-size'] : '',
							'background_attachment'	=> isset( $page_title_background['background-attachment'] ) ? $page_title_background['background-attachment'] : '',
						)
					),
					'color'			=> !empty( $page_title_text_color ) ? $page_title_text_color : '',
				);
			}

			return $page_title;
		}

		public function sekarbumi_berita_page_title( $page_title ){
			if ( is_post_type_archive( 'berita' ) ) {
				$page_title_background_color	= get_theme_mod( 'page_title_background_color', '#fff' );
				$page_title_background			= get_theme_mod( 'page_title_background', array() );
				$page_title_text_color			= get_theme_mod( 'page_title_text_color', '#000' );
	
				$page_title = array(
					'title'			=> __( 'Berita', 'sekarbumi' ),
					'background'	=> $this->sekarbumi_page_title_background(
						array(
							'background_color'		=> !empty( $page_title_background_color ) ? $page_title_background_color : '',
							'background_image'		=> isset( $page_title_background['background-image'] ) ? $page_title_background['background-image'] : '',
							'background_repeat'		=> isset( $page_title_background['background-repeat'] ) ? $page_title_background['background-repeat'] : '',
							'background_position'	=> isset( $page_title_background['background-position'] ) ? $page_title_background['background-position'] : '',
							'background_size'		=> isset( $page_title_background['background-size'] ) ? $page_title_background['background-size'] : '',
							'background_attachment'	=> isset( $page_title_background['background-attachment'] ) ? $page_title_background['background-attachment'] : '',
						)
					),
					'color'			=> !empty( $page_title_text_color ) ? $page_title_text_color : '',
				);
			}

			return $page_title;
		}

		public function sekarbumi_siaran_pers_page_title( $page_title ){
			if ( is_post_type_archive( 'siaran-pers' ) ) {
				$page_title_background_color	= get_theme_mod( 'page_title_background_color', '#fff' );
				$page_title_background			= get_theme_mod( 'page_title_background', array() );
				$page_title_text_color			= get_theme_mod( 'page_title_text_color', '#000' );
	
				$page_title = array(
					'title'			=> __( 'Siaran Pers', 'sekarbumi' ),
					'background'	=> $this->sekarbumi_page_title_background(
						array(
							'background_color'		=> !empty( $page_title_background_color ) ? $page_title_background_color : '',
							'background_image'		=> isset( $page_title_background['background-image'] ) ? $page_title_background['background-image'] : '',
							'background_repeat'		=> isset( $page_title_background['background-repeat'] ) ? $page_title_background['background-repeat'] : '',
							'background_position'	=> isset( $page_title_background['background-position'] ) ? $page_title_background['background-position'] : '',
							'background_size'		=> isset( $page_title_background['background-size'] ) ? $page_title_background['background-size'] : '',
							'background_attachment'	=> isset( $page_title_background['background-attachment'] ) ? $page_title_background['background-attachment'] : '',
						)
					),
					'color'			=> !empty( $page_title_text_color ) ? $page_title_text_color : '',
				);
			}

			return $page_title;
		}

		public function sekarbumi_laporan_page_title( $page_title ){
			if ( is_post_type_archive( 'laporan' ) ) {
				$page_title_background_color	= get_theme_mod( 'page_title_background_color', '#fff' );
				$page_title_background			= get_theme_mod( 'page_title_background', array() );
				$page_title_text_color			= get_theme_mod( 'page_title_text_color', '#000' );
	
				$page_title = array(
					'title'			=> __( 'Laporan', 'sekarbumi' ),
					'background'	=> $this->sekarbumi_page_title_background(
						array(
							'background_color'		=> !empty( $page_title_background_color ) ? $page_title_background_color : '',
							'background_image'		=> isset( $page_title_background['background-image'] ) ? $page_title_background['background-image'] : '',
							'background_repeat'		=> isset( $page_title_background['background-repeat'] ) ? $page_title_background['background-repeat'] : '',
							'background_position'	=> isset( $page_title_background['background-position'] ) ? $page_title_background['background-position'] : '',
							'background_size'		=> isset( $page_title_background['background-size'] ) ? $page_title_background['background-size'] : '',
							'background_attachment'	=> isset( $page_title_background['background-attachment'] ) ? $page_title_background['background-attachment'] : '',
						)
					),
					'color'			=> !empty( $page_title_text_color ) ? $page_title_text_color : '',
				);
			}

			return $page_title;
		}

		public function sekarbumi_newsletter_page_title( $page_title ){
			if ( is_post_type_archive( 'newsletter' ) ) {
				$page_title_background_color	= get_theme_mod( 'page_title_background_color', '#fff' );
				$page_title_background			= get_theme_mod( 'page_title_background', array() );
				$page_title_text_color			= get_theme_mod( 'page_title_text_color', '#000' );
	
				$page_title = array(
					'title'			=> __( 'Newsletter', 'sekarbumi' ),
					'background'	=> $this->sekarbumi_page_title_background(
						array(
							'background_color'		=> !empty( $page_title_background_color ) ? $page_title_background_color : '',
							'background_image'		=> isset( $page_title_background['background-image'] ) ? $page_title_background['background-image'] : '',
							'background_repeat'		=> isset( $page_title_background['background-repeat'] ) ? $page_title_background['background-repeat'] : '',
							'background_position'	=> isset( $page_title_background['background-position'] ) ? $page_title_background['background-position'] : '',
							'background_size'		=> isset( $page_title_background['background-size'] ) ? $page_title_background['background-size'] : '',
							'background_attachment'	=> isset( $page_title_background['background-attachment'] ) ? $page_title_background['background-attachment'] : '',
						)
					),
					'color'			=> !empty( $page_title_text_color ) ? $page_title_text_color : '',
				);
			}

			return $page_title;
		}

		public function sekarbumi_register_page_title( $args = array() ) {
			$this->registered_page_title = array(
				'title'				=> isset( $args['title'] ) ? $args['title'] : '',
				'background'		=> $this->sekarbumi_page_title_background( isset( $args['background'] ) ? $args['background'] : array() ),
				'color'				=> isset( $args['color'] ) ? $args['color'] : ''
			);

		}

		public function sekarbumi_set_page_title( $default_styles ){
			$this->registered_page_title = apply_filters( 'sekarbumi_register_page_title', $this->registered_page_title, $this );

			$page_title_color = isset( $this->registered_page_title['color'] ) ? $this->registered_page_title['color'] : '#000';
			
			$default_styles .= sprintf(
				'
				.cf-page-title-heading {
					color: %1$s;
				}
				',
				esc_attr( $page_title_color )
			);

			return $default_styles;
		}

		public function sekarbumi_page_title_background( $background ){


			if ( !is_array( $background ) || empty( $background ) ) return;

			$image_url = '';
			$inline_style = sprintf(
				'background-color:%1$s;',
				isset( $background['background_color'] ) ? $background['background_color'] : '#ffffff'
			);

			if ( !empty( $background['background_image'] ) ){

				if ( strpos( $background['background_image'], 'http' ) !== false ) {

					$image_url = $background['background_image'];

				} else {

					$image_url = wp_get_attachment_image_src( $background['background_image'], 'full' );

					$image_url = isset( $image_url[0] ) ? $image_url[0] : '';

				}

				if ( !empty( $image_url ) ) {

					$inline_style .= sprintf(
						'background-image:url(%1$s);background-position:%2$s;background-repeat:%3$s;background-size:%4$s;background-attachment:%5$s;',
						$image_url,
						isset( $background['background_position'] ) ? esc_attr( $background['background_position'] ) : 'left top',
						isset( $background['background_repeat'] ) ? esc_attr( $background['background_repeat'] ) : 'no-repeat',
						isset( $background['background_size'] ) ? esc_attr( $background['background_size'] ) : 'cover',
						isset( $background['background_attachment'] ) ? esc_attr( $background['background_attachment'] ) : 'scroll'
					);

				}
			}

			return $inline_style;
		}

		public function sekarbumi_render_page_title(){
			$page_titles = $this->registered_page_title;
			if ( is_array( $page_titles ) && !empty( $page_titles ) ) {
				include_once get_template_directory() . '/inc/modules/page-title/templates/page-title.php';
			}
		}
		
	}

	new SKBM_Page_Title();

}