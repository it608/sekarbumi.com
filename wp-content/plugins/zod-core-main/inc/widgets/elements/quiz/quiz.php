<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Quiz extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style( 'zod-quiz-style', ZOD_CORE_WIDGETS_URI . '/elements/quiz/assets/quiz.css', array() , ZOD_CORE_VERSION );
		wp_register_script( 'zod-quiz-script', ZOD_CORE_WIDGETS_URI . '/elements/quiz/assets/quiz.js', array( 'elementor-frontend', 'zod-core-uikit-script' ) , ZOD_CORE_VERSION, false );
	}

	public function get_name() {
		return 'zod-quiz';
	}
	
	public function get_title() {
		return __( 'Quiz', 'zod-core' );
	}

	public function get_style_depends() {
		return array( 'zod-quiz-style' );
	}

	public function get_script_depends() {
		return array( 'zod-quiz-script' );
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {

		// Start Content
		$this->start_controls_section(
			'team_section',
			array(
				'label' => __( 'General', 'zod-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'quiz_sc',
			array(
				'label' 	    => __( 'Quiz Shortcode', 'zod-core' ),
				'description' 	=> __( 'Based on forminator shortcode', 'zod-core' ),
				'type' 		    => Controls_Manager::TEXTAREA,
				'default'	    => array(),
			)
		);
	
		// End of Content
		$this->end_controls_section();

		// Start Section Style
		// $this->start_controls_section(
		// 	'section_style_primary',
		// 	array(
		// 		'label' => esc_html__( 'Primary', 'ks-extender' ),
		// 		'tab' => Controls_Manager::TAB_STYLE,
		// 	)
		// );

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	array(
		// 		'name' => 'primary_typography',
		// 		'global' => array(
		// 			'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
		// 		),
		// 		'selector' => '{{WRAPPER}} *:not(p):not(i)',
		// 	)
		// );

		// $this->end_controls_section();

		// $this->start_controls_section(
		// 	'section_style_secondary',
		// 	array(
		// 		'label' => esc_html__( 'Secondary', 'ks-extender' ),
		// 		'tab' => Controls_Manager::TAB_STYLE,
		// 	)
		// );

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	array(
		// 		'name' => 'secondary_typography',
		// 		'global' => array(
		// 			'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
		// 		),
		// 		'selector' => '{{WRAPPER}} p',
		// 	)
		// );
	
		// $this->end_controls_section();
	}
	
	protected function render() {
		$settings 		= $this->get_settings_for_display();
		$output			= '';

		// Render
		$output = sprintf(
			'
			<div class="zod-quiz">
                <div class="zod-quiz__progressbar">
                    <div class="zod-quiz__progressbar--item" style="height:24px;width:20%%;background:blue;"></div>
                </div>
				%1$s
			</div>
			',
			do_shortcode( $settings['quiz_sc'] )
		);


		echo apply_filters( 'zod_team_member', $output );

	}
}