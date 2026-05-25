<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Widgets_Card extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct( $data, $args );

		wp_register_style( 'zod-core-card-style', ZOD_CORE_WIDGETS_URI . '/elements/card/assets/card.css', array() , ZOD_CORE_VERSION );
	}

	public function get_name() {
		return 'zod-core-cards';
	}
	
	public function get_title() {
		return __( 'Card', 'zod-core' );
	}

	public function get_style_depends() {
		return array( 'zod-core-card-style' );
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function _register_controls() {
	
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$this->add_responsive_control(
			'column',
			array(
				'label'     => __( 'Card Column', 'zod-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '3',
				'options'   => array(
					'2'	=> __( '2', 'zod-core' ),
					'3'	=> __( '3', 'zod-core' ),
					'4'	=> __( '4', 'zod-core' ),
				)
			)
		);

		$this->add_control(
			'gap',
			array(
				'label' 	=> __( 'Card Gap', 'zod-core' ),
				'type' 		=> Controls_Manager::SELECT,
				'default'	=> 'medium',
				'options'	=> array(
					'small'			=> __( 'Small', 'zod-core' ),
					'medium'		=> __( 'Medium', 'zod-core' ),
					'large'			=> __( 'Large', 'zod-core' ),
					'collapse'		=> __( 'Collapse', 'zod-core' ),
				)
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label' 	=> __( 'Card Alignment', 'zod-core' ),
				'type' 		=> Controls_Manager::SELECT,
				'default'	=> 'left',
				'options'	=> array(
					'left'		=> __( 'Left', 'zod-core' ),
					'center'	=> __( 'Center', 'zod-core' ),
					'right'		=> __( 'Right', 'zod-core' ),
				)
			)
		);

		$this->add_control(
			'text_alignment',
			array(
				'label' 	=> __( 'Text Alignment', 'zod-core' ),
				'type' 		=> Controls_Manager::SELECT,
				'default'	=> 'left',
				'options'	=> array(
					'left'		=> __( 'Left', 'zod-core' ),
					'center'	=> __( 'Center', 'zod-core' ),
					'right'		=> __( 'Right', 'zod-core' ),
				)
			)
		);
		
		$repeater = new Repeater();
	
		$repeater->add_control(
			'sub_heading', array(
				'label' 	=> __( 'Sub-Heading', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> __( 'Sub Heading' , 'zod-core' ),
			)
		);

		$repeater->add_control(
			'heading', array(
				'label' 	=> __( 'Heading', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> __( 'Heading' , 'zod-core' ),
			)
		);

		$repeater->add_control(
			'text', array(
				'label' 	=> __( 'Text', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXTAREA,
				'default' 	=> __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' , 'zod-core' ),
			)
		);
	
		$this->add_control(
			'card_list',
			array(
				'label' 	=> __( 'Card List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater->get_controls(),
				'default' 	=> array(
					array(
						'sub_heading'	=> __( 'Sub Heading', 'zod-core' ),
						'heading'	    => __( 'Heading', 'zod-core' ),
						'content' 	    => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'zod-core' ),
					),
					array(
						'sub_heading'	=> __( 'Sub Heading', 'zod-core' ),
						'heading'	    => __( 'Heading', 'zod-core' ),
						'content' 	    => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'zod-core' ),
					),
				),
				'title_field' => '{{{ heading }}}',
			)
		);

		$this->end_controls_section();

		// Tabs Style Sub-Heading
		$this->start_controls_section(
			'style_sub_heading_section',
			array(
				'label' => __( 'Sub-Heading', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' 		=> 'sub_title_typography',
				'global' 	=> array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' 	=> '{{WRAPPER}} .card-sub-heading',
			)
		);

		$this->add_control(
			'sub_color',
			array(
				'label' 	=> __( 'Color', 'cf-extender' ),
				'type' 		=> Controls_Manager::COLOR,
				'default'	=> '#000',
				'selectors' => array(
					'{{WRAPPER}} .card-sub-heading' => 'color: {{VALUE}}'
				),
			)
		);
	
		$this->end_controls_section();
	
		// Tabs Style Heading
		$this->start_controls_section(
			'style_heading_section',
			array(
				'label' => __( 'Heading', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' 		=> 'heading_typography',
				'global' 	=> array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .card-heading',
			)
		);

		$this->add_control(
			'h_color',
			array(
				'label' 	=> __( 'Color', 'cf-extender' ),
				'type' 		=> Controls_Manager::COLOR,
				'default'	=> '#000',
				'selectors' => array(
					'{{WRAPPER}} .card-heading' => 'color: {{VALUE}}'
				),
			)
		);
	
		$this->end_controls_section();
	
		// Tabs Style Text
		$this->start_controls_section(
			'style_text_section',
			array(
				'label' => __( 'Text', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' 		=> 'text_typography',
				'global' 	=> array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .card-text',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label' 	=> __( 'Color', 'cf-extender' ),
				'type' 		=> Controls_Manager::COLOR,
				'default'	=> '#000',
				'selectors' => array(
					'{{WRAPPER}} .card-text' => 'color: {{VALUE}}'
				),
			)
		);
	
		$this->end_controls_section();
	
	}
	
	protected function render() {
		$settings 		= $this->get_settings_for_display();

		$card_list 	        = isset( $settings['card_list'] ) ? $settings['card_list'] : array();
		$column 	        = isset( $settings['column'] ) ? $settings['column'] : '4';
		$gap 	   		    = isset( $settings['gap'] ) ? $settings['gap'] : 'medium';
		$column_tablet 	    = isset( $settings['column_tablet'] ) ? $settings['column_tablet'] : '3' ;
		$column_mobile 	    = isset( $settings['column_mobile'] ) ? $settings['column_mobile'] : '2';
		$alignment	        = isset( $settings['alignment'] ) ? $settings['alignment'] : 'left';
		$text_alignment	    = isset( $settings['text_alignment'] ) ? $settings['text_alignment'] : 'left';

		if( $card_list ) {
			$content = sprintf(
				'
				<div class="card-wrapper uk-child-width-1-%1$s uk-child-width-1-%2$s@s uk-child-width-1-%3$s@m%4$s%5$s" data-uk-grid data-col="%1$s">
				',
				!empty( $column_mobile ) ? esc_attr( $column_mobile ) : '2',
				!empty( $column_tablet ) ? esc_attr( $column_tablet ) : '2',
				!empty( $column ) ? esc_attr( $column ) : '2',
				!empty( $gap ) ? ' uk-grid-'. esc_attr( $gap ) : 'medium',
				!empty( $alignment ) ? ' uk-flex-'. esc_attr( $alignment ) .'' : ''
			);
			foreach( $card_list as $item ){
				$sub_heading	= $item['sub_heading'];
				$heading		= $item['heading'];
				$text			= $item['text'];

				$content .= sprintf(
					'
					<div class="card-item-%1$s uk-text-%2$s">
						<div class="uk-card uk-padding uk-box-shadow-medium">
							%3$s
							%4$s
							%5$s
						</div>
					</div>
					',
					esc_attr( $item['_id'] ),
					!empty( $text_alignment ) ? esc_attr( $text_alignment ) : 'left',
					!empty( $sub_heading ) ? '<p class="card-sub-heading uk-margin-remove-top uk-margin-small-bottom">'. __( $sub_heading, 'zod-core' ).'</p>' : '',
					!empty( $heading ) ? '<h4 class="card-heading uk-margin-remove">'. __( $heading, 'zod-core' ) .'</h4>' : '',
					!empty( $text ) ? '<p class="card-text uk-margin-small-top uk-margin-remove-bottom">'. __( $text, 'zod-core' ).'</p>' : ''
				);
			}
			$content .= '</div>';
		}

		echo apply_filters( 'zod_card', $content );
	}
}