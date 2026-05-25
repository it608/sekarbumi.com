<?php
namespace ypmFrontend;
use YpmPopup\Popup as backendPopup;
use YpmPopup\Popup as PopupMain;

require_once(YPM_POPUPS.'/Popup.php');
require_once(dirname(__FILE__).'/Popup.php');

class PopupIncluder extends Popup {

	public function includePopup() {

		$id = $this->getId();

		$isPublished = backendPopup::isPostPublished($id);

		if(!$isPublished) {
			return false;
		}

		$popupPostContent = get_post_field('post_content', $id);
		$popup = $this->getPopup();

		if (!$popup) {
			return false;
		}
		$popupData = apply_filters('ypmPopupOptions', $popup->getOptions());

		$popupData['title'] = get_the_title($id);
		$this->setContent($popupPostContent);
		$this->setOptions($popupData);

		$conditionsChecker = ConditionsChecker::instance();
		$conditionsChecker->setPopup($popup);
		$isAllow = $conditionsChecker->isAllow();
		if(!$isAllow) {
			return false;
		}
		$this->passToManager();
	}

	private function passToManager() {

		require_once(dirname(__FILE__).'/IncludeManager.php');
		$managerObj = new IncludeManager();
		$managerObj->setIncluderObj($this);
		$managerObj->loadPopupToPage();
	}
}