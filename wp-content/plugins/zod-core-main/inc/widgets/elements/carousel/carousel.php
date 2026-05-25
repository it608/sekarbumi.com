<?php
namespace Elementor;

use Core\Kits\Documents\Tabs\Global_Colors;
use Core\Kits\Documents\Tabs\Global_Typography;
use Group_Control_Typography;

class ZOD_Core_Carousel extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script( 'zod-carousel-script', ZOD_CORE_WIDGETS_URI . '/elements/carousel/assets/carousel.js', array( 'elementor-frontend', 'slick-script' ), ZOD_CORE_VERSION, true );
		wp_register_style( 'zod-carousel-style', ZOD_CORE_WIDGETS_URI . '/elements/carousel/assets/carousel.css', array() , ZOD_CORE_VERSION );
	}

	public function get_name() {
		return 'zod-carousel';
	}
	
	public function get_title() {
		return __( 'Carousel', 'zod-core' );
	}

	public function get_style_depends() {
		return array( 'slick-style', 'zod-carousel-style' );
	}

	public function get_script_depends() {
		return array('slick-script', 'zod-carousel-script');
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {

		// Start Content
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
	
		$this->add_control(
			'layout',
			array(
				'label' 	=> __( 'Carousel Layout', 'zod-core' ),
				'type' 		=> Controls_Manager::SELECT,
				'default'	=> 'layout-1',
				'options'   => array(
					'layout-1' => esc_html__( 'Layout 1', 'zod-core' ),
					'layout-2' => esc_html__( 'Layout 2', 'zod-core' ),
					'layout-3' => esc_html__( 'Layout 3', 'zod-core' ),
				)
			)
		);

    $repeater = new Repeater();

    $repeater->add_control(
			'media',
			array(
				'label' 	=> __( 'Add Images', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);

    $repeater->add_control(
			'caption',
			array(
				'label' 	=> __( 'Carousel Caption', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXTAREA,
				'default'	=> __('This is caption for carousel', 'zod-core'),
			)
		);

    $this->add_control(
			'carousel_content',
			array(
				'label'   => esc_html__( 'Carousel Content', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
            array(
              'media' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
              'link' => 'https://elementor.com/',
            ),
            array(
              'text' => esc_html__( 'List Item #1', 'zod-core' ),
              'link' => 'https://elementor.com/',
            ),
        ),
				'title_field' => __('Carousel Content', 'zod-core'),
      )
		);
	
		$this->end_controls_section();
		// End of Content

		// Starts Carousel Settings
		$this->start_controls_section(
			'section_carousel_settings',
			array(
				'label' => esc_html__( 'Carousel Settings', 'cf-extender' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
	
		$this->add_control(
			'autoplay',
			array(
				'label' 	    => __( 'Autoplay', 'zod-core' ),
				'type' 		    => Controls_Manager::SWITCHER,
				'return_value'  => 'yes',
				'default'       => 'yes',
			)
		);
	
		$this->add_control(
			'autoplay_speed',
			array(
				'label' 	    => __( 'Autoplay Speed', 'zod-core' ),
				'type' 		    => Controls_Manager::TEXT,
				'default'       => 3000,
				'condition'		=> array(
					'autoplay'	=> 'yes',
				),
			)
		);
	
		$this->add_control(
			'arrows',
			array(
				'label' 	    => __( 'Arrows', 'zod-core' ),
				'type' 		    => Controls_Manager::SWITCHER,
				'return_value'  => 'yes',
				'default'       => 'yes',
			)
		);
	
		$this->add_control(
			'infinite',
			array(
				'label' 	    => __( 'Infinite', 'zod-core' ),
				'type' 		    => Controls_Manager::SWITCHER,
				'return_value'  => 'yes',
				'default'       => 'yes',
			)
		);
	
		$this->end_controls_section();
		// End of Carousel Settings
	}
	
	protected function render() {
		$settings	= $this->get_settings_for_display();
		$output		= '';

		$data_carousel = array(
			'autoplay'		=> isset( $settings['autoplay'] ) && $settings['autoplay'] == 'yes' ? true : false,
			'autoplaySpeed'	=> isset( $settings['autoplay_speed'] ) ? $settings['autoplay_speed'] : 3000,
		);

		// Open Wrapper
		$output .= sprintf( 
			'<div class="zod-carousel %1$s" data-carousel-settings="%2$s"><div class="zod-carousel__inner">',
			isset( $settings['layout'] ) ? esc_attr( $settings ['layout'] ) : '',
			esc_attr( json_encode( $data_carousel ) ),
		);

      // Starts Loop
      if( $settings['carousel_content'] ){
        foreach( $settings['carousel_content'] as $item ){
          $output .= sprintf(
            '
            <div class="zod-carousel__item">
              %1$s
            </div>
            ',
            zod_core_render_widgets_gallery($item['media']['id'])
          );
        }
      }
    
    // Close Carousel Wrapper
    $output .= '</div>';

    // Carousel Caption
    $output .= '<div class="zod-carousel__caption">';
    if( $settings['carousel_content'] ){
      foreach( $settings['carousel_content'] as $item ){
        $output .= sprintf(
          '
          <p>
            %1$s
          </p>
          ',
          __( $item['caption'], 'zod-core' )
        );
      }
    }
    $output .= '</div>';

    // Arrow
    $output .= '
      <div class="zod-carousel__nav">
        <a href="#" class="zod-carousel__nav--left">
          <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.49012e-08 10C1.49012e-08 9.44771 0.447715 9 1 9H23C23.5523 9 24 9.44771 24 10C24 10.5523 23.5523 11 23 11H1C0.447715 11 1.49012e-08 10.5523 1.49012e-08 10Z" fill="#00153A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.7071 0.292893C11.0976 0.683417 11.0976 1.31658 10.7071 1.70711L2.41421 10L10.7071 18.2929C11.0976 18.6834 11.0976 19.3166 10.7071 19.7071C10.3166 20.0976 9.68342 20.0976 9.29289 19.7071L0.292893 10.7071C-0.0976311 10.3166 -0.0976311 9.68342 0.292893 9.29289L9.29289 0.292893C9.68342 -0.0976311 10.3166 -0.0976311 10.7071 0.292893Z" fill="#00153A"/>
          </svg>
        </a>
        <a href="#" class="zod-carousel__nav--right">
          <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.49012e-08 10C1.49012e-08 9.44771 0.447715 9 1 9H23C23.5523 9 24 9.44771 24 10C24 10.5523 23.5523 11 23 11H1C0.447715 11 1.49012e-08 10.5523 1.49012e-08 10Z" fill="#00153A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.7071 0.292893C11.0976 0.683417 11.0976 1.31658 10.7071 1.70711L2.41421 10L10.7071 18.2929C11.0976 18.6834 11.0976 19.3166 10.7071 19.7071C10.3166 20.0976 9.68342 20.0976 9.29289 19.7071L0.292893 10.7071C-0.0976311 10.3166 -0.0976311 9.68342 0.292893 9.29289L9.29289 0.292893C9.68342 -0.0976311 10.3166 -0.0976311 10.7071 0.292893Z" fill="#00153A"/>
          </svg>
        </a>
      </div>
    ';
 
		// Close Wrapper
		$output .= '</div>';

		echo apply_filters( 'zod_carousel', $output );

	}
}