<?php
namespace YpmPopup;

class ConditionsConditionBuilder extends ConditionBuilder {
	public function __construct() {
		global $YPM_CONDITIONS_SETTINGS_CONFIG;
		$configData = $YPM_CONDITIONS_SETTINGS_CONFIG;

		$this->setConfigData($configData);
		$this->setNameString('ypm-conditions-settings');
	}
}