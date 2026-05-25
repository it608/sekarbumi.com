<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Animated_Headline extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script( 'zod-core-animated-headline-js', ZOD_CORE_WIDGETS_URI . '/elements/animated-headline/assets/animated-headline.js', array( 'elementor-frontend' ), ZOD_CORE_VERSION, true );
		wp_register_style( 'zod-core-animated-headline-style', ZOD_CORE_WIDGETS_URI . '/elements/animated-headline/assets/animated-headline.css', array() , ZOD_CORE_VERSION );
	}

	public function get_name() {
		return 'zod-core-animated-headline';
	}
	
	public function get_title() {
		return __( 'Animated Headline', 'zod-core' );
	}

	public function get_style_depends() {
		return array('zod-core-animated-headline-style' );
	}

	public function get_script_depends() {
		return array('zod-core-animated-headline-js');
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {
	
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$this->add_control(
			'before_text',
			array(
				'label' 		=> esc_html__( 'Before Text', 'zod-core' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> esc_html__( 'This page is', 'zod-core' ),
				'placeholder' 	=> esc_html__( 'Enter your headline', 'zod-core' ),
				'label_block' 	=> true,
				'separator' 	=> 'before',
			)
		);

		$this->add_control(
			'rotating_text',
			array(
				'label' 		=> esc_html__( 'Rotating Text', 'zod-core' ),
				'type' 			=> Controls_Manager::TEXTAREA,
				'placeholder' 	=> esc_html__( 'Enter each word in a separate line', 'zod-core' ),
				'separator' 	=> 'none',
				'default' 		=> "Better\nBigger\nFaster",
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'after_text',
			array(
				'label' 		=> esc_html__( 'After Text', 'zod-core' ),
				'type' 			=> Controls_Manager::TEXT,
				'placeholder' 	=> esc_html__( 'Enter your headline', 'zod-core' ),
				'label_block' 	=> true,
				'separator' 	=> 'after',
			)
		);

		$this->add_control(
			'animation_delay',
			array(
				'label' 				=> esc_html__( 'Duration', 'elementor-pro' ) . ' (ms)',
				'type' 					=> Controls_Manager::NUMBER,
				'default' 				=> 2500,
				'frontend_available' 	=> true,
				'separator' 			=> 'before',
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label' 	=> esc_html__( 'Alignment', 'zod-core' ),
				'type' 		=> Controls_Manager::CHOOSE,
				'options' 	=> array(
					'left' 	=> array(
						'title' => esc_html__( 'Left', 'zod-core' ),
						'icon' 	=> 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'zod-core' ),
						'icon' 	=> 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'zod-core' ),
						'icon' 	=> 'eicon-text-align-right',
					),
				),
				'default' 	=> 'center',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .zod-headline' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag',
			array(
				'label' 	=> esc_html__( 'HTML Tag', 'zod-core' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> array(
					'h1' 	=> 'H1',
					'h2' 	=> 'H2',
					'h3' 	=> 'H3',
					'h4' 	=> 'H4',
					'h5' 	=> 'H5',
					'h6' 	=> 'H6',
					'div' 	=> 'div',
					'span' 	=> 'span',
					'p' 	=> 'p',
				),
				'default' 	=> 'h3',
			)
		);
	
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Headline', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label' 	=> esc_html__( 'Text Color', 'zod-core' ),
				'type' 		=> Controls_Manager::COLOR,
				'global' 	=> array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .zod-static-headline' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' 		=> 'title_typography',
				'global' 	=> array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'	=> '{{WRAPPER}} .zod-headline',
			)
		);

		$this->add_control(
			'heading_words_style',
			array(
				'type' 		=> Controls_Manager::HEADING,
				'label' 	=> esc_html__( 'Animated Text', 'zod-core' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'words_color',
			array(
				'label' 	=> esc_html__( 'Text Color', 'zod-core' ),
				'type' 		=> Controls_Manager::COLOR,
				'global' 	=> array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors'	=> array(
					'{{WRAPPER}} .zod-headline-dynamic-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' 		=> 'words_typography',
				'global' 	=> array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'	=> '{{WRAPPER}} .zod-headline-dynamic-text',
				'exclude'	=> array( 'font_size' ),
			)
		);
	
		$this->end_controls_section();
	
	}
	
	protected function render() {
		$settings 		= $this->get_settings_for_display();
		$output			= '';

		// Open Wrapper
		$tag			 	      = $settings['tag'];
		$animation_delay 	= $settings['animation_delay'];
		$alignment			  = $settings['alignment'];
		$output .= sprintf( '<%1$s class="zod-headline slide-down uk-margin-remove" data-animation-delay="%2$s" data-alignment="%3$s">', esc_html( $tag ), esc_attr( $animation_delay ), esc_attr( $alignment ) );

		// Before Text
		$before_text = $settings['before_text'];
		if( !empty( $before_text ) ){
			$output .= sprintf(
				'
				<span class="zod-static-headline zod-headline-text-wrapper">%1$s</span>
				',
				__( $before_text, 'zod-core' )
			);
		}

		// Rotating Text
		if( $settings['rotating_text'] ){
			$output .= '<span class="zod-headline-dynamic-wrapper zod-headline-text-wrapper">';
				$rotating_text = explode( "\n", $settings['rotating_text'] );
				foreach ( $rotating_text as $key => $text ) {
					$output .= sprintf(
						'
						<span class="zod-headline-dynamic-text%1$s"> %2$s </span>
						',
						1 > $key ? ' zod-headline-text-active' : '',
						str_replace( ' ', '&nbsp;', $text )
					);
				}
			$output .= '</span>';
		}

		// After Text
		$after_text = $settings['after_text'];
		if( !empty( $after_text ) ){
			$output .= sprintf(
				'
				<span class="zod-static-headline zod-headline-text-wrapper">%1$s</span>
				',
				esc_html__( $after_text, 'zod-core' )
			);
		}

		// Close Wrapper
		$output .= sprintf( '</%1$s>', esc_html( $tag ) );

		echo apply_filters( 'ks_animated_headline', $output );

	}
}