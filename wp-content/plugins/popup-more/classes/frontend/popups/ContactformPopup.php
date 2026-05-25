<?php
namespace YpmPopup;

class ContactformPopup extends Popup implements PopupViewInterface {

	public $shortCodeName = 'ypm_contactform';

	public function getMenuLabelName() {

		return __('Contact Form', YPM_POPUP_TEXT_DOMAIN);
	}

	public function __construct() {
		if(is_admin()) {
			$this->includeAdminScripts();
		}
		$this->extendDefaults();
		add_filter('ypmDefaultOptions', array($this, 'defOptions'));
		$this->extendDefaultData();
	}

	public function defOptions($options) {
		$options[] = array('name' => 'ypm-popup-contacted-behavior', 'type' => 'text', 'defaultValue' => 'message');
		$YpmDefaults[] = array('name' => 'ypm-popup-contact-expiration-message', 'type' => 'textMessage', 'defaultValue' => '<p>Thank you for your message. It has been sent.</p>');

		$options[] = array('name' => 'ypm-contact-form-section', 'type' => 'text', 'defaultValue' => 'fields');
		$options[] = array('name' => 'ypm-contact-form-width', 'type' => 'text', 'defaultValue' => '100');
		$options[] = array('name' => 'ypm-contact-send-to-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$options[] = array('name' => 'ypm-contact-send-from-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$options[] = array('name' => 'ypm-contact-send-email-subject', 'type' => 'text', 'defaultValue' => __('Contact form', YPM_POPUP_TEXT_DOMAIN));
		$options[] = array('name' => 'ypm-contact-message', 'type' => 'textMessage', 'defaultValue' => '<p>Hello!</p><p>This is your contact form data:</p></br><p>[form_data]</p>');

		return $options;
	}

	private function extendDefaults() {

		global $YpmDefaults;

		$YpmDefaults[] = array('name' => 'ypm-popup-contacted-behavior', 'type' => 'text', 'defaultValue' => 'message');
		$YpmDefaults[] = array('name' => 'ypm-popup-contact-expiration-message', 'type' => 'textMessage', 'defaultValue' => '<p>Thank you for your message. It has been sent.</p>');

		$YpmDefaults[] = array('name' => 'ypm-contact-form-section', 'type' => 'text', 'defaultValue' => 'fields');
		$YpmDefaults[] = array('name' => 'ypm-contact-form-width', 'type' => 'text', 'defaultValue' => '100');
		$YpmDefaults[] = array('name' => 'ypm-contact-send-to-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$YpmDefaults[] = array('name' => 'ypm-contact-send-from-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$YpmDefaults[] = array('name' => 'ypm-contact-send-email-subject', 'type' => 'text', 'defaultValue' => __('Contact form', YPM_POPUP_TEXT_DOMAIN));
		$YpmDefaults[] = array('name' => 'ypm-contact-message', 'type' => 'textMessage', 'defaultValue' => '<p>Hello!</p><p>This is your contact form data:</p></br><p>[form_data]</p>');
	}

	private function extendDefaultData() {

		global $YpmDefaultsData;

		$YpmDefaultsData['contactFormWidthMeasure'] = array(
			'%' => __('Percents', YPM_POPUP_TEXT_DOMAIN),
			'px' => __('Pixels', YPM_POPUP_TEXT_DOMAIN)
		);
	}

	public static function create($data, $obj = '') {

		$obj = new self();
		parent::create($data, $obj);
	}

	public function save() {

		parent::save();
		$sanitizedData = $this->getSanitizedData();
		$fieldsOrder = (!empty($sanitizedData['ypm-contact-fields-order'])) ? $sanitizedData['ypm-contact-fields-order']: '';
		$fieldsData = ContactformPopup::changeFieldsOrdering(get_option('YcfPopupFormDraft'), $fieldsOrder);
		$formFields = json_encode($fieldsData);
		$data = $this->getSanitizedData();
		$formId = $data['ypm-popup-id'];

		global $wpdb;

		$selectForm = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."ypm_contact_form_fields WHERE form_id=%d", $formId);
		$selectResult = $wpdb->query($selectForm);

		if(!$selectResult) {
			$insertToFieldsQuery = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."ypm_contact_form_fields (form_id, fields_data) VALUES (%d, %s)", $formId, $formFields);
			$insertResult = $wpdb->query($insertToFieldsQuery);
		}
		else {
			$fieldsUpdateSql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."ypm_contact_form_fields SET fields_data=%s WHERE form_id=%d",$formFields, $formId);
			$wpdb->query($fieldsUpdateSql);
		}
	}

	public static function changeFieldsOrdering($fieldsData, $ordersId) {

		if(!empty($ordersId) && gettype($ordersId) == 'string') {
			$ordersId = explode(',', $ordersId);
		}

		if(!is_array($ordersId)) {
			return $fieldsData;
		}
		$newOrderingData = array();

		foreach($ordersId as $fieldId) {

			if(empty($fieldsData[$fieldId])) {
				continue;
			}
			$currentFieldData = $fieldsData[$fieldId];
			$newOrderingData[] = $currentFieldData;
		}

		if(empty($newOrderingData)) {
			return $fieldsData;
		}

		return $newOrderingData;
	}

	private function includeAdminScripts() {

		wp_register_style('ypmFormAdminStyles', YPM_POPUP_CSS_URL.'contactform/formAdmin.css');
		wp_enqueue_style('ypmFormAdminStyles');
		wp_register_script('ypmFormAdminJs', YPM_POPUP_JS_URL.'contactform/formBackend.js', array('jquery', 'jquery-ui-sortable'));
		$backLocalizeData = array(
			'ajaxNonce' => wp_create_nonce('ycfAjaxNonce')
		);
		wp_localize_script('ypmFormAdminJs', 'ycfBackendLocalization', $backLocalizeData);

		wp_enqueue_script('ypmFormAdminJs');

	}

	private function createValidateObj($subsFields, $validationMessages = array())
	{

		$validateArray = array(
			'rules' => array(),
			'messages' => array()
		);

		//		$requiredMessage = $this->getOptionValue('ypm-subs-validation-message');
//		$emailMessage = $this->getOptionValue('ypm-subs-invalid-message');
//
//		if (empty($subsFields)) {
//			return $validateObj;
//		}
//
//		if (empty($emailMessage)) {
//			$emailMessage = 'defined message';
//		}
//
//		if (empty($requiredMessage)) {
//			$requiredMessage = 'defined message';
//		}
		$requiredMessage = YPM_FORM_REQUIRED_MESSAGE;
		$emailMessage = YPM_FORM_INVALID_EMAIL;

		foreach ($subsFields as $subsField) {

			if (empty($subsField['settings'])) {
				continue;
			}
			$settings = $subsField['settings'];
			$type = 'text';
			$name = '';
			$required = false;

			if (!empty($settings['required'])) {
				$required = $settings['required'];
			}

			if (!$required) {
				continue;
			}

			if (!empty($subsField['type'])) {
				$type = $subsField['type'];
			}
			if (!empty($subsField['name'])) {
				$name = $subsField['name'];
			}

			if ($type == 'email') {
				$validateArray['rules'][$name] = array('required' => $required, 'email' => true);
				$validateArray['messages'][$name] = array('required' => $requiredMessage, 'email' => $emailMessage);
				continue;
			}
			$validateArray['rules'][$name] = 'required';
			$validateArray['messages'][$name] = $requiredMessage;
		}

		return $validateArray;
	}

	private function getExpirationOptions()
	{
		$options = array(
			'ypm-popup-contacted-behavior',
			'ypm-popup-contact-expiration-message',
			'ypm-popup-contact-redirect-url',
			'ypm-popup-contact-redirect-url-tab'
		);
		$options = apply_filters('ypmContactOptions', $options);
		$keyValue = array();

		foreach ($options as $option) {
			$keyValue[$option] = $this->getOptionValue($option);
		}

		return $keyValue;
	}

	public function getContactFormObj() {

		$popupId = $this->getPopupId();
		require_once YPM_POPUP_CLASSES.'form/YcfContactForm.php';
		$formObj = new YcfContactForm();
		$formObj->setFormId($popupId);

		return $formObj;
	}

	public function render()
	{
		$popupId = $this->getPopupId();
		require_once(YPM_POPUP_CLASSES.'form/YcfBuilder.php');
		$formData = $this->getContactFormObj()->getFormData();
		$validateObj = $this->createValidateObj($formData);

		$this->includeCss();
		$this->includeJs();

		$formBuilderObj = new YcfBuilder();
		$formBuilderObj->setFormId($popupId);
		$formBuilderObj->setFormElementsData($formData);
		$expirationOptions = $this->getExpirationOptions();

		$contactForm = '<form 
			id="ycf-contact-form"
			data-id="'.$popupId.'"
			class="ycf-contact-form ycf-form-'.$popupId.'"
			action="admin-post.php"
			method="post"
			data-expiration-options=\''.json_encode($expirationOptions).'\'
			data-validate=\''.json_encode($validateObj).'\'
			>';
		$contactForm .= $formBuilderObj->getFormFields();
		$contactForm .= '</form>';

		return $contactForm;
	}

	private function includeCss()
	{
		$args = array();
		$args['styleSrc'] = YPM_POPUP_CSS_URL.'/form/';
		ScriptsManager::registerStyle('ycfFormStyle.css', $args);
		ScriptsManager::enqueueStyle('ycfFormStyle.css');
		ScriptsManager::registerStyle('theme1.css', $args);
		ScriptsManager::enqueueStyle('theme1.css');
	}

	public function includeJs()
	{
		$args = array();
		$args['dirUrl'] = YPM_POPUP_FRONT_JS_URL.'contactform/';

		ScriptsManager::registerScript('YpmPopupValidate.js', array('dirUrl' => YPM_POPUP_FRONT_JS_URL));
		ScriptsManager::enqueueScript('YpmPopupValidate.js');

		ScriptsManager::registerScript('YpmContactForm.js', $args);
		$backLocalizeData = array(
			'ajaxNonce' => wp_create_nonce('ycfAjaxNonce'),
			'ajaxurl' => admin_url('admin-ajax.php')
		);
		ScriptsManager::localizeScript('YpmContactForm.js', 'ypmFormLocalization', $backLocalizeData);
		ScriptsManager::enqueueScript('YpmContactForm.js');
	}

	public function renderView($args, $content)
	{
		return $this->render();
	}

	public function send($formData)
	{
		$toEmail = $this->getOptionValue('ypm-contact-send-to-email');
		$fromEmail = $this->getOptionValue('ypm-contact-send-from-email');
		$subject = $this->getOptionValue('ypm-contact-send-email-subject');

		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'From: '.$fromEmail.''."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$message = $this->getFormMessage($formData);

		$sendStatus = wp_mail($toEmail, $subject, $message, $headers);
	}

	private function getFormMessage($formData)
	{
		$popupId = $this->getPopupId();
		$formObj = new YcfContactForm();
		$formObj->setFormId($popupId);
		$formOptionsData = $formObj->getFormList();
		$message = $this->getOptionValue('ypm-contact-message');

		$patternFormData = '/\[form_data]/';

		$formDataString = '';

		foreach ($formData as $name => $value) {
			foreach ($formOptionsData as $optionData) {
				if ($name == $optionData['name']) {
					$sendData[$optionData['label']] = $value;
					$formDataString .= "<b>".$optionData['label']."</b>: ".$value.'<br>';
					continue;
				}
			}
		}

		$message = preg_replace($patternFormData, $formDataString, $message);

		return $message;
	}
}