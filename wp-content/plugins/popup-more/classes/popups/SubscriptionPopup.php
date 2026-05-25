<?php
namespace YpmPopup;
use \YpmAdminHelper;
class SubscriptionPopup extends Popup implements PopupViewInterface
{

	public $shortCodeName = 'ypm_subscription';

	public function getMenuLabelName()
	{

		return esc_attr__('Subscription', 'popup_master');
	}

	public function __construct()
	{
		if (is_admin()) {
			$this->includeAdminScripts();
			$this->includeCss();
			$this->includejS();
		}
		if (!defined('YPM_FORM_REQUIRED_MESSAGE')) {
			define('YPM_FORM_REQUIRED_MESSAGE', esc_attr__('This field is required.'));
		}
		if (!defined('YPM_FORM_INVALID_EMAIL')) {
			define('YPM_FORM_INVALID_EMAIL', esc_attr__('Please enter a valid email address.'));
		}
		$this->extendDefaults();
		add_filter('ypmDefaultOptions', array($this, 'defOptions'));
		$this->extendDefaultData();
	}

	public function defOptions($options)
	{

		$options[] = array('name' => 'ypm-popup-subscription-behavior', 'type' => 'text', 'defaultValue' => 'message');
		$options[] = array('name' => 'ypm-popup-subscription-expiration-message', 'type' => 'textMessage', 'defaultValue' => '<p>Thank you for your subscription.</p>');

		$options[] = array('name' => 'ypm-subscription-section', 'type' => 'text', 'defaultValue' => 'fields');
		$options[] = array('name' => 'ypm-subscription-form-width', 'type' => 'text', 'defaultValue' => '100');
		$options[] = array('name' => 'ypm-subscription-send-to-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$options[] = array('name' => 'ypm-subscription-send-from-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$options[] = array('name' => 'ypm-subscription-send-email-subject', 'type' => 'text', 'defaultValue' => esc_attr__('Contact form', 'popup_master'));
		$options[] = array('name' => 'ypm-subscription-message', 'type' => 'textMessage', 'defaultValue' => '<p>Hello!</p><p>This is your subscription form data:</p></br><p>[form_data]</p>');
		$options[] = array('name' => 'ypm-popup-subscription-enable-redirect', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-subscription-text-redirect-url', 'type' => 'text', 'defaultValue' => '');

		return $options;
	}

	private function extendDefaults()
	{

		global $YpmDefaults;

		$YpmDefaults[] = array('name' => 'ypm-popup-subscription-behavior', 'type' => 'text', 'defaultValue' => 'message');
		$YpmDefaults[] = array('name' => 'ypm-popup-subscription-expiration-message', 'type' => 'textMessage', 'defaultValue' => '<p>Thank you for your message. It has been sent.</p>');

		$YpmDefaults[] = array('name' => 'ypm-subscription-section', 'type' => 'text', 'defaultValue' => 'fields');
		$YpmDefaults[] = array('name' => 'ypm-subscription-form-width', 'type' => 'text', 'defaultValue' => '100');
		$YpmDefaults[] = array('name' => 'ypm-subscription-send-to-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$YpmDefaults[] = array('name' => 'ypm-subscription-send-from-email', 'type' => 'text', 'defaultValue' => get_option('admin_email'));
		$YpmDefaults[] = array('name' => 'ypm-subscription-send-email-subject', 'type' => 'text', 'defaultValue' => esc_attr__('Contact form', 'popup_master'));
		$YpmDefaults[] = array('name' => 'ypm-subscription-message', 'type' => 'textMessage', 'defaultValue' => '<p>Hello!</p><p>This is your scription form data:</p></br><p>[form_data]</p>');
	}

	private function extendDefaultData()
	{

		global $YpmDefaultsData;

		$YpmDefaultsData['subscriptionFormWidthMeasure'] = array(
			'%' => esc_attr__('Percents', 'popup_master'),
			'px' => esc_attr__('Pixels', 'popup_master')
		);
	}

	public static function create($data, $obj = '')
	{

		$obj = new self();
		parent::create($data, $obj);
	}

	public function save()
	{

		parent::save();
		$sanitizedData = $this->getSanitizedData();
		$fieldsOrder = (!empty($sanitizedData['ypm-subscription-fields-order'])) ? $sanitizedData['ypm-subscription-fields-order'] : '';
		$fieldsData = self::changeFieldsOrdering(get_option('YcfPopupFormDraft'), $fieldsOrder);
		$formFields = json_encode($fieldsData);
		$data = $this->getSanitizedData();
		$formId = $data['ypm-popup-id'];

		global $wpdb;

		$selectForm = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ypm_subscription_form_fields WHERE form_id=%d", $formId);
		$selectResult = $wpdb->query($selectForm);

		if (!$selectResult) {
			$insertToFieldsQuery = $wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ypm_subscription_form_fields (form_id, fields_data) VALUES (%d, %s)", $formId, $formFields);
			$insertResult = $wpdb->query($insertToFieldsQuery);
		} else {
			$fieldsUpdateSql = $wpdb->prepare("UPDATE " . $wpdb->prefix . "ypm_subscription_form_fields SET fields_data=%s WHERE form_id=%d", $formFields, $formId);
			$wpdb->query($fieldsUpdateSql);
		}
	}

	public static function changeFieldsOrdering($fieldsData, $ordersId)
	{

		if (!empty($ordersId) && gettype($ordersId) == 'string') {
			$ordersId = explode(',', $ordersId);
		}

		if (!is_array($ordersId)) {
			return $fieldsData;
		}
		$newOrderingData = array();

		foreach ($ordersId as $fieldId) {

			if (empty($fieldsData[$fieldId])) {
				continue;
			}
			$currentFieldData = $fieldsData[$fieldId];
			$newOrderingData[] = $currentFieldData;
		}

		if (empty($newOrderingData)) {
			return $fieldsData;
		}

		return $newOrderingData;
	}

	private function includeAdminScripts()
	{

		wp_register_style('ypmFormAdminStyles', YPM_POPUP_CSS_URL . 'subscription/formAdmin.css');
		wp_enqueue_style('ypmFormAdminStyles');
		wp_register_script('ypmFormAdminJs', YPM_POPUP_JS_URL . 'subscription/formBackend.js', array('jquery', 'jquery-ui-sortable'));
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
			'ypm-popup-subscription-behavior',
			'ypm-popup-subscription-expiration-message',
			'ypm-popup-subscription-redirect-url',
			'ypm-popup-subscription-redirect-url-tab',
			'ypm-popup-subscription-enable-redirect',
			'ypm-popup-subscription-text-redirect-url'
		);

		$options = apply_filters('ypmContactOptions', $options);
		$keyValue = array();

		foreach ($options as $option) {
			$keyValue[$option] = htmlspecialchars(wp_kses($this->getOptionValue($option), \YpmAdminHelper::getAllowedTags()));
		}

		return $keyValue;
	}

	public function getSubscriptionFormObj()
	{

		$popupId = $this->getPopupId();
		require_once YPM_POPUP_CLASSES . 'form/YpmSubscriptionForm.php';
		$formObj = new YpmSubscriptionForm();
		$formObj->setFormId($popupId);

		return $formObj;
	}

	public function render($args)
	{
		$popupId = $this->getPopupId();

		if (!get_post_status($popupId)) {
			return '';
		}
		require_once(YPM_POPUP_CLASSES . 'form/YcfBuilder.php');
		$formData = $this->getSubscriptionFormObj()->getFormData();
		$validateObj = $this->createValidateObj($formData);

		$this->includeCss();
		$this->includeJs();

		$formBuilderObj = new YcfBuilder();
		$formBuilderObj->setFormId($popupId);
		$formBuilderObj->setFormElementsData($formData);
		$expirationOptions = $this->getExpirationOptions();

		$subscriptionForm = '<form 
			id="ycf-subscription-form"
			data-id="' . esc_attr($popupId) . '"
			class="ycf-subscription-form ycf-form-' . esc_attr($popupId) . '"
			action="admin-post.php"
			method="post"
			data-expiration-options=\'' . json_encode($expirationOptions) . '\'
			data-validate=\'' . json_encode($validateObj) . '\'
			>';
		$subscriptionForm .= $formBuilderObj->getFormFields();
		$subscriptionForm .= '</form>';

		return $subscriptionForm;
	}

	private function includeCss()
	{
		SubscriptionPopup::formCSS();
	}

	public static function formCSS() {
		$args = array();
		if(is_admin()) {
			return;
		}
		$args['styleSrc'] = YPM_POPUP_CSS_URL . '/form/';
		ScriptsManager::registerStyle('ycfFormStyle.css', $args);
		ScriptsManager::enqueueStyle('ycfFormStyle.css');
		ScriptsManager::registerStyle('theme1.css', $args);
		ScriptsManager::enqueueStyle('theme1.css');
	}

	public function includeJs()
	{
		$args = array();
		$args['dirUrl'] = YPM_POPUP_FRONT_JS_URL . 'subscription/';
		$args['dep'] = array('jquery');

		ScriptsManager::registerScript('YpmPopupValidate.js', array('dirUrl' => YPM_POPUP_FRONT_JS_URL, 'dep' => array('jquery')));
		ScriptsManager::enqueueScript('YpmPopupValidate.js');

		ScriptsManager::registerScript('YpmSubscription.js', $args);
		$backLocalizeData = array(
			'ajaxNonce' => wp_create_nonce('ycfAjaxNonce'),
			'ajaxurl' => admin_url('admin-ajax.php')
		);
		ScriptsManager::localizeScript('YpmSubscription.js', 'ypmFormLocalization', $backLocalizeData);
		ScriptsManager::enqueueScript('YpmSubscription.js');
	}

	public function renderView($args, $content)
	{
		$content = $this->render($args, $content);
		$content .= $this->getStyles();

		return $content;
	}

	public function getStyles() {

		$id = $this->getId();
		$submitWidth = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-submit-width'));
		$submitHeight = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-submit-height'));
		$submitFontSize = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-submit-font-size'));
		
		$inputWidth = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-input-width'));
		$inputHeight = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-input-height'));
		$inputFontSize = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-input-font-size'));
		$inputColor = $this->getOptionValue('ypm-subscription-input-color');
		
		$labelsFontSize = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-labels-font-size'));
		$marginBottom = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-labels-margin-bottom'));
		$labelsColor = $this->getOptionValue('ypm-subscription-labels-color');
		$fontWeight = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-subscription-labels-font-weight'));
	
		ob_start();
		?>
		<style type="text/css">
			.ycf-form-<?php echo esc_attr($id); ?> .ycf-submit input {
				<?php if (!empty($submitWidth)): ?>
					width: <?php echo esc_attr($submitWidth);?>;
				<?php endif; ?>
				<?php if (!empty($submitHeight)): ?>
					height: <?php echo esc_attr($submitHeight);?> !important;
				<?php endif; ?>
				<?php if (!empty($submitFontSize)): ?>
					font-size: <?php echo esc_attr($submitFontSize);?> !important;
				<?php endif; ?>
			}
			.ycf-form-<?php echo esc_attr($id); ?> .ycf-form-element-wrapper input {
				<?php if (!empty($inputWidth)): ?>
					width: <?php echo esc_attr($inputWidth);?> !important;
				<?php endif; ?>
				<?php if (!empty($inputHeight)): ?>
					height: <?php echo esc_attr($inputHeight);?> !important;
				<?php endif; ?>
				<?php if (!empty($inputFontSize)): ?>
					font-size: <?php echo esc_attr($inputFontSize);?> !important;
				<?php endif; ?>
				<?php if (!empty($inputColor)): ?>
					color: <?php echo esc_attr($inputColor);?> !important;
				<?php endif; ?>
			}
			.ycf-form-<?php echo esc_attr($id); ?> .ycf-form-label {
				<?php if (!empty($labelsFontSize )): ?>
					font-size: <?php echo esc_attr($labelsFontSize);?>;
				<?php endif; ?>
				<?php if (!empty($fontWeight )): ?>
					font-weight: <?php echo esc_attr($fontWeight);?>;
				<?php endif; ?>
				<?php if (!empty($marginBottom)): ?>
					margin-bottom: <?php echo esc_attr($marginBottom);?> !important;
				<?php endif; ?>
				<?php if (!empty($labelsColor)): ?>
					color: <?php echo esc_attr($labelsColor);?> !important;
				<?php endif; ?>
				display: inline-block;
			}
			.ycf-form-<?php echo esc_attr($id); ?> .ycf-form-label {
				<?php if (!empty($labelsFontSize )): ?>
					font-size: <?php echo esc_attr($labelsFontSize);?>;
				<?php endif; ?>
				<?php if (!empty($fontWeight )): ?>
					font-weight: <?php echo esc_attr($fontWeight);?>;
				<?php endif; ?>
				<?php if (!empty($marginBottom)): ?>
					margin-bottom: <?php echo esc_attr($marginBottom);?> !important;
				<?php endif; ?>
				<?php if (!empty($labelsColor)): ?>
					color: <?php echo esc_attr($labelsColor);?> !important;
				<?php endif; ?>
				display: inline-block;
			}
		</style>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public function subscribe($formData, $submitData)
	{
		require_once YPM_POPUP_CLASSES . 'form/YpmSubscriptionForm.php';
		
		$id = sanitize_text_field($submitData['formId']);
		$formObj = new YpmSubscriptionForm();
		$formObj->setFormId($id);
		$subsTitle = get_the_title($id);

		// $popup = Popup::find($id);
		// var_dump();die;
		$savedFields = $formObj->getFormData();
		$firstName = '';
		$lastname = '';
		$email = '';
		$data = array();

		foreach ($formData as $name => $value) {
		
			$foundKey = array_search($name, array_column($savedFields, 'name'));
			if (empty($savedFields[$foundKey])) {
				continue;
			}
			$fieldData = $savedFields[$foundKey];
			
			if ($fieldData['fieldType'] === 'firstName') {
				$firstName = $value;
			} else if ($fieldData['fieldType'] === 'lastName') {
				$lastname = $value;
			} else if ($fieldData['fieldType'] === 'email') {
				$email = $value;
			}
			$data[$name] = array('label' => $fieldData['label'], 'value' => $value);
		}
	
		global $wpdb;
		$selectForm = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . YPM_SUBSCRIBERS_TABLE_NAME . " WHERE email=%s", $email);
		$selectResult = $wpdb->query($selectForm);
		$date = date('Y-m-d');
		if (!$selectResult) {
			$insertToFieldsQuery = $wpdb->prepare("INSERT INTO " . $wpdb->prefix . YPM_SUBSCRIBERS_TABLE_NAME . " 
				(firstName, lastName, email, formId, popupId, cDate, status, `options`) 
					VALUES (%s, %s, %s, %d, %d, %s, %d, %s)",
				$firstName, $lastname, $email, $id, $this->getId(), $date, 1, json_encode($data));
			$wpdb->query($insertToFieldsQuery);
		} else {
			$fieldsUpdateSql = $wpdb->prepare("UPDATE " . $wpdb->prefix . YPM_SUBSCRIBERS_TABLE_NAME . " SET 
				firstName=%s, lastName=%s, email=%s, cDate=%s, options=%s  WHERE form_id=%d", $firstName, $lastname, $email, $date);
			$wpdb->query($fieldsUpdateSql);
		}
		do_action('YpmCustomerSubscribed', array('id' => $id, 'email' => $email, 'firstName' => $firstName, 'lastname' => $lastname, 'subsTitle' => $subsTitle));
		return json_encode($data);
	}

	private function getFormMessage($formData)
	{
		$popupId = $this->getPopupId();
		$formObj = new YpmSubscriptionForm();
		$formObj->setFormId($popupId);
		$formOptionsData = $formObj->getFormList();
		$message = $this->getOptionValue('ypm-subscription-message');

		$patternFormData = '/\[form_data]/';

		$formDataString = '';

		foreach ($formData as $name => $value) {
			foreach ($formOptionsData as $optionData) {
				if ($name == $optionData['name']) {
					$sendData[$optionData['label']] = $value;
					$formDataString .= "<b>" . esc_attr($optionData['label']) . "</b>: " . esc_attr($value) . '<br>';
					continue;
				}
			}
		}

		$message = preg_replace($patternFormData, $formDataString, $message);

		return $message;
	}

	public static function getAllSubscriptionForms()
	{
		global $wpdb;

		$tablePrefix = $wpdb->prefix;
		$subscribersTableName = $tablePrefix . YPM_SUBSCRIBERS_TABLE_NAME;
		$postsTableName = $tablePrefix . 'posts';

		$query = $wpdb->prepare(
			"SELECT %i.*, %i.post_title AS postTitle
     FROM %i
     LEFT JOIN %i ON %i.formId = %i.ID",
			$subscribersTableName,
			$postsTableName,
			$subscribersTableName,
			$postsTableName,
			$subscribersTableName,
			$postsTableName
		);
		$results = $wpdb->get_results($query, ARRAY_A);
		$subscriptions = array();

		// when there is not any result
		if (empty($results)) {
			return $subscriptions;
		}
		foreach ($results as $result) {
			// $result is assoc array
			$id = (int)$result['formId'];
			$title = '(no title)';
			if (!empty($result['title'])) {
				$title = $result['title'];
			}
			$subscriptions[$id] = $title .' '.$id;
		}

		return $subscriptions;
	}

	public static function getAllSubscribersDate() {
		$subsDateList = array();
		global $wpdb;
		$subscriptionPopups = $wpdb->get_results('SELECT id, cDate FROM '.$wpdb->prefix.YPM_SUBSCRIBERS_TABLE_NAME, ARRAY_A);

		if (empty($subscriptionPopups)) {
			return $subsDateList;
		}

		foreach ($subscriptionPopups as $subscriptionForm) {
			$id = $subscriptionForm['id'];
			$date = substr($subscriptionForm['cDate'], 0, 7);

			$subsDateList[$id]['date-value'] = $date;
			$subsDateList[$id]['date-title'] = \YpmAdminHelper::getFormattedDate($date);
		}

		return $subsDateList;
	}

	public function getSubPopupObj()
	{
		$subPopupId = $this->getOptionValue('ypm-popup-sub-id');
		if (empty($subPopupId)) {
			return false;
		}
		$popup = Popup::find($subPopupId);
		if (empty($popup)) {
			return false;
		}
		if ($popup->getOptionValue('ypm-popup-subscription-behavior') === 'openPopup') {

			$closePopup = $popup->getOptionValue('ypm-popup-subscription-popup');
			$subPopupObj = self::find($closePopup);

			if (!empty($subPopupObj) && ($subPopupObj instanceof Popup) && get_post_status($closePopup) != 'trash') {
				// We remove all events because this popup will be open after successful subscription
				//$subPopupObj->setEvents(array('param' => 'click', 'value' => ''));
				$subPopupObj->options['ypm-events-settings'] = array(array('param' => 'click', 'value' => ''));
				return [$subPopupObj];
			}
		}
	}
}