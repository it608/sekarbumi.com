<?php
namespace YpmPopup;
require_once(dirname(__FILE__).'/Popup.php');
class ImagePopup extends Popup {

	public function __construct()
	{
		add_filter('ypmMetaboxes', array($this, 'metaboxes'),1,1);
		add_filter('ypmDefaultOptions', array($this, 'defaults'));
	}

	public function defaults($defaultOptions) {
		$changingOptions = array(
			'ypm-popup-dimensions-mode' => array('name' => 'ypm-popup-dimensions-mode', 'type' => 'string', 'defaultValue' => 'auto')
		);
		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		return $defaultOptions;
	}

	public function metaboxes($metaboxes) {
		$current = array('popup_master_image_popup_main_options' => array(
			'support_post_type' => array(YPM_POPUP_POST_TYPE),
			'label' => esc_attr__('Image options', 'popup_master'),
			'callback' => array($this, 'imageOptions'),
			'priority' => 'high'
		));

		return array_merge($current, $metaboxes);
	}

	public function imageOptions() {
		$typeObj = $this;
		require_once(YPM_POPUP_METABOXES."/typesMainOptions/imageOptions.php");
	}

	public static function create($data, $obj = '') {

		$obj = new self();
		parent::create($data, $obj);
	}
}