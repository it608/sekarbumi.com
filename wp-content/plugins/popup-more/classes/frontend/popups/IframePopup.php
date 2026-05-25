<?php
namespace YpmPopup;
require_once(dirname(__FILE__).'/Popup.php');

class IframePopup extends Popup implements PopupViewInterface {

	private $id;
	public $shortCodeName = 'ypm_iframe';

	public function __construct() {

		wp_register_script('iframeJs', YPM_POPUP_ADMIN_JS_URL . '/iframe.js', array('jquery'));
		wp_enqueue_script('iframeJs');
	}

	public function getMenuLabelName() {

		return esc_attr__('Iframe', 'popup_master');
	}

	public function setId($id) {

		$this->id = (int)$id;
	}

	public function getId() {

		return $this->id;
	}

	public static function create($data, $obj = '') {

		$obj = new self();
		parent::create($data, $obj);
	}

	public function renderView($args = array(), $content = array())
	{
		return $this->getContent();
	}

	public function getContent() {

		$id = $this->getId();
		$savedData = $this->getOptions();

		$iframeUrl = $this->getOptionValue('ypm-iframe-url');
		$iframeWidth = $this->getOptionValue('ypm-iframe-width');
		$iframeHeight = $this->getOptionValue('ypm-iframe-height');

		$iframe = "<iframe src='$iframeUrl' width='$iframeWidth' height='$iframeHeight'></iframe>";

		return $iframe;
	}
}