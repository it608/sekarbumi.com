<?php
namespace YpmPopup;
require_once YPM_POPUP_CLASSES.'form/YcfForm.php';

class GamificationForm extends YcfForm
{
	private $popup;

	public function __construct($popup)
	{
		$this->popup = $popup;
		if (!defined('YPM_FORM_REQUIRED_MESSAGE')) {
			define('YPM_FORM_REQUIRED_MESSAGE', esc_attr__('This field is required.'));
		}
		if (!defined('YPM_FORM_INVALID_EMAIL')) {
			define('YPM_FORM_INVALID_EMAIL', esc_attr__('Please enter a valid email address.'));
		}
		$this->setOrderFieldName('ypm-gamification-fields-order');
		$this->setTableName('ypm_gamification_form_fields');
	}

	public function defaultFormObjectData()
	{
		$defaults = array(
			'email',
			'submit'
		);
		$formData = array();

		foreach($defaults as $key) {
			$field = $this->getFormDefaultConfigByKey($key);
			if ($key === "submit") {
				$field['value'] = $this->popup->getOptionValue('ypm-gamification-btn-title');
				$field['attrs']['data-progress-title'] = $this->popup->getOptionValue('ypm-gamification-btn-progress-title');
			}
			if ($key === 'email') {
				$field['label'] = $this->popup->getOptionValue('ypm-gamification-text-placeholder');
			}
 			$formData[] = $field;
		}

		return $formData;
	}
}