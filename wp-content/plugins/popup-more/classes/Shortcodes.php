<?php
namespace YpmPopup;
class Shortcodes{

	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$this->shortcodes();
	}

	private function shortcodes()
	{
		DataConfig::init();
		global $YpmPostTypesInfo;

		$popupPostTypes = $YpmPostTypesInfo['postTypes'];
		$popupPostTypesLabels = $YpmPostTypesInfo['postTypesLabels'];
		unset($popupPostTypes[YPM_POPUP_POST_TYPE]);

		foreach ($popupPostTypes as $postType => $level) {


			if($level < YPM_POPUP_PKG && YPM_POPUP_PKG > YPM_POPUP_FREE) {
			//	continue;
			}

			$typeName = str_replace('ypm', '', $postType);
			$customArgs = array('type' => $typeName, 'postType' => $postType);
			add_shortcode('ypm_'.esc_attr($typeName), function($args, $content) use ($customArgs) {

				return call_user_func(array($this, 'renderView'), $args, $content, $customArgs);
			});
		}
	}

	public function renderView($args, $content, $customArgs)
	{
		if (empty($args['id'])) {
			return '';
		}
		$id = (int)$args['id'];

		if (empty(Popup::getSavedData($id))) {
			return '';
		}
		$popupTypeObj = Popup::findByIdAndType($customArgs['postType'], $args['id']);

		if (!($popupTypeObj instanceof PopupViewInterface)) {
			return '';
		}

		return $popupTypeObj->renderView($args, $content);
	}
}

new Shortcodes();