<?php
namespace YpmPopup;
use \YpmConfig;
use ypmFrontend\PopupIncluder;

class Header {

	public function __construct() {

		$this->header();
	}

	private function header() {

		echo YpmConfig::YpmHeaders();
		$postId = get_queried_object_id();
		$popupData = get_post_meta($postId, 'ypm-metabox-popup');

		if(!empty($popupData[0])) {
			$this->includePopupToPageViaHeader($popupData[0]);
		}
	}

	private function includePopupToPageViaHeader($popupId) {

		$popup = Popup::find($popupId);

		$includer = new PopupIncluder();
		$includer->setId($popupId);
		$includer->setPopup($popup);
		$includer->setLoadable(true);
		$includer->setReferBy(2);
		$includer->includePopup();
	}
}