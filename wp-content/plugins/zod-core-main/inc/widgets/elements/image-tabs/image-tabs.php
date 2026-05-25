<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Image_Tabs extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style( 'zod-image-tabs-style', ZOD_CORE_WIDGETS_URI . '/elements/image-tabs/assets/image-tabs.css', array() , ZOD_CORE_VERSION );
		wp_register_script( 'zod-image-tabs-script', ZOD_CORE_WIDGETS_URI . '/elements/image-tabs/assets/image-tabs.js', array() , ZOD_CORE_VERSION );
	}

	public function get_name() {
		return 'zod-core-image-tabs';
	}
	
	public function get_title() {
		return __( 'Image Tabs', 'zod-core' );
	}

	public function get_script_depends() {
		return array( 'zod-image-tabs-script', 'slick-script' );
	}

	public function get_style_depends() {
		return array( 'zod-image-tabs-style', 'slick-style' );
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {

		// Start Tabs Title
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'column',
			array(
				'label' 	    => __( 'Column', 'zod-core' ),
				'type' 		    => Controls_Manager::NUMBER,
				'default'     => 5,
			)
		);
	
		$this->end_controls_section();

		// Start Tabs Title
		$this->start_controls_section(
			'section_tabs_title',
			array(
				'label' => __( 'Tabs Title', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

    $tabs_title = new Repeater();

		$tabs_title->add_control(
			'title_image',
			array(
				'label'   => esc_html__( 'Choose Image', 'textdomain' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
        ),
			)
		);

		$this->add_control(
			'tabs_title',
			array(
				'label'   => esc_html__( 'Tabs Title', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $tabs_title->get_controls(),
				'default' => array(
            array(
              'title' => array(
					      'url' => Utils::get_placeholder_image_src(),
              )
            ),
            array(
              'title' => array(
					      'url' => Utils::get_placeholder_image_src(),
              )
            ),
        ),
				'title_field' => __('Tabs Title', 'zod-core'),
      )
		);
	
		$this->end_controls_section();

		// Start Tabs Content
		$this->start_controls_section(
			'section_tabs_content',
			array(
				'label' => __( 'Tabs Content', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

    $tabs_content = new Repeater();

		$tabs_content->add_control(
			'left_content',
			array(
        'label'       => __( 'Tabs Left Content', 'zod-core' ),
				'type' 		    => Controls_Manager::TEXTAREA,
				'default'     => __( 'Left Content', 'zod-core' ),
        'label_block' => true,
			)
		);

		$tabs_content->add_control(
			'right_content',
			array(
        'label'       => __( 'Tabs Right Content', 'zod-core' ),
				'type' 		    => Controls_Manager::TEXTAREA,
				'default'     => __( 'Right Content', 'zod-core' ),
        'label_block' => true,
			)
		);

		$this->add_control(
			'tabs_content',
			array(
				'label'   => esc_html__( 'Tabs Content', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $tabs_content->get_controls(),
				'default' => array(
            array(
              'left_content' => __( 'Please Input Tabs Content Here', 'zod-core' ),
              'right_content' => __( 'Please Input Tabs Content Here', 'zod-core' ),
            ),
            array(
              'left_content' => __( 'Please Input Tabs Content Here', 'zod-core' ),
              'right_content' => __( 'Please Input Tabs Content Here', 'zod-core' ),
            ),
        ),
				'title_field' => __('Tabs Content', 'zod-core'),
      )
		);
	
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings 	= $this->get_settings_for_display();
    $uniq_id = uniqid();
		$output			= '';
    
    // Tabs Title
    $tabs_title = '';
    foreach( $settings['tabs_title'] as $item ){
      $tabs_title .= sprintf(
        '
        <li data-key="%1$s">
          %2$s
        </li>
        ',
        esc_attr( $item['_id'] ),
        zod_core_render_widgets_image( $item['title_image'] )
      );
    }
    
    // Tabs Content
    $tabs_content = '';
    foreach( $settings['tabs_content'] as $item ){
      $tabs_content .= sprintf(
        '
        <li data-key="%1$s">
          <div class="uk-child-width-1-2@m uk-grid-large" data-uk-grid>
            <div>
              <h3 class="zod-image-tabs__content--heading">%2$s</h3>
            </div>
            <div>
              <p>%3$s</p>
            </div>
          </div>
        </li>
        ',
        esc_attr( $item['_id'] ),
        __( $item['left_content'], 'zod-core' ),
        __( $item['right_content'], 'zod-core' )
      );
    }

    $data_carousel = [];
    $data_carousel['columnMobile'] = isset( $settings['column_mobile'] ) ? $settings['column_mobile'] : 2;
    $data_carousel['columnTablet'] = isset( $settings['column_tablet'] ) ? $settings['column_tablet'] : 4;
    $data_carousel['column'] = isset( $settings['column'] ) ? $settings['column'] : 6;

    $output = sprintf(
      '
      <div class="zod-image-tabs__title" data-carousel="%1$s" data-tabs="zod-image-tabs--%3$s">
        <div class="zod-image-tabs__wrapper">
          %2$s
        </div>
      </div>
      <ul class="zod-image-tabs__content zod-image-tabs--%3$s uk-switcher">
        %4$s
      </ul>
      ',
      esc_attr( json_encode( $data_carousel ) ),
      $tabs_title,
      esc_attr( $uniq_id ),
      $tabs_content,
    );

		echo apply_filters( 'zod_image_tabs', $output );

	}
}