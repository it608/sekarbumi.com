<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!class_exists('\Elementor\Widget_Base')) {
	return false;
}
//require_once ABSPATH . 'wp-content/plugins/elementor/includes/base/element-base.php';
//require_once ABSPATH . 'wp-content/plugins/elementor/includes/base/widget-base.php';
class Ypm_Custom_Elementor_Widget extends Widget_Base {

	public function get_name() {
		return 'popup-more-elementor-widget';
	}

	public function get_title() {
		return esc_attr__( 'Popup More', "popup_master" );
	}

	public function get_icon() {
		return 'wp-menu-image dashicons-before dashicons-admin-post';
	}

	public function get_categories() {
		return [ 'general', 'basic' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_attr__( 'Select Popup', "popup_master" ),
			]
		);

		$this->add_control(
			'popupId',
			array(
				'label'       => esc_attr__( 'Select popup(Note: Popup Will appear in visual mode)', "popup_master" ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => YpmPopup\Popup::getPopupIdTitleData(),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode('[ypm_popup id="'.esc_attr($settings['popupId']).'"]');
	}

}

add_action( 'elementor/widgets/register', function( $widgets_manager ) {
	$widgets_manager->register( new \Ypm_Custom_Elementor_Widget() );
} );


class Ypm_Subscription_Elementor_Widget extends Widget_Base {

	public function get_name() {
		return 'popup-more-subscription-elementor-widget';
	}

	public function get_title() {
		return esc_attr__( 'Popup More Subscription', "popup_master" );
	}

	public function get_icon() {
		return 'wp-menu-image dashicons-before dashicons-admin-post';
	}

	public function get_categories() {
		return [ 'general', 'basic' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_attr__( 'Select Popup Module', "popup_master" ),
			]
		);
		$typeData = YpmPopup\Popup::getModulesDataArray(array('type' =>'ypmsubscription', 'returnFalse' => true));
		$data = array();
		if (is_array($typeData)) {
			$data = array_values($typeData)[0];
		}
		$this->add_control(
			'popupId',
			array(
				'label'       => esc_attr__( 'Select popup module', "popup_master" ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $data
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode('[ypm_subscription id="'.esc_attr($settings['popupId']).'"]');
	}

}

add_action( 'elementor/widgets/register', function( $widgets_manager ) {
	$widgets_manager->register( new \Ypm_Subscription_Elementor_Widget() );
} );

function ypm_enqueue_custom_stylesheet() {
	require_once(YPM_POPUPS .'SubscriptionPopup'.'.php');
	YpmPopup\SubscriptionPopup::formCSS();
}
add_action('elementor/frontend/after_enqueue_styles', 'ypm_enqueue_custom_stylesheet');

