<?php
namespace YpmPopup;
require_once(dirname(__FILE__).'/Popup.php');
class LinkPopup extends Popup {

	public function __construct()
	{
		add_filter('ypmMetaboxes', array($this, 'metaboxes'),1,1);
	}

	public function metaboxes($metaboxes) {
		unset($metaboxes['popup_master_open_events']);
		$current = array('popup_master_link_popup_main_options' => array(
			'support_post_type' => array(YPM_POPUP_POST_TYPE),
			'label' => __('Link options', YPM_POPUP_TEXT_DOMAIN),
			'callback' => array($this, 'linkOptions'),
			'priority' => 'high'
		));

		return array_merge($current, $metaboxes);
	}

	public function linkOptions() {
		$typeObj = $this;
		require_once(YPM_POPUP_METABOXES."/typesMainOptions/linkOptions.php");
	}

	public static function create($data, $obj = '') {

		$obj = new self();
		parent::create($data, $obj);
	}
}