<?php
namespace YpmPopup;

class DisplayConditionBuilder extends ConditionBuilder {
	public function __construct() {
		global $YPM_DISPLAY_SETTINGS_CONFIG;
		$configData = $YPM_DISPLAY_SETTINGS_CONFIG;

		$this->setConfigData($configData);
		$this->setNameString('ypm-display-settings');
	}
}