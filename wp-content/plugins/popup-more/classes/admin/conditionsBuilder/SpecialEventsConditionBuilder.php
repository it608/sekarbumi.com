<?php
namespace YpmPopup;

class SpecialEventsConditionBuilder extends ConditionBuilder {
	public function __construct() {
		global $YPM_CUSTOM_EVENTS_SETTINGS_CONFIG;
		$configData = $YPM_CUSTOM_EVENTS_SETTINGS_CONFIG;

		$this->setConfigData($configData);
		$this->setNameString('ypm-popup-special-events-settings');
	}
}