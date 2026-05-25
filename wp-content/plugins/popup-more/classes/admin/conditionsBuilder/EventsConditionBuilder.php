<?php
namespace YpmPopup;

class EventsConditionBuilder extends ConditionBuilder {
	public function __construct() {
		global $YPM_EVENTS_SETTINGS_CONFIG;
		$configData = $YPM_EVENTS_SETTINGS_CONFIG;

		$this->setConfigData($configData);
		$this->setNameString('ypm-events-settings');
	}
}