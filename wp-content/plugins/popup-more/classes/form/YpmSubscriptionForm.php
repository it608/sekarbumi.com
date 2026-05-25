<?php
namespace YpmPopup;
require_once YPM_POPUP_CLASSES.'form/YcfForm.php';

class YpmSubscriptionForm extends YcfForm
{
	public function __construct()
	{
		$this->setOrderFieldName('ypm-subscription-fields-order');
		$this->setTableName('ypm_subscription_form_fields');
	}

	public function defaultFormObjectData()
	{
		$defaults = array(
			'firstName',
			'lastName',
			'email',
			'gdpr',
			'submit'
		);
		$formData = array();

		foreach($defaults as $key) {
			$formData[] = $this->getFormDefaultConfigByKey($key);
		}

		return $formData;
	}
}